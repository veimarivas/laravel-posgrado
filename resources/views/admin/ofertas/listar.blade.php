@extends('admin.dashboard')
@section('admin')
    <!-- Header y breadcrumb -->
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Ofertas Académicas</li>
            <li class="breadcrumb-item active">Listado</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-12">
            @include('admin.ofertas.partials.filtros')
            @include('admin.ofertas.partials.tabla-resultados')
        </div>
    </div>

    <!-- Modales -->
    @include('admin.ofertas.modals.editar-oferta')
    @include('admin.ofertas.modals.editar-fase2')
    @include('admin.ofertas.modals.agregar-plan-pago')
    @include('admin.ofertas.modals.inscribir-estudiante')
    @include('admin.ofertas.modals.ver-planes-pago')
    @include('admin.ofertas.modals.editar-planes-pago')
@endsection

@push('style')
    @include('admin.ofertas.partials.estilos')
@endpush

@push('script')
    @include('admin.ofertas.partials.scripts')
@endpush
