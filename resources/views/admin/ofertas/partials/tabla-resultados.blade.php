<div id="results-container">
    <div class="table-responsive">
        <table class="table table-hover table-bordered align-middle" style="min-width: 1100px;">
            <thead class="table-light">
                <tr class="text-center">
                    <th width="5%">N°</th>
                    <th width="10%">Código</th>
                    <th width="15%">Programa</th>
                    <th width="5%">N° Módulos</th>
                    <th width="5%">Convenio</th>
                    <th width="5%">Modalidad</th>
                    <th width="5%">Inicio - Fin</th>
                    <th width="5%">Inscritos</th>
                    <th width="5%">Fase</th>
                    <th width="40%">Acciones</th>
                </tr>
            </thead>
            @include('admin.ofertas.partials.table-body')
        </table>
    </div>
    <div class="d-flex justify-content-end mt-3" id="pagination-container">
        {{ $ofertas->links('pagination::bootstrap-5') }}
    </div>
</div>
