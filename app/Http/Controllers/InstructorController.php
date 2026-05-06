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

    public function store(Request $request)
    {
        $request->validate([
            'npi' => 'required|string|max:8|unique:instructores,npi|regex:/^\d{6}-\d{1}$/',
            'grado_nombre' => 'required|string|max:255',
        ], [
            'npi.unique' => 'Este NPI ya está registrado en el sistema.',
            'npi.regex' => 'El NPI debe tener el formato 341725-9 (6 dígitos, guión, 1 dígito verificador).'
        ]);

        Instructor::create([
            'npi' => strtoupper($request->npi),
            'grado_nombre' => $request->grado_nombre,
            'activo' => true
        ]);

        return redirect()->back()->with('success', 'Instructor agregado correctamente al escuadrón.');
    }

    public function update(Request $request, $id)
    {
        $instructor = Instructor::findOrFail($id);
        
        $request->validate([
            'npi' => 'required|string|max:8|unique:instructores,npi,' . $instructor->id . '|regex:/^\d{6}-\d{1}$/',
            'grado_nombre' => 'required|string|max:255',
        ], [
            'npi.unique' => 'Este NPI ya está registrado en el sistema.',
            'npi.regex' => 'El NPI debe tener el formato 341725-9 (6 dígitos, guión, 1 dígito verificador).'
        ]);

        $instructor->update([
            'npi' => strtoupper($request->npi),
            'grado_nombre' => $request->grado_nombre,
        ]);

        return redirect()->back()->with('success', 'Datos del instructor actualizados.');
    }

    public function toggleEstado($id)
    {
        $instructor = Instructor::findOrFail($id);
        $instructor->update(['activo' => !$instructor->activo]);
        
        $estado = $instructor->activo ? 'activado' : 'desactivado';
        return redirect()->back()->with('success', "Instructor $estado correctamente.");
    }
}