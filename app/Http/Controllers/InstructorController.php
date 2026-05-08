<?php

namespace App\Http\Controllers;

use App\Models\Instructor;
use Illuminate\Http\Request;

class InstructorController extends Controller
{
    public function index()
    {
        $instructores = Instructor::withCount(['sesiones' => function ($query) {
                $query->where('estado', 'finalizada');
            }])
            ->withSum(['sesiones as total_minutos' => function ($query) {
                $query->where('estado', 'finalizada');
            }], 'duracion_minutos')
            ->orderBy('grado_nombre', 'asc')
            ->get();

        $totalInstructores = $instructores->count();
        $activos = $instructores->where('activo', true)->count();
        $inactivos = $instructores->where('activo', false)->count();
        $minutosGlobales = $instructores->sum('total_minutos');
        $horasGlobales = floor($minutosGlobales / 60);

        return view('instructores.index', compact(
            'instructores', 'totalInstructores', 'activos', 'inactivos', 'horasGlobales'
        ));
    }

    public function show($id)
    {
        $instructor = Instructor::findOrFail($id);

        // Traemos sus sesiones ordenadas desde la más reciente, paginadas de 10 en 10
        // Hacemos 'with(alumno)' para no saturar la base de datos al mostrar la tabla
        $sesiones = $instructor->sesiones()
                            ->with('alumno')
                            ->orderBy('fecha', 'desc')
                            ->orderBy('hora_inicio', 'desc')
                            ->paginate(10);

        // Obtenemos solo las sesiones finalizadas para calcular los tiempos reales
        $sesionesFinalizadas = $instructor->sesiones()->where('estado', 'finalizada');

        $estadisticas = [
            'total_sesiones' => $instructor->sesiones()->count(),
            'tiempo_total_minutos' => (clone $sesionesFinalizadas)->sum('duracion_minutos'),
            'promedio_duracion' => (clone $sesionesFinalizadas)->avg('duracion_minutos'),
            'ultima_sesion' => $instructor->sesiones()->orderBy('fecha', 'desc')->first(),
        ];

        return view('instructores.show', compact('instructor', 'sesiones', 'estadisticas'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'npi' => 'required|string|max:8|unique:instructores,npi|regex:/^\d{6}-\d{1}$/',
            'pin' => 'required|string|size:4|regex:/^\d{4}$/', // Validamos 4 dígitos exactos
            'grado_nombre' => 'required|string|max:255',
        ], [
            'npi.unique' => 'Este NPI ya está registrado.',
            'npi.regex' => 'El NPI debe tener el formato 123456-7.',
            'pin.size' => 'El PIN debe ser de exactamente 4 números.',
            'pin.regex' => 'El PIN solo puede contener números.'
        ]);

        Instructor::create([
            'npi' => strtoupper($request->npi),
            'pin' => $request->pin,
            'grado_nombre' => $request->grado_nombre,
            'activo' => true
        ]);

        return redirect()->back()->with('success', 'Instructor agregado correctamente.');
    }

    public function edit($id)
    {
        $instructor = Instructor::findOrFail($id);
        return view('instructores.edit', compact('instructor'));
    }

    public function update(Request $request, $id)
    {
        $instructor = Instructor::findOrFail($id);
        
        $request->validate([
            'npi' => 'required|string|max:8|unique:instructores,npi,' . $id . '|regex:/^\d{6}-\d{1}$/',
            'pin' => 'required|string|size:4|regex:/^\d{4}$/',
            'grado_nombre' => 'required|string|max:255',
        ], [
            'npi.regex' => 'Formato de NPI inválido.',
            'pin.size' => 'El PIN debe tener 4 dígitos.'
        ]);

        $instructor->update([
            'npi' => strtoupper($request->npi),
            'pin' => $request->pin,
            'grado_nombre' => $request->grado_nombre,
        ]);

        return redirect()->route('instructores.index')->with('success', 'Instructor actualizado correctamente.');
    }

    public function toggleEstado($id)
    {
        $instructor = Instructor::findOrFail($id);
        $instructor->update(['activo' => !$instructor->activo]);
        
        $estado = $instructor->activo ? 'activado' : 'desactivado';
        return redirect()->back()->with('success', "Instructor $estado correctamente.");
    }
}