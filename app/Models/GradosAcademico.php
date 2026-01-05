<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GradosAcademico extends Model
{
    protected $table = 'grados_academicos';

    protected $fillable = [
        'nombre'
    ];

    public function estudios()
    {
        return $this->hasMany(Estudio::class, 'grados_academico_id');
    }
}
