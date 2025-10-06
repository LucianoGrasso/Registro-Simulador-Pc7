<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // ===== MÉTODOS DE ROLES =====
    
    /**
     * Verificar si el usuario es administrador
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Verificar si el usuario es invitado
     */
    public function isInvitado(): bool
    {
        return $this->role === 'invitado';
    }

    /**
     * Verificar si el usuario está activo
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    // ===== RELACIONES =====
    
    /**
     * Sesiones iniciadas por este usuario
     */
    public function sesionesIniciadas()
    {
        return $this->hasMany(Sesion::class, 'usuario_inicio_id');
    }

    /**
     * Sesiones finalizadas por este usuario
     */
    public function sesionesFinalizadas()
    {
        return $this->hasMany(Sesion::class, 'usuario_fin_id');
    }

    // ===== SCOPES =====
    
    /**
     * Scope para usuarios activos
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope para administradores
     */
    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    /**
     * Scope para invitados
     */
    public function scopeInvitados($query)
    {
        return $query->where('role', 'invitado');
    }
}