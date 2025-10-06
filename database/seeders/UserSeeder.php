<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Usuario Administrador (TÚ)
        User::create([
            'name' => 'Administrador Principal',
            'email' => 'admin@simulador.local',
            'password' => Hash::make('admin123'), // Cambiar en producción
            'role' => 'admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Usuario Invitado/Operador (para la sala del simulador)
        User::create([
            'name' => 'Operador Sala',
            'email' => 'operador@simulador.local',
            'password' => Hash::make('operador123'), // Password simple para uso rápido
            'role' => 'invitado',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Usuario Invitado adicional (por si hay más operadores)
        User::create([
            'name' => 'Operador Turno',
            'email' => 'turno@simulador.local',
            'password' => Hash::make('turno123'),
            'role' => 'invitado',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $this->command->info('👑 ADMIN creado - Acceso completo al sistema');
        $this->command->info('📧 Email: admin@simulador.local');
        $this->command->info('🔑 Password: admin123');
        $this->command->info('');
        $this->command->info('👤 OPERADORES creados - Solo registro de sesiones');
        $this->command->info('📧 Email: operador@simulador.local / turno@simulador.local');
        $this->command->info('🔑 Password: operador123 / turno123');
        $this->command->info('');
        $this->command->info('ℹ️  Los ALUMNOS no son usuarios, solo se registran con QR');
    }
}