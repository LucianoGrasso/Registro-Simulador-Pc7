<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Soporte extends Model
{
    use HasFactory;

    protected $table = 'soporte';

    protected $fillable = [
        'user_id',
        'tipo',
        'titulo',
        'descripcion',
        'prioridad',
        'estado',
        'respuesta_admin',
        'fecha_resolucion'
    ];

    protected $casts = [
        'fecha_resolucion' => 'datetime',
    ];

    /**
     * Relación con el usuario que creó el ticket
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scope para filtrar por estado
     */
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    public function scopeResueltos($query)
    {
        return $query->where('estado', 'resuelto');
    }

    /**
     * Scope para filtrar por tipo
     */
    public function scopeFallas($query)
    {
        return $query->where('tipo', 'falla');
    }

    public function scopeSugerencias($query)
    {
        return $query->where('tipo', 'sugerencia');
    }

    /**
     * Obtener color según prioridad
     */
    public function getColorPrioridadAttribute()
    {
        return match($this->prioridad) {
            'alta' => 'red',
            'media' => 'yellow',
            'baja' => 'green',
            default => 'gray'
        };
    }

    /**
     * Obtener color según estado
     */
    public function getColorEstadoAttribute()
    {
        return match($this->estado) {
            'pendiente' => 'yellow',
            'en_revision' => 'blue',
            'resuelto' => 'green',
            'rechazado' => 'red',
            default => 'gray'
        };
    }

    /**
     * Obtener etiqueta de prioridad
     */
    public function getPrioridadLabelAttribute()
    {
        return match($this->prioridad) {
            'alta' => 'Alta',
            'media' => 'Media',
            'baja' => 'Baja',
            default => 'Sin definir'
        };
    }

    /**
     * Obtener etiqueta de estado
     */
    public function getEstadoLabelAttribute()
    {
        return match($this->estado) {
            'pendiente' => 'Pendiente',
            'en_revision' => 'En Revisión',
            'resuelto' => 'Resuelto',
            'rechazado' => 'Rechazado',
            default => 'Sin estado'
        };
    }
}