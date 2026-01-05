<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Convenio;
use App\Models\OfertasAcademica;
use App\Models\Sucursale;
use App\Models\Tipo;
use App\Models\Trabajadore;
use Illuminate\Http\Request;

class BienvenidosController extends Controller
{
    public function principal()
    {
        $tipos = Tipo::all();
        $convenios = Convenio::all();
        $sucursales = Sucursale::all();

        // Obtener sucursales con ofertas académicas en fase de inscripciones
        $sucursalesDisponibles = Sucursale::whereHas('ofertas_academicas', function ($query) {
            $query->whereHas('fase', function ($q) {
                $q->where('nombre', 'Inscripciones');
            });
        })->get();

        // Obtener ofertas académicas con relaciones (para la sección principal)
        $ofertas = OfertasAcademica::with([
            'posgrado.area',
            'posgrado.convenio',
            'posgrado.tipo',
            'sucursal',
            'modalidad',
            'fase',
            'plan_concepto' => function ($query) {
                $query->whereHas('plan_pago', function ($q) {
                    $q->where('nombre', 'Al Contado');
                })->with(['plan_pago', 'concepto']);
            }
        ])
            ->whereHas('fase', function ($query) {
                $query->where('nombre', 'Inscripciones');
            })
            ->whereHas('plan_concepto.plan_pago', function ($query) {
                $query->where('nombre', 'Al Contado');
            })
            ->orderBy('fecha_inicio_programa', 'asc')
            ->get();

        // Obtener ofertas en fase 2 (para el modal)
        $ofertasFase2 = OfertasAcademica::with([
            'posgrado.area',
            'posgrado.convenio',
            'posgrado.tipo',
            'sucursal',
            'modalidad',
            'fase',
            'plan_concepto' => function ($query) {
                $query->with(['plan_pago', 'concepto']);
            }
        ])
            ->whereHas('fase', function ($query) {
                $query->where('n_fase', 3); // Filtrar por fase 2
            })
            ->orderBy('fecha_inicio_programa', 'asc')
            ->take(3) // Limitar a 3 ofertas para el modal
            ->get();

        // OPTIMIZADO: Consulta para obtener trabajadores con cargos principales vigentes
        // Incluye tanto cargos con sucursal como gerenciales sin sucursal
        $trabajadores = Trabajadore::with([
            'persona',
            'trabajadores_cargos' => function ($query) {
                $query->where('principal', 1)
                    ->where('estado', 'Vigente')
                    ->whereIn('cargo_id', [2, 3, 6]) // Solo cargos 2, 3 y 6
                    ->with(['cargo', 'sucursal.sede']);
            }
        ])
            ->whereHas('trabajadores_cargos', function ($query) {
                $query->where('principal', 1)
                    ->where('estado', 'Vigente')
                    ->whereIn('cargo_id', [2, 3, 6]); // Solo cargos 2, 3 y 6
            })
            // Ordenar por área/cargo para mejor organización
            ->orderBy('id')
            ->get()
            ->map(function ($trabajador) {
                // Agregar información adicional para facilitar la vista
                $cargoPrincipal = $trabajador->trabajadores_cargos->first();
                $trabajador->cargo_nombre = $cargoPrincipal->cargo->nombre ?? 'Sin cargo';
                $trabajador->es_gerencial = in_array(
                    $cargoPrincipal->cargo->nombre ?? '',
                    ['Director Académico', 'Gerente de Marketing', 'Director Financiera Contable']
                );
                $trabajador->area = $trabajador->determinarArea();
                return $trabajador;
            })
            ->groupBy('area');

        return view('welcome', compact(
            'tipos',
            'convenios',
            'sucursales',
            'ofertas',
            'sucursalesDisponibles',
            'trabajadores',
            'ofertasFase2' // Nueva variable para el modal
        ));
    }
}
