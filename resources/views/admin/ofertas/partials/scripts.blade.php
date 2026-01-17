<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Configuración global
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        }
    });

    const PLANES_PAGOS = @json($planesPagos);
    const CONCEPTOS = @json($conceptos);
    const ciudadesConDepartamento = @json($ciudades->map(fn($c) => ['id' => $c->id, 'nombre' => $c->nombre, 'departamento_id' => $c->departamento_id]));
    const grados = @json($grados->pluck('nombre', 'id'));
    const profesiones = @json($profesiones->pluck('nombre', 'id'));
    const universidades = @json($universidades->map(fn($u) => ['id' => $u->id, 'nombre' => $u->nombre, 'sigla' => $u->sigla]));
</script>

<script>
    // Variables globales
    let debounceTimerInscripcion, lastCarnetValue, debounceCarnet = null,
        debounceCorreo = null;

    // Función para refrescar Feather Icons
    function refreshFeather() {
        if (typeof window.feather !== 'undefined') {
            window.feather.replace();
        }
    }

    // Función para mostrar toast
    function mostrarToast(icon, message) {
        if (typeof Toast !== 'undefined') {
            Toast.fire({
                icon: icon,
                title: message
            });
        } else if (typeof Swal !== 'undefined') {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: icon,
                title: message,
                showConfirmButton: false,
                timer: 3000
            });
        } else {
            alert(message);
        }
    }

    // Función para cargar resultados con filtros
    function loadResults() {
        const params = {
            sucursale_id: $('#filtroSucursal').val() || '',
            convenio_id: $('#filtroConvenio').val() || '',
            fase_id: $('#filtroFase').val() || '',
            modalidade_id: $('#filtroModalidad').val() || '',
            _token: '{{ csrf_token() }}'
        };

        $.ajax({
            url: '{{ route('admin.ofertas.listar') }}',
            type: 'GET',
            data: params,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(res) {
                if (res.success) {
                    const newTable = `
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered align-middle" style="min-width: 1100px;">
                                <thead class="table-light">
                                    <tr class="text-center">
                                        <th width="20">N°</th>
                                        <th width="60">Código</th>
                                        <th width="120">Programa</th>
                                        <th width="80">N° Módulos</th>
                                        <th width="50">Convenio</th>
                                        <th width="80">Modalidad</th>
                                        <th width="80">Inicio - Fin</th>
                                        <th width="70">Inscritos</th>
                                        <th width="50">Fase</th>
                                        <th width="200">Acciones</th>
                                    </tr>
                                </thead>
                                ${res.html}
                            </table>
                        </div>
                    `;
                    $('#results-container .table-responsive').replaceWith(newTable);
                    $('#pagination-container').html(res.pagination);
                    refreshFeather();
                }
            },
            error: function(xhr) {
                console.error('Error:', xhr);
            }
        });
    }

    $(document).ready(function() {
        refreshFeather();

        // Event listeners for filters
        $('#filtroSucursal, #filtroConvenio, #filtroFase, #filtroModalidad').on('change', loadResults);

        $('#clearFilters').on('click', function() {
            $('#filtroSucursal, #filtroConvenio, #filtroFase, #filtroModalidad').val('');
            loadResults();
        });

        // Handle pagination clicks
        $(document).on('click', '#pagination-container .pagination a', function(e) {
            e.preventDefault();
            const url = $(this).attr('href');
            const urlObj = new URL(url);
            const page = urlObj.searchParams.get('page');

            $.ajax({
                url: url,
                data: {
                    sucursale_id: $('#filtroSucursal').val() || '',
                    convenio_id: $('#filtroConvenio').val() || '',
                    fase_id: $('#filtroFase').val() || '',
                    modalidade_id: $('#filtroModalidad').val() || '',
                    page: page
                },
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(res) {
                    if (typeof res === 'object' && res.html) {
                        $('#results-container table tbody').html(res.html);
                        $('#pagination-container').html(res.pagination);
                    } else {
                        $('#results-container').html($(res).find('#results-container')
                        .html());
                        $('#pagination-container').html($(res).find('#pagination-container')
                            .html());
                    }
                    refreshFeather();
                },
                error: function(xhr) {
                    console.error('Error:', xhr);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al cargar la página'
                    });
                }
            });
        });
    });
</script>

@include('admin.ofertas.partials.scripts-cambiar-fase')
@include('admin.ofertas.partials.scripts-editar-oferta')
@include('admin.ofertas.partials.scripts-editar-fase2')
@include('admin.ofertas.partials.scripts-agregar-plan-pago')
@include('admin.ofertas.partials.scripts-inscribir-estudiante')
@include('admin.ofertas.partials.scripts-ver-planes-pago')
@include('admin.ofertas.partials.scripts-editar-planes-pago')
