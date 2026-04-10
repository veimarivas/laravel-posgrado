@extends('admin.dashboard')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

@section('admin')

@include('admin.ofertas.partials.tabs.header')

@include('admin.ofertas.partials.tabs.info-principal')

@include('admin.ofertas.partials.tabs.nav-tabs')

    @include('admin.ofertas.partials.tabs.resumen')

    @include('admin.ofertas.partials.tabs.participantes')

    @include('admin.ofertas.partials.tabs.finanzas')

    @include('admin.ofertas.partials.tabs.academico')

    @include('admin.ofertas.partials.tabs.demografico')

    @include('admin.ofertas.partials.tabs.gestion')

</div>

@include('admin.ofertas.partials.modals')

@endsection

@push('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@include('admin.ofertas.partials.scripts.charts')
@include('admin.ofertas.partials.scripts.actions')
@include('admin.ofertas.partials.scripts.convertir')
@endpush

@push('style')
@include('admin.ofertas.partials.styles')
@endpush

<style>
    .nav-pills .nav-link {
        background: var(--dash-primary-light);
        color: var(--dash-text-muted);
        transition: all 0.3s ease;
    }
    .nav-pills .nav-link:hover {
        color: var(--dash-primary);
        background: #c8f7f3;
    }
    .nav-pills .nav-link.active {
        background: linear-gradient(135deg, var(--dash-primary), #0d9488);
        color: white !important;
        box-shadow: 0 4px 15px rgba(10,179,156,0.35);
    }
    .nav-pills .nav-link i {
        font-size: 0.95rem;
    }
    @media (max-width: 991px) {
        .nav-pills .nav-link {
            padding: 0.5rem 0.4rem;
            font-size: 0.75rem;
        }
    }
</style>