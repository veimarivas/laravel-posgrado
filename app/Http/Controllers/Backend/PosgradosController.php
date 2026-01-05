<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Cargo;
use App\Models\Concepto;
use App\Models\Convenio;
use App\Models\Modalidade;
use App\Models\OfertasAcademica;
use App\Models\PlanesPago;
use App\Models\Posgrado;
use App\Models\Sede;
use App\Models\Tipo;
use App\Models\TrabajadoresCargo;
use Illuminate\Http\Request;

class PosgradosController extends Controller
{
    public function posgradoslistar(Request $request)
    {
        $query = Posgrado::with('area', 'tipo', 'convenio');

        // Pasar el año actual a la vista
        $currentYear = now()->year;

        // Búsqueda por nombre
        if ($request->filled('search')) {
            $query->where('nombre', 'like', '%' . $request->search . '%');
        }

        // Filtros por relaciones
        if ($request->filled('area_id')) {
            $query->where('area_id', $request->area_id);
        }

        if ($request->filled('tipo_id')) {
            $query->where('tipo_id', $request->tipo_id);
        }

        if ($request->filled('convenio_id')) {
            $query->where('convenio_id', $request->convenio_id);
        }

        $posgrados = $query->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.posgrados.partials.table-body', compact('posgrados'))->render(),
                'pagination' => $posgrados->links('pagination::bootstrap-5')->toHtml()
            ]);
        }

        // === Datos para filtros y modales ===
        $areas = Area::all();
        $tipos = Tipo::all();
        $convenios = Convenio::all();
        $planesPagos = PlanesPago::all();
        $conceptos = Concepto::all();

        // === Datos para el modal de registrar oferta académica ===
        $sedes = Sede::with('sucursales')->get(); // Carga sedes con sus sucursales
        $modalidades = Modalidade::all();

        // IDs de cargos que contienen "Académico" o "Marketing"
        $idsCargosAcademicos = Cargo::where('nombre', 'like', '%Académico%')->pluck('id');
        $idsCargosMarketing  = Cargo::where('nombre', 'like', '%Marketing%')->pluck('id');

        // Trabajadores con cargo académico Y estado = 'Vigente'
        $trabajadoresAcademicos = collect();
        if ($idsCargosAcademicos->isNotEmpty()) {
            $trabajadoresAcademicos = TrabajadoresCargo::with('trabajador.persona', 'cargo')
                ->whereIn('cargo_id', $idsCargosAcademicos)
                ->where('estado', 'Vigente')
                ->get();
        }

        // Trabajadores con cargo de marketing Y estado = 'Vigente'
        $trabajadoresMarketing = collect();
        if ($idsCargosMarketing->isNotEmpty()) {
            $trabajadoresMarketing = TrabajadoresCargo::with('trabajador.persona', 'cargo')
                ->whereIn('cargo_id', $idsCargosMarketing)
                ->where('estado', 'Vigente')
                ->get();
        }

        return view('admin.posgrados.listar', compact(
            'posgrados',
            'areas',
            'tipos',
            'convenios',
            'sedes',
            'modalidades',
            'trabajadoresAcademicos',
            'trabajadoresMarketing',
            'planesPagos',
            'conceptos',
            'currentYear'
        ));
    }

    // En PosgradosController, método posgradosVer
    public function posgradosVer($id)
    {
        $posgrado = Posgrado::with('area', 'tipo', 'convenio')->findOrFail($id);

        $ofertas = OfertasAcademica::with([
            'sucursal',
            'modalidad',
            'programa',
            'plan_concepto.plan_pago',
            'plan_concepto.concepto',
            'fase'
        ])
            ->where('posgrado_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Agregar estas variables para el modal de planes de pago
        $planesPagos = PlanesPago::all();
        $conceptos = Concepto::all();

        return view('admin.posgrados.ver', compact(
            'posgrado',
            'ofertas',
            'planesPagos',
            'conceptos'
        ));
    }

    public function posgradosRegistrar(Request $request)
    {
        $request->validate([
            'nombre' => 'required|unique:posgrados,nombre,NULL,id,area_id,' . $request->area_id . ',tipo_id,' . $request->tipo_id . ',convenio_id,' . $request->convenio_id,
            'creditaje' => 'required|numeric|min:0',
            'carga_horaria' => 'required|numeric|min:0',
            'duracion_numero' => 'required|numeric|min:1',
            'duracion_unidad' => 'required|in:Días,Meses,Años,Semanas',
            'dirigido' => 'required|string',
            'objetivo' => 'required|string',
            'estado' => 'required|in:activo,inactivo',
            'area_id' => 'required|exists:areas,id',
            'convenio_id' => 'required|exists:convenios,id',
            'tipo_id' => 'required|exists:tipos,id',
        ]);

        $posgrado = Posgrado::create($request->only([
            'nombre',
            'creditaje',
            'carga_horaria',
            'duracion_numero',
            'duracion_unidad',
            'dirigido',
            'objetivo',
            'estado',
            'area_id',
            'convenio_id',
            'tipo_id'
        ]));

        return response()->json([
            'success' => true,
            'msg' => 'Posgrado registrado correctamente.',
        ]);
    }

    // Verificación de combinación única
    public function verificarNombre(Request $request)
    {
        $nombre = trim($request->input('nombre'));
        $areaId = $request->input('area_id');
        $tipoId = $request->input('tipo_id');
        $convenioId = $request->input('convenio_id');
        $id = $request->input('id');

        // Si falta algún dato, no se puede verificar → permitir (no bloquear)
        if (!$nombre || !$areaId || !$tipoId || !$convenioId) {
            return response()->json(['exists' => false]);
        }

        $query = Posgrado::where('nombre', $nombre)
            ->where('area_id', $areaId)
            ->where('tipo_id', $tipoId)
            ->where('convenio_id', $convenioId);

        if ($id && is_numeric($id) && $id > 0) {
            $query->where('id', '!=', $id);
        }

        return response()->json(['exists' => $query->exists()]);
    }

    public function posgradosModificar(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:posgrados,id',
            'nombre' => 'required|unique:posgrados,nombre,' . $request->id . ',id,area_id,' . $request->area_id . ',tipo_id,' . $request->tipo_id . ',convenio_id,' . $request->convenio_id,
            'creditaje' => 'required|numeric|min:0',
            'carga_horaria' => 'required|numeric|min:0',
            'duracion_numero' => 'required|numeric|min:1',
            'duracion_unidad' => 'required|in:Días,Meses,Años,Semanas',
            'dirigido' => 'required|string',
            'objetivo' => 'required|string',
            'estado' => 'required|in:activo,inactivo',
            'area_id' => 'required|exists:areas,id',
            'convenio_id' => 'required|exists:convenios,id',
            'tipo_id' => 'required|exists:tipos,id',
        ]);

        Posgrado::where('id', $request->id)->update($request->only([
            'nombre',
            'creditaje',
            'carga_horaria',
            'duracion_numero',
            'duracion_unidad',
            'dirigido',
            'objetivo',
            'estado',
            'area_id',
            'convenio_id',
            'tipo_id'
        ]));

        return response()->json([
            'success' => true,
            'msg' => 'Posgrado actualizado correctamente.'
        ]);
    }

    public function posgradosEliminar(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:posgrados,id'
        ]);

        Posgrado::where('id', $request->id)->delete();

        return response()->json([
            'success' => true,
            'msg' => 'Posgrado eliminado correctamente.'
        ]);
    }
}
