@extends('admin.dashboard')
@section('admin')
    @include('admin.profile.styles')

    <div class="container-fluid">
        @include('admin.profile.header')

        <div class="row">
            @include('admin.profile.sidebar')

            <div class="col-xl-9 col-lg-9">
                <div class="card">
                    <div class="card-body p-0">
                        <div class="border-bottom">
                            @include('admin.profile.tabs.navigation')
                        </div>

                        <div class="tab-content p-4">
                            <div class="tab-pane active" id="personal" role="tabpanel">
                                @include('admin.profile.tabs.personal')
                            </div>

                            <div class="tab-pane" id="cargos" role="tabpanel">
                                @include('admin.profile.tabs.cargos')
                            </div>

                            <div class="tab-pane" id="estudios" role="tabpanel">
                                @include('admin.profile.tabs.estudios')
                            </div>

                            @if ($tieneMarketing)
                                <div class="tab-pane" id="marketing" role="tabpanel">
                                    @include('admin.profile.tabs.marketing')
                                </div>

                                <div class="tab-pane" id="ofertas-activas" role="tabpanel">
                                    @include('admin.profile.tabs.ofertas-activas')
                                </div>
                            @endif

                            <div class="tab-pane" id="password" role="tabpanel">
                                @include('admin.profile.tabs.password')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('admin.profile.modals.upload-foto')
    @include('admin.profile.modals.convertir-preinscrito')
    @include('admin.profile.modals.enlace')
    @include('admin.profile.modals.enlace-plan')
@endsection

@push('script')
    @include('admin.profile.scripts')
@endpush
