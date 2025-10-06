<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🚀 Iniciando seeders del Registro Simulador...');
        $this->command->info('');
        
        // 1. Crear usuarios del sistema (admin y operadores)
        $this->command->info('👥 Creando usuarios del sistema...');
        $this->call(UserSeeder::class);
        
        $this->command->info('');
        
        // 2. Crear alumnos de prueba
        $this->command->info('🎓 Creando alumnos de prueba...');
        $this->call(AlumnoSeeder::class);
        
        $this->command->info('');
        
        // // 3. Crear historial de sesiones (opcional)
        // $this->command->info('📊 ¿Crear historial de sesiones de prueba? (opcional)');
        // $this->call(SesionSeeder::class);
        
        $this->command->info('');
        $this->command->info('🎉 ¡Seeders completados exitosamente!');
        $this->command->info('');
        $this->command->info('📋 RESUMEN:');
        $this->command->info('  👑 1 Administrador (acceso completo)');
        $this->command->info('  👤 2 Operadores (solo registro de sesiones)');
        $this->command->info('  🎓 ' . \App\Models\Alumno::count() . ' Alumnos registrados');
        $this->command->info('  📈 ' . \App\Models\Sesion::count() . ' Sesiones de ejemplo');
        $this->command->info('');
        $this->command->info('🔐 CREDENCIALES:');
        $this->command->info('  ADMIN: admin@simulador.local / admin123');
        $this->command->info('  OPERADOR: operador@simulador.local / operador123');
        $this->command->info('');
        $this->command->info('📱 Los códigos QR están listos en public/qr-codes/');
    }
}