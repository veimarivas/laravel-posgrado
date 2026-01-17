<script>
    // === CARGAR DATOS EN MODAL FASE 2 ===
    $(document).on('click', '.editFase2Btn', function() {
        const id = $(this).data('oferta-id');
        $.get(`/admin/ofertas/${id}/editar`, function(oferta) {
            $('#f2_oferta_id').val(oferta.id);
            $('#f2_responsable_academico_cargo_id').val(oferta.responsable_academico_cargo_id);
            $('#f2_responsable_marketing_cargo_id').val(oferta.responsable_marketing_cargo_id);
            $('#f2_fecha_inicio_inscripciones').val(oferta.fecha_inicio_inscripciones?.split(' ')[0] ||
                '');
            $('#f2_fecha_inicio_programa').val(oferta.fecha_inicio_programa?.split(' ')[0] || '');
            $('#f2_fecha_fin_programa').val(oferta.fecha_fin_programa?.split(' ')[0] || '');
            const color = oferta.color || '#cccccc';
            $('#f2_color').val(color);
            $('#f2_preview_color').css('background-color', color);

            $('#f2_preview_portada, #f2_preview_certificado').hide();
            $('#f2_placeholder_portada, #f2_placeholder_certificado').show();

            $('#modalEditarFase2').modal('show');
        });
    });

    $('#f2_color').on('input', function() {
        $('#f2_preview_color').css('background-color', $(this).val());
    });

    $('#f2_portada_input').on('change', function(e) {
        const file = e.target.files[0];
        const preview = $('#f2_preview_portada');
        const placeholder = $('#f2_placeholder_portada');
        if (file) {
            const reader = new FileReader();
            reader.onload = () => {
                preview.attr('src', reader.result).show();
                placeholder.hide();
            };
            reader.readAsDataURL(file);
        } else {
            preview.hide();
            placeholder.show();
        }
    });

    $('#f2_certificado_input').on('change', function(e) {
        const file = e.target.files[0];
        const preview = $('#f2_preview_certificado');
        const placeholder = $('#f2_placeholder_certificado');
        if (file) {
            const reader = new FileReader();
            reader.onload = () => {
                preview.attr('src', reader.result).show();
                placeholder.hide();
            };
            reader.readAsDataURL(file);
        } else {
            preview.hide();
            placeholder.show();
        }
    });

    $('#editFase2Form').submit(function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        $.ajax({
            url: "{{ route('admin.ofertas.fase2.actualizar') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(res) {
                alert(res.msg);
                if (res.success) {
                    $('#modalEditarFase2').modal('hide');
                    location.reload();
                }
            },
            error: function(xhr) {
                alert(xhr.responseJSON?.msg || 'Error al actualizar en fase 2.');
            }
        });
    });
</script>
