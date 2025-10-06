<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class Alumno extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre_completo',
        'rut_dni',
        'npi',
        'correo',
        'qr_code',
        'qr_image_path',
        'is_active',
        'metadata',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'metadata' => 'array',
    ];

    // ===== RELACIONES =====
    
    /**
     * Todas las sesiones del alumno
     */
    public function sesiones()
    {
        return $this->hasMany(Sesion::class);
    }

    /**
     * Sesiones activas del alumno
     */
    public function sesionesActivas()
    {
        return $this->hasMany(Sesion::class)->where('estado', 'activa');
    }

    /**
     * Última sesión del alumno
     */
    public function ultimaSesion()
    {
        return $this->hasOne(Sesion::class)->latest('hora_inicio');
    }

    // ===== MÉTODOS ÚTILES =====
    
    /**
     * Verificar si el alumno tiene una sesión activa
     */
    public function tieneSeionActiva(): bool
    {
        return $this->sesionesActivas()->exists();
    }

    /**
     * Obtener la sesión activa del alumno
     */
    public function getSesionActiva()
    {
        return $this->sesionesActivas()->first();
    }

    /**
     * Generar código QR para el alumno
     */
    public function generarQR(): string
    {
        // El QR contiene el NPI del alumno
        $qrContent = $this->npi;
        
        // Crear directorio si no existe
        $qrDirectory = public_path('qr-codes');
        if (!file_exists($qrDirectory)) {
            mkdir($qrDirectory, 0755, true);
        }
        
        // Nombre del archivo SVG
        $fileName = 'qr_' . $this->npi . '.svg';
        $filePath = $qrDirectory . '/' . $fileName;
        
        // Generar QR Code en formato SVG
        $svgContent = QrCode::format('svg')
            ->size(300)
            ->margin(2)
            ->generate($qrContent);
        
        // Guardar el archivo SVG
        file_put_contents($filePath, $svgContent);
        
        // Guardar la ruta en la base de datos
        $this->update([
            'qr_code' => $qrContent,
            'qr_image_path' => 'qr-codes/' . $fileName
        ]);
        
        return 'qr-codes/' . $fileName;
    }

    /**
     * Obtener la URL completa del QR
     */
    public function getQrUrlAttribute(): string
    {
        if (!$this->qr_image_path) {
            $this->generarQR();
        }
        
        return asset($this->qr_image_path);
    }

    /**
     * Obtener el contenido SVG del QR directamente
     */
    public function getQrSvgAttribute(): string
    {
        if (!$this->qr_image_path) {
            $this->generarQR();
        }
        
        $filePath = public_path($this->qr_image_path);
        
        if (file_exists($filePath)) {
            return file_get_contents($filePath);
        }
        
        // Si no existe el archivo, generarlo de nuevo
        $this->generarQR();
        return file_get_contents(public_path($this->qr_image_path));
    }

    /**
     * Formatear RUT/DNI para mostrar
     */
    public function getRutFormateadoAttribute(): string
    {
        // Si es RUT chileno (contiene guión), ya está formateado
        if (str_contains($this->rut_dni, '-')) {
            return $this->rut_dni;
        }
        
        // Si es solo números, asumir que es RUT y formatear
        if (is_numeric(str_replace(['.', '-'], '', $this->rut_dni))) {
            $rut = str_replace(['.', '-'], '', $this->rut_dni);
            if (strlen($rut) >= 8) {
                $verificador = substr($rut, -1);
                $numero = substr($rut, 0, -1);
                return number_format($numero, 0, '', '.') . '-' . $verificador;
            }
        }
        
        return $this->rut_dni;
    }

    /**
     * Obtener total de horas utilizadas
     */
    public function getTotalHorasAttribute(): int
    {
        return $this->sesiones()
                   ->where('estado', 'finalizada')
                   ->sum('duracion_minutos');
    }

    /**
     * Obtener número de sesiones completadas
     */
    public function getTotalSesionesAttribute(): int
    {
        return $this->sesiones()->count();
    }

    // ===== SCOPES =====
    
    /**
     * Scope para alumnos activos
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope para buscar por nombre
     */
    public function scopeBuscarPorNombre($query, $nombre)
    {
        return $query->where('nombre_completo', 'like', '%' . $nombre . '%');
    }

    /**
     * Scope para buscar por NPI
     */
    public function scopePorNpi($query, $npi)
    {
        return $query->where('npi', $npi);
    }

    /**
     * Scope para alumnos con sesiones activas
     */
    public function scopeConSesionActiva($query)
    {
        return $query->whereHas('sesionesActivas');
    }

    // ===== EVENTOS DEL MODELO =====
    
    /**
     * Boot del modelo - COMENTADO PARA DEBUG
     */
    protected static function boot()
    {
        parent::boot();
        
        // TODO: Descomentar cuando funcione el seeder básico
        /*
        // Al crear un alumno, generar automáticamente el QR
        static::created(function ($alumno) {
            $alumno->generarQR();
        });
        
        // Si se actualiza el NPI, regenerar el QR
        static::updated(function ($alumno) {
            if ($alumno->wasChanged('npi')) {
                $alumno->generarQR();
            }
        });
        */
    }
}