<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trabajadore extends Model
{
    protected $table = 'trabajadores';

    protected $fillable = [
        'persona_id'
    ];

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'persona_id');
    }

    public function trabajadores_cargos()
    {
        return $this->hasMany(TrabajadoresCargo::class, 'trabajadore_id');
    }

    // En app/Models/Trabajadore.php
    public function determinarArea()
    {
        $cargoPrincipal = $this->trabajadores_cargos()
            ->where('principal', 1)
            ->where('estado', 'Vigente')
            ->with('cargo')
            ->first();

        if (!$cargoPrincipal || !$cargoPrincipal->cargo) {
            return 'Otros';
        }

        $cargoNombre = $cargoPrincipal->cargo->nombre;

        // Mapear cargos a áreas
        $areas = [
            'Académica' => ['Director Académico', 'Encargado Académico', 'Asesor Académico', 'Coordinador Académico'],
            'Marketing' => ['Gerente de Marketing', 'Ejecutivo de Marketing', 'Asesor de Marketing'],
            'Contable' => ['Director Financiera Contable', 'Ejecutivo Contable', 'Asistente Contable'],
            'Administrativa' => ['Administrador', 'Secretaria', 'Recepción']
        ];

        foreach ($areas as $area => $cargos) {
            if (in_array($cargoNombre, $cargos)) {
                return $area;
            }
        }

        return 'Otros';
    }
}
