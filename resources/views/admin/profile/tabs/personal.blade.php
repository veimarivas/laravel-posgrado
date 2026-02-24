<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label fw-medium">Nombres</label>
        <div class="input-group">
            <span class="input-group-text">
                <i class="ri-user-line"></i>
            </span>
            <input type="text" class="form-control" value="{{ auth()->user()->persona->nombres ?? '' }}" readonly>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <label class="form-label fw-medium">Apellido Paterno</label>
        <input type="text" class="form-control" value="{{ auth()->user()->persona->apellido_paterno ?? '' }}"
            readonly>
    </div>

    <div class="col-md-3 mb-3">
        <label class="form-label fw-medium">Apellido Materno</label>
        <input type="text" class="form-control" value="{{ auth()->user()->persona->apellido_materno ?? '' }}"
            readonly>
    </div>

    <div class="col-md-3 mb-3">
        <label class="form-label fw-medium">Sexo</label>
        <div class="input-group">
            <span class="input-group-text">
                <i class="ri-genderless-line"></i>
            </span>
            <input type="text" class="form-control" value="{{ auth()->user()->persona->sexo ?? '' }}" readonly>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <label class="form-label fw-medium">Estado Civil</label>
        <input type="text" class="form-control" value="{{ auth()->user()->persona->estado_civil ?? '' }}" readonly>
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label fw-medium">Fecha de Nacimiento</label>
        <div class="input-group">
            <span class="input-group-text">
                <i class="ri-calendar-line"></i>
            </span>
            <input type="text" class="form-control"
                value="{{ auth()->user()->persona->fecha_nacimiento ? \Carbon\Carbon::parse(auth()->user()->persona->fecha_nacimiento)->format('d/m/Y') : '' }}"
                readonly>
        </div>
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label fw-medium">Correo Electrónico</label>
        <div class="input-group">
            <span class="input-group-text">
                <i class="ri-mail-line"></i>
            </span>
            <input type="text" class="form-control" value="{{ auth()->user()->persona->correo ?? '' }}" readonly>
        </div>
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label fw-medium">Celular</label>
        <div class="input-group">
            <span class="input-group-text">
                <i class="ri-phone-line"></i>
            </span>
            <input type="text" class="form-control" value="{{ auth()->user()->persona->celular ?? '' }}" readonly>
        </div>
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label fw-medium">Teléfono</label>
        <div class="input-group">
            <span class="input-group-text">
                <i class="ri-phone-fill"></i>
            </span>
            <input type="text" class="form-control" value="{{ auth()->user()->persona->telefono ?? '' }}" readonly>
        </div>
    </div>

    <div class="col-12 mb-3">
        <label class="form-label fw-medium">Dirección</label>
        <div class="input-group">
            <span class="input-group-text">
                <i class="ri-home-line"></i>
            </span>
            <textarea class="form-control" rows="2" readonly>{{ auth()->user()->persona->direccion ?? '' }}</textarea>
        </div>
    </div>

    <div class="col-12">
        <label class="form-label fw-medium">Ciudad y Departamento</label>
        <div class="input-group">
            <span class="input-group-text">
                <i class="ri-map-pin-line"></i>
            </span>
            <input type="text" class="form-control"
                value="{{ auth()->user()->persona->ciudad->nombre ?? '' }} 
                      @if (auth()->user()->persona->ciudad && auth()->user()->persona->ciudad->departamento) - {{ auth()->user()->persona->ciudad->departamento->nombre }} @endif"
                readonly>
        </div>
    </div>
</div>
