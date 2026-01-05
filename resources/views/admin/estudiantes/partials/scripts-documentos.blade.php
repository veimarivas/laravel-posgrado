<script>
    $(document).ready(function() {
        // Previsualización de documentos PDF
        $(document).on('click', '.preview-documento', function() {
            const url = $(this).data('url');

            // Configurar el iframe para mostrar el PDF
            $('#pdfPreview').attr('src', url);

            // Configurar enlace de descarga
            $('#downloadPdf').attr('href', url);

            // Mostrar el modal
            $('#modalPreviewDocumento').modal('show');
        });

        // Limpiar iframe al cerrar el modal
        $('#modalPreviewDocumento').on('hidden.bs.modal', function() {
            $('#pdfPreview').attr('src', '');
        });

        // Cerrar modal con Escape
        $(document).keydown(function(e) {
            if (e.key === "Escape") {
                $('#modalPreviewDocumento').modal('hide');
            }
        });

        // Obtener el ID del estudiante desde Blade
        const estudianteId = {{ $estudiante->id }};

        // Subir Carnet
        $('#formSubirCarnet').submit(function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const url = "{{ route('admin.estudiantes.subir-documento-carnet', ':id') }}".replace(
                ':id',
                estudianteId);

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $('#modalSubirCarnet .btn-primary').html(
                        '<span class="spinner-border spinner-border-sm me-1"></span> Subiendo...'
                    ).prop('disabled', true);
                },
                success: function(response) {
                    if (response.success) {
                        $('#modalSubirCarnet').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: response.msg,
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.msg
                        });
                    }
                },
                error: function(xhr) {
                    let errorMsg = 'Error al subir el archivo';
                    if (xhr.responseJSON && xhr.responseJSON.msg) {
                        errorMsg = xhr.responseJSON.msg;
                    } else if (xhr.status === 422) {
                        errorMsg =
                            'Error de validación: Por favor, verifica que el archivo sea un PDF válido (máximo 2MB).';
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: errorMsg
                    });
                },
                complete: function() {
                    $('#modalSubirCarnet .btn-primary').html('Subir').prop('disabled',
                        false);
                }
            });
        });

        // Subir Certificado de Nacimiento
        $('#formSubirCertificadoNacimiento').submit(function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const url =
                "{{ route('admin.estudiantes.subir-documento-certificado-nacimiento', ':id') }}"
                .replace(':id', estudianteId);

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $('#modalSubirCertificadoNacimiento .btn-primary').html(
                        '<span class="spinner-border spinner-border-sm me-1"></span> Subiendo...'
                    ).prop('disabled', true);
                },
                success: function(response) {
                    if (response.success) {
                        $('#modalSubirCertificadoNacimiento').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: response.msg,
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.msg
                        });
                    }
                },
                error: function(xhr) {
                    let errorMsg = 'Error al subir el archivo';
                    if (xhr.responseJSON && xhr.responseJSON.msg) {
                        errorMsg = xhr.responseJSON.msg;
                    } else if (xhr.status === 422) {
                        errorMsg =
                            'Error de validación: Por favor, verifica que el archivo sea un PDF válido (máximo 2MB).';
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: errorMsg
                    });
                },
                complete: function() {
                    $('#modalSubirCertificadoNacimiento .btn-primary').html('Subir').prop(
                        'disabled', false);
                }
            });
        });

        // Subir Título Académico
        $('#formSubirTituloAcademico').submit(function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const url = "{{ route('admin.estudiantes.subir-documento-titulo-academico', ':id') }}"
                .replace(':id', estudianteId);

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $('#modalSubirTituloAcademico .btn-primary').html(
                        '<span class="spinner-border spinner-border-sm me-1"></span> Subiendo...'
                    ).prop('disabled', true);
                },
                success: function(response) {
                    if (response.success) {
                        $('#modalSubirTituloAcademico').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: response.msg,
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.msg
                        });
                    }
                },
                error: function(xhr) {
                    let errorMsg = 'Error al subir el archivo';
                    if (xhr.responseJSON && xhr.responseJSON.msg) {
                        errorMsg = xhr.responseJSON.msg;
                    } else if (xhr.status === 404) {
                        errorMsg =
                            'No existe un estudio marcado como principal. Por favor, marque un estudio como principal primero.';
                    } else if (xhr.status === 422) {
                        errorMsg =
                            'Error de validación: Por favor, verifica que el archivo sea un PDF válido (máximo 2MB).';
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: errorMsg
                    });
                },
                complete: function() {
                    $('#modalSubirTituloAcademico .btn-primary').html('Subir').prop(
                        'disabled', false);
                }
            });
        });

        // Subir Provisión Nacional
        $('#formSubirProvisionNacional').submit(function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const url = "{{ route('admin.estudiantes.subir-documento-provision-nacional', ':id') }}"
                .replace(':id', estudianteId);

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $('#modalSubirProvisionNacional .btn-primary').html(
                        '<span class="spinner-border spinner-border-sm me-1"></span> Subiendo...'
                    ).prop('disabled', true);
                },
                success: function(response) {
                    if (response.success) {
                        $('#modalSubirProvisionNacional').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: response.msg,
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.msg
                        });
                    }
                },
                error: function(xhr) {
                    let errorMsg = 'Error al subir el archivo';
                    if (xhr.responseJSON && xhr.responseJSON.msg) {
                        errorMsg = xhr.responseJSON.msg;
                    } else if (xhr.status === 404) {
                        errorMsg =
                            'No existe un estudio marcado como principal. Por favor, marque un estudio como principal primero.';
                    } else if (xhr.status === 422) {
                        errorMsg =
                            'Error de validación: Por favor, verifica que el archivo sea un PDF válido (máximo 2MB).';
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: errorMsg
                    });
                },
                complete: function() {
                    $('#modalSubirProvisionNacional .btn-primary').html('Subir').prop(
                        'disabled', false);
                }
            });
        });

        // Verificar documentos
        $(document).on('click', '.btn-verificar-carnet, .btn-verificar-documento', function() {
            const tipo = $(this).data('tipo');
            const boton = $(this);
            const verificadoActual = boton.hasClass('btn-outline-danger') ? 0 : 1;
            const nuevoEstado = verificadoActual ? 0 : 1;

            let rutaTemplate = '';
            let nombreDocumento = '';

            switch (tipo) {
                case 'carnet':
                    rutaTemplate =
                        "{{ route('admin.estudiantes.verificar-documento-carnet', ':id') }}";
                    nombreDocumento = 'Carnet';
                    break;
                case 'certificado_nacimiento':
                    rutaTemplate =
                        "{{ route('admin.estudiantes.verificar-documento-certificado-nacimiento', ':id') }}";
                    nombreDocumento = 'Certificado de Nacimiento';
                    break;
                case 'documento_academico':
                    rutaTemplate =
                        "{{ route('admin.estudiantes.verificar-documento-academico', ':id') }}";
                    nombreDocumento = 'Documento Académico';
                    break;
                case 'provision_nacional':
                    rutaTemplate =
                        "{{ route('admin.estudiantes.verificar-documento-provision-nacional', ':id') }}";
                    nombreDocumento = 'Provisión Nacional';
                    break;
            }

            const ruta = rutaTemplate.replace(':id', estudianteId);

            Swal.fire({
                title: nuevoEstado ? '¿Marcar como verificado?' : '¿Marcar como no verificado?',
                text: `¿Está seguro de cambiar el estado de verificación del ${nombreDocumento.toLowerCase()}?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: nuevoEstado ? 'Sí, verificar' : 'Sí, no verificar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: nuevoEstado ? '#198754' : '#6c757d',
                cancelButtonColor: '#6c757d'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: ruta,
                        type: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            verificado: nuevoEstado
                        },
                        beforeSend: function() {
                            boton.html(
                                '<span class="spinner-border spinner-border-sm me-1"></span> Procesando...'
                            ).prop('disabled', true);
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: '¡Éxito!',
                                    text: response.msg,
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: response.msg
                                });
                            }
                        },
                        error: function(xhr) {
                            let errorMsg =
                                'Error al cambiar el estado de verificación';
                            if (xhr.responseJSON && xhr.responseJSON.msg) {
                                errorMsg = xhr.responseJSON.msg;
                            } else if (xhr.status === 404) {
                                errorMsg =
                                    'No se encontró el documento o el estudio principal.';
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: errorMsg
                            });
                        },
                        complete: function() {
                            boton.html(nuevoEstado ?
                                '<i class="ri-check-line me-1"></i> Verificar' :
                                '<i class="ri-close-line me-1"></i> No Verificar'
                            ).prop('disabled', false);
                        }
                    });
                }
            });
        });

        // Ver notas (existente)
        $(document).on('click', '.ver-notas-btn', function() {
            const inscripcionId = $(this).data('inscripcion-id');
            $('#contenidoNotas').html(`
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="mt-2">Cargando notas...</p>
                </div>
            `);
            $('#modalVerNotas').modal('show');
        });

        // Ver pagos (existente)
        $(document).on('click', '.ver-pagos-btn', function() {
            const inscripcionId = $(this).data('inscripcion-id');
            $('#contenidoPagos').html(`
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="mt-2">Cargando información de pagos...</p>
                </div>
            `);
            $('#modalVerPagos').modal('show');
        });

        // Limpiar formularios al cerrar modales
        $('#modalSubirCarnet, #modalSubirCertificadoNacimiento, #modalSubirTituloAcademico, #modalSubirProvisionNacional')
            .on('hidden.bs.modal', function() {
                $(this).find('form')[0].reset();
                $(this).find('.form-control').removeClass('is-invalid');
                $(this).find('.invalid-feedback').remove();
            });

        // Validación visual de archivos
        $('input[type="file"]').on('change', function() {
            const file = this.files[0];
            const $input = $(this);
            const $formGroup = $input.closest('.mb-3');

            // Limpiar validaciones anteriores
            $formGroup.find('.invalid-feedback').remove();
            $input.removeClass('is-invalid');

            if (file) {
                // Validar tamaño (2MB = 2 * 1024 * 1024 bytes)
                if (file.size > 2 * 1024 * 1024) {
                    $input.addClass('is-invalid');
                    $formGroup.append(
                        '<div class="invalid-feedback">El archivo es demasiado grande. Máximo 2MB.</div>'
                    );
                    this.value = '';
                }

                // Validar tipo
                else if (file.type !== 'application/pdf') {
                    $input.addClass('is-invalid');
                    $formGroup.append(
                        '<div class="invalid-feedback">Solo se permiten archivos PDF.</div>');
                    this.value = '';
                }
            }
        });
    });
</script>
