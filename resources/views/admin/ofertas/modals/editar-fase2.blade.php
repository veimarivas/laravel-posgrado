<!-- Modal Editar Fase 2 -->
<div class="modal fade" id="modalEditarFase2" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Oferta (Fase 2)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editFase2Form" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" id="f2_oferta_id">

                    <!-- Responsables -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Responsable Académico *</label>
                            <select name="responsable_academico_cargo_id" id="f2_responsable_academico_cargo_id"
                                class="form-control" required>
                                <option value="">Seleccione</option>
                                @foreach ($trabajadoresAcademicos as $ta)
                                    <option value="{{ $ta->id }}">
                                        {{ $ta->trabajador->persona->nombres }}
                                        {{ $ta->trabajador->persona->apellido_paterno }}
                                        ({{ $ta->cargo->nombre }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Responsable Marketing *</label>
                            <select name="responsable_marketing_cargo_id" id="f2_responsable_marketing_cargo_id"
                                class="form-control" required>
                                <option value="">Seleccione</option>
                                @foreach ($trabajadoresMarketing as $tm)
                                    <option value="{{ $tm->id }}">
                                        {{ $tm->trabajador->persona->nombres }}
                                        {{ $tm->trabajador->persona->apellido_paterno }}
                                        ({{ $tm->cargo->nombre }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Fechas -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Fecha Inicio Inscripciones *</label>
                            <input type="date" name="fecha_inicio_inscripciones" id="f2_fecha_inicio_inscripciones"
                                class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Fecha Inicio Programa *</label>
                            <input type="date" name="fecha_inicio_programa" id="f2_fecha_inicio_programa"
                                class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Fecha Fin Programa *</label>
                            <input type="date" name="fecha_fin_programa" id="f2_fecha_fin_programa"
                                class="form-control" required>
                        </div>
                    </div>

                    <!-- Color -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Color de la oferta</label>
                            <div class="d-flex align-items-center gap-3">
                                <input type="color" id="f2_color" name="color"
                                    class="form-control form-control-color shadow-none p-1" value="#ccc">
                                <span id="f2_preview_color" class="rounded-circle border d-inline-block"
                                    style="width: 32px; height: 32px; background-color: #ccc;"></span>
                            </div>
                            <small class="form-text text-muted">Se usará en calendarios y tarjetas.</small>
                        </div>
                    </div>

                    <!-- Imágenes -->
                    @include('admin.ofertas.partials.imagenes-oferta', ['tipo' => 'fase2'])

                    <div class="text-center mt-3">
                        <button type="submit" class="btn btn-warning">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
