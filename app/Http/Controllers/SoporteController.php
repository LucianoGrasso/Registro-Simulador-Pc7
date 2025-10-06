<?php

namespace App\Http\Controllers;

use App\Models\Soporte;
use Illuminate\Http\Request;

class SoporteController extends Controller
{
    /**
     * Vista para operadores - crear ticket de soporte
     */
    public function create()
    {
        return view('soporte.create');
    }

    /**
     * Almacenar nuevo ticket
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tipo' => 'required|in:falla,sugerencia',
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'prioridad' => 'required|in:baja,media,alta'
        ]);

        $validated['user_id'] = auth()->id();

        Soporte::create($validated);

        return redirect()->back()->with('success', 'Ticket de soporte enviado exitosamente. Será revisado por el administrador.');
    }

    /**
     * Vista de admin - listado de tickets
     */
    public function index(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'No tienes permiso para ver esta sección');
        }

        $query = Soporte::with('usuario')->orderBy('created_at', 'desc');

        // Filtros
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('prioridad')) {
            $query->where('prioridad', $request->prioridad);
        }

        $tickets = $query->paginate(15);

        // Estadísticas
        $stats = [
            'total' => Soporte::count(),
            'pendientes' => Soporte::where('estado', 'pendiente')->count(),
            'en_revision' => Soporte::where('estado', 'en_revision')->count(),
            'resueltos' => Soporte::where('estado', 'resuelto')->count(),
            'fallas' => Soporte::where('tipo', 'falla')->count(),
            'sugerencias' => Soporte::where('tipo', 'sugerencia')->count(),
        ];

        return view('soporte.index', compact('tickets', 'stats'));
    }

    /**
     * Ver detalle de un ticket
     */
    public function show(Soporte $soporte)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'No tienes permiso para ver esta sección');
        }

        return view('soporte.show', compact('soporte'));
    }

    /**
     * Actualizar estado del ticket
     */
    public function updateEstado(Request $request, Soporte $soporte)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'No tienes permiso para realizar esta acción');
        }

        $validated = $request->validate([
            'estado' => 'required|in:pendiente,en_revision,resuelto,rechazado',
            'respuesta_admin' => 'nullable|string'
        ]);

        if ($validated['estado'] === 'resuelto' || $validated['estado'] === 'rechazado') {
            $validated['fecha_resolucion'] = now();
        }

        $soporte->update($validated);

        return redirect()->back()->with('success', 'Estado del ticket actualizado correctamente.');
    }

    /**
     * Eliminar ticket
     */
    public function destroy(Soporte $soporte)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'No tienes permiso para realizar esta acción');
        }

        $soporte->delete();

        return redirect()->route('soporte.index')->with('success', 'Ticket eliminado correctamente.');
    }

    /**
     * Mis tickets (para operadores)
     */
    public function misTickets()
    {
        $tickets = Soporte::where('user_id', auth()->id())
                          ->orderBy('created_at', 'desc')
                          ->paginate(10);

        return view('soporte.mis-tickets', compact('tickets'));
    }
}