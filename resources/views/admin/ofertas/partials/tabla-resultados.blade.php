<div id="results-container">
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="min-width:1100px;font-size:.84rem;">
                    <thead>
                        <tr style="background:#f8f9fa;">
                            <th width="3%"  class="border-0 py-3 px-3 text-muted fw-semibold text-center" style="font-size:.7rem;">N°</th>
                            <th width="7%"  class="border-0 py-3 text-muted fw-semibold text-center"       style="font-size:.7rem;">CÓDIGO</th>
                            <th width="20%" class="border-0 py-3 text-muted fw-semibold"                   style="font-size:.7rem;">PROGRAMA</th>
                            <th width="5%"  class="border-0 py-3 text-muted fw-semibold text-center"       style="font-size:.7rem;">MÓD.</th>
                            <th width="11%" class="border-0 py-3 text-muted fw-semibold"                   style="font-size:.7rem;">CONVENIO</th>
                            <th width="9%"  class="border-0 py-3 text-muted fw-semibold text-center"       style="font-size:.7rem;">MODALIDAD</th>
                            <th width="10%" class="border-0 py-3 text-muted fw-semibold text-center"       style="font-size:.7rem;">FECHAS</th>
                            <th width="6%"  class="border-0 py-3 text-muted fw-semibold text-center"       style="font-size:.7rem;">INSCRITOS</th>
                            <th width="8%"  class="border-0 py-3 text-muted fw-semibold text-center"       style="font-size:.7rem;">FASE</th>
                            <th width="21%" class="border-0 py-3 text-muted fw-semibold text-center"       style="font-size:.7rem;">ACCIONES</th>
                        </tr>
                    </thead>
                    @include('admin.ofertas.partials.table-body')
                </table>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-end mt-3" id="pagination-container">
        {{ $ofertas->links('pagination::bootstrap-5') }}
    </div>
</div>
