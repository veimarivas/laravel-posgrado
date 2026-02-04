<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    protected $table = 'users';

    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'estado',
        'persona_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'persona_id');
    }

    // Relación para obtener el trabajador
    public function trabajador()
    {
        return $this->hasOneThrough(
            Trabajadore::class,
            Persona::class,
            'id',          // Foreign key on Persona table
            'persona_id',  // Foreign key on Trabajadore table
            'persona_id',  // Local key on User table
            'id'           // Local key on Persona table
        );
    }

    // Relación para obtener el cargo principal del trabajador
    public function trabajadorCargoPrincipal()
    {
        return $this->hasOneThrough(
            TrabajadoresCargo::class,
            Trabajadore::class,
            'persona_id',      // Foreign key on Trabajadore table
            'trabajadore_id',  // Foreign key on TrabajadoresCargo table
            'persona_id',      // Local key on User table
            'id'               // Local key on Trabajadore table
        )->where('principal', 1)->where('estado', 'Vigente');
    }

    // Método helper para obtener el trabajadore_cargo_id
    public function getTrabajadorCargoIdAttribute()
    {
        return $this->trabajadorCargoPrincipal->id ?? null;
    }

    // Método para verificar si el usuario es trabajador
    public function esTrabajador()
    {
        return $this->trabajador !== null;
    }

    // Método para obtener el área del trabajador
    public function getAreaAttribute()
    {
        if ($this->trabajadorCargoPrincipal && $this->trabajadorCargoPrincipal->cargo) {
            $cargoNombre = $this->trabajadorCargoPrincipal->cargo->nombre;

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
        }

        return 'Otros';
    }
}
