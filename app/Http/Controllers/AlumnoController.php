<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlumnoController extends Controller
{
    /**
     * Listado de alumnos
     */
    public function index(Request $request)
    {
        $query = Alumno::query();

        // Filtros de búsqueda
        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function($q) use ($buscar) {
                $q->where('nombre_completo', 'like', "%{$buscar}%")
                ->orWhere('rut_dni', 'like', "%{$buscar}%")
                ->orWhere('npi', 'like', "%{$buscar}%")
                ->orWhere('correo', 'like', "%{$buscar}%");
            });
        }

        if ($request->filled('estado')) {
            $query->where('is_active', $request->estado === 'activo' ? true : false);
        }

        $alumnos = $query->withCount(['sesiones', 'sesionesActivas'])
                        ->orderByRaw('is_active DESC')  // Primero activos, luego inactivos
                        ->orderBy('nombre_completo', 'asc')  // Luego alfabéticamente
                        ->paginate(15)
                        ->withQueryString();

        return view('alumnos.index', compact('alumnos'));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        return view('alumnos.create');
    }

    /**
     * Guardar nuevo alumno
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre_completo' => 'required|string|max:255',
            'rut_dni' => 'required|string|max:20|unique:alumnos,rut_dni',
            'npi' => 'required|string|max:8|unique:alumnos,npi|regex:/^\d{6}-\d{1}$/',
            'correo' => 'nullable|email|unique:alumnos,correo',
        ], [
            'rut_dni.unique' => 'Este RUT/DNI ya está registrado',
            'npi.unique' => 'Este NPI ya está registrado',
            'npi.regex' => 'El NPI debe tener el formato 341725-9 (6 dígitos, guión, 1 dígito verificador)',
            'correo.unique' => 'Este correo ya está registrado',
        ]);

        try {
            $alumno = Alumno::create([
                'nombre_completo' => $request->nombre_completo,
                'rut_dni' => $request->rut_dni,
                'npi' => strtoupper($request->npi), // NPI en mayúsculas
                'correo' => $request->correo,
                'is_active' => true,
            ]);

            // Generar QR automáticamente
            $alumno->generarQR();

            return redirect()->route('alumnos.index')
                           ->with('success', 'Alumno creado correctamente. QR generado.');

        } catch (\Exception $e) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Error al crear alumno: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar detalles del alumno
     */
    public function show($id)
    {
        $alumno = Alumno::with(['sesiones' => function($query) {
                            $query->orderBy('fecha', 'desc')->orderBy('hora_inicio', 'desc');
                        }])
                        ->findOrFail($id);

        $estadisticas = [
            'total_sesiones' => $alumno->sesiones->count(),
            'sesiones_activas' => $alumno->sesionesActivas->count(),
            'tiempo_total_minutos' => $alumno->sesiones->where('estado', 'finalizada')->sum('duracion_minutos'),
            'sesiones_instruccion' => $alumno->sesiones->where('es_instruccion', true)->count(),
            'tiempo_instruccion_minutos' => $alumno->sesiones->where('estado', 'finalizada')->where('es_instruccion', true)->sum('duracion_minutos'),
            'promedio_duracion' => $alumno->sesiones->where('estado', 'finalizada')->avg('duracion_minutos'),
            'ultima_sesion' => $alumno->sesiones->first(),
        ];

        return view('alumnos.show', compact('alumno', 'estadisticas'));
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        $alumno = Alumno::findOrFail($id);
        return view('alumnos.edit', compact('alumno'));
    }

    /**
     * Actualizar alumno
     */
    public function update(Request $request, $id)
    {
        $alumno = Alumno::findOrFail($id);

        $request->validate([
            'nombre_completo' => 'required|string|max:255',
            'rut_dni' => 'required|string|max:20|unique:alumnos,rut_dni,' . $id,
            'npi' => 'required|string|max:8|unique:alumnos,npi,' . $id . '|regex:/^\d{6}-\d{1}$/',
            'correo' => 'nullable|email|unique:alumnos,correo,' . $id,
            'is_active' => 'required|boolean',
        ]);

        try {
            $npiAnterior = $alumno->npi;
            
            $alumno->update([
                'nombre_completo' => $request->nombre_completo,
                'rut_dni' => $request->rut_dni,
                'npi' => strtoupper($request->npi),
                'correo' => $request->correo,
                'is_active' => $request->is_active,
            ]);

            // Si cambió el NPI, regenerar QR
            if ($npiAnterior !== $alumno->npi) {
                $alumno->generarQR();
            }

            return redirect()->route('alumnos.index')
                           ->with('success', 'Alumno actualizado correctamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Error al actualizar alumno: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar alumno
     */
    public function destroy($id)
    {
        try {
            $alumno = Alumno::findOrFail($id);
            
            // Verificar si tiene sesiones activas
            if ($alumno->sesionesActivas->count() > 0) {
                return redirect()->back()
                               ->with('error', 'No se puede eliminar: el alumno tiene sesiones activas');
            }

            $alumno->delete();

            return redirect()->route('alumnos.index')
                           ->with('success', 'Alumno eliminado correctamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Error al eliminar alumno: ' . $e->getMessage());
        }
    }

    /**
     * Regenerar QR del alumno
     */
    public function regenerarQR($id)
    {
        try {
            $alumno = Alumno::findOrFail($id);
            $alumno->generarQR();

            return redirect()->back()
                           ->with('success', 'Código QR regenerado correctamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Error al regenerar QR: ' . $e->getMessage());
        }
    }

    /**
     * Descargar QR del alumno
     */
    public function descargarQR($id)
    {
        try {
            $alumno = Alumno::findOrFail($id);
            
            if (!$alumno->qr_image_path) {
                $alumno->generarQR();
            }

            $filePath = public_path($alumno->qr_image_path);
            
            if (!file_exists($filePath)) {
                $alumno->generarQR();
                $filePath = public_path($alumno->qr_image_path);
            }

            return response()->download($filePath, "QR_{$alumno->npi}.svg");

        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Error al descargar QR: ' . $e->getMessage());
        }
    }

    /**
     * Obtener datos del QR para AJAX
     */
    public function qrData($id)
    {
        try {
            $alumno = Alumno::findOrFail($id);
            
            // Generar QR si no existe
            if (!$alumno->qr_image_path) {
                $alumno->generarQR();
            }

            return response()->json([
                'success' => true,
                'nombre' => $alumno->nombre_completo,
                'npi' => $alumno->npi,
                'qr_svg' => $alumno->qr_svg,
                'qr_url' => $alumno->qr_url
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Activar/Desactivar alumno
     */
    public function toggleEstado($id)
    {
        try {
            $alumno = Alumno::findOrFail($id);
            
            // No se puede desactivar si tiene sesiones activas
            if ($alumno->is_active && $alumno->sesionesActivas->count() > 0) {
                return redirect()->back()
                               ->with('error', 'No se puede desactivar: el alumno tiene sesiones activas');
            }

            $alumno->update([
                'is_active' => !$alumno->is_active
            ]);

            $estado = $alumno->is_active ? 'activado' : 'desactivado';
            
            return redirect()->back()
                           ->with('success', "Alumno {$estado} correctamente.");

        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Error al cambiar estado: ' . $e->getMessage());
        }
    }
}