<div class="row">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header border-0 py-3 px-4"
                 style="background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);border-radius:10px 10px 0 0;">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle d-flex align-items-center justify-content-center"
                         style="width:36px;height:36px;background:rgba(255,255,255,.2);flex-shrink:0;">
                        <i class="ri-lock-password-line text-white fs-18"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 text-white fw-semibold">Cambiar Contraseña</h6>
                        <p class="mb-0 text-white-50" style="font-size:.78rem;">Actualiza tu contraseña de acceso</p>
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                <form id="changePasswordForm">
                    @csrf
                    <div class="mb-3">
                        <label for="current_password" class="form-label fw-semibold" style="font-size:.85rem;">
                            Contraseña Actual
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="ri-lock-password-line text-muted"></i>
                            </span>
                            <input type="password" class="form-control border-start-0 ps-0" id="current_password"
                                   name="current_password" placeholder="Ingresa tu contraseña actual" required>
                            <button class="btn btn-outline-secondary toggle-password" type="button">
                                <i class="ri-eye-line"></i>
                            </button>
                        </div>
                        <div class="form-text text-muted" style="font-size:.78rem;">
                            Ingresa tu contraseña actual para autorizar el cambio.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="new_password" class="form-label fw-semibold" style="font-size:.85rem;">
                            Nueva Contraseña
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="ri-key-line text-muted"></i>
                            </span>
                            <input type="password" class="form-control border-start-0 ps-0" id="new_password"
                                   name="new_password" placeholder="Mínimo 8 caracteres" required minlength="8">
                            <button class="btn btn-outline-secondary toggle-password" type="button">
                                <i class="ri-eye-line"></i>
                            </button>
                        </div>
                        <div class="form-text text-muted" style="font-size:.78rem;">
                            <ul class="mb-0 ps-3">
                                <li>Mínimo 8 caracteres</li>
                                <li>Debe contener al menos una letra</li>
                                <li>Debe contener al menos un número</li>
                            </ul>
                        </div>
                        <div class="progress mt-2" style="height: 5px; border-radius:3px;">
                            <div id="passwordStrengthBar" class="progress-bar" role="progressbar" style="width: 0%"></div>
                        </div>
                        <small id="passwordStrengthText" class="form-text"></small>
                    </div>

                    <div class="mb-4">
                        <label for="new_password_confirmation" class="form-label fw-semibold" style="font-size:.85rem;">
                            Confirmar Nueva Contraseña
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="ri-key-fill text-muted"></i>
                            </span>
                            <input type="password" class="form-control border-start-0 ps-0" id="new_password_confirmation"
                                   name="new_password_confirmation" placeholder="Repite la nueva contraseña" required>
                            <button class="btn btn-outline-secondary toggle-password" type="button">
                                <i class="ri-eye-line"></i>
                            </button>
                        </div>
                        <div id="passwordMatch" class="form-text"></div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary px-4" id="changePasswordBtn">
                            <i class="ri-shield-check-line me-1"></i> Actualizar Contraseña
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4">
                <h6 class="fw-semibold mb-3" style="font-size:.85rem;">
                    <i class="ri-shield-keyhole-line me-2 text-primary"></i>Recomendaciones de Seguridad
                </h6>
                <ul class="list-unstyled mb-0">
                    <li class="d-flex align-items-start gap-2 mb-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:28px;height:28px;background:rgba(67,97,238,.1);">
                            <i class="ri-check-line text-primary" style="font-size:.85rem;"></i>
                        </div>
                        <div>
                            <p class="mb-0 fw-semibold" style="font-size:.82rem;">Usa al menos 8 caracteres</p>
                            <p class="mb-0 text-muted" style="font-size:.75rem;">Contraseñas más largas son más seguras</p>
                        </div>
                    </li>
                    <li class="d-flex align-items-start gap-2 mb-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:28px;height:28px;background:rgba(67,97,238,.1);">
                            <i class="ri-check-line text-primary" style="font-size:.85rem;"></i>
                        </div>
                        <div>
                            <p class="mb-0 fw-semibold" style="font-size:.82rem;">Combina letras y números</p>
                            <p class="mb-0 text-muted" style="font-size:.75rem;">Mezcla mayúsculas, minúsculas y dígitos</p>
                        </div>
                    </li>
                    <li class="d-flex align-items-start gap-2 mb-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:28px;height:28px;background:rgba(67,97,238,.1);">
                            <i class="ri-check-line text-primary" style="font-size:.85rem;"></i>
                        </div>
                        <div>
                            <p class="mb-0 fw-semibold" style="font-size:.82rem;">Evita contraseñas comunes</p>
                            <p class="mb-0 text-muted" style="font-size:.75rem;">No uses "123456", "qwerty" o tu nombre</p>
                        </div>
                    </li>
                    <li class="d-flex align-items-start gap-2 mb-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:28px;height:28px;background:rgba(67,97,238,.1);">
                            <i class="ri-check-line text-primary" style="font-size:.85rem;"></i>
                        </div>
                        <div>
                            <p class="mb-0 fw-semibold" style="font-size:.82rem;">No uses información personal</p>
                            <p class="mb-0 text-muted" style="font-size:.75rem;">Evita fechas de nacimiento o CI</p>
                        </div>
                    </li>
                    <li class="d-flex align-items-start gap-2">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:28px;height:28px;background:rgba(67,97,238,.1);">
                            <i class="ri-check-line text-primary" style="font-size:.85rem;"></i>
                        </div>
                        <div>
                            <p class="mb-0 fw-semibold" style="font-size:.82rem;">Cambia periódicamente</p>
                            <p class="mb-0 text-muted" style="font-size:.75rem;">Actualiza tu contraseña cada 3-6 meses</p>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
