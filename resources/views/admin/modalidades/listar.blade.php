@extends('admin.dashboard')
@section('admin')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#registrar">
                Registrar Modalidad de Clases
            </button>
            <div class="modal fade" id="registrar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">
                                Registrar Modalidad de Clases
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="btn-close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="col-md-12 col-xl-12 middle-wrapper">
                                <div class="row">
                                    <div class="card">
                                        <div class="card-body">
                                            <form id="addForm" class="forms-sample">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="form-group mb-3">
                                                            <label class="form-label">Nombre de Modalidad de Clases</label>
                                                            <input type="text" id="nombre_registro" name="nombre"
                                                                class="form-control">
                                                            <div id="feedback_registro" class="mt-2"
                                                                style="font-size: 0.875em;"></div>
                                                        </div>
                                                    </div><!-- Col -->

                                                </div>


                                                <div class="row">
                                                    <div class="col-md-12 text-center">
                                                        <button type="submit" class="btn btn-success addBtn" disabled>
                                                            Registrar Modalidad de Clases
                                                        </button>
                                                    </div>
                                                </div>

                                            </form>

                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </ol>
    </nav>
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin mb-3">
                        <div>
                            <h4 class="mb-3 mb-md-0">Lista de Modalidad de Clases registradas</h4>
                        </div>
                        <div class="d-flex align-items-center flex-wrap text-nowrap">
                            <div class="input-group flatpickr wd-300 me-2 mb-2 mb-md-0" id="dashboardDate">
                                Buscar: <input type="text" id="searchInput" class="form-control"
                                    placeholder="Buscar Modalidad de Clases...">
                            </div>

                        </div>
                    </div>
                    <div id="results-container">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr class="text-center">
                                        <th>N°</th>
                                        <th>Nombre Modalidad de Clases</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                @include('admin.modalidades.partials.table-body')
                            </table>
                        </div>

                        <div class="d-flex justify-content-end mt-3" id="pagination-container">
                            {{ $modalidades->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                    <!-- Modal Modificar -->
                    <div class="modal fade modificar" id="modalModificar" tabindex="-1"
                        aria-labelledby="modalModificarLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalModificarLabel">Modificar Modalidad de Clases</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Cerrar"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="updateForm" class="forms-sample">
                                        @csrf
                                        <input type="hidden" name="id" id="modalidadeId">
                                        <div class="mb-3">
                                            <label for="nombre_edicion" class="form-label">Nombre de Modalidad de
                                                Clases</label>
                                            <input type="text" class="form-control" id="nombre_edicion" name="nombre"
                                                required>
                                            <div id="feedback_edicion" class="text-danger mt-2" style="font-size: 0.875em;">
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-warning updateBtn" disabled>
                                                Modificar Modalidad de Clases
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Modal Eliminar -->
                    <div class="modal fade eliminar" id="modalEliminar" tabindex="-1" aria-labelledby="modalEliminarLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalEliminarLabel">Eliminar Modalidad de Clases</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Cerrar"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="deleteForm" class="forms-sample">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="id" id="eliminarId">
                                        <p>¿Está seguro de que desea eliminar la Modalidad de Clases? Esta acción no se
                                            puede
                                            deshacer.</p>
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-danger btnDelete">
                                                Eliminar Modalidad de Clases
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            let debounceTimer;

            //verifica si el nombre de la nacionalidad esta en la BD
            $('#nombre_registro').on('input', function() {
                const nombre = $(this).val().trim();
                const feedback = $('#feedback_registro');
                const submitBtn = $('.addBtn');

                // Limpiar el mensaje previo
                feedback.removeClass('text-success text-danger').text('');

                if (nombre.length === 0) {
                    submitBtn.prop('disabled', true);
                    return;
                }

                // Cancelar la petición anterior si el usuario sigue escribiendo
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    $.ajax({
                        url: "{{ route('admin.modalidades.verificar') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            nombre: nombre
                        },
                        success: function(res) {
                            if (res.exists) {
                                feedback.addClass('text-danger').text(
                                    '⚠️ La modalidad ya está registrada.');
                                submitBtn.prop('disabled', true);
                            } else {
                                feedback.addClass('text-success').text(
                                    '✅ Nombre disponible.');
                                submitBtn.prop('disabled', false);
                            }
                        },
                        error: function() {
                            feedback.addClass('text-danger').text(
                                '❌ Error al verificar el nombre.');
                            submitBtn.prop('disabled', true);
                        }
                    });
                }, 100); // Espera 500ms después de dejar de escribir
            });

            // registro de nacionalidad
            $('#addForm').submit(function(e) {
                e.preventDefault();
                $('.addBtn').prop('disabled', true);

                var formData = $(this).serialize();

                $.ajax({
                    url: "{{ route('admin.modalidades.registrar') }}",
                    type: "POST",
                    data: formData,
                    success: function(res) {
                        alert(res.msg);
                        if (res.success) {
                            location.reload();
                        } else {
                            $('.addBtn').prop('disabled', false);
                        }
                    },
                    error: function(xhr) {
                        // Manejar errores de validación (por ejemplo, si el nombre se registró mientras escribías)
                        let errorMsg = 'Error al registrar.';
                        if (xhr.responseJSON?.errors?.nombre) {
                            errorMsg = xhr.responseJSON.errors.nombre[0];
                        }
                        alert(errorMsg);
                        $('.addBtn').prop('disabled', false);
                    }
                });
            });

            //modificar
            $('.editBtn').click(function() {
                var data = $(this).data('bs-obj');
                currentEditId = data.id;
                console.log(data);

                $('#modalidadeId').val(data.id);
                $('#nombre_edicion').val(data.nombre);

                const nombre = data.nombre;
                const id = data.id;
                const feedback = $(this).closest('form').find('.nombre-feedback');
                const submitBtn = $('.updateBtn');

                // Verificar inmediatamente
                verificarDisponibilidadNombre(
                    data.nombre,
                    data.id,
                    $('#feedback_edicion'),
                    $('.updateBtn')
                );
            });
            // Validación en tiempo real para el campo de EDICIÓN
            $('#nombre_edicion').on('input', function() {
                const nombre = $(this).val().trim();
                const feedback = $('#feedback_edicion');
                const submitBtn = $('.updateBtn');
                const id = $('#modalidadeId').val();

                // Limpiar el mensaje previo
                feedback.removeClass('text-success text-danger').text('');

                if (nombre.length === 0) {
                    submitBtn.prop('disabled', true);
                    return;
                }

                // Cancelar la petición anterior si el usuario sigue escribiendo
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    $.ajax({
                        url: "{{ route('admin.modalidades.verificaredicion') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            nombre: nombre
                        },
                        success: function(res) {
                            if (res.exists) {
                                feedback.addClass('text-danger').text(
                                    '⚠️ Esta modalidad ya está registrada.');
                                submitBtn.prop('disabled', true);
                            } else {
                                feedback.addClass('text-success').text(
                                    '✅ Nombre disponible.');
                                submitBtn.prop('disabled', false);
                            }
                        },
                        error: function() {
                            feedback.addClass('text-danger').text(
                                '❌ Error al verificar el nombre.');
                            submitBtn.prop('disabled', true);
                        }
                    });
                }, 100); // Espera 500ms después de dejar de escribir
            });
            $('#updateForm').submit(function(e) {
                e.preventDefault();
                $('.updateBtn').prop('disabled', true);

                var formData = $(this).serialize();

                $.ajax({
                    url: "{{ route('admin.modalidades.modificar') }}",
                    type: "POST",
                    data: formData,
                    success: function(res) {
                        alert(res.msg);
                        $('.updateBtn').prop('disabled', false);
                        if (res.success) {
                            location.reload();
                        }
                    }
                });
            });

            $('.deleteBtn').click(function() {
                var data = $(this).data('bs-obj');

                $('#eliminarId').val(data.id);
            });

            $('#deleteForm').submit(function(e) {
                e.preventDefault();
                $('.btnDelete').prop('disabled', true);

                var formData = $(this).serialize(); // Incluye _token, _method y id

                $.ajax({
                    url: "{{ route('admin.modalidades.eliminar') }}",
                    type: "POST",
                    data: formData,
                    success: function(res) {
                        alert(res.msg);
                        if (res.success) {
                            location.reload();
                        } else {
                            $('.btnDelete').prop('disabled', false);
                        }
                    },
                    error: function(xhr) {
                        alert('Error al eliminar: ' + (xhr.responseJSON?.msg || 'Desconocido'));
                        $('.btnDelete').prop('disabled', false);
                    }
                });
            });
        });



        function verificarDisponibilidadNombre(nombre, id, feedbackElement, buttonElement) {
            if (nombre.trim() === '') {
                buttonElement.prop('disabled', true);
                feedbackElement.text('').removeClass('text-success text-danger');
                return;
            }

            $.ajax({
                url: "{{ route('admin.modalidades.verificaredicion') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    nombre: nombre,
                    id: id
                },
                success: function(res) {
                    if (res.exists) {
                        feedbackElement.addClass('text-danger').text(
                            '⚠️ Esta modalidad ya está registrada.');
                        buttonElement.prop('disabled', true);
                    } else {
                        feedbackElement.addClass('text-success').text('✅ Nombre disponible.');
                        buttonElement.prop('disabled', false);
                    }
                },
                error: function() {
                    feedbackElement.addClass('text-danger').text('❌ Error al verificar.');
                    buttonElement.prop('disabled', true);
                }
            });
        }
    </script>
    <script>
        $(document).ready(function() {
            let debounceTimer;

            function loadResults(search = '') {
                $.ajax({
                    url: '{{ route('admin.modalidades.listar') }}', // Asegúrate de tener esta ruta
                    method: 'GET',
                    data: {
                        search: search
                    },
                    dataType: 'json',
                    success: function(response) {
                        $('#results-container .table-responsive table').find('tbody').replaceWith(
                            response.html);
                        $('#pagination-container').html(response.pagination);
                        // Re-inicializar feather icons si es necesario
                        if (typeof feather !== 'undefined') {
                            feather.replace();
                        }
                    },
                    error: function() {
                        alert('Error al cargar los resultados');
                    }
                });
            }

            $('#searchInput').on('input', function() {
                const searchTerm = $(this).val().trim();
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    loadResults(searchTerm);
                }, 300);
            });

            // Manejar clics en la paginación (para que funcionen con AJAX)
            $(document).on('click', '#pagination-container .pagination a', function(e) {
                e.preventDefault();
                const url = $(this).attr('href');
                const search = $('#searchInput').val().trim();

                $.ajax({
                    url: url,
                    method: 'GET',
                    data: {
                        search: search
                    },
                    dataType: 'json',
                    success: function(response) {
                        $('#results-container .table-responsive table').find('tbody')
                            .replaceWith(response.html);
                        $('#pagination-container').html(response.pagination);
                        if (typeof feather !== 'undefined') {
                            feather.replace();
                        }
                    }
                });
            });
        });
    </script>
@endpush
