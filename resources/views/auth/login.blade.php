<!doctype html>
<html lang="es" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg"
    data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="default">

<head>
    <meta charset="utf-8" />
    <title>Ingresar | Sistema UNIP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistema de gestión UNIP - Unidad de Negocios Inteligentes Profesionales">
    <meta name="author" content="Sistema UNIP">
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('backend/assets/images/favicon.ico') }}">

    <!-- Layout config Js -->
    <script src="{{ asset('backend/assets/js/layout.js') }}"></script>
    <!-- Bootstrap Css -->
    <link href="{{ asset('backend/assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('backend/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('backend/assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- custom Css-->
    <link href="{{ asset('backend/assets/css/custom.min.css') }}" rel="stylesheet" type="text/css" />

    <style>
        /* ===== VARIABLES Y RESET ===== */
        :root {
            --primary-color: #1e293b;
            --primary-dark: #055248;
            --primary-light: #0a8a7a;
            --accent-color: #0d9488;
            --text-dark: #1e293b;
            --text-light: #64748b;
            --text-white: #ffffff;
            --bg-light: #f8fafc;
            --border-color: #e2e8f0;
            --success-color: #10b981;
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.12);
            --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 25px rgba(0, 0, 0, 0.15);
            --radius-sm: 6px;
            --radius-md: 10px;
            --radius-lg: 16px;
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body,
        html {
            height: 100%;
            width: 100%;
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            overflow-x: hidden;
        }

        /* ===== FONDO PRINCIPAL ===== */
        .login-container {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-dark) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        /* Patrón sutil de fondo */
        .login-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image:
                radial-gradient(circle at 15% 50%, rgba(255, 255, 255, 0.05) 0%, transparent 55%),
                radial-gradient(circle at 85% 30%, rgba(255, 255, 255, 0.05) 0%, transparent 55%);
            z-index: 1;
        }

        /* ===== CONTENEDOR PRINCIPAL ===== */
        .login-wrapper {
            width: 100%;
            max-width: 1200px;
            min-height: 600px;
            height: 85vh;
            max-height: 850px;
            background: white;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-lg);
            overflow: hidden;
            display: flex;
            position: relative;
            z-index: 2;
            margin: 0 auto;
        }

        /* ===== PANEL IZQUIERDO (INFORMACIÓN) ===== */
        .info-panel {
            flex: 1;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            padding: 50px 40px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
            color: var(--text-white);
        }

        .info-panel::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            width: 1px;
            background: linear-gradient(to bottom,
                    transparent,
                    rgba(255, 255, 255, 0.2),
                    transparent);
        }

        /* Header del panel izquierdo */
        .brand-header {
            margin-bottom: 40px;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 30px;
        }

        .logo-img {
            height: 45px;
            width: auto;
            filter: brightness(0) invert(1);
        }

        .brand-name {
            font-size: 1.8rem;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .brand-tagline {
            font-size: 0.9rem;
            opacity: 0.9;
            margin-top: 4px;
        }

        /* Contenido principal */
        .welcome-section {
            margin-bottom: 40px;
        }

        .welcome-title {
            font-size: 2rem;
            font-weight: 700;
            line-height: 1.3;
            margin-bottom: 20px;
            position: relative;
            padding-bottom: 15px;
            color: white;
        }

        .welcome-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 3px;
            background: var(--accent-color);
            border-radius: 2px;
        }

        .welcome-description {
            font-size: 1.05rem;
            line-height: 1.6;
            opacity: 0.95;
            margin-bottom: 30px;
        }

        /* Lista de características */
        .features-list {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 25px;
            padding-right: 20px;
        }

        .feature-item {
            position: relative;
            padding: 10px 0;
        }

        .feature-item:not(:last-child)::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(to right,
                    transparent,
                    rgba(255, 255, 255, 0.3),
                    transparent);
        }

        .feature-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 10px;
        }

        .feature-icon {
            width: 40px;
            height: 40px;
            border-radius: var(--radius-md);
            background: rgba(255, 255, 255, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: white;
        }

        .feature-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin: 0;
            color: white;
        }

        .feature-description {
            font-size: 0.95rem;
            opacity: 0.9;
            line-height: 1.5;
            padding-left: 55px;
        }

        /* ===== PANEL DERECHO (FORMULARIO) ===== */
        .form-panel {
            flex: 0 0 45%;
            min-width: 450px;
            background: var(--bg-light);
            padding: 50px 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            overflow-y: auto;
        }

        .form-panel::-webkit-scrollbar {
            width: 6px;
        }

        .form-panel::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        .form-panel::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }

        /* Header del formulario */
        .form-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .form-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 10px;
            position: relative;
            display: inline-block;
        }

        .form-title::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 50%;
            transform: translateX(-50%);
            width: 40px;
            height: 3px;
            background: var(--accent-color);
            border-radius: 2px;
        }

        .form-subtitle {
            color: var(--text-light);
            font-size: 0.95rem;
            line-height: 1.5;
        }

        /* Formulario */
        .login-form {
            width: 100%;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--text-dark);
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .input-wrapper {
            position: relative;
        }

        .form-input {
            width: 100%;
            padding: 14px 50px 14px 16px;
            border: 2px solid var(--border-color);
            border-radius: var(--radius-md);
            font-size: 1rem;
            background: white;
            color: var(--text-dark);
            transition: var(--transition);
            font-family: inherit;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(6, 107, 94, 0.1);
        }

        .form-input.has-error {
            border-color: #ef4444;
        }

        .input-icon {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
            font-size: 1.1rem;
            pointer-events: none;
        }

        .password-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--text-light);
            cursor: pointer;
            padding: 4px;
            transition: var(--transition);
        }

        .password-toggle:hover {
            color: var(--primary-color);
        }

        .error-message {
            color: #ef4444;
            font-size: 0.85rem;
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        /* Opciones del formulario */
        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
        }

        .remember-checkbox {
            width: 18px;
            height: 18px;
            border-radius: 4px;
            border: 2px solid var(--border-color);
            background: white;
            appearance: none;
            cursor: pointer;
            position: relative;
            transition: var(--transition);
        }

        .remember-checkbox:checked {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }

        .remember-checkbox:checked::after {
            content: '✓';
            position: absolute;
            color: white;
            font-size: 12px;
            font-weight: bold;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .remember-label {
            color: var(--text-dark);
            font-size: 0.9rem;
            user-select: none;
        }

        .forgot-link {
            color: var(--primary-color);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: var(--transition);
        }

        .forgot-link:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        /* Botón de submit */
        .submit-button {
            width: 100%;
            padding: 15px;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: var(--radius-md);
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            letter-spacing: 0.5px;
        }

        .submit-button:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .submit-button:active {
            transform: translateY(0);
        }

        .button-icon {
            font-size: 1.2rem;
        }

        /* Footer del formulario */
        .form-footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid var(--border-color);
            color: var(--text-light);
            font-size: 0.9rem;
        }

        .help-link {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
        }

        .help-link:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        /* Alertas */
        .alert-container {
            margin-bottom: 25px;
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .custom-alert {
            padding: 15px 20px;
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            gap: 12px;
            background: #fef2f2;
            border-left: 4px solid #dc2626;
            color: #7f1d1d;
        }

        .alert-icon {
            font-size: 1.2rem;
            color: #dc2626;
        }

        .alert-content {
            flex: 1;
        }

        .alert-title {
            font-weight: 600;
            margin-bottom: 4px;
        }

        .alert-message {
            font-size: 0.9rem;
        }

        .alert-close {
            background: none;
            border: none;
            color: #7f1d1d;
            cursor: pointer;
            font-size: 1.2rem;
            opacity: 0.7;
            transition: var(--transition);
            padding: 0;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .alert-close:hover {
            opacity: 1;
        }

        /* ===== RESPONSIVE DESIGN ===== */
        @media (max-width: 1200px) {
            .login-wrapper {
                max-width: 95%;
                height: 90vh;
            }

            .form-panel {
                min-width: 400px;
                padding: 40px 50px;
            }

            .info-panel {
                padding: 40px 30px;
            }
        }

        @media (max-width: 992px) {
            .login-wrapper {
                flex-direction: column;
                height: auto;
                max-height: 95vh;
                margin: 20px auto;
            }

            .info-panel {
                padding: 30px 25px;
                min-height: 400px;
            }

            .form-panel {
                min-width: 100%;
                padding: 40px 35px;
                max-height: 500px;
            }

            .welcome-title {
                font-size: 1.8rem;
            }

            .form-title {
                font-size: 1.6rem;
            }

            .brand-logo {
                justify-content: center;
                text-align: center;
                flex-direction: column;
                gap: 10px;
            }

            .brand-name {
                font-size: 1.6rem;
            }
        }

        @media (max-width: 768px) {
            .login-container {
                padding: 10px;
                align-items: flex-start;
            }

            .login-wrapper {
                height: auto;
                max-height: none;
                margin: 10px;
            }

            .info-panel {
                min-height: 350px;
                padding: 25px 20px;
            }

            .form-panel {
                padding: 30px 25px;
                max-height: none;
            }

            .form-options {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }

            .welcome-title {
                font-size: 1.6rem;
            }

            .form-title {
                font-size: 1.4rem;
            }
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 5px;
            }

            .login-wrapper {
                border-radius: 12px;
                margin: 5px;
            }

            .info-panel,
            .form-panel {
                padding: 20px 15px;
            }

            .welcome-title {
                font-size: 1.4rem;
            }

            .form-title {
                font-size: 1.3rem;
            }

            .brand-name {
                font-size: 1.4rem;
            }

            .form-input {
                padding: 12px 45px 12px 12px;
            }

            .submit-button {
                padding: 14px;
                font-size: 0.95rem;
            }
        }

        /* ===== ANIMACIONES ===== */
        .feature-item {
            animation: fadeInUp 0.5s ease-out forwards;
            opacity: 0;
            transform: translateY(10px);
        }

        .feature-item:nth-child(1) {
            animation-delay: 0.1s;
        }

        .feature-item:nth-child(2) {
            animation-delay: 0.2s;
        }

        .feature-item:nth-child(3) {
            animation-delay: 0.3s;
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Ajuste para evitar scroll */
        @media (max-height: 700px) {
            .login-wrapper {
                height: auto;
                max-height: 95vh;
            }

            .info-panel {
                min-height: 300px;
            }
        }
    </style>
</head>

<body>
    <!-- Contenedor principal -->
    <div class="login-container">
        <div class="login-wrapper">
            <!-- Panel izquierdo - Información -->
            <div class="info-panel">
                <div>
                    <!-- Header de marca -->
                    <div class="brand-header">
                        <div class="brand-logo">
                            <img src="{{ asset('backend/assets/images/logo_principal.png') }}" alt="UNIP"
                                class="logo-img">
                        </div>
                    </div>

                    <!-- Sección de bienvenida -->
                    <div class="welcome-section">
                        <h1 class="welcome-title">Bienvenido al Sistema UNIP</h1>
                        <p class="welcome-description">
                            Gestión integral de inscripciones, seguimiento contable, seguimiento académico,
                            todos los procesos centralizados para verificación de datos y análisis de la información.
                        </p>
                    </div>
                </div>

                <!-- Lista de características -->
                <div class="features-list">
                    <div class="feature-item">
                        <div class="feature-header">
                            <div class="feature-icon">
                                <i class="ri-shield-check-line"></i>
                            </div>
                            <h3 class="feature-title">Acceso Seguro</h3>
                        </div>
                        <p class="feature-description">Autenticación encriptada y protección de datos</p>
                    </div>

                    <div class="feature-item">
                        <div class="feature-header">
                            <div class="feature-icon">
                                <i class="ri-bar-chart-line"></i>
                            </div>
                            <h3 class="feature-title">Reportes en Tiempo Real</h3>
                        </div>
                        <p class="feature-description">Información actualizada y dashboards interactivos</p>
                    </div>

                </div>
            </div>

            <!-- Panel derecho - Formulario -->
            <div class="form-panel">
                <!-- Header del formulario -->
                <div class="form-header">
                    <h2 class="form-title">Iniciar Sesión</h2>
                </div>

                <!-- Alertas de error -->
                @if ($errors->any())
                    <div class="alert-container">
                        <div class="custom-alert">
                            <i class="ri-error-warning-line alert-icon"></i>
                            <div class="alert-content">
                                <div class="alert-title">Error de autenticación</div>
                                <div class="alert-message">{{ $errors->first() }}</div>
                            </div>
                            <button type="button" class="alert-close">
                                <i class="ri-close-line"></i>
                            </button>
                        </div>
                    </div>
                @endif

                <!-- Formulario de login -->
                <form method="POST" action="{{ route('login') }}" class="login-form" id="loginForm">
                    @csrf

                    <!-- Campo de email -->
                    <div class="form-group">
                        <label for="email" class="form-label">Correo Electrónico</label>
                        <div class="input-wrapper">
                            <input type="email" class="form-input @error('email') has-error @enderror" id="email"
                                name="email" value="{{ old('email') }}" placeholder="ejemplo@gmail.com" required
                                autocomplete="email" autofocus>
                            <div class="input-icon">
                                <i class="ri-mail-line"></i>
                            </div>
                        </div>
                        @error('email')
                            <div class="error-message">
                                <i class="ri-error-warning-line"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Campo de contraseña -->
                    <div class="form-group">
                        <label for="password" class="form-label">Contraseña</label>
                        <div class="input-wrapper">
                            <input type="password" class="form-input @error('password') has-error @enderror"
                                id="password" name="password" placeholder="Ingrese su contraseña" required
                                autocomplete="current-password">
                            <button type="button" class="password-toggle" id="togglePassword">
                                <i class="ri-eye-line"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="error-message">
                                <i class="ri-error-warning-line"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Opciones del formulario -->
                    <div class="form-options">
                        <label class="remember-me">
                            <input type="checkbox" class="remember-checkbox" name="remember" id="remember"
                                {{ old('remember') ? 'checked' : '' }}>
                            <span class="remember-label">Mantener sesión activa</span>
                        </label>
                        <a href="#" class="forgot-link">¿Olvidó su contraseña?</a>
                    </div>

                    <!-- Botón de submit -->
                    <button type="submit" class="submit-button">
                        <i class="ri-login-circle-line button-icon"></i>
                        Ingresar al Sistema
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- JAVASCRIPT -->
    <script src="{{ asset('backend/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/pages/plugins/lord-icon-2.1.0.js') }}"></script>
    <script src="{{ asset('backend/assets/js/plugins.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ===== TOGGLE PASSWORD VISIBILITY =====
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');

            if (togglePassword && passwordInput) {
                togglePassword.addEventListener('click', function() {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);

                    // Cambiar icono
                    const icon = this.querySelector('i');
                    icon.className = type === 'password' ? 'ri-eye-line' : 'ri-eye-off-line';
                });
            }

            // ===== CERRAR ALERTAS =====
            function setupAlertClose() {
                const alertCloseButtons = document.querySelectorAll('.alert-close');
                alertCloseButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const alertContainer = this.closest('.alert-container');
                        if (alertContainer) {
                            alertContainer.style.animation = 'slideUp 0.3s ease-out';
                            setTimeout(() => {
                                if (alertContainer.parentNode) {
                                    alertContainer.remove();
                                }
                            }, 300);
                        }
                    });
                });
            }
            setupAlertClose();

            // Auto cerrar alertas después de 7 segundos
            const alerts = document.querySelectorAll('.alert-container');
            alerts.forEach(alert => {
                setTimeout(() => {
                    if (alert && alert.parentNode) {
                        alert.style.opacity = '0';
                        alert.style.transform = 'translateY(-10px)';
                        setTimeout(() => {
                            if (alert.parentNode) alert.remove();
                        }, 300);
                    }
                }, 7000);
            });

            // ===== VALIDACIÓN DEL FORMULARIO =====
            const loginForm = document.getElementById('loginForm');
            if (loginForm) {
                loginForm.addEventListener('submit', function(e) {
                    const email = document.getElementById('email');
                    const password = document.getElementById('password');
                    let isValid = true;

                    // Limpiar errores previos
                    email.classList.remove('has-error');
                    password.classList.remove('has-error');

                    // Remover mensajes de error previos
                    const existingErrors = document.querySelectorAll('.error-message:not([data-original])');
                    existingErrors.forEach(error => error.remove());

                    // Validar email
                    if (!email.value || !isValidEmail(email.value)) {
                        email.classList.add('has-error');
                        showFieldError(email, 'Por favor ingrese un correo electrónico válido');
                        isValid = false;
                    }

                    // Validar contraseña
                    if (!password.value) {
                        password.classList.add('has-error');
                        showFieldError(password, 'La contraseña es requerida');
                        isValid = false;
                    }

                    if (!isValid) {
                        e.preventDefault();

                        // Mostrar alerta de error general
                        if (!document.querySelector('.alert-container')) {
                            const alertDiv = document.createElement('div');
                            alertDiv.className = 'alert-container';
                            alertDiv.innerHTML = `
                                <div class="custom-alert">
                                    <i class="ri-error-warning-line alert-icon"></i>
                                    <div class="alert-content">
                                        <div class="alert-title">Error de validación</div>
                                        <div class="alert-message">Por favor complete todos los campos correctamente</div>
                                    </div>
                                    <button type="button" class="alert-close">
                                        <i class="ri-close-line"></i>
                                    </button>
                                </div>
                            `;
                            loginForm.prepend(alertDiv);

                            // Configurar el botón de cerrar
                            alertDiv.querySelector('.alert-close').addEventListener('click', function() {
                                alertDiv.style.animation = 'slideUp 0.3s ease-out';
                                setTimeout(() => {
                                    if (alertDiv.parentNode) alertDiv.remove();
                                }, 300);
                            });

                            // Auto cerrar
                            setTimeout(() => {
                                if (alertDiv.parentNode) {
                                    alertDiv.style.opacity = '0';
                                    alertDiv.style.transform = 'translateY(-10px)';
                                    setTimeout(() => {
                                        if (alertDiv.parentNode) alertDiv.remove();
                                    }, 300);
                                }
                            }, 5000);
                        }
                    }
                });
            }

            // ===== FUNCIONES AUXILIARES =====
            function isValidEmail(email) {
                const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return re.test(email);
            }

            function showFieldError(field, message) {
                // Crear elemento de error
                const errorDiv = document.createElement('div');
                errorDiv.className = 'error-message';
                errorDiv.innerHTML = `<i class="ri-error-warning-line"></i> ${message}`;

                // Insertar después del input wrapper
                field.parentNode.parentNode.appendChild(errorDiv);
            }

            // ===== EFECTO DE FOCUS EN INPUTS =====
            const inputs = document.querySelectorAll('.form-input');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentNode.style.transform = 'translateY(-2px)';
                });

                input.addEventListener('blur', function() {
                    this.parentNode.style.transform = 'translateY(0)';
                });
            });

            // ===== AJUSTAR ALTURA EN PANTALLAS PEQUEÑAS =====
            function adjustLayout() {
                const windowHeight = window.innerHeight;
                const loginWrapper = document.querySelector('.login-wrapper');

                if (windowHeight < 700) {
                    if (loginWrapper) {
                        loginWrapper.style.height = '95vh';
                        loginWrapper.style.maxHeight = '95vh';
                    }
                }
            }

            // Ejecutar al cargar y al redimensionar
            adjustLayout();
            window.addEventListener('resize', adjustLayout);
        });
    </script>
</body>

</html>
