<div id="results-container">
    <div class="table-card">
        <div class="table-card-header">
            <h5><i class="ri-list-check me-2 text-muted"></i>Listado de Ofertas Académicas</h5>
        </div>
        <div class="table-responsive">
            <table class="ofertas-table">
                <thead>
                    <tr>
                        <th width="3%" class="text-center">N°</th>
                        <th>Oferta</th>
                        <th width="4%" class="text-center">Mód.</th>
                        <th width="9%" class="text-center">Convenio</th>
                        <th width="7%" class="text-center">Modalidad</th>
                        <th width="7%" class="text-center">Fechas</th>
                        <th width="5%" class="text-center">Inscr.</th>
                        <th width="5%" class="text-center">Fase</th>
                        <th width="13%" class="text-center">Acciones</th>
                    </tr>
                </thead>
                @include('admin.ofertas.partials.table-body')
            </table>
        </div>
        @if ($ofertas->total() > 0)
            <div class="table-footer">
                <div class="results-count">
                    Mostrando <span class="fw-medium">{{ $ofertas->firstItem() }}</span> a
                    <span class="fw-medium">{{ $ofertas->lastItem() }}</span> de
                    <span class="fw-medium">{{ $ofertas->total() }}</span> resultados
                </div>
                <div class="pagination-container">
                    {{ $ofertas->appends(request()->input())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
    </div>
</div>
