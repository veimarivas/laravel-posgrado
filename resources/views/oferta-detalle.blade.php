<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UNIP - {{ $oferta->posgrado->nombre ?? 'Detalle del Programa' }}</title>
    <link rel="icon" type="image/png" href="{{ asset('frontend/assets/img/logo.jpeg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/style.css') }}">
    <style>
        /* Estilos específicos para la página de detalle */
        .oferta-hero {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);
            color: white;
            padding: 100px 0 60px;
            position: relative;
            overflow: hidden;
        }

        .oferta-hero .container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .oferta-hero-content {
            flex: 1;
            min-width: 300px;
            padding-right: 40px;
        }

        .oferta-hero-image {
            flex: 1;
            min-width: 300px;
            text-align: center;
        }

        .oferta-hero-image img {
            max-width: 100%;
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
        }

        .oferta-hero h1 {
            font-size: 3rem;
            margin-bottom: 20px;
            line-height: 1.2;
        }

        .oferta-hero p {
            font-size: 1.2rem;
            margin-bottom: 30px;
            opacity: 0.9;
        }

        .hero-actions {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .btn-inscribirse {
            background: var(--accent);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-inscribirse:hover {
            background: var(--accent-dark);
            transform: translateY(-5px);
        }

        .btn-contactar {
            background: transparent;
            color: white;
            border: 2px solid white;
            padding: 15px 30px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-contactar:hover {
            background: white;
            color: var(--primary-dark);
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            margin-bottom: 60px;
        }

        .info-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(0, 0, 0, 0.05);
            text-align: center;
            transition: transform 0.3s ease;
        }

        .info-card:hover {
            transform: translateY(-10px);
        }

        .info-card i {
            font-size: 2.5rem;
            color: var(--accent);
            margin-bottom: 20px;
        }

        .info-card h3 {
            font-size: 1.5rem;
            margin-bottom: 10px;
        }

        .modulos-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .modulo-item {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .modulo-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .modulo-title h3 {
            font-size: 1.5rem;
            color: var(--primary-dark);
        }

        .modulo-docente {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 20px;
        }

        .modulo-docente img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
        }

        .modulo-docente-info h4 {
            margin: 0;
            font-size: 1rem;
        }

        .modulo-docente-info p {
            margin: 0;
            font-size: 0.9rem;
            opacity: 0.7;
        }

        .horarios-list {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 20px;
        }

        .horario-item {
            background: var(--light-bg);
            padding: 10px 20px;
            border-radius: 50px;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .planes-pago-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 30px;
        }

        .plan-credito-card {
            background: linear-gradient(135deg, var(--white) 0%, #f8f9fa 100%);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.08);
            border: 2px solid var(--accent);
            position: relative;
            overflow: hidden;
            transition: var(--transition);
        }

        .plan-credito-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 50px rgba(94, 201, 177, 0.15);
        }

        .plan-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 25px;
            position: relative;
        }

        .plan-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--accent), var(--accent-dark));
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
        }

        .plan-header h3 {
            font-size: 1.8rem;
            color: var(--primary-dark);
            margin: 0;
            flex-grow: 1;
        }

        .plan-badge {
            background: linear-gradient(135deg, #ff6b6b, #ff8e8e);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        .plan-cuota {
            background: linear-gradient(135deg, var(--primary-dark), var(--secondary));
            border-radius: 15px;
            padding: 25px;
            text-align: center;
            color: white;
            margin-bottom: 25px;
        }

        .cuota-label {
            font-size: 0.9rem;
            opacity: 0.9;
            margin-bottom: 5px;
        }

        .cuota-monto {
            font-size: 2.8rem;
            font-weight: 800;
            line-height: 1;
            margin: 10px 0;
        }

        .cuota-periodo {
            font-size: 1rem;
            opacity: 0.9;
        }

        .plan-detalle {
            background: rgba(94, 201, 177, 0.05);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 25px;
        }

        .detalle-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 12px 0;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .detalle-item:last-child {
            border-bottom: none;
        }

        .detalle-item i {
            color: var(--accent);
            font-size: 1.2rem;
            min-width: 30px;
        }

        .detalle-label {
            font-size: 0.85rem;
            color: var(--gray);
            margin-bottom: 2px;
        }

        .detalle-valor {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--primary-dark);
        }

        .text-success {
            color: #28a745 !important;
        }

        .plan-beneficios {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 25px;
            border: 1px solid rgba(94, 201, 177, 0.2);
        }

        .plan-beneficios h4 {
            color: var(--primary-dark);
            margin-bottom: 15px;
            font-size: 1.2rem;
        }

        .plan-beneficios ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .plan-beneficios li {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
            font-size: 0.95rem;
            color: var(--gray);
        }

        .plan-beneficios li i {
            color: var(--accent);
            font-size: 0.9rem;
        }

        .plan-nota {
            margin-top: 20px;
            padding: 15px;
            background: rgba(255, 193, 7, 0.1);
            border-radius: 10px;
            font-size: 0.85rem;
            color: #856404;
            border-left: 4px solid #ffc107;
        }

        .plan-info-card {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-radius: 20px;
            padding: 30px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            border: 2px dashed #dee2e6;
        }

        .info-icon {
            width: 80px;
            height: 80px;
            background: var(--primary-dark);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
            margin-bottom: 20px;
        }

        .plan-info-card h4 {
            color: var(--primary-dark);
            margin-bottom: 15px;
            font-size: 1.3rem;
        }

        .plan-info-card p {
            color: var(--gray);
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .plan-info-card ul {
            list-style: none;
            padding: 0;
            margin: 0 0 25px 0;
            text-align: left;
            width: 100%;
        }

        .plan-info-card li {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
            color: var(--gray);
        }

        .plan-info-card li i {
            color: var(--accent);
        }

        .btn-inscribirse.full-width {
            width: 100%;
            justify-content: center;
        }

        /* Estilos adicionales para conceptos */
        .conceptos-detalle {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid rgba(94, 201, 177, 0.2);
        }

        .conceptos-detalle h4 {
            color: var(--primary-dark);
            margin-bottom: 15px;
            font-size: 1.1rem;
            border-bottom: 2px solid var(--accent);
            padding-bottom: 8px;
        }

        .conceptos-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .concepto-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 15px;
            background: rgba(94, 201, 177, 0.05);
            border-radius: 10px;
            border-left: 4px solid var(--accent);
        }

        .concepto-nombre {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
            color: var(--primary-dark);
        }

        .concepto-nombre i {
            color: var(--accent);
            font-size: 0.9rem;
        }

        .concepto-precio {
            font-weight: 600;
            color: var(--accent-dark);
            font-size: 0.95rem;
        }

        /* Estilos para el formulario */
        #plan_seleccionado {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: none;
        }

        #plan_seleccionado.visible {
            display: block;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .responsables-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }

        .responsable-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .responsable-card img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
        }

        .responsable-info h3 {
            margin: 0 0 10px;
            font-size: 1.5rem;
        }

        .responsable-info p {
            margin: 0;
            opacity: 0.7;
        }

        .responsable-contact {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        .responsable-contact a {
            color: var(--accent);
            font-size: 1.2rem;
        }

        .sticky-inscripcion {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .sticky-btn {
            background: var(--accent);
            color: white;
            border: none;
            padding: 15px 25px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            box-shadow: 0 10px 25px rgba(94, 201, 177, 0.3);
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
        }

        .sticky-btn:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(94, 201, 177, 0.4);
        }

        .whatsapp-btn {
            background: #25D366;
        }

        @media (max-width: 768px) {
            .oferta-hero .container {
                flex-direction: column;
            }

            .oferta-hero-content {
                padding-right: 0;
                margin-bottom: 40px;
            }

            .oferta-hero h1 {
                font-size: 2.5rem;
            }

            .hero-actions {
                justify-content: center;
            }

            .sticky-inscripcion {
                bottom: 20px;
                right: 20px;
            }

            .sticky-btn {
                padding: 12px 20px;
                font-size: 0.9rem;
            }

            .planes-pago-grid {
                grid-template-columns: 1fr;
            }

            .plan-credito-card {
                padding: 20px;
            }

            .cuota-monto {
                font-size: 2.2rem;
            }
        }

        /* Estilos para la página de detalle */
        .programa-detalle-hero {
            background: linear-gradient(135deg, #1a237e 0%, #283593 100%);
            padding: 100px 0 60px;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .programa-detalle-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none"><path d="M0,0 L100,0 L100,100 Z" fill="rgba(255,255,255,0.03)"/></svg>');
            background-size: cover;
        }

        .timeline {
            position: relative;
            max-width: 1200px;
            margin: 0 auto;
        }

        .timeline::after {
            content: '';
            position: absolute;
            width: 6px;
            background-color: var(--accent);
            top: 0;
            bottom: 0;
            left: 50%;
            margin-left: -3px;
        }

        .timeline-item {
            padding: 10px 40px;
            position: relative;
            width: 50%;
            box-sizing: border-box;
        }

        .timeline-item::after {
            content: '';
            position: absolute;
            width: 25px;
            height: 25px;
            background-color: white;
            border: 4px solid var(--accent);
            top: 15px;
            border-radius: 50%;
            z-index: 1;
        }

        .timeline-left {
            left: 0;
        }

        .timeline-right {
            left: 50%;
        }

        .timeline-left::after {
            right: -12px;
        }

        .timeline-right::after {
            left: -12px;
        }

        .timeline-content {
            padding: 20px 30px;
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        /* Responsive para timeline */
        @media screen and (max-width: 768px) {
            .timeline::after {
                left: 31px;
            }

            .timeline-item {
                width: 100%;
                padding-left: 70px;
                padding-right: 25px;
            }

            .timeline-item::after {
                left: 20px;
            }

            .timeline-right {
                left: 0%;
            }
        }
    </style>
    <!-- Agrega estos scripts GSAP (igual que en welcome.blade.php) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollToPlugin.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/Flip.min.js"></script>
</head>

<body>
    <!-- Loading Screen -->
    <div class="loading-screen">
        <div class="loader-container">
            <div class="loader"></div>
            <div class="loader-text">CARGANDO PROGRAMA ACADÉMICO</div>
        </div>
    </div>

    <!-- Header -->
    <header id="header">
        <div class="container">
            <nav class="navbar">
                <a href="/" class="logo">
                    <img src="{{ asset('frontend/assets/img/logo_principal.png') }}" alt="Logo UNIP" class="logo-img">
                </a>
                <ul class="nav-links" id="navLinks">
                    <li><a href="/#inicio">Inicio</a></li>
                    <li><a href="/#programs">Programas</a></li>
                    <li><a href="/#team">Equipo</a></li>
                    <li><a href="/#sedes">Sedes</a></li>
                    <li><a href="/#contacto">Contacto</a></li>
                </ul>
                @if (Route::has('login'))
                    <nav class="flex items-center justify-end gap-4">
                        @auth
                            <a href="{{ url('/admin/dashboard') }}" class="cta-button">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="cta-button">
                                Ingresar
                            </a>
                        @endauth
                    </nav>
                @endif
                <div class="mobile-menu-toggle" id="mobileMenuToggle">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </nav>
        </div>
    </header>

    @php
        // Filtrar planes de pago para mostrar solo "Al Credito"
        $planesCredito = $oferta->plan_concepto->filter(function ($planConcepto) {
            $planNombre = $planConcepto->plan_pago->nombre ?? '';
            // Buscar planes de crédito (con o sin tilde)
            return stripos($planNombre, 'Al Credito') !== false || stripos($planNombre, 'Al Crédito') !== false;
        });

        // Calcular totales para el plan de crédito
        $totalMensualCredito = 0;
        $totalCuotasCredito = 1;
        $totalProgramaCredito = 0;

        if ($planesCredito->isNotEmpty()) {
            // Sumar todos los conceptos del plan de crédito
            $totalMensualCredito = $planesCredito->sum('pago_bs');
            // Tomar el número de cuotas del primer concepto
            $primerPlan = $planesCredito->first();
            $totalCuotasCredito = $primerPlan->n_cuotas ?? 1;
            $totalProgramaCredito = $totalMensualCredito * $totalCuotasCredito;
        }

        // También obtener plan al contado para comparación si existe
        $planContado = $oferta->plan_concepto->first(function ($planConcepto) {
            $planNombre = $planConcepto->plan_pago->nombre ?? '';
            return stripos($planNombre, 'contado') !== false;
        });

        $totalContado = $planContado ? $planContado->pago_bs : 0;
        $ahorroMensual = $totalContado > 0 ? $totalContado - $totalMensualCredito : 0;
    @endphp

    <!-- Hero Section de la Oferta -->
    <section class="oferta-hero">
        <div class="container">
            <div class="oferta-hero-content">
                <h1>{{ $oferta->posgrado->nombre ?? 'Programa sin nombre' }}</h1>
                <p>{{ $oferta->posgrado->objetivo ?? 'Descripción no disponible' }}</p>

                <div class="hero-actions">
                    <button class="btn-inscribirse" onclick="scrollToInscripcion()">
                        <i class="fas fa-pencil-alt"></i> Inscribirse ahora
                    </button>
                    <button class="btn-contactar" onclick="scrollToContacto()">
                        <i class="fas fa-envelope"></i> Solicitar información
                    </button>
                </div>
            </div>
            <div class="oferta-hero-image">
                @if ($oferta->portada)
                    <img src="{{ asset($oferta->portada) }}"
                        alt="{{ $oferta->posgrado->nombre ?? 'Programa sin nombre' }}">
                @else
                    <img src="{{ asset('frontend/assets/img/afiches/default.jpg') }}" alt="Imagen por defecto">
                @endif
            </div>
        </div>
    </section>

    <!-- Información General -->
    <section class="section">
        <div class="container">
            <div class="section-title">
                <h2>Información <span>General</span></h2>
                <p>Conoce los detalles clave de este programa académico</p>
            </div>

            <div class="info-grid">
                <div class="info-card">
                    <i class="fas fa-university"></i>
                    <h3>Sede</h3>
                    <p>{{ $oferta->sucursal->nombre ?? 'No especificada' }}</p>
                    <small>{{ $oferta->sucursal->direccion ?? '' }}</small>
                </div>

                <div class="info-card">
                    <i class="fas fa-calendar-alt"></i>
                    <h3>Duración</h3>
                    <p>
                        @if ($oferta->posgrado->duracion_numero && $oferta->posgrado->duracion_unidad)
                            {{ $oferta->posgrado->duracion_numero }} {{ $oferta->posgrado->duracion_unidad }}
                        @else
                            {{ $oferta->n_modulos ?? '?' }} módulos
                        @endif
                    </p>
                </div>

                <div class="info-card">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <h3>Modalidad</h3>
                    <p>{{ $oferta->modalidad->nombre ?? 'No especificada' }}</p>
                </div>

                <div class="info-card">
                    <i class="fas fa-graduation-cap"></i>
                    <h3>Tipo</h3>
                    <p>{{ $oferta->posgrado->tipo->nombre ?? 'No especificado' }}</p>
                </div>

                <div class="info-card">
                    <i class="fas fa-book"></i>
                    <h3>Área</h3>
                    <p>{{ $oferta->posgrado->area->nombre ?? 'No especificada' }}</p>
                </div>

                <div class="info-card">
                    <i class="fas fa-clock"></i>
                    <h3>Inicio</h3>
                    <p>{{ $oferta->fecha_inicio_programa->format('d/m/Y') ?? 'No especificada' }}</p>
                </div>

                <div class="info-card">
                    <i class="fas fa-users"></i>
                    <h3>Cupos</h3>
                    <p>Limitados</p>
                    <small>{{ $oferta->inscripciones->count() ?? 0 }} inscritos</small>
                </div>

                <div class="info-card">
                    <i class="fas fa-money-bill-wave"></i>
                    <h3>Inversión</h3>
                    @if ($totalMensualCredito > 0)
                        <p>Desde Bs. {{ number_format($totalMensualCredito, 0, ',', '.') }}/mes</p>
                        <small>Plan de crédito {{ $totalCuotasCredito }} meses</small>
                    @else
                        <p>Consultar</p>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Descripción del Programa -->
    <section class="section" style="background: var(--light-bg);">
        <div class="container">
            <div class="section-title">
                <h2>Descripción del <span>Programa</span></h2>
            </div>

            <div class="content" style="max-width: 800px; margin: 0 auto; font-size: 1.1rem; line-height: 1.8;">
                {!! nl2br(e($oferta->posgrado->descripcion ?? 'Descripción no disponible.')) !!}

                @if ($oferta->posgrado->perfil_egreso)
                    <h3 style="margin-top: 40px; color: var(--primary-dark);">Perfil del Egresado</h3>
                    <p>{{ $oferta->posgrado->perfil_egreso }}</p>
                @endif

                @if ($oferta->posgrado->requisitos)
                    <h3 style="margin-top: 40px; color: var(--primary-dark);">Requisitos de Admisión</h3>
                    <p>{{ $oferta->posgrado->requisitos }}</p>
                @endif
            </div>
        </div>
    </section>

    <!-- Módulos del Programa -->
    @if ($oferta->modulos->count() > 0)
        <section class="section">
            <div class="container">
                <div class="section-title">
                    <h2>Módulos <span>Académicos</span></h2>
                    <p>Estructura curricular del programa</p>
                </div>

                <div class="modulos-list">
                    @foreach ($oferta->modulos as $modulo)
                        <div class="modulo-item">
                            <div class="modulo-header">
                                <div class="modulo-title">
                                    <h3>Módulo {{ $modulo->n_modulo }}: {{ $modulo->nombre }}</h3>

                                </div>
                                <div class="modulo-color"
                                    style="width: 30px; height: 30px; border-radius: 5px; background: {{ $modulo->color ?? '#ccc' }};">
                                </div>
                            </div>

                            @if ($modulo->docente)
                                <div class="modulo-docente">
                                    @if ($modulo->docente->persona->fotografia)
                                        <img src="{{ asset($modulo->docente->persona->fotografia) }}"
                                            alt="{{ $modulo->docente->persona->nombres }}">
                                    @else
                                        <img src="{{ asset('frontend/assets/img/personal/hombre.png') }}"
                                            alt="Docente">
                                    @endif
                                    <div class="modulo-docente-info">
                                        <h4>{{ $modulo->docente->persona->nombres }}
                                            {{ $modulo->docente->persona->apellido_paterno }}</h4>
                                        <p>Docente del módulo</p>
                                    </div>
                                </div>
                            @endif

                            @if ($modulo->horarios->count() > 0)
                                <div class="horarios-list">
                                    @foreach ($modulo->horarios as $horario)
                                        <div class="horario-item">
                                            <i class="far fa-clock"></i>
                                            {{ \Carbon\Carbon::parse($horario->fecha)->format('d/m/Y') }}:
                                            {{ \Carbon\Carbon::parse($horario->hora_inicio)->format('H:i') }} -
                                            {{ \Carbon\Carbon::parse($horario->hora_fin)->format('H:i') }}
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Planes de Pago - MODIFICADO Y CORREGIDO -->
    @if ($planesCredito->isNotEmpty())
        <section class="section" style="background: var(--light-bg);">
            <div class="container">
                <div class="section-title">
                    <h2>Opciones de <span>Financiamiento</span></h2>
                    <p>Plan de pago flexible diseñado para facilitar tu inversión educativa</p>
                </div>

                <div class="planes-pago-grid">
                    <div class="plan-credito-card">
                        <div class="plan-header">
                            <div class="plan-icon">
                                <i class="fas fa-credit-card"></i>
                            </div>
                            <h3>Plan de Crédito</h3>
                            @if ($totalContado > 0)
                                <span class="plan-badge">Plan Al Credito</span>
                            @endif
                        </div>

                        <div class="plan-content">
                            <div class="plan-cuota">
                                <div class="cuota-label">Costo Total</div>
                                <div class="cuota-monto">Bs. {{ number_format($totalMensualCredito, 0, ',', '.') }}
                                </div>
                            </div>

                            <div class="plan-detalle">
                                <div class="detalle-item">
                                    <i class="fas fa-calculator"></i>
                                    <div>
                                        <div class="detalle-label">Total Costo del Programa</div>
                                        <div class="detalle-valor">Bs.
                                            {{ number_format($totalProgramaCredito, 0, ',', '.') }}</div>
                                    </div>
                                </div>

                                @if ($planesCredito->count() > 1)
                                    <div class="detalle-item">
                                        <i class="fas fa-layer-group"></i>
                                        <div>
                                            <div class="detalle-label">Conceptos Incluidos</div>
                                            <div class="detalle-valor">{{ $planesCredito->count() }} conceptos</div>
                                        </div>
                                    </div>
                                @endif

                                <div class="detalle-item">
                                    <i class="fas fa-percentage"></i>
                                    <div>
                                        <div class="detalle-label">Tasa de Interés</div>
                                        <div class="detalle-valor">0% (Sin intereses)</div>
                                    </div>
                                </div>

                                @if ($ahorroMensual > 0)
                                    <div class="detalle-item">
                                        <i class="fas fa-piggy-bank"></i>
                                        <div>
                                            <div class="detalle-label">Ahorro vs Contado</div>
                                            <div class="detalle-valor text-success">Bs.
                                                {{ number_format($ahorroMensual, 0, ',', '.') }}/mes</div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Mostrar detalles de cada concepto si hay más de uno -->
                            @if ($planesCredito->count() > 1)
                                <div class="conceptos-detalle">
                                    <h4>Desglose de Conceptos:</h4>
                                    <div class="conceptos-list">
                                        @foreach ($planesCredito as $concepto)
                                            <div class="concepto-item">
                                                <div class="concepto-nombre">
                                                    <i class="fas fa-check-circle"></i>
                                                    {{ $concepto->concepto->nombre ?? 'Concepto' }}
                                                </div>
                                                <div class="concepto-precio">
                                                    Bs. {{ number_format($concepto->pago_bs, 0, ',', '.') }} en
                                                    {{ $concepto->n_cuotas }} cuotas
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <div class="plan-beneficios">
                                <h4>Beneficios del Plan:</h4>
                                <ul>
                                    <li><i class="fas fa-check-circle"></i> Sin trámites bancarios</li>
                                    <li><i class="fas fa-check-circle"></i> Sin pagos adicionales</li>
                                    <li><i class="fas fa-check-circle"></i> Pago mensual fijo</li>
                                    <li><i class="fas fa-check-circle"></i> Flexibilidad en fechas de pago</li>
                                    <li><i class="fas fa-check-circle"></i> Sin intereses adicionales</li>
                                </ul>
                            </div>

                            <button class="btn-inscribirse full-width" onclick="seleccionarPlan('credito')">
                                <i class="fas fa-check"></i> Seleccionar Plan de Crédito
                            </button>

                            <div class="plan-nota">
                                <small><i class="fas fa-info-circle"></i> El plan de crédito está sujeto a aprobación.
                                    Se requiere documento de identidad vigente.</small>
                            </div>
                        </div>
                    </div>

                    <!-- Tarjeta informativa adicional -->
                    <div class="plan-info-card">
                        <div class="info-icon">
                            <i class="fas fa-handshake"></i>
                        </div>
                        <h4>¿Necesitas otra opción de pago?</h4>
                        <p>Contamos con diferentes planes de financiamiento adaptados a tus necesidades:</p>
                        <ul>
                            @if ($planContado)
                                <li><i class="fas fa-caret-right"></i> Plan al contado: Bs.
                                    {{ number_format($totalContado, 0, ',', '.') }}</li>
                            @endif
                            <li><i class="fas fa-caret-right"></i> Planes personalizados</li>
                            <li><i class="fas fa-caret-right"></i> Descuentos por pronto pago</li>
                            <li><i class="fas fa-caret-right"></i> Convenios con empresas</li>
                        </ul>
                        <button class="btn-contactar" onclick="scrollToContacto()">
                            <i class="fas fa-phone-alt"></i> Solicitar Información
                        </button>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- Responsables del Programa -->
    @if ($responsableAcademico || $responsableMarketing)
        <section class="section">
            <div class="container">
                <div class="section-title">
                    <h2>Responsables del <span>Programa</span></h2>
                    <p>Contacta con nuestros especialistas</p>
                </div>

                <div class="responsables-grid">
                    @if ($responsableAcademico)
                        <div class="responsable-card">
                            @if ($responsableAcademico->trabajador->persona->fotografia)
                                <img src="{{ asset($responsableAcademico->trabajador->persona->fotografia) }}"
                                    alt="{{ $responsableAcademico->trabajador->persona->nombres }}">
                            @else
                                <img src="{{ asset('frontend/assets/img/personal/hombre.png') }}" alt="Responsable">
                            @endif
                            <div class="responsable-info">
                                <h3>{{ $responsableAcademico->trabajador->persona->nombres }}
                                    {{ $responsableAcademico->trabajador->persona->apellido_paterno }}</h3>
                                <p>{{ $responsableAcademico->cargo->nombre }}</p>
                                <p>{{ $responsableAcademico->trabajador->persona->correo ?? 'Correo no disponible' }}
                                </p>
                                <div class="responsable-contact">
                                    @if ($responsableAcademico->trabajador->persona->correo)
                                        <a href="mailto:{{ $responsableAcademico->trabajador->persona->correo }}"
                                            title="Enviar correo"><i class="fas fa-envelope"></i></a>
                                    @endif
                                    @if ($responsableAcademico->trabajador->persona->celular)
                                        <a href="https://wa.me/591{{ $responsableAcademico->trabajador->persona->celular }}"
                                            target="_blank" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($responsableMarketing)
                        <div class="responsable-card">
                            @if ($responsableMarketing->trabajador->persona->fotografia)
                                <img src="{{ asset($responsableMarketing->trabajador->persona->fotografia) }}"
                                    alt="{{ $responsableMarketing->trabajador->persona->nombres }}">
                            @else
                                <img src="{{ asset('frontend/assets/img/personal/hombre.png') }}" alt="Responsable">
                            @endif
                            <div class="responsable-info">
                                <h3>{{ $responsableMarketing->trabajador->persona->nombres }}
                                    {{ $responsableMarketing->trabajador->persona->apellido_paterno }}</h3>
                                <p>{{ $responsableMarketing->cargo->nombre }}</p>
                                <p>{{ $responsableMarketing->trabajador->persona->correo ?? 'Correo no disponible' }}
                                </p>
                                <div class="responsable-contact">
                                    @if ($responsableMarketing->trabajador->persona->correo)
                                        <a href="mailto:{{ $responsableMarketing->trabajador->persona->correo }}"
                                            title="Enviar correo"><i class="fas fa-envelope"></i></a>
                                    @endif
                                    @if ($responsableMarketing->trabajador->persona->celular)
                                        <a href="https://wa.me/591{{ $responsableMarketing->trabajador->persona->celular }}"
                                            target="_blank" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    @endif

    <!-- Formulario de Inscripción -->
    <section class="section" id="formulario-inscripcion" style="background: var(--primary-dark); color: white;">
        <div class="container">
            <div class="section-title form">
                <h2>Formulario de <span>Inscripción</span></h2>
                <p>Completa tus datos para iniciar el proceso de inscripción</p>
            </div>

            <form id="inscripcionForm" style="max-width: 600px; margin: 0 auto;">
                @csrf
                <input type="hidden" name="oferta_id" value="{{ $oferta->id }}">
                <input type="hidden" name="plan_seleccionado" id="plan_seleccionado" value="">

                <div id="plan_seleccionado_info"></div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="nombre_completo" style="display: block; margin-bottom: 5px;">Nombre completo</label>
                    <input type="text" id="nombre_completo" name="nombre_completo" required
                        style="width: 100%; padding: 15px; border-radius: 10px; border: none; background: rgba(255,255,255,0.1); color: white;">
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="email" style="display: block; margin-bottom: 5px;">Correo electrónico</label>
                    <input type="email" id="email" name="email" required
                        style="width: 100%; padding: 15px; border-radius: 10px; border: none; background: rgba(255,255,255,0.1); color: white;">
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="telefono" style="display: block; margin-bottom: 5px;">Teléfono</label>
                    <input type="tel" id="telefono" name="telefono" required
                        style="width: 100%; padding: 15px; border-radius: 10px; border: none; background: rgba(255,255,255,0.1); color: white;">
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="carnet" style="display: block; margin-bottom: 5px;">Carnet de identidad</label>
                    <input type="text" id="carnet" name="carnet" required
                        style="width: 100%; padding: 15px; border-radius: 10px; border: none; background: rgba(255,255,255,0.1); color: white;">
                </div>

                <div class="form-group" style="margin-bottom: 30px;">
                    <input type="checkbox" id="terminos" name="terminos" required>
                    <label for="terminos">Acepto los términos y condiciones y autorizo el tratamiento de mis datos
                        personales.</label>
                </div>

                <button type="submit" class="btn-inscribirse" style="width: 100%; justify-content: center;">
                    <i class="fas fa-paper-plane"></i> Enviar inscripción
                </button>
            </form>
        </div>
    </section>

    <!-- Footer -->
    <footer id="contacto">
        <div class="container">
            <div class="footer-content">
                <div class="footer-column">
                    <h3>UNIP</h3>
                    <p>
                        En UNIP nos dedicamos a ofrecer una educación de postgrado de alta calidad y excelencia
                        académica, con un equipo docente especializado y una metodología innovadora y global.
                    </p>
                    <div class="social-links">
                        <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="footer-column">
                    <h3>Enlaces Rápidos</h3>
                    <ul class="footer-links">
                        <li><a href="/#inicio">Inicio</a></li>
                        <li><a href="/#programs">Programas</a></li>
                        <li><a href="/#team">Equipo Académico</a></li>
                        <li><a href="/#sedes">Nuestras Sedes</a></li>
                        <li><a href="/#programas-detalle">Catálogo Completo</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Contacto Directo</h3>
                    <ul class="footer-contact">
                        <li>
                            <i class="fas fa-map-marker-alt"></i>
                            Dirección: {{ $oferta->sucursal->direccion ?? 'No disponible' }}
                        </li>
                        <li>
                            <i class="fas fa-phone"></i>
                            Teléfono: {{ $responsableMarketing->trabajador->persona->celular ?? 'No disponible' }}
                        </li>
                        <li>
                            <i class="fas fa-envelope"></i>
                            Email: {{ $responsableMarketing->trabajador->persona->correo ?? 'No disponible' }}
                        </li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 UNIP. Todos los derechos reservados. | <a href="#"
                        style="color: rgba(255,255,255,0.6); text-decoration: underline;">Términos y Condiciones</a> |
                    <a href="#" style="color: rgba(255,255,255,0.6); text-decoration: underline;">Política de
                        Privacidad</a>
                </p>
            </div>
        </div>
    </footer>

    <!-- Botón sticky para inscripción rápida -->
    <div class="sticky-inscripcion">
        <button class="sticky-btn whatsapp-btn" onclick="abrirWhatsApp()">
            <i class="fab fa-whatsapp"></i> WhatsApp
        </button>
        <button class="sticky-btn" onclick="scrollToInscripcion()">
            <i class="fas fa-pencil-alt"></i> Inscribirse
        </button>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollToPlugin.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/Flip.min.js"></script>

    <script>
        // Variables desde PHP
        const totalMensualCredito = {{ $totalMensualCredito }};
        const totalCuotasCredito = {{ $totalCuotasCredito }};
        const totalProgramaCredito = {{ $totalProgramaCredito }};
        const totalContado = {{ $totalContado }};
        const ahorroMensual = {{ $ahorroMensual }};

        // Animaciones GSAP
        document.addEventListener('DOMContentLoaded', function() {
            gsap.registerPlugin(ScrollTrigger, ScrollToPlugin, Flip);

            // Remover loading screen
            const loadingScreen = document.querySelector('.loading-screen');
            const loaderText = document.querySelector('.loader-text');

            if (loaderText) {
                setTimeout(() => {
                    gsap.to(loaderText, {
                        opacity: 1,
                        duration: 0.5
                    });
                }, 500);
            }

            setTimeout(() => {
                gsap.to(loadingScreen, {
                    opacity: 0,
                    duration: 0.8,
                    ease: "power2.in",
                    onComplete: () => {
                        loadingScreen.style.display = 'none';
                    }
                });
            }, 1000);

            // Header scroll animation with GSAP
            let lastScrollTop = 0;
            const navbar = document.querySelector('.navbar');
            const header = document.getElementById('header');

            if (header && navbar) {
                window.addEventListener('scroll', function() {
                    let scrollTop = window.pageYOffset || document.documentElement.scrollTop;

                    // Efecto de fondo al hacer scroll
                    if (scrollTop > 100) {
                        header.classList.add('scrolled');
                    } else {
                        header.classList.remove('scrolled');
                    }

                    // Efecto de esconder/mostrar navbar
                    if (scrollTop > lastScrollTop && scrollTop > 100) {
                        // Scrolling down
                        gsap.to(navbar, {
                            y: -100,
                            duration: 0.3,
                            ease: "power2.in"
                        });
                    } else {
                        // Scrolling up
                        gsap.to(navbar, {
                            y: 0,
                            duration: 0.3,
                            ease: "power2.out"
                        });
                    }

                    lastScrollTop = scrollTop;
                });
            }

            // Enhanced scroll progress indicator
            const progress = document.querySelector('.scroll-progress');
            if (progress) {
                ScrollTrigger.create({
                    start: 0,
                    end: 'bottom bottom',
                    onUpdate: self => {
                        progress.style.transform = `scaleX(${self.progress})`;
                        const gradientIntensity = Math.min(self.progress * 2, 1);
                        progress.style.background =
                            `linear-gradient(90deg, rgba(94, 201, 177, ${0.6 + gradientIntensity * 0.4}), rgba(106, 217, 197, ${0.5 + gradientIntensity * 0.5}))`;
                    }
                });
            }

            // Mobile Menu Toggle
            const mobileMenuToggle = document.getElementById('mobileMenuToggle');
            const navLinks = document.getElementById('navLinks');
            const mobileOverlay = document.getElementById('mobileMenuOverlay');

            if (mobileMenuToggle && navLinks) {
                mobileMenuToggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    e.preventDefault();
                    const isActive = this.classList.toggle('active');
                    navLinks.classList.toggle('active');
                    if (mobileOverlay) mobileOverlay.classList.toggle('active');

                    if (isActive) {
                        gsap.to('.mobile-menu-toggle span', {
                            backgroundColor: 'var(--accent)',
                            duration: 0.3
                        });
                    } else {
                        gsap.to('.mobile-menu-toggle span', {
                            backgroundColor: 'var(--white)',
                            duration: 0.3
                        });
                    }

                    if (isActive) {
                        document.body.style.overflow = 'hidden';
                    } else {
                        document.body.style.overflow = '';
                    }
                });

                if (mobileOverlay) {
                    mobileOverlay.addEventListener('click', function() {
                        mobileMenuToggle.classList.remove('active');
                        navLinks.classList.remove('active');
                        this.classList.remove('active');
                        document.body.style.overflow = '';

                        gsap.to('.mobile-menu-toggle span', {
                            backgroundColor: 'var(--white)',
                            duration: 0.3
                        });
                    });
                }

                const menuLinks = navLinks.querySelectorAll('a');
                menuLinks.forEach(link => {
                    link.addEventListener('click', function(e) {
                        mobileMenuToggle.classList.remove('active');
                        navLinks.classList.remove('active');
                        if (mobileOverlay) mobileOverlay.classList.remove('active');
                        document.body.style.overflow = '';

                        gsap.to('.mobile-menu-toggle span', {
                            backgroundColor: 'var(--white)',
                            duration: 0.3
                        });
                    });
                });
            }

            // Animaciones de entrada para elementos
            gsap.utils.toArray('.info-card, .modulo-item, .plan-credito-card, .responsable-card').forEach((item,
                index) => {
                gsap.fromTo(item, {
                    opacity: 0,
                    y: 50
                }, {
                    opacity: 1,
                    y: 0,
                    duration: 0.8,
                    delay: index * 0.1,
                    scrollTrigger: {
                        trigger: item,
                        start: "top 85%",
                        toggleActions: "play none none none"
                    }
                });
            });
        });

        // Funciones para los botones
        function scrollToInscripcion() {
            document.getElementById('formulario-inscripcion').scrollIntoView({
                behavior: 'smooth'
            });
        }

        function scrollToContacto() {
            document.getElementById('contacto').scrollIntoView({
                behavior: 'smooth'
            });
        }

        function seleccionarPlan(tipoPlan) {
            const planSeleccionado = document.getElementById('plan_seleccionado');
            const planSeleccionadoInfo = document.getElementById('plan_seleccionado_info');

            planSeleccionado.value = tipoPlan;

            // Mostrar información del plan seleccionado
            if (tipoPlan === 'credito') {
                planSeleccionadoInfo.innerHTML = `
                    <div style="background: rgba(94, 201, 177, 0.1); border-radius: 10px; padding: 15px; margin-bottom: 20px; border-left: 4px solid var(--accent);">
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <i class="fas fa-check-circle" style="color: var(--accent); font-size: 1.5rem;"></i>
                            <div>
                                <strong style="color: white;">Plan de Crédito Seleccionado</strong><br>
                                <small style="color: rgba(255,255,255,0.8);">
                                    Cuota mensual: Bs. ${totalMensualCredito.toLocaleString('es-BO')} por ${totalCuotasCredito} meses<br>
                                    Total del programa: Bs. ${totalProgramaCredito.toLocaleString('es-BO')}
                                </small>
                            </div>
                        </div>
                    </div>
                `;
            }

            scrollToInscripcion();
        }

        function abrirWhatsApp() {
            const telefono = '{{ $responsableMarketing->trabajador->persona->celular ?? '123456789' }}';
            const mensaje =
                `Hola, estoy interesado en el programa: {{ $oferta->posgrado->nombre ?? 'Programa' }} (Código: {{ $oferta->codigo }})`;
            window.open(`https://wa.me/591${telefono}?text=${encodeURIComponent(mensaje)}`, '_blank');
        }

        // Manejo del formulario de inscripción
        document.getElementById('inscripcionForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const nombre = formData.get('nombre_completo');

            alert(
                `¡Gracias por tu interés, ${nombre}!\n\nTu solicitud de inscripción ha sido enviada. Nos pondremos en contacto contigo en las próximas 24 horas.`
            );

            // Aquí podrías agregar una llamada AJAX para enviar los datos al servidor
            // fetch('/api/inscripcion', {
            //     method: 'POST',
            //     body: formData
            // })
            // .then(response => response.json())
            // .then(data => {
            //     alert('¡Inscripción exitosa!');
            //     this.reset();
            // })
            // .catch(error => {
            //     alert('Error al enviar la inscripción');
            // });
        });
    </script>
</body>

</html>
