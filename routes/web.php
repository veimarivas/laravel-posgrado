<?php

use App\Http\Controllers\Backend\AdminController;
use App\Http\Controllers\Backend\AreasController;
use App\Http\Controllers\Backend\BienvenidosController;
use App\Http\Controllers\Backend\CargosController;
use App\Http\Controllers\Backend\CiudadesController;
use App\Http\Controllers\Backend\ConceptosController;
use App\Http\Controllers\Backend\ConveniosController;
use App\Http\Controllers\Backend\CuentasController;
use App\Http\Controllers\Backend\DepartamentosController;
use App\Http\Controllers\Backend\DocentesController;
use App\Http\Controllers\Backend\EstudiantesController;
use App\Http\Controllers\Backend\FasesController;
use App\Http\Controllers\Backend\GradosAcademicosController;
use App\Http\Controllers\Backend\InscripcionesController;
use App\Http\Controllers\Backend\ModalidadesController;
use App\Http\Controllers\Backend\OfertasAcademicasController;
use App\Http\Controllers\Backend\PermissionsController;
use App\Http\Controllers\Backend\PersonasController;
use App\Http\Controllers\Backend\PlanesPagosController;
use App\Http\Controllers\Backend\PosgradosController;
use App\Http\Controllers\Backend\ProfesionesController;
use App\Http\Controllers\Backend\ProgramasController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\RolePermissionsController;
use App\Http\Controllers\Backend\RolesController;
use App\Http\Controllers\Backend\SedesController;
use App\Http\Controllers\Backend\SucursalesController;
use App\Http\Controllers\Backend\TiposController;
use App\Http\Controllers\Backend\TrabajadoresController;
use App\Http\Controllers\Backend\UniversidadesController;
use App\Http\Controllers\Backend\UserProfileController;
use App\Http\Controllers\Backend\UsersController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsUser;
use App\Models\OfertasAcademica;
use Illuminate\Support\Facades\Route;


Route::get('/', [BienvenidosController::class, 'principal'])->name('principal');
Route::get('/oferta/{id}', [OfertasAcademicasController::class, 'detallePublico'])
    ->name('oferta.detalle');
Route::get('/oferta/{id}/asesor/{asesorId}', [OfertasAcademicasController::class, 'ofertaConAsesor'])
    ->name('oferta.asesor');
// Pre-inscripción pública con asesor
Route::post('/api/inscripcion-con-asesor', [InscripcionesController::class, 'registrarConAsesor'])
    ->name('api.inscripcion.asesor');


///User Routes
Route::middleware(['auth', IsUser::class])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

