<script>
    // === CARGAR DATOS EN MODAL DE EDICIÓN ===
    $(document).on('click', '.editOfertaBtn', function() {
        const id = $(this).data('oferta-id');
        $.get(`/admin/ofertas/${id}/editar`, function(oferta) {
            // Campos básicos
            $('#edit_oferta_id').val(oferta.id);
            $('#edit_codigo').val(oferta.codigo);
            $('#edit_gestion').val(oferta.gestion);
            $('#edit_n_modulos').val(oferta.n_modulos || 1);
            $('#edit_cantidad_sesiones').val(oferta.cantidad_sesiones || 1);
            $('#edit_version').val(oferta.version || '1');
            $('#edit_grupo').val(oferta.grupo || '1');
            $('#edit_nota_minima').val(oferta.nota_minima || 61);
            $('#edit_fecha_inicio_inscripciones').val(oferta.fecha_inicio_inscripciones ||
                '');
            $('#edit_fecha_inicio_programa').val(oferta.fecha_inicio_programa || '');
            $('#edit_fecha_fin_programa').val(oferta.fecha_fin_programa || '');
            $('#edit_modalidade_id').val(oferta.modalidade_id);
            $('#edit_responsable_academico_cargo_id').val(oferta
                .responsable_academico_cargo_id);
            $('#edit_responsable_marketing_cargo_id').val(oferta
                .responsable_marketing_cargo_id);
            $('#edit_programa_nombre').val(oferta.programa?.nombre || '');
            $('#edit_programa_id').val(oferta.programa_id);
            const color = oferta.color || '#cccccc';
            $('#edit_color').val(color);
            $('#edit_preview_color').css('background-color', color);

            // Limpiar placeholders de imágenes
            $('#edit_preview_portada, #edit_preview_certificado').hide();
            $('#edit_placeholder_portada, #edit_placeholder_certificado').show();

            // Sede y sucursal
            $('#edit_sede_id').val(oferta.sucursal?.sede_id).trigger('change');
            setTimeout(() => {
                $('#edit_sucursale_id').val(oferta.sucursale_id);
            }, 300);

            // Módulos
            let modHtml = '';
            if (oferta.modulos?.length) {
                oferta.modulos.forEach(m => {
                    modHtml += `
                    <div class="row mb-2">
                        <div class="col-md-1">
                            <input type="hidden" name="modulos[${m.n_modulo}][n_modulo]" value="${m.n_modulo}">
                            <span class="form-control-plaintext">${m.n_modulo}</span>
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="modulos[${m.n_modulo}][nombre]" class="form-control" value="${m.nombre}" required>
                        </div>
                        <div class="col-md-3">
                            <input type="date" name="modulos[${m.n_modulo}][fecha_inicio]" class="form-control" value="${m.fecha_inicio?.split(' ')[0] || ''}" required>
                        </div>
                        <div class="col-md-3">
                            <input type="date" name="modulos[${m.n_modulo}][fecha_fin]" class="form-control" value="${m.fecha_fin?.split(' ')[0] || ''}" required>
                        </div>
                    </div>`;
                });
            }
            $('#edit_modulos-container').html(modHtml ||
                '<p class="text-muted">Sin módulos.</p>');

            // Planes de pago
            let planesHtml = '';
            const agrupados = {};
            if (oferta.plan_concepto) {
                oferta.plan_concepto.forEach(pc => {
                    if (!agrupados[pc.planes_pago_id]) {
                        agrupados[pc.planes_pago_id] = {
                            nombre: PLANES_PAGOS.find(p => p.id == pc
                                    .planes_pago_id)?.nombre || 'Plan ' + pc
                                .planes_pago_id,
                            conceptos: []
                        };
                    }
                    agrupados[pc.planes_pago_id].conceptos.push(pc);
                });
            }
            Object.entries(agrupados).forEach(([planId, data]) => {
                planesHtml += `
                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between">
                        <strong>${data.nombre}</strong>
                        <button type="button" class="btn btn-sm btn-outline-primary add-concepto-edit" data-plan-id="${planId}">➕ Concepto</button>
                    </div>
                    <div class="card-body conceptos-container" id="conceptos_edit_${planId}">
                        ${data.conceptos.map((pc, idx) => `
                            <div class="row mb-2 concepto-item">
                                <div class="col-md-5">
                                    <select name="planes[${planId}][${idx}][concepto_id]" class="form-control" required>
                                        ${CONCEPTOS.map(c => `<option value="${c.id}" ${c.id == pc.concepto_id ? 'selected' : ''}>${c.nombre}</option>`).join('')}
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <input type="number" name="planes[${planId}][${idx}][n_cuotas]" class="form-control" value="${pc.n_cuotas}" min="1" required>
                                </div>
                                <div class="col-md-3">
                                    <input type="number" step="0.01" name="planes[${planId}][${idx}][pago_bs]" class="form-control" value="${pc.pago_bs}" min="0" required>
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="button" class="btn btn-sm btn-outline-danger remove-concepto">🗑️</button>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                </div>`;
            });
            $('#edit_planes-pago-container').html(planesHtml ||
                '<p class="text-muted">Sin planes de pago.</p>');

            $('#modalEditarOferta').modal('show');
        });
    });

    // Previsualización de imágenes en edición
    document.getElementById('edit_portada_input').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('edit_preview_portada');
        const placeholder = document.getElementById('edit_placeholder_portada');
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                preview.src = event.target.result;
                preview.style.display = 'block';
                placeholder.style.display = 'none';
            };
            reader.readAsDataURL(file);
        } else {
            preview.style.display = 'none';
            placeholder.style.display = 'block';
        }
    });

    document.getElementById('edit_certificado_input').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('edit_preview_certificado');
        const placeholder = document.getElementById('edit_placeholder_certificado');
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                preview.src = event.target.result;
                preview.style.display = 'block';
                placeholder.style.display = 'none';
            };
            reader.readAsDataURL(file);
        } else {
            preview.style.display = 'none';
            placeholder.style.display = 'block';
        }
    });

    // Color preview
    $('#edit_color').on('input', function() {
        const color = $(this).val();
        $('#edit_preview_color').css('background-color', color);
    });

    // Sede → Sucursal en edición
    $('#edit_sede_id').on('change', function() {
        const sedeId = $(this).val();
        const sel = $('#edit_sucursale_id');
        sel.empty().prop('disabled', true);
        if (!sedeId) {
            sel.append('<option value="">Seleccione sede primero</option>');
            return;
        }
        $.get("{{ route('admin.sucursales.por-sede') }}", {
            sede_id: sedeId
        }, function(data) {
            sel.prop('disabled', false).append(
                '<option value="">Seleccione sucursal</option>');
            data.forEach(s => sel.append(`<option value="${s.id}">${s.nombre}</option>`));
        });
    });

    // Validación de código único en edición
    let editDebounceCodigo;
    $('#edit_codigo').on('input', function() {
        const codigo = $(this).val().trim();
        const id = $('#edit_oferta_id').val();
        if (!codigo) return;
        clearTimeout(editDebounceCodigo);
        editDebounceCodigo = setTimeout(() => {
            $.post("{{ route('admin.ofertas-academicas.verificar-codigo') }}", {
                _token: '{{ csrf_token() }}',
                codigo: codigo,
                exclude_id: id
            }, function(res) {
                const fb = $('#edit_feedback_codigo');
                if (res.exists) {
                    fb.removeClass('text-success').addClass('text-danger').text(
                        '❌ Código ya en uso.');
                } else {
                    fb.removeClass('text-danger').addClass('text-success').text(
                        '✅ Disponible.');
                }
            });
        }, 400);
    });

    // Generar módulos al cambiar n_modulos
    $('#edit_n_modulos').on('input change', function() {
        const n = parseInt($(this).val()) || 0;
        const container = $('#edit_modulos-container');
        container.empty();
        if (n < 1) {
            container.html('<p class="text-muted">Ingrese ≥1 módulo.</p>');
            return;
        }
        let html = '';
        for (let i = 1; i <= n; i++) {
            html += `
            <div class="row mb-2">
                <div class="col-md-1">
                    <input type="hidden" name="modulos[${i}][n_modulo]" value="${i}">
                    <span class="form-control-plaintext">${i}</span>
                </div>
                <div class="col-md-4">
                    <input type="text" name="modulos[${i}][nombre]" class="form-control" placeholder="Nombre del módulo ${i}" required>
                </div>
                <div class="col-md-3">
                    <input type="date" name="modulos[${i}][fecha_inicio]" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <input type="date" name="modulos[${i}][fecha_fin]" class="form-control" required>
                </div>
            </div>`;
        }
        container.html(html);
    });

    // Conceptos dinámicos en edición
    $(document).on('click', '.add-concepto-edit', function() {
        const planId = $(this).data('plan-id');
        const container = $(`#conceptos_edit_${planId}`);
        const idx = container.children('.concepto-item').length;
        let opts = '<option value="">Concepto</option>';
        CONCEPTOS.forEach(c => opts += `<option value="${c.id}">${c.nombre}</option>`);
        const html = `
        <div class="row mb-2 concepto-item">
            <div class="col-md-5">
                <select name="planes[${planId}][${idx}][concepto_id]" class="form-control" required>${opts}</select>
            </div>
            <div class="col-md-2">
                <input type="number" name="planes[${planId}][${idx}][n_cuotas]" class="form-control" min="1" required>
            </div>
            <div class="col-md-3">
                <input type="number" step="0.01" name="planes[${planId}][${idx}][pago_bs]" class="form-control" min="0" required>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="button" class="btn btn-sm btn-outline-danger remove-concepto">🗑️</button>
            </div>
        </div>`;
        container.append(html);
    });

    $(document).on('click', '.remove-concepto', function() {
        $(this).closest('.concepto-item').remove();
    });

    // Submit edición
    $('#editOfertaForm').submit(function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        // 👇 Agrega esto temporalmente para depurar
        for (let [key, value] of formData.entries()) {
            console.log(key, value);
        }
        $.ajax({
            url: "{{ route('admin.ofertas.actualizar') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(res) {
                alert(res.msg);
                if (res.success) {
                    $('#modalEditarOferta').modal('hide');
                    location.reload();
                }
            },
            error: function(xhr) {
                alert(xhr.responseJSON?.msg || 'Error al actualizar.');
            }
        });
    });
</script>
