<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Instructor extends Model
{
    protected $table = 'instructores';
    protected $fillable = ['npi', 'grado_nombre', 'activo', 'pin'];
    public function sesiones()
    {
        // Un instructor tiene muchas sesiones, vinculadas por la columna 'npi'
        return $this->hasMany(Sesion::class, 'instructor_npi', 'npi');
    }

    // Relación con el instructor (si la sesión es de instrucción)
    public function instructor()
    {
        return $this->belongsTo(Instructor::class, 'instructor_npi', 'npi');
    }
}
