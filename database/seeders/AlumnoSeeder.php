<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Alumno;

class AlumnoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🎓 Iniciando creación de alumnos...');
        
        $alumnos = [
            [
                'nombre_completo' => 'Juan Carlos Pérez González',
                'rut_dni' => '12.345.678-9',
                'npi' => '341725-9', // Tu ejemplo
                'correo' => 'juan.perez@email.com',
                'is_active' => true,
            ],
            [
                'nombre_completo' => 'María Fernanda Silva López',
                'rut_dni' => '98.765.432-1',
                'npi' => '287463-5',
                'correo' => 'maria.silva@email.com',
                'is_active' => true,
            ],
            [
                'nombre_completo' => 'Carlos Eduardo Ramírez Soto',
                'rut_dni' => '11.222.333-4',
                'npi' => '195028-7',
                'correo' => 'carlos.ramirez@email.com',
                'is_active' => true,
            ],
            [
                'nombre_completo' => 'Ana Victoria Morales Castro',
                'rut_dni' => '55.666.777-8',
                'npi' => '763421-2',
                'correo' => 'ana.morales@email.com',
                'is_active' => true,
            ],
            [
                'nombre_completo' => 'Diego Alejandro Torres Mendez',
                'rut_dni' => '22.333.444-5',
                'npi' => '452189-0',
                'correo' => 'diego.torres@email.com',
                'is_active' => true,
            ],
        ];

        foreach ($alumnos as $index => $alumnoData) {
            try {
                $this->command->info("Creando alumno " . ($index + 1) . "/" . count($alumnos) . "...");
                
                $alumno = Alumno::updateOrCreate(
                    ['npi' => $alumnoData['npi']],
                    $alumnoData
                );
                
                $this->command->info("✅ {$alumno->nombre_completo} (NPI: {$alumno->npi})");
                
            } catch (\Exception $e) {
                $this->command->error("❌ Error: " . $e->getMessage());
            }
        }

        $this->command->info('');
        $this->command->info('🎓 Total alumnos: ' . \App\Models\Alumno::count());
        $this->command->info('📱 Los códigos QR se generarán cuando sea necesario');
        $this->command->info('ℹ️  Para generar QR manualmente: $alumno->generarQR()');
    }
}