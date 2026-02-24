<div class="row">
    <div class="col-md-8">
        <div class="card border">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="ri-lock-password-line me-2"></i> Cambiar Contraseña
                </h5>
            </div>
            <div class="card-body">
                <form id="changePasswordForm">
                    @csrf
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Contraseña Actual</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="ri-lock-password-line"></i>
                            </span>
                            <input type="password" class="form-control" id="current_password" name="current_password"
                                required>
                            <button class="btn btn-outline-secondary toggle-password" type="button">
                                <i class="ri-eye-line"></i>
                            </button>
                        </div>
                        <div class="form-text">Ingresa tu contraseña actual para autorizar el cambio.</div>
                    </div>

                    <div class="mb-3">
                        <label for="new_password" class="form-label">Nueva Contraseña</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="ri-key-line"></i>
                            </span>
                            <input type="password" class="form-control" id="new_password" name="new_password" required
                                minlength="8">
                            <button class="btn btn-outline-secondary toggle-password" type="button">
                                <i class="ri-eye-line"></i>
                            </button>
                        </div>
                        <div class="form-text">
                            <ul class="mb-0 ps-3">
                                <li>Mínimo 8 caracteres</li>
                                <li>Debe contener al menos una letra</li>
                                <li>Debe contener al menos un número</li>
                            </ul>
                        </div>
                        <div class="progress mt-2" style="height: 5px;">
                            <div id="passwordStrengthBar" class="progress-bar" role="progressbar" style="width: 0%">
                            </div>
                        </div>
                        <small id="passwordStrengthText" class="form-text"></small>
                    </div>

                    <div class="mb-4">
                        <label for="new_password_confirmation" class="form-label">Confirmar Nueva Contraseña</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="ri-key-fill"></i>
                            </span>
                            <input type="password" class="form-control" id="new_password_confirmation"
                                name="new_password_confirmation" required>
                            <button class="btn btn-outline-secondary toggle-password" type="button">
                                <i class="ri-eye-line"></i>
                            </button>
                        </div>
                        <div id="passwordMatch" class="form-text"></div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary" id="changePasswordBtn">
                            <i class="ri-refresh-line me-1"></i> Actualizar Contraseña
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 bg-light">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="ri-information-line me-2"></i> Recomendaciones de Seguridad
                </h6>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <i class="ri-checkbox-circle-line text-success me-2"></i>
                        <small>Usa al menos 8 caracteres</small>
                    </li>
                    <li class="mb-2">
                        <i class="ri-checkbox-circle-line text-success me-2"></i>
                        <small>Combina letras y números</small>
                    </li>
                    <li class="mb-2">
                        <i class="ri-checkbox-circle-line text-success me-2"></i>
                        <small>Evita contraseñas comunes</small>
                    </li>
                    <li class="mb-2">
                        <i class="ri-checkbox-circle-line text-success me-2"></i>
                        <small>No uses información personal</small>
                    </li>
                    <li>
                        <i class="ri-checkbox-circle-line text-success me-2"></i>
                        <small>Cambia tu contraseña periódicamente</small>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
