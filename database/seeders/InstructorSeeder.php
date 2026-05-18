<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class InstructorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Instructor Sala',
            'email' => 'instructor@simulador.local',
            'role' => 'instructor',
            'is_active' => 1,
            // Hash::make() encripta la contraseña automáticamente
            'password' => Hash::make('instructor123'), 
        ]);
    }
}