///Admin Routes
Route::middleware(['auth', IsAdmin::class])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/dashboard/data', [AdminController::class, 'dashboardData'])->name('admin.dashboard.data');
    Route::get('/admin/vendedor/inscripciones/{personaId}', [AdminController::class, 'verInscripcionesVendedor'])
        ->name('admin.vendedor.inscripciones');
    Route::get('/admin/vendedor/{personaId}/data', [AdminController::class, 'vendedorData'])->name('admin.vendedor.data');
    Route::get('/admin/logout', [AdminController::class, 'AdminLogout'])->name('admin.logout');

    // TRABAJADORES
    Route::controller(TrabajadoresController::class)->group(function () {
        Route::get('/admin/trabajadores/listar', 'listar')->name('admin.trabajadores.listar');
        Route::post('/admin/trabajadores/verificar-carnet', 'verificarCarnet')->name('admin.trabajadores.verificar-carnet');
        Route::post('/admin/trabajadores/registrar', 'registrar')->name('admin.trabajadores.registrar');
        Route::delete('/admin/trabajadores/eliminar', 'eliminar')->name('admin.trabajadores.eliminar');
        Route::post('/admin/trabajadores/registrar-persona-trabajador', 'registrarPersonaYTrabajador')->name('admin.trabajadores.registrar-persona-trabajador');

        Route::post('/admin/trabajadores/cargo/actualizar-estado', 'actualizarEstadoCargo')->name('admin.trabajadores.cargo.actualizar-estado');
        Route::post('/admin/trabajadores/verificar-cargo-existente', 'verificarCargoExistente')->name('admin.trabajadores.verificar-cargo-existente');
        Route::post('/admin/trabajadores/asignar-nuevo-cargo', 'asignarNuevoCargo')->name('admin.trabajadores.asignar-nuevo-cargo');
        Route::get('/admin/trabajadores/sucursales-disponibles', 'sucursalesDisponibles')->name('admin.trabajadores.sucursales-disponibles');
        Route::get('/admin/trabajadores/cargos-disponibles', 'cargosDisponibles')->name('admin.trabajadores.cargos-disponibles');

        // Dentro del grupo de rutas de TrabajadoresController
        Route::post('/admin/trabajadores/subir-foto', 'subirFoto')->name('admin.trabajadores.subir-foto');

        // En el grupo de TrabajadoresController
        Route::post('/admin/trabajadores/cargo/actualizar-principal', 'actualizarPrincipalCargo')->name('admin.trabajadores.cargo.actualizar-principal');

        // Rutas para gestión de usuarios de trabajadores
        Route::post('/admin/trabajadores/verificar-usuario', 'verificarUsuarioExistente')->name('admin.trabajadores.verificar-usuario');
        Route::post('/admin/trabajadores/registrar-usuario', 'registrarUsuarioTrabajador')->name('admin.trabajadores.registrar-usuario');
        Route::get('/admin/trabajadores/roles-disponibles', 'obtenerRolesDisponibles')->name('admin.trabajadores.roles-disponibles');

        Route::get('/admin/vendedores/listar', 'listarVendedores')->name('admin.vendedores.listar');
    });



    //SUCURSALES
    Route::controller(SucursalesController::class)->group(function () {
        Route::post('/admin/sucursales/registrar', 'registrar')->name('admin.sucursales.registrar');
        Route::post('/admin/sucursales/verificar', 'verificarNombre')->name('admin.sucursales.verificar');
        Route::get('/sucursales/por-sede', 'porSede')->name('admin.sucursales.por-sede');
    });

    //PROGRAMAS
    Route::controller(ProgramasController::class)->group(function () {
        Route::post('/programas/buscar-o-crear', 'buscarOCrear')->name('admin.programas.buscar-o-crear');
    });

    //OFERTAS ACADEMICAS
    Route::controller(OfertasAcademicasController::class)->group(function () {
        Route::post('/ofertas-academicas/registrar', 'registrar')->name('admin.ofertas-academicas.registrar');
        Route::post('/admin/ofertas-academicas/verificar-codigo', 'verificarCodigo')->name('admin.ofertas-academicas.verificar-codigo');
        // OFERTAS ACADEMICAS - LISTAR TODAS (por sucursal)
        Route::get('/admin/ofertas/listar', 'listar')->name('admin.ofertas.listar');
        Route::get('/admin/ofertas/ver/{id}', 'vermodulos')->name('admin.ofertas.vermodulos');
        Route::post('/admin/modulos/asignar-horarios', 'asignarHorarios')->name('admin.modulos.asignar-horarios');
        Route::get('/admin/modulos/horarios/{modulo}', 'obtenerHorarios')->name('admin.modulos.obtener-horarios');
        Route::post('/admin/horarios/actualizar-estado', 'actualizarEstadoHorario')->name('admin.horarios.actualizar-estado');
        Route::post('/admin/modulos/actualizar-color', 'actualizarColorModulo')->name('admin.modulos.actualizar-color');
        // Nueva ruta para obtener los eventos de un módulo en formato FullCalendar
        Route::get('/admin/modulos/eventos/{modulo}', 'obtenerEventosModulo')->name('admin.modulos.obtener-eventos');
        Route::post('/admin/horarios/reprogramar', 'reprogramarHorario')->name('admin.horarios.reprogramar');

        // OFERTAS ACADEMICAS - CRONOGRAMA GENERAL
        Route::get('/admin/ofertas/cronograma', 'cronograma')->name('admin.ofertas.cronograma');
        Route::get('/admin/ofertas/cronograma/eventos', 'obtenerEventosCronograma')->name('admin.ofertas.cronograma.eventos');
        Route::get('/admin/ofertas/json', 'listarJson');
        Route::get('/admin/ofertas/filtradas', 'listarOfertasFiltradas')->name('admin.ofertas.filtradas');

        Route::post('/admin/ofertas/{oferta}/cambiar-fase', 'cambiarFase')->name('admin.ofertas.cambiar-fase');
        Route::get('/admin/ofertas/{id}/editar', 'obtenerOfertaParaEdicion')->name('admin.ofertas.editar');
        Route::post('/admin/ofertas/actualizar', 'actualizar')->name('admin.ofertas.actualizar');

        Route::post('/admin/ofertas/fase2/actualizar', 'actualizarFase2')->name('admin.ofertas.fase2.actualizar');
        Route::get('/admin/ofertas/{id}/datos', 'obtenerDatosOferta')->name('admin.ofertas.datos');

        Route::post('/admin/ofertas/agregar-plan-pago', 'agregarPlanPago')->name('admin.ofertas.agregar-plan-pago');
        Route::post('/admin/ofertas/{id}/actualizar-planes-pago', 'actualizarPlanesPago')
            ->name('admin.ofertas.actualizar-planes-pago');

        Route::get('/admin/ofertas/{id}/planes-pago', 'obtenerPlanesPagoOferta')
            ->name('admin.ofertas.planes-pago');

        // En el grupo de OfertasAcademicasController
        Route::get('/admin/ofertas/{id}/dashboard', 'dashboardOferta')->name('admin.ofertas.dashboard');

        Route::get('/admin/ofertas/{ofertaId}/modulo/{moduloId}/detalle', 'detalleModulo')
            ->name('admin.ofertas.modulo.detalle');

        // En el grupo de rutas de OfertasAcademicasController, ya tienes estas rutas:
        Route::delete('/admin/ofertas/{oferta}/inscripciones/{inscripcion}/eliminar', 'eliminarInscripcion')
            ->name('admin.ofertas.inscripciones.eliminar');
        Route::post('/admin/ofertas/{oferta}/inscripciones/{inscripcion}/transferir', 'transferirInscripcion')
            ->name('admin.ofertas.inscripciones.transferir');
        Route::get('/admin/ofertas/disponibles-transferencia', 'ofertasDisponiblesTransferencia')
            ->name('admin.ofertas.disponibles-transferencia');
        Route::get('/admin/ofertas/{oferta}/planes-transferencia', 'planesTransferencia')
            ->name('admin.ofertas.planes-transferencia');
    });

    Route::controller(UserProfileController::class)->group(function () {
        Route::get('/admin/profile', 'profile')->name('admin.profile');
        Route::get('/admin/profile/ver', 'getProfileData')->name('admin.profile.data');
        Route::post('/admin/profile/update-personal', 'updatePersonal')->name('admin.profile.update-personal');
        Route::post('/admin/profile/upload-foto', 'uploadFoto')->name('admin.profile.upload-foto');
        Route::post('/admin/profile/update-estudios', 'updateEstudios')->name('admin.profile.update-estudios');
        Route::get('/admin/profile/estudios', 'getEstudios')->name('admin.profile.estudios');
        Route::post('/admin/profile/update-cargo-data', 'updateCargoData')->name('admin.profile.update-cargo-data');
        Route::get('/admin/profile/cargos', 'getCargos')->name('admin.profile.cargos');

        // Marketing - solo para cargos 2,3,6
        Route::get('/admin/profile/marketing/inscripciones', 'getInscripcionesMarketing')->name('admin.profile.marketing.inscripciones');

        // En el grupo de rutas de UserProfileController
        Route::get('/admin/profile/marketing/estadisticas', 'getEstadisticasMarketing')->name('admin.profile.marketing.estadisticas');
        Route::get('/admin/profile/marketing/inscripciones-filtradas', 'getInscripcionesFiltradas')->name('admin.profile.marketing.inscripciones-filtradas');

        // En el grupo de UserProfileController
        Route::get('/admin/profile/marketing/ofertas-activas', 'getOfertasMarketingActivas')->name('admin.profile.marketing.ofertas-activas');

        // Nueva ruta para convertir pre-inscrito a inscrito
        Route::post('/admin/profile/marketing/convertir-inscrito', 'convertirPreInscritoAInscrito')
            ->name('admin.profile.marketing.convertir-inscrito');

        // Ruta para obtener planes de pago de una oferta
        Route::get('/admin/profile/marketing/oferta/{id}/planes-pago', 'obtenerPlanesPagoOferta')
            ->name('admin.profile.marketing.oferta.planes-pago');

        // Nueva ruta para generar formulario PDF
        Route::get('/admin/profile/marketing/inscripcion/{id}/formulario-pdf', 'generarFormularioPdf')
            ->name('admin.profile.marketing.inscripcion.formulario-pdf');

        Route::post('/admin/profile/change-password', 'changePassword')->name('admin.profile.change-password');
        Route::post('/admin/users/reset-password', 'resetPassword')->name('admin.users.reset-password');
    });

    //AREAS
    Route::controller(AreasController::class)->group(function () {
        Route::get('/admin/areas/listar', 'areasListar')->name('admin.areas.listar');
        Route::post('/admin/areas/registrar', 'areasRegistrar')->name('admin.areas.registrar');
        Route::post('/admin/areas/verificar',  'verificarNombre')->name('admin.areas.verificar');
        Route::post('/admin/areas/verificaredicion',  'verificarNombreEdicion')->name('admin.areas.verificaredicion');
        Route::put('/admin/areas/modificar', 'areasModificar')->name('admin.areas.modificar');
        Route::delete('/admin/areas/eliminar', 'areasEliminar')->name('admin.areas.eliminar');
        Route::get('/admin/areas/ver/{id}', 'areasVer')->name('admin.areas.ver');
    });

    //Convenios
    Route::controller(ConveniosController::class)->group(function () {
        Route::get('/admin/convenios/listar', 'conveniosListar')->name('admin.convenios.listar');
        Route::post('/admin/convenios/registrar', 'conveniosRegistrar')->name('admin.convenios.registrar');
        Route::post('/admin/convenios/verificar',  'verificarNombre')->name('admin.convenios.verificar');
        Route::post('/admin/convenios/verificaredicion',  'verificarNombreEdicion')->name('admin.convenios.verificaredicion');
        Route::put('/admin/convenios/modificar', 'conveniosModificar')->name('admin.convenios.modificar');
        Route::delete('/admin/convenios/eliminar', 'conveniosEliminar')->name('admin.convenios.eliminar');
        Route::get('/admin/convenios/ver/{id}', 'ver')->name('admin.convenios.ver');
    });

    //Tipos
    Route::controller(TiposController::class)->group(function () {
        Route::get('/admin/tipos/listar', 'tiposListar')->name('admin.tipos.listar');
        Route::post('/admin/tipos/registrar', 'tiposRegistrar')->name('admin.tipos.registrar');
        Route::post('/admin/tipos/verificar',  'verificarNombre')->name('admin.tipos.verificar');
        Route::post('/admin/tipos/verificaredicion',  'verificarNombreEdicion')->name('admin.tipos.verificaredicion');
        Route::post('/admin/tipos/modificar', 'tiposModificar')->name('admin.tipos.modificar');
        Route::post('/admin/tipos/eliminar', 'tiposEliminar')->name('admin.tipos.eliminar');
        Route::get('/admin/tipos/ver/{id}', 'tiposVer')->name('admin.tipos.ver');
    });

    //Grados Academicos
    Route::controller(GradosAcademicosController::class)->group(function () {
        Route::get('/admin/grados/listar', 'gradosListar')->name('admin.grados.listar');
        Route::post('/admin/grados/registrar', 'gradosRegistrar')->name('admin.grados.registrar');
        Route::post('/admin/grados/verificar',  'verificarNombre')->name('admin.grados.verificar');
        Route::post('/admin/grados/verificaredicion',  'verificarNombreEdicion')->name('admin.grados.verificaredicion');
        Route::post('/admin/grados/modificar', 'gradosModificar')->name('admin.grados.modificar');
        Route::post('/admin/grados/eliminar', 'gradosEliminar')->name('admin.grados.eliminar');
        Route::get('/admin/grados/ver/{id}', 'gradosVer')->name('admin.grados.ver');
    });

    //Profesiones
    Route::controller(ProfesionesController::class)->group(function () {
        Route::get('/admin/profesiones/listar', 'profesionesListar')->name('admin.profesiones.listar');
        Route::post('/admin/profesiones/registrar', 'profesionesRegistrar')->name('admin.profesiones.registrar');
        Route::post('/admin/profesiones/verificar',  'verificarNombre')->name('admin.profesiones.verificar');
        Route::post('/admin/profesiones/verificaredicion',  'verificarNombreEdicion')->name('admin.profesiones.verificaredicion');
        Route::put('/admin/profesiones/modificar', 'profesionesModificar')->name('admin.profesiones.modificar');
        Route::delete('/admin/profesiones/eliminar', 'profesionesEliminar')->name('admin.profesiones.eliminar');
        Route::get('/admin/profesiones/ver/{id}', 'profesionesVer')->name('admin.profesiones.ver');
    });

    // UNIVERSIDADES
    Route::controller(UniversidadesController::class)->group(function () {
        Route::get('/admin/universidades/listar', 'listar')->name('admin.universidades.listar');
        Route::post('/admin/universidades/registrar', 'registrar')->name('admin.universidades.registrar');
        Route::post('/admin/universidades/verificar', 'verificar')->name('admin.universidades.verificar');
        Route::post('/admin/universidades/verificar-edicion', 'verificarEdicion')->name('admin.universidades.verificar-edicion');
        Route::put('/admin/universidades/modificar', 'modificar')->name('admin.universidades.modificar');
        Route::delete('/admin/universidades/eliminar', 'eliminar')->name('admin.universidades.eliminar');
    });

    // UNIVERSIDADES
    Route::controller(CuentasController::class)->group(function () {
        Route::get('/admin/cuentas/listar', 'listar')->name('admin.cuentas.listar');
        Route::post('/admin/cuentas/registrar', 'registrar')->name('admin.cuentas.registrar');
        Route::post('/admin/cuentas/verificar', 'verificar')->name('admin.cuentas.verificar');
        Route::post('/admin/cuentas/verificar-edicion', 'verificarEdicion')->name('admin.cuentas.verificar-edicion');
        Route::post('/admin/cuentas/modificar', 'modificar')->name('admin.cuentas.modificar');
        Route::delete('/admin/cuentas/eliminar', 'eliminar')->name('admin.cuentas.eliminar');
    });

    // POSGRADOS
    Route::controller(PosgradosController::class)->group(function () {
        Route::get('/admin/posgrados/ver/{id}', 'posgradosVer')->name('admin.posgrados.ver');
        Route::get('/admin/posgrados/listar', 'posgradosListar')->name('admin.posgrados.listar');
        Route::post('/admin/posgrados/registrar', 'posgradosRegistrar')->name('admin.posgrados.registrar');
        Route::post('/admin/posgrados/verificar', 'verificarNombre')->name('admin.posgrados.verificar');
        Route::post('/admin/posgrados/modificar', 'posgradosModificar')->name('admin.posgrados.modificar');
        Route::delete('/admin/posgrados/eliminar', 'posgradosEliminar')->name('admin.posgrados.eliminar');
    });

    // PERSONAS
    Route::controller(PersonasController::class)->group(function () {
        Route::get('/admin/personas/listar', 'listar')->name('admin.personas.listar');
        Route::post('/admin/personas/registrar', 'registrar')->name('admin.personas.registrar');
        Route::post('/admin/personas/verificar-carnet', 'verificarCarnet')->name('admin.personas.verificar-carnet');
        Route::post('/admin/personas/verificar-correo', 'verificarCorreo')->name('admin.personas.verificar-correo');
        Route::post('/admin/personas/verificar-edicion', 'verificarEdicion')->name('admin.personas.verificar-edicion');
        Route::post('/admin/personas/modificar', 'modificar')->name('admin.personas.modificar');
        Route::delete('/admin/personas/eliminar', 'eliminar')->name('admin.personas.eliminar');
        Route::get('/admin/personas/ver/{id}', 'ver')->name('admin.personas.ver');
        Route::delete('/admin/personas/eliminar', 'eliminar')->name('admin.personas.eliminar');
    });

    //MODALIDADES
    Route::controller(ModalidadesController::class)->group(function () {
        Route::get('/admin/modalidades/listar', 'modalidadesListar')->name('admin.modalidades.listar');
        Route::post('/admin/modalidades/registrar', 'modalidadesRegistrar')->name('admin.modalidades.registrar');
        Route::post('/admin/modalidades/verificar',  'verificarNombre')->name('admin.modalidades.verificar');
        Route::post('/admin/modalidades/verificaredicion',  'verificarNombreEdicion')->name('admin.modalidades.verificaredicion');
        Route::post('/admin/modalidades/modificar', 'modalidadesModificar')->name('admin.modalidades.modificar');
        Route::delete('/admin/modalidades/eliminar', 'modalidadesEliminar')->name('admin.modalidades.eliminar');
        Route::get('/admin/modalidades/ver/{id}', 'modalidadesVer')->name('admin.modalidades.ver');
    });

    //PLANES DE PAGOS
    Route::controller(PlanesPagosController::class)->group(function () {
        Route::get('/admin/planes/listar', 'planesListar')->name('admin.planes.listar');
        Route::post('/admin/planes/registrar', 'planesRegistrar')->name('admin.planes.registrar');
        Route::post('/admin/planes/verificar',  'verificarNombre')->name('admin.planes.verificar');
        Route::post('/admin/planes/verificaredicion',  'verificarNombreEdicion')->name('admin.planes.verificaredicion');
        Route::put('/admin/planes/modificar', 'planesModificar')->name('admin.planes.modificar');
        Route::delete('/admin/planes/eliminar', 'planesEliminar')->name('admin.planes.eliminar');
        Route::get('/admin/planes/ver/{id}', 'planesVer')->name('admin.planes.ver');
    });

    //CONCEPTOS
    Route::controller(ConceptosController::class)->group(function () {
        Route::get('/admin/conceptos/listar', 'conceptosListar')->name('admin.conceptos.listar');
        Route::post('/admin/conceptos/registrar', 'conceptosRegistrar')->name('admin.conceptos.registrar');
        Route::post('/admin/conceptos/verificar',  'verificarNombre')->name('admin.conceptos.verificar');
        Route::post('/admin/conceptos/verificaredicion',  'verificarNombreEdicion')->name('admin.conceptos.verificaredicion');
        Route::put('/admin/conceptos/modificar', 'conceptosModificar')->name('admin.conceptos.modificar');
        Route::delete('/admin/conceptos/eliminar', 'conceptosEliminar')->name('admin.conceptos.eliminar');
        Route::get('/admin/conceptos/ver/{id}', 'conceptosVer')->name('admin.conceptos.ver');
    });

    // FASES
    Route::controller(FasesController::class)->group(function () {
        Route::get('/admin/fases/listar', 'fasesListar')->name('admin.fases.listar');
        Route::post('/admin/fases/registrar', 'fasesRegistrar')->name('admin.fases.registrar');
        Route::post('/admin/fases/verificar', 'verificarNombre')->name('admin.fases.verificar');
        Route::put('/admin/fases/modificar', 'fasesModificar')->name('admin.fases.modificar');
        Route::post('/admin/fases/eliminar', 'fasesEliminar')->name('admin.fases.eliminar');
    });

    // CARGOS
    Route::controller(CargosController::class)->group(function () {
        Route::get('/admin/cargos/listar', 'cargosListar')->name('admin.cargos.listar');
        Route::post('/admin/cargos/registrar', 'cargosRegistrar')->name('admin.cargos.registrar');
        Route::post('/admin/cargos/verificar',  'verificarNombre')->name('admin.cargos.verificar');
        Route::post('/admin/cargos/verificaredicion',  'verificarNombreEdicion')->name('admin.cargos.verificaredicion');
        Route::put('/admin/cargos/modificar', 'cargosModificar')->name('admin.cargos.modificar');
        Route::delete('/admin/cargos/eliminar', 'cargosEliminar')->name('admin.cargos.eliminar');
    });

    // DEPARTAMENTOS
    Route::controller(DepartamentosController::class)->group(function () {
        Route::get('/admin/departamentos/listar', 'departamentosListar')->name('admin.departamentos.listar');
        Route::post('/admin/departamentos/registrar', 'departamentosRegistrar')->name('admin.departamentos.registrar');
        Route::post('/admin/departamentos/verificar', 'verificarNombre')->name('admin.departamentos.verificar');
        Route::post('/admin/departamentos/verificaredicion', 'verificarNombreEdicion')->name('admin.departamentos.verificaredicion');
        Route::put('/admin/departamentos/modificar', 'departamentosModificar')->name('admin.departamentos.modificar');
        Route::delete('/admin/departamentos/eliminar', 'departamentosEliminar')->name('admin.departamentos.eliminar');
        Route::get('/admin/departamentos/ver/{id}', 'departamentosVer')->name('admin.departamentos.ver');
    });

    // CIUDADES
    Route::controller(CiudadesController::class)->group(function () {
        Route::post('/admin/ciudades/registrar', 'registrar')->name('admin.ciudades.registrar');
        Route::put('/admin/ciudades/modificar', 'modificar')->name('admin.ciudades.modificar');
        Route::delete('/admin/ciudades/eliminar', 'eliminar')->name('admin.ciudades.eliminar');
        Route::post('/admin/ciudades/verificar', 'verificarNombre')->name('admin.ciudades.verificar');
        Route::post('/admin/ciudades/verificaredicion', 'verificarNombreEdicion')->name('admin.ciudades.verificaredicion');
        Route::get('/ciudades/por-ciudad', 'porCiudad')->name('admin.ciudades.por-ciudad');
    });

    // SEDES - CORREGIR
    Route::controller(SedesController::class)->group(function () {
        Route::get('/admin/sedes/listar', 'sedesListar')->name('admin.sedes.listar');
        Route::post('/admin/sedes/registrar', 'sedesRegistrar')->name('admin.sedes.registrar');
        Route::post('/admin/sedes/verificar', 'verificarNombre')->name('admin.sedes.verificar');
        Route::post('/admin/sedes/verificaredicion', 'verificarNombreEdicion')->name('admin.sedes.verificaredicion');
        Route::put('/admin/sedes/modificar', 'sedesModificar')->name('admin.sedes.modificar');
        Route::delete('/admin/sedes/eliminar', 'sedesEliminar')->name('admin.sedes.eliminar');

        Route::get('/admin/sedes/ver/{id}', 'ver')->name('admin.sedes.ver');
    });

    // SUCURSALES - AGREGAR RUTAS FALTANTES
    Route::controller(SucursalesController::class)->group(function () {
        Route::post('/admin/sucursales/registrar', 'registrar')->name('admin.sucursales.registrar');
        Route::put('/admin/sucursales/modificar', 'modificar')->name('admin.sucursales.modificar');
        Route::delete('/admin/sucursales/eliminar', 'eliminar')->name('admin.sucursales.eliminar');
        Route::post('/admin/sucursales/verificar', 'verificarNombre')->name('admin.sucursales.verificar');
    });

    // ESTUDIANTES
    Route::controller(EstudiantesController::class)->group(function () {
        Route::get('/admin/estudiantes/listar', 'listar')->name('admin.estudiantes.listar');
        Route::post('/admin/estudiantes/verificar-carnet', 'verificarCarnet')->name('admin.estudiantes.verificar-carnet');
        Route::post('/admin/estudiantes/registrar', 'registrar')->name('admin.estudiantes.registrar');
        Route::delete('/admin/estudiantes/eliminar', 'eliminar')->name('admin.estudiantes.eliminar');
        Route::post('/admin/estudiantes/registrar-persona-estudiante', 'registrarPersonaYEstudiante')->name('admin.estudiantes.registrar-persona-estudiante');

        // En el grupo de rutas de EstudiantesController
        Route::get('/admin/estudiantes/editar/{id}', [EstudiantesController::class, 'editar'])->name('admin.estudiantes.editar');
        Route::post('/admin/estudiantes/actualizar', [EstudiantesController::class, 'actualizar'])->name('admin.estudiantes.actualizar');
        Route::get('/admin/estudiantes/detalle/{id}', [EstudiantesController::class, 'detalle'])->name('admin.estudiantes.detalle');

        // Subida de documentos
        Route::post('/admin/estudiantes/{id}/subir-documento-carnet', 'subirDocumentoCarnet')->name('admin.estudiantes.subir-documento-carnet');
        Route::post('/admin/estudiantes/{id}/subir-documento-certificado-nacimiento', 'subirDocumentoCertificadoNacimiento')->name('admin.estudiantes.subir-documento-certificado-nacimiento');
        Route::post('/admin/estudiantes/{id}/subir-documento-titulo-academico', 'subirDocumentoTituloAcademico')->name('admin.estudiantes.subir-documento-titulo-academico');
        Route::post('/admin/estudiantes/{id}/subir-documento-provision-nacional', 'subirDocumentoProvisionNacional')->name('admin.estudiantes.subir-documento-provision-nacional');

        // Verificación de documentos
        Route::post('/admin/estudiantes/{id}/verificar-documento-carnet', 'verificarDocumentoCarnet')->name('admin.estudiantes.verificar-documento-carnet');
        Route::post('/admin/estudiantes/{id}/verificar-documento-certificado-nacimiento', 'verificarDocumentoCertificadoNacimiento')->name('admin.estudiantes.verificar-documento-certificado-nacimiento');
        Route::post('/admin/estudiantes/{id}/verificar-documento-academico', 'verificarDocumentoAcademico')->name('admin.estudiantes.verificar-documento-academico');
        Route::post('/admin/estudiantes/{id}/verificar-documento-provision-nacional', 'verificarDocumentoProvisionNacional')->name('admin.estudiantes.verificar-documento-provision-nacional');

        // En el grupo de EstudiantesController
        Route::post('/admin/estudiantes/{id}/pagar-cuota', 'registrarPago')->name('admin.estudiantes.pagar-cuota');
        Route::get('/admin/estudiantes/{id}/cuota/{cuotaId}', 'obtenerDatosCuota')->name('admin.estudiantes.cuota.datos');
        Route::get('/admin/estudiantes/pago/{id}/descargar-recibo', 'descargarRecibo')->name('admin.estudiantes.descargar-recibo');

        Route::get('/admin/estudiantes/pago/{pagoId}/detalle', 'obtenerDetallePago')->name('admin.estudiantes.pago.detalle');

        Route::get('/admin/estudiantes/cuota/{cuotaId}/recibos', 'recibosCuota')->name('admin.estudiantes.cuota.recibos');

        Route::get('/admin/recibos/historial', 'historialRecibos')->name('admin.recibos.historial');
        Route::get('/admin/recibos/filtrados', 'recibosFiltrados')->name('admin.recibos.filtrados');
        Route::get('/admin/recibos/exportar', 'exportarRecibos')->name('admin.recibos.exportar');

        Route::get('/admin/contabilidad/buscar', 'busquedaContable')->name('admin.contabilidad.buscar');
        Route::post('/admin/contabilidad/verificar-carnet', 'verificarCarnetContable')->name('admin.contabilidad.verificar-carnet');
        Route::get('/admin/contabilidad/estudiante/{id}', 'detalleContable')->name('admin.contabilidad.estudiante');
    });

    // INSCRIPCIONES
    Route::controller(InscripcionesController::class)->group(function () {
        Route::post('/admin/inscripciones/verificar-carnet', 'verificarCarnet')->name('admin.inscripciones.verificar-carnet');
        Route::post('/admin/inscripciones/registrar', 'registrar')->name('admin.inscripciones.registrar');
        Route::post('/admin/inscripciones/generar-cuotas-preview', 'generarCuotasPreview')->name('admin.inscripciones.generar-cuotas-preview');
        Route::post('/admin/inscripciones/confirmar-cuotas', 'confirmarCuotas')->name('admin.inscripciones.confirmar-cuotas');

        Route::get('/admin/ofertas/{oferta}/inscritos', 'listarPorOferta')->name('admin.ofertas.inscritos');

        Route::post('/admin/inscripciones/convertir-a-inscrito', 'convertirAPagado')->name('admin.inscripciones.convertir-a-inscrito');

        Route::post('/admin/inscripciones/verificar-inscripcion-existente', 'verificarInscripcionExistente')->name('admin.inscripciones.verificar-inscripcion-existente');

        // Obtener módulos + notas de una inscripción
        Route::get('/admin/inscripciones/{inscripcion}/modulos-notas',  'modulosNotas')->name('admin.inscripciones.modulos-notas');

        // Registrar nota
        Route::post('/admin/inscripciones/{matriculacion}/registrar-nota', 'registrarNota')->name('admin.inscripciones.registrar-nota');

        // Obtener cuotas de una inscripción
        Route::get('/admin/inscripciones/{inscripcion}/cuotas', 'cuotas')->name('admin.inscripciones.cuotas');

        Route::post('/admin/pagos/registrar', 'registrarPago')->name('admin.pagos.registrar');

        Route::get('/admin/inscripciones/{inscripcion}/cuotas-pendientes', 'cuotasPendientes')->name('admin.inscripciones.cuotas-pendientes');
    });

    // DOCENTES
    Route::controller(DocentesController::class)->group(function () {
        Route::post('/admin/docentes/verificar-carnet', 'verificarCarnet')->name('admin.docentes.verificar-carnet');
        Route::post('/admin/docentes/registrar', 'registrar')->name('admin.docentes.registrar');
        Route::post('/admin/docentes/registrar-persona-y-docente', 'registrarPersonaYDocente')->name('admin.docentes.registrar-persona-y-docente');
        Route::post('/admin/modulos/asignar-docente', 'asignarADocente')->name('admin.modulos.asignar-docente');
    });


    Route::controller(PermissionsController::class)->group(function () {
        Route::get('/admin/permissions/listar', 'permissionsListar')->name('admin.permissions.listar');
        Route::post('/admin/permissions/registrar', 'permissionsRegistrar')->name('admin.permissions.registrar');
        Route::post('/admin/permissions/verificar', 'verificarNombre')->name('admin.permissions.verificar');
        Route::post('/admin/permissions/verificaredicion', 'verificarNombreEdicion')->name('admin.permissions.verificaredicion');
        Route::post('/admin/permissions/modificar', 'permissionsModificar')->name('admin.permissions.modificar');
        Route::delete('/admin/permissions/eliminar', 'permissionsEliminar')->name('admin.permissions.eliminar');
        Route::get('/permissions/groups', 'getGroups')->name('admin.permissions.groups');
    });

    Route::controller(RolesController::class)->group(function () {
        Route::get('/admin/roles/listar', 'rolesListar')->name('admin.roles.listar');
        Route::post('/admin/roles/registrar', 'rolesRegistrar')->name('admin.roles.registrar');
        Route::post('/admin/roles/verificar', 'verificarNombre')->name('admin.roles.verificar');
        Route::post('/admin/roles/verificaredicion', 'verificarNombreEdicion')->name('admin.roles.verificaredicion');
        Route::post('/admin/roles/modificar', 'rolesModificar')->name('admin.roles.modificar');
        Route::delete('/admin/roles/eliminar', 'rolesEliminar')->name('admin.roles.eliminar');
    });

    Route::controller(RolePermissionsController::class)->group(function () {
        Route::get('/admin/role-permissions', 'index')->name('admin.role-permissions.index');
        Route::get('/admin/role-permissions/{id}', 'show')->name('admin.role-permissions.show');
        Route::post('/admin/role-permissions/assign', 'assignPermission')->name('admin.role-permissions.assign');
        Route::post('/admin/role-permissions/revoke', 'revokePermission')->name('admin.role-permissions.revoke');
    });

    Route::controller(UsersController::class)->group(function () {
        Route::get('/admin/users/listar', 'listar')->name('admin.users.listar');
        Route::post('/admin/users/verificar-email', 'verificarEmail')->name('admin.users.verificar-email');
        Route::post('/admin/users/actualizar', 'actualizar')->name('admin.users.actualizar');
        Route::post('/admin/users/obtener-data', 'obtenerUserData')->name('admin.users.obtener-data');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
