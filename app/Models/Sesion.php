<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Sesion extends Model
{
    use HasFactory;

    protected $table = 'sesiones';

    protected $fillable = [
        'alumno_id',
        'npi',
        'fecha',
        'hora_inicio',
        'hora_fin',
        'duracion_minutos',
        'actividad',
        'estado',
        'usuario_inicio_id',
        'usuario_fin_id',
        'detalles',
        'observaciones',
        'archivo_vuelo',
    ];

    protected $casts = [
        'fecha' => 'date',
        'hora_inicio' => 'datetime',
        'hora_fin' => 'datetime',
        'detalles' => 'array',
    ];

    // ===== RELACIONES =====
    
    /**
     * Alumno que realizó la sesión
     */
    public function alumno()
    {
        return $this->belongsTo(Alumno::class);
    }

    /**
     * Usuario que inició la sesión
     */
    public function usuarioInicio()
    {
        return $this->belongsTo(User::class, 'usuario_inicio_id');
    }

    /**
     * Usuario que finalizó la sesión
     */
    public function usuarioFin()
    {
        return $this->belongsTo(User::class, 'usuario_fin_id');
    }

    // ===== MÉTODOS ÚTILES =====
    
    /**
     * Verificar si la sesión está activa
     */
    public function estaActiva(): bool
    {
        return $this->estado === 'activa';
    }

    /**
     * Verificar si la sesión está finalizada
     */
    public function estaFinalizada(): bool
    {
        return $this->estado === 'finalizada';
    }

    /**
     * Calcular duración en minutos
     */
    public function calcularDuracion(): ?int
    {
        if (!$this->hora_fin) {
            return null;
        }
        
        return $this->hora_inicio->diffInMinutes($this->hora_fin);
    }

    /**
     * Finalizar la sesión
     */
    public function finalizar($usuarioId = null): void
    {
        $this->update([
            'hora_fin' => now(),
            'estado' => 'finalizada',
            'usuario_fin_id' => $usuarioId,
            'duracion_minutos' => $this->calcularDuracion(),
        ]);
    }

    /**
     * Obtener duración formateada (ej: "2h 30m")
     */
    public function getDuracionFormateadaAttribute(): string
    {
        if (!$this->duracion_minutos) {
            return '-';
        }
        
        $horas = intdiv($this->duracion_minutos, 60);
        $minutos = $this->duracion_minutos % 60;
        
        if ($horas > 0) {
            return $horas . 'h ' . ($minutos > 0 ? $minutos . 'm' : '');
        }
        
        return $minutos . 'm';
    }

    /**
     * Obtener tiempo transcurrido si está activa
     */
    public function getTiempoTranscurridoAttribute(): string
    {
        if ($this->estado !== 'activa') {
            return $this->duracion_formateada;
        }
        
        $minutos = $this->hora_inicio->diffInMinutes(now());
        $horas = intdiv($minutos, 60);
        $minutosRestantes = $minutos % 60;
        
        if ($horas > 0) {
            return $horas . 'h ' . ($minutosRestantes > 0 ? $minutosRestantes . 'm' : '');
        }
        
        return $minutosRestantes . 'm';
    }

    /**
     * Verificar si la sesión necesita atención (muy larga)
     */
    public function necesitaAtencion(): bool
    {
        if ($this->estado !== 'activa') {
            return false;
        }
        
        $minutosTranscurridos = $this->hora_inicio->diffInMinutes(now());
        return $minutosTranscurridos > config('simulador.max_sesion_duration', 480); // 8 horas por defecto
    }

    /**
     * Obtener color del estado para la UI
     */
    public function getColorEstadoAttribute(): string
    {
        return match($this->estado) {
            'activa' => 'warning',
            'finalizada' => 'success',
            'cancelada' => 'danger',
            default => 'secondary'
        };
    }

    // ===== SCOPES =====
    
    /**
     * Scope para sesiones activas
     */
    public function scopeActivas($query)
    {
        return $query->where('estado', 'activa');
    }

    /**
     * Scope para sesiones finalizadas
     */
    public function scopeFinalizadas($query)
    {
        return $query->where('estado', 'finalizada');
    }

    /**
     * Scope para sesiones de hoy
     */
    public function scopeHoy($query)
    {
        return $query->whereDate('fecha', today());
    }

    /**
     * Scope para sesiones por fecha
     */
    public function scopePorFecha($query, $fecha)
    {
        return $query->whereDate('fecha', $fecha);
    }

    /**
     * Scope para sesiones por rango de fechas
     */
    public function scopePorRangoFechas($query, $fechaInicio, $fechaFin)
    {
        return $query->whereBetween('fecha', [$fechaInicio, $fechaFin]);
    }

    /**
     * Scope para sesiones por mes
     */
    public function scopePorMes($query, $año, $mes)
    {
        return $query->whereYear('fecha', $año)->whereMonth('fecha', $mes);
    }

    /**
     * Scope para sesiones por alumno
     */
    public function scopePorAlumno($query, $alumnoId)
    {
        return $query->where('alumno_id', $alumnoId);
    }

    /**
     * Scope para sesiones que necesitan atención
     */
    public function scopeNecesitanAtencion($query)
    {
        $limitMinutos = config('simulador.max_sesion_duration', 480);
        return $query->where('estado', 'activa')
                    ->where('hora_inicio', '<=', now()->subMinutes($limitMinutos));
    }

    // ===== EVENTOS DEL MODELO =====
    
    /**
     * Boot del modelo
     */
    protected static function boot()
    {
        parent::boot();
        
        // Al crear una sesión, establecer fecha automáticamente
        static::creating(function ($sesion) {
            if (!$sesion->fecha) {
                $sesion->fecha = today();
            }
        });
        
        // Al actualizar, recalcular duración si cambia hora_fin
        static::updating(function ($sesion) {
            if ($sesion->isDirty('hora_fin') && $sesion->hora_fin) {
                $sesion->duracion_minutos = $sesion->calcularDuracion();
            }
        });
    }
}