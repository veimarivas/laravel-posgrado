<div class="row g-4">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm" style="border-radius:12px;overflow:hidden;">
            <div class="card-header border-0 py-3 px-4"
                 style="background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width:42px;height:42px;background:rgba(255,255,255,.18);border:1.5px solid rgba(255,255,255,.3);">
                        <i class="ri-lock-password-line text-white" style="font-size:1.15rem;"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 text-white fw-semibold" style="font-size:.95rem;">Cambiar Contraseña</h6>
                        <p class="mb-0 text-white-50" style="font-size:.76rem;">Actualiza tu contraseña de acceso al sistema</p>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                <form id="changePasswordForm">
                    @csrf

                    {{-- Contraseña actual --}}
                    <div class="mb-4">
                        <label for="current_password" class="form-label fw-semibold mb-1" style="font-size:.83rem;">
                            <i class="ri-lock-line me-1 text-muted"></i>Contraseña Actual
                        </label>
                        <div class="input-group shadow-sm" style="border-radius:8px;overflow:hidden;">
                            <span class="input-group-text bg-light border-end-0 border-0 ps-3">
                                <i class="ri-lock-password-line text-muted"></i>
                            </span>
                            <input type="password" class="form-control border-0 bg-light ps-1"
                                   id="current_password" name="current_password"
                                   placeholder="Ingresa tu contraseña actual" required>
                            <button class="btn bg-light border-0 toggle-password text-muted px-3" type="button">
                                <i class="ri-eye-line"></i>
                            </button>
                        </div>
                        <p class="text-muted mb-0 mt-1" style="font-size:.76rem;">
                            <i class="ri-information-line me-1"></i>Necesaria para autorizar el cambio
                        </p>
                    </div>

                    <hr class="my-3 border-dashed opacity-25">

                    {{-- Nueva contraseña --}}
                    <div class="mb-3">
                        <label for="new_password" class="form-label fw-semibold mb-1" style="font-size:.83rem;">
                            <i class="ri-key-line me-1 text-muted"></i>Nueva Contraseña
                        </label>
                        <div class="input-group shadow-sm" style="border-radius:8px;overflow:hidden;">
                            <span class="input-group-text bg-light border-end-0 border-0 ps-3">
                                <i class="ri-key-line text-muted"></i>
                            </span>
                            <input type="password" class="form-control border-0 bg-light ps-1"
                                   id="new_password" name="new_password"
                                   placeholder="Mínimo 8 caracteres" required minlength="8">
                            <button class="btn bg-light border-0 toggle-password text-muted px-3" type="button">
                                <i class="ri-eye-line"></i>
                            </button>
                        </div>

                        {{-- Strength bar --}}
                        <div class="mt-2">
                            <div class="progress" style="height:6px;border-radius:4px;background:#e9ecef;">
                                <div id="passwordStrengthBar" class="progress-bar transition-all"
                                     role="progressbar" style="width:0%;transition:width .4s,background .4s;border-radius:4px;"></div>
                            </div>
                            <small id="passwordStrengthText" class="form-text fw-semibold" style="font-size:.75rem;"></small>
                        </div>

                        <ul class="mb-0 ps-3 mt-1 text-muted" style="font-size:.75rem;">
                            <li>Mínimo 8 caracteres</li>
                            <li>Al menos una letra y un número</li>
                        </ul>
                    </div>

                    {{-- Confirmar contraseña --}}
                    <div class="mb-4">
                        <label for="new_password_confirmation" class="form-label fw-semibold mb-1" style="font-size:.83rem;">
                            <i class="ri-key-fill me-1 text-muted"></i>Confirmar Nueva Contraseña
                        </label>
                        <div class="input-group shadow-sm" style="border-radius:8px;overflow:hidden;">
                            <span class="input-group-text bg-light border-end-0 border-0 ps-3">
                                <i class="ri-key-fill text-muted"></i>
                            </span>
                            <input type="password" class="form-control border-0 bg-light ps-1"
                                   id="new_password_confirmation" name="new_password_confirmation"
                                   placeholder="Repite la nueva contraseña" required>
                            <button class="btn bg-light border-0 toggle-password text-muted px-3" type="button">
                                <i class="ri-eye-line"></i>
                            </button>
                        </div>
                        <div id="passwordMatch" class="form-text fw-semibold mt-1" style="font-size:.76rem;"></div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary px-5 fw-semibold" id="changePasswordBtn"
                                style="border-radius:8px;background:linear-gradient(135deg,#667eea,#764ba2);border:none;box-shadow:0 4px 14px rgba(102,126,234,.4);">
                            <i class="ri-shield-check-line me-1"></i>Actualizar Contraseña
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100" style="border-radius:12px;overflow:hidden;">
            <div class="card-header border-0 py-3 px-4"
                 style="background:linear-gradient(135deg,#f8f9fa,#e9ecef);">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width:36px;height:36px;background:rgba(102,126,234,.12);">
                        <i class="ri-shield-keyhole-line text-primary" style="font-size:1rem;"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-semibold" style="font-size:.85rem;">Recomendaciones</h6>
                        <p class="mb-0 text-muted" style="font-size:.72rem;">Buenas prácticas de seguridad</p>
                    </div>
                </div>
            </div>
            <div class="card-body p-4" style="background:linear-gradient(180deg,#fafbff 0%,#fff 100%);">
                <ul class="list-unstyled mb-0">
                    @foreach([
                        ['icon'=>'ri-text-wrap','color'=>'#667eea','title'=>'Usa al menos 8 caracteres','desc'=>'Contraseñas más largas son más seguras'],
                        ['icon'=>'ri-shuffle-line','color'=>'#20c997','title'=>'Combina letras y números','desc'=>'Mezcla mayúsculas, minúsculas y dígitos'],
                        ['icon'=>'ri-spam-2-line','color'=>'#fd7e14','title'=>'Evita contraseñas comunes','desc'=>'No uses "123456", "qwerty" o tu nombre'],
                        ['icon'=>'ri-user-forbid-line','color'=>'#e83e8c','title'=>'Sin información personal','desc'=>'Evita fechas de nacimiento o carnet'],
                        ['icon'=>'ri-loop-right-line','color'=>'#6f42c1','title'=>'Cambia periódicamente','desc'=>'Actualiza tu contraseña cada 3-6 meses'],
                    ] as $tip)
                    <li class="d-flex align-items-start gap-3 mb-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:30px;height:30px;background:{{ $tip['color'] }}1a;">
                            <i class="{{ $tip['icon'] }}" style="font-size:.85rem;color:{{ $tip['color'] }};"></i>
                        </div>
                        <div>
                            <p class="mb-0 fw-semibold" style="font-size:.81rem;">{{ $tip['title'] }}</p>
                            <p class="mb-0 text-muted" style="font-size:.73rem;">{{ $tip['desc'] }}</p>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
