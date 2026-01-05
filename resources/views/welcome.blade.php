<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UNIP - Ofertas académicas</title>
    <link rel="icon" type="image/png" href="{{ asset('frontend/assets/img/logo.jpeg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollToPlugin.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/Flip.min.js"></script>
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/style.css') }}">

</head>

<body>
    <!-- Loading Screen -->
    <div class="loading-screen">
        <div class="loader-container">
            <div class="loader"></div>
            <div class="loader-text">CARGANDO EXPERIENCIA UNIP</div>
        </div>
    </div>
    <!-- Mobile Menu Overlay -->
    <div class="mobile-menu-overlay" id="mobileMenuOverlay"></div>
    <!-- Scroll Progress Indicator -->
    <div class="scroll-progress"></div>
    <!-- Content Wrapper -->
    <div id="smooth-content">
        <!-- Header -->
        <header id="header">
            <div class="container">
                <nav class="navbar">
                    <a href="#" class="logo">
                        <img src="{{ asset('frontend/assets/img/logo_principal.png') }}" alt="Logo EduOfertas"
                            class="logo-img">
                    </a>
                    <ul class="nav-links" id="navLinks">
                        <li><a href="#inicio">Inicio</a></li>
                        <li><a href="#programs">Programas</a></li>
                        <li><a href="#team">Equipo</a></li>
                        <li><a href="#sedes">Sedes</a></li>
                        <li><a href="#contacto">Contacto</a></li>
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
        <!-- Hero Section -->
        <section class="hero" id="inicio">
            <div class="container">
                <div class="hero-content">
                    <h1>Bienvenido al <span>Centro de Posgrados</span> que transforma tu futuro profesional</h1>
                    <p>Descubre programas educativos de excelencia diseñados para potenciar tu desarrollo profesional en
                        un entorno de aprendizaje innovador y personalizado.</p>
                    <div class="cta-container">
                        <a href="#programs" class="cta-button">Explorar Programas</a>
                        <a href="#contacto" class="cta-button secondary">Contáctanos</a>
                    </div>
                </div>
            </div>
            <div class="hero-visual">
                <img src="{{ asset('frontend/assets/img/logo_principal.png') }}" alt="Logo EduOfertas" class="hero-logo"
                    onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
            </div>
        </section>
        <!-- Programs Section -->
        <section id="programs" class="section">
            <div class="container">
                <div class="section-title fade-in">
                    <h2><span>Programas Exclusivos</span></h2>
                    <p>Descubre nuestra selección de programas académicos diseñados por expertos para impulsar tu
                        carrera profesional con un enfoque práctico y actualizado</p>
                </div>
                <div class="programs-grid">
                    @foreach ($tipos as $tipo)
                        <div class="program-card stagger-item">
                            <div class="program-icon">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <h3>{{ $tipo->nombre }}</h3>
                            <p>
                                {{ $tipo->descripcion }}
                            </p>
                            <a href="#" class="learn-more">Más información <i class="fas fa-arrow-right"></i></a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
        <!-- Team Section -->
        <section id="team" class="section team-section">
            <div class="container">
                <div class="section-title fade-in">
                    <h2>Nuestro <span>Personal</span></h2>
                    <p>
                        Conoce a los profesionales líderes en sus áreas que conforman nuestro equipo académico,
                        marketing
                    </p>
                </div>
                <div class="team-carousel-container">
                    <button class="carousel-btn prev" id="teamPrev">
                        <i class="fas fa-chevron-left"></i>
                    </button>

                    <div class="team-carousel-wrapper" id="teamCarouselWrapper">
                        <div class="team-grid" id="teamCarousel">
                            @if ($trabajadores->isEmpty())
                                <!-- Contenido por defecto si no hay trabajadores -->
                                <div class="team-member stagger-item">
                                    <div class="member-img">
                                        <img src="{{ asset('frontend/assets/img/personal/2.jpg') }}"
                                            alt="Carlos Méndez">
                                    </div>
                                    <div class="member-info">
                                        <h3>Carlos Méndez, MBA</h3>
                                        <span class="member-role">Coordinador de Becas</span>
                                        <div class="member-traits">
                                            <div>
                                                <span class="trait-label">Sedes:</span>
                                                <span class="trait-value">Sucursal Tarija</span>
                                            </div>
                                        </div>
                                        <div class="member-contact-buttons">
                                            <a href="mailto:ejemplo@correo.com" class="contact-btn email-btn"
                                                title="Enviar correo">
                                                <i class="fas fa-envelope"></i>
                                            </a>
                                            <a href="https://wa.me/59112345678" target="_blank"
                                                class="contact-btn whatsapp-btn" title="WhatsApp">
                                                <i class="fab fa-whatsapp"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @else
                                @foreach ($trabajadores as $area => $trabajadoresArea)
                                    @foreach ($trabajadoresArea as $trabajador)
                                        @php
                                            $cargoPrincipal = $trabajador->trabajadores_cargos->first();
                                        @endphp
                                        @if ($cargoPrincipal && $cargoPrincipal->cargo)
                                            <div class="team-member stagger-item" data-area="{{ $area }}">
                                                <div class="member-img">
                                                    @if ($trabajador->persona->fotografia)
                                                        <img src="{{ asset($trabajador->persona->fotografia) }}"
                                                            alt="{{ $trabajador->persona->nombres }} {{ $trabajador->persona->apellido_paterno }}">
                                                    @else
                                                        @if ($trabajador->persona->sexo == 'Hombre')
                                                            <img src="{{ asset('frontend/assets/img/personal/hombre.png') }}"
                                                                alt="Foto por defecto">
                                                        @else
                                                            <img src="{{ asset('frontend/assets/img/personal/mujer.png') }}"
                                                                alt="Foto por defecto">
                                                        @endif
                                                    @endif
                                                </div>
                                                <div class="member-info">
                                                    <h3>{{ $trabajador->persona->apellido_paterno }}
                                                        {{ $trabajador->persona->apellido_materno }},
                                                        {{ $trabajador->persona->nombres }}</h3>
                                                    <span
                                                        class="member-role">{{ $cargoPrincipal->cargo->nombre }}</span>

                                                    <!-- Información de sucursal o indicación de gerencial -->
                                                    <div class="member-traits">
                                                        <div>
                                                            @if ($cargoPrincipal->sucursal)
                                                                <span class="trait-label">Sede:</span>
                                                                <span
                                                                    class="trait-value">{{ $cargoPrincipal->sucursal->sede->nombre }}
                                                                    - {{ $cargoPrincipal->sucursal->nombre }}</span>
                                                            @else
                                                                <span class="trait-label">Área:</span>
                                                                <span
                                                                    class="trait-value text-success"><strong>{{ $area }}</strong>
                                                                    (Todas las sedes)
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <!-- Botones de contacto -->
                                                    <div class="member-contact-buttons">
                                                        @if ($trabajador->persona->correo)
                                                            <a href="mailto:{{ $trabajador->persona->correo }}"
                                                                class="contact-btn email-btn" title="Enviar correo">
                                                                <i class="fas fa-envelope"></i>
                                                                <span
                                                                    class="contact-tooltip">{{ $trabajador->persona->correo }}</span>
                                                            </a>
                                                        @endif

                                                        @if ($trabajador->persona->celular)
                                                            <a href="https://wa.me/591{{ $trabajador->persona->celular }}?text=Hola,%20estoy%20interesado%20en%20contactarme%20con%20usted."
                                                                target="_blank" class="contact-btn whatsapp-btn"
                                                                title="WhatsApp">
                                                                <i class="fab fa-whatsapp"></i>
                                                                <span
                                                                    class="contact-tooltip">{{ $trabajador->persona->celular }}</span>
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <button class="carousel-btn next" id="teamNext">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </section>
        <!-- Partners Section -->
        <section class="partners-section">
            <div class="container">
                <div class="section-title fade-in">
                    <h2><span>Instituciones</span> de Convenio</h2>
                    <p>Colaboramos con las mejores universidades y centros educativos a nivel nacional e internacional
                        para ofrecer programas de máximo nivel</p>
                </div>
            </div>
            <div class="partners-carousel-wrapper">
                <div class="partners-carousel">
                    @foreach ($convenios as $convenio)
                        <div class="partner-logo">
                            <img src="{{ asset($convenio->imagen) }}" alt="{{ $convenio->nombre }}"
                                onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                            <div style="font-weight: bold; color: white; font-size: 0.9rem; display: none;">
                                {{ $convenio->nombre }}
                            </div>
                        </div>
                    @endforeach


                    <!-- Duplicated set for seamless infinite scroll -->
                    @foreach ($convenios as $convenio)
                        <div class="partner-logo">
                            <img src="{{ asset($convenio->imagen) }}" alt="{{ $convenio->nombre }}"
                                onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                            <div style="font-weight: bold; color: white; font-size: 0.9rem; display: none;">
                                {{ $convenio->nombre }}
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </section>
        <!-- Sedes Section -->
        <section class="sedes" id="sedes">
            <div class="container">
                <div class="section-title">
                    <h2>Donde <span>nos Ubicamos</span></h2>
                    <p>
                        Estamos en diferentes ciudades con sede física
                    </p>
                </div>
            </div>
            <!-- resources/views/sucursales/index.blade.php -->
            <div class="sede-carousel-container">
                <button class="carousel-btn prev" id="sedePrev">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <div class="sede-carousel-wrapper" id="sedeCarouselWrapper">
                    <div class="sede-cards" id="sedeCarousel">
                        @foreach ($sucursales as $sucursal)
                            <div class="sede-card stagger-item" data-sede="{{ strtolower($sucursal->nombre) }}">
                                <a href="https://maps.google.com/?q={{ $sucursal->latitud }},{{ $sucursal->longitud }}"
                                    target="_blank" class="sede-map-link">
                                    <iframe
                                        src="https://www.google.com/maps/embed/v1/place?key=AIzaSyC11Mvkl0voVbsklAQ-eTIBLfWmJB2w64k&q={{ $sucursal->latitud }},{{ $sucursal->longitud }}"
                                        width="100%" height="200" style="border:0;" allowfullscreen=""
                                        loading="lazy">
                                    </iframe>
                                </a>
                                <div class="sede-content">
                                    <h3>{{ $sucursal->nombre }}</h3>
                                    <p>{{ $sucursal->direccion }}</p>
                                    <div class="sede-stats">
                                        <!-- Si tienes campos para programas, estudiantes y laboratorios en la base de datos, reemplaza los valores estáticos -->
                                        <div class="stat">
                                            <span class="stat-value">{{ $sucursal->programas ?? '25+' }}</span>
                                            <span class="stat-label">Programas</span>
                                        </div>
                                        <div class="stat">
                                            <span class="stat-value">{{ $sucursal->estudiantes ?? '1500' }}</span>
                                            <span class="stat-label">Estudiantes</span>
                                        </div>
                                        <div class="stat">
                                            <span class="stat-value">{{ $sucursal->laboratorios ?? '8' }}</span>
                                            <span class="stat-label">Laboratorios</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <button class="carousel-btn next" id="sedeNext">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>

        </section>
        <!-- Programas Section -->
        <section class="programas" id="programas-detalle">
            <div class="container">
                <div class="section-title">
                    <h2>Catálogo de <span>Programas Académicos</span></h2>
                    <p>Explora nuestra oferta educativa premium organizada por sede. Selecciona una ubicación para ver
                        los programas disponibles en cada campus.</p>
                </div>
                <!-- En la sección de filtros, reemplaza los botones estáticos por dinámicos -->
                <div class="programas-filters">
                    <button class="filter-btn active" data-filter="todos">Todos los Programas</button>
                    @foreach ($sucursalesDisponibles as $sucursal)
                        <button class="filter-btn"
                            data-filter="{{ strtolower(str_replace(' ', '', $sucursal->nombre)) }}">
                            {{ $sucursal->nombre }}
                        </button>
                    @endforeach
                </div>

                <!-- En la sección de programas-grid, reemplaza el contenido estático por dinámico -->
                <div class="programas-grid">
                    @if ($ofertas->isEmpty())
                        <div class="no-ofertas-message"
                            style="grid-column: span 3; text-align: center; padding: 40px;">
                            <i class="fas fa-info-circle"
                                style="font-size: 48px; color: #ffc107; margin-bottom: 15px; display: block;"></i>
                            <h3>No hay ofertas académicas disponibles</h3>
                            <p>Actualmente no tenemos programas académicos disponibles en fase de inscripciones.</p>
                        </div>
                    @else
                        @foreach ($ofertas as $oferta)
                            @php
                                // Obtener el plan de pago "Al Contado"
                                $planAlContado = $oferta->plan_concepto->firstWhere('plan_pago.nombre', 'Al Contado');
                                $precio = $planAlContado ? $planAlContado->pago_bs : 0;

                                // Crear slug para la sede (sin espacios y en minúsculas)
                                $sedeSlug = strtolower(str_replace(' ', '', $oferta->sucursal->nombre ?? ''));
                                $nombreTipo = $oferta->posgrado->tipo->nombre ?? 'Programa';
                                $tipoSlug = strtolower(str_replace(' ', '', $nombreTipo));

                                // Formatear la duración
                                $duracion =
                                    isset($oferta->posgrado->duracion_numero) &&
                                    isset($oferta->posgrado->duracion_unidad)
                                        ? "{$oferta->posgrado->duracion_numero} {$oferta->posgrado->duracion_unidad}"
                                        : 'Duración no especificada';
                            @endphp

                            <div class="programa-card stagger-item" data-sede="{{ $sedeSlug }}"
                                data-category="{{ $tipoSlug }}">
                                <div class="programa-header">
                                    <span class="programa-category">{{ $nombreTipo }}</span>
                                    <span
                                        class="programa-sede">{{ $oferta->sucursal->nombre ?? 'Sede no especificada' }}</span>
                                </div>
                                <div class="programa-image">
                                    @if ($oferta->portada)
                                        <img src="{{ asset($oferta->portada) }}"
                                            alt="{{ $oferta->posgrado->nombre ?? 'Programa sin nombre' }}"
                                            onerror="this.onerror=null; this.src='{{ asset('frontend/assets/img/afiches/default.jpg') }}';">
                                    @else
                                        <img src="{{ asset('frontend/assets/img/afiches/default.jpg') }}"
                                            alt="{{ $oferta->posgrado->nombre ?? 'Programa sin nombre' }}">
                                    @endif
                                    <div class="programa-badge inscripciones">INSCRIPCIONES</div>
                                    <div class="programa-badge oferta">OFERTA</div>
                                    @if ($oferta->posgrado->convenio && $oferta->posgrado->convenio->imagen)
                                        <div class="programa-convenio-logo">
                                            <img src="{{ asset($oferta->posgrado->convenio->imagen) }}"
                                                alt="{{ $oferta->posgrado->convenio->nombre }}"
                                                onerror="this.onerror=null; this.parentElement.style.display='none';">
                                        </div>
                                    @endif
                                </div>
                                <div class="programa-body">
                                    <h3 class="programa-title">
                                        {{ $oferta->posgrado->nombre ?? 'Programa sin nombre' }}</h3>
                                    <p>{{ Str::limit($oferta->posgrado->objetivo ?? 'Descripción no disponible', 120) }}
                                    </p>
                                    <div class="programa-details">
                                        <div class="programa-detail">
                                            <i class="far fa-clock"></i>
                                            {{ $oferta->fecha_inicio_programa->format('d M Y') }} -
                                            {{ $duracion }}
                                        </div>
                                        <div class="programa-detail">
                                            <i class="fas fa-book"></i>
                                            {{ $oferta->posgrado->area->nombre ?? 'Área no especificada' }}
                                        </div>
                                    </div>
                                    <div class="programa-footer">
                                        <div class="programa-price">Bs. {{ number_format($precio, 0, ',', '.') }}
                                        </div>
                                        <a href="{{ route('oferta.detalle', $oferta->id) }}" class="cta-button">Más
                                            Info</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </section>
        <!-- CTA Section -->
        <section class="cta-section">
            <div class="cta-bg-element"></div>
            <div class="cta-bg-element-2"></div>
            <div class="container">
                <div class="cta-content">
                    <h2>Transforma tu Futuro con <span style="color: var(--accent);">UNIP</span></h2>
                    <p>Contáctanos hoy mismo para recibir asesoría personalizada sobre nuestros programas exclusivos y
                        sedes disponibles. Nuestro equipo de admisiones está listo para ayudarte a encontrar el camino
                        perfecto para alcanzar tus metas profesionales.</p>
                    <button class="cta-button">Solicitar Información Personalizada</button>
                </div>
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
                            Ofrecemos una amplia variedad de programas de postgrado y servicios educativos
                            personalizados para formar líderes capaces de enfrentar los desafíos del mundo empresarial
                            actual.
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
                            <li><a href="#inicio">Inicio</a></li>
                            <li><a href="#programs">Programas</a></li>
                            <li><a href="#team">Equipo Académico</a></li>
                            <li><a href="#sedes">Nuestras Sedes</a></li>
                            <li><a href="#programas-detalle">Catálogo Completo</a></li>
                        </ul>
                    </div>
                    <div class="footer-column">
                        <h3>Programas Destacados</h3>
                        <ul class="footer-links">
                            <li><a href="#">Diplomados</a></li>

                        </ul>
                    </div>
                    <div class="footer-column">
                        <h3>Contacto Directo</h3>
                        <ul class="footer-contact">
                            <li>
                                <i class="fas fa-map-marker-alt"></i>

                            </li>
                            <li>
                                <i class="fas fa-phone"></i>

                            </li>
                            <li>
                                <i class="fas fa-envelope"></i>

                            </li>
                            <li>
                                <i class="fas fa-clock"></i>

                            </li>
                        </ul>
                    </div>
                </div>
                <div class="footer-bottom">
                    <p>&copy; 2025 UNIP. Todos los derechos reservados. | <a href="#"
                            style="color: rgba(255,255,255,0.6); text-decoration: underline;">Términos y
                            Condiciones</a> | <a href="#"
                            style="color: rgba(255,255,255,0.6); text-decoration: underline;">Política de
                            Privacidad</a></p>
                </div>
            </div>
        </footer>
    </div>

    <!-- Modal de Ofertas Especiales -->
    <div id="offerModal" class="modal">
        <div class="modal-content">
            <span class="close" id="closeModal">&times;</span>
            <div class="modal-header">
                <h2>Ofertas Especiales <span>Exclusivas</span></h2>
                <p>Pre-inscripción con descuentos únicos por tiempo limitado</p>
            </div>
            <div class="modal-body">
                <div class="offers-grid">
                    @if ($ofertasFase2->isEmpty())
                        <div class="offer-card" style="grid-column: span 3; text-align: center;">
                            <h3>No hay ofertas especiales disponibles</h3>
                            <p>Por el momento no contamos con ofertas especiales. Visita nuestra sección de programas
                                para ver todas las opciones disponibles.</p>
                        </div>
                    @else
                        @foreach ($ofertasFase2 as $oferta)
                            @php
                                // Obtener el precio del plan "Al Contado" si existe
                                $planAlContado = $oferta->plan_concepto->firstWhere('plan_pago.nombre', 'Al Contado');
                                $precio = $planAlContado ? $planAlContado->pago_bs : 0;

                                // Calcular precio con descuento (20% menos como ejemplo)
                                $precioDescuento = $precio * 0.8;
                            @endphp
                            <div class="offer-card">
                                <div class="offer-badge">20% DESCUENTO</div>
                                <h3>{{ $oferta->posgrado->nombre ?? 'Programa sin nombre' }}</h3>
                                <p>{{ Str::limit($oferta->posgrado->objetivo ?? 'Descripción no disponible', 100) }}
                                </p>
                                <div class="offer-price">
                                    <span class="original-price">Bs. {{ number_format($precio, 0, ',', '.') }}</span>
                                    <span class="discounted-price">Bs.
                                        {{ number_format($precioDescuento, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
                <div class="preinscription-form">
                    <h3>Pre-inscripción Especial</h3>
                    <form id="preinscriptionForm">
                        <div class="form-group">
                            <input type="text" id="fullName" name="fullName" placeholder="Nombre completo"
                                required>
                        </div>
                        <div class="form-group">
                            <input type="email" id="email" name="email" placeholder="Correo electrónico"
                                required>
                        </div>
                        <div class="form-group">
                            <input type="tel" id="phone" name="phone" placeholder="Teléfono de contacto"
                                required>
                        </div>
                        <div class="form-group">
                            <select id="program" name="program" required>
                                <option value="">Seleccione un programa</option>
                                @if ($ofertasFase2->isNotEmpty())
                                    @foreach ($ofertasFase2 as $oferta)
                                        <option value="{{ $oferta->id }}">
                                            {{ $oferta->posgrado->nombre ?? 'Programa sin nombre' }}</option>
                                    @endforeach
                                @else
                                    <option value="">No hay ofertas disponibles</option>
                                @endif
                            </select>
                        </div>
                        <div class="form-group">
                            <textarea id="message" name="message" placeholder="¿En qué sede le interesa estudiar?"></textarea>
                        </div>
                        <div class="form-group checkbox-group">
                            <input type="checkbox" id="terms" name="terms" required>
                            <label for="terms">Acepto los términos y condiciones y autorizo el tratamiento de mis
                                datos personales</label>
                        </div>
                        <button type="submit" class="cta-button">Enviar Pre-inscripción</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // GSAP Animations with ScrollTrigger - Enhanced
        document.addEventListener('DOMContentLoaded', function() {
            gsap.registerPlugin(ScrollTrigger, ScrollToPlugin, Flip);

            // Remove loading screen with animation
            const loadingScreen = document.querySelector('.loading-screen');
            const loaderText = document.querySelector('.loader-text');

            // Show loader text after 500ms
            setTimeout(() => {
                gsap.to(loaderText, {
                    opacity: 1,
                    duration: 0.5
                });
            }, 500);

            // Hide loading screen after 1500ms
            setTimeout(() => {
                gsap.to(loadingScreen, {
                    opacity: 0,
                    duration: 0.8,
                    ease: "power2.in",
                    onComplete: () => {
                        loadingScreen.style.display = 'none';
                        // Start page animations after loader disappears
                        startPageAnimations();
                    }
                });
            }, 1500);

            function startPageAnimations() {
                // Header scroll effect with enhanced animation
                let lastScroll = 0;
                const header = document.getElementById('header');

                window.addEventListener('scroll', function() {
                    const currentScroll = window.scrollY;
                    if (currentScroll > 100) {
                        header.classList.add('scrolled');
                    } else {
                        header.classList.remove('scrolled');
                    }

                    // Parallax effect for header logo on scroll
                    if (currentScroll > 50) {
                        const scrollRatio = Math.min(currentScroll / 500, 1);
                        gsap.to('.logo-icon', {
                            y: scrollRatio * 5,
                            rotate: scrollRatio * 10,
                            duration: 0.3
                        });
                    }

                    lastScroll = currentScroll;
                });

                // Enhanced scroll progress indicator
                const progress = document.querySelector('.scroll-progress');
                ScrollTrigger.create({
                    start: 0,
                    end: 'bottom bottom',
                    onUpdate: self => {
                        progress.style.transform = `scaleX(${self.progress})`;
                        // Change color based on scroll position
                        const gradientIntensity = Math.min(self.progress * 2, 1);
                        progress.style.background =
                            `linear-gradient(90deg, rgba(94, 201, 177, ${0.6 + gradientIntensity * 0.4}), rgba(106, 217, 197, ${0.5 + gradientIntensity * 0.5}))`;
                    }
                });

                // Floating shapes animation with physics-based movement
                gsap.to('.shape-1', {
                    keyframes: [{
                            y: -20,
                            x: 30,
                            duration: 4
                        },
                        {
                            y: -40,
                            x: 15,
                            duration: 4
                        },
                        {
                            y: -20,
                            x: 30,
                            duration: 4
                        }
                    ],
                    repeat: -1,
                    ease: "sine.inOut"
                });

                gsap.to('.shape-2', {
                    keyframes: [{
                            y: 25,
                            x: -25,
                            duration: 5
                        },
                        {
                            y: 40,
                            x: -10,
                            duration: 5
                        },
                        {
                            y: 25,
                            x: -25,
                            duration: 5
                        }
                    ],
                    repeat: -1,
                    ease: "sine.inOut"
                });

                // Add floating animation to CTA buttons in hero
                gsap.utils.toArray('.hero .cta-button').forEach((btn, index) => {
                    gsap.to(btn, {
                        y: index === 0 ? 10 : -10,
                        repeat: -1,
                        yoyo: true,
                        duration: 2 + index,
                        ease: "sine.inOut",
                        delay: index * 0.3
                    });
                });

                // Hero section animations - SIMPLIFIED
                const heroH1 = document.querySelector('.hero h1');
                if (heroH1) {
                    gsap.fromTo(heroH1, {
                        opacity: 0,
                        y: 30
                    }, {
                        opacity: 1,
                        y: 0,
                        duration: 1,
                        ease: "power3.out"
                    });
                }

                const heroP = document.querySelector('.hero p');
                if (heroP) {
                    gsap.fromTo(heroP, {
                        opacity: 0,
                        y: 20
                    }, {
                        opacity: 1,
                        y: 0,
                        duration: 0.8,
                        delay: 0.3,
                        ease: "power3.out"
                    });
                }

                const heroButtons = document.querySelectorAll('.hero .cta-button');
                if (heroButtons.length > 0) {
                    gsap.fromTo(heroButtons, {
                        opacity: 0,
                        y: 20
                    }, {
                        opacity: 1,
                        y: 0,
                        duration: 0.8,
                        delay: 0.5,
                        stagger: 0.1,
                        ease: "power3.out"
                    });
                }

                // Section titles animation with clip-path reveal
                gsap.utils.toArray('.section-title').forEach((titleSection, index) => {
                    const heading = titleSection.querySelector('h2');
                    const paragraph = titleSection.querySelector('p');

                    // Animate heading with fade and slide
                    gsap.fromTo(heading, {
                        opacity: 0,
                        y: 30
                    }, {
                        opacity: 1,
                        y: 0,
                        duration: 0.8,
                        ease: "power4.out",
                        scrollTrigger: {
                            trigger: heading,
                            start: "top 90%",
                            toggleActions: "play none none none"
                        },
                        delay: index * 0.2
                    });

                    // Animate paragraph with fade and slide
                    gsap.fromTo(paragraph, {
                        opacity: 0,
                        y: 30
                    }, {
                        opacity: 1,
                        y: 0,
                        duration: 0.8,
                        ease: "power3.out",
                        scrollTrigger: {
                            trigger: paragraph,
                            start: "top 90%",
                            toggleActions: "play none none none"
                        },
                        delay: index * 0.2 + 0.3
                    });
                });

                // Programs cards animation with 3D flip effect
                gsap.utils.toArray('.program-card').forEach((card, index) => {
                    const icon = card.querySelector('.program-icon');
                    const content = card.querySelectorAll('h3, p, .learn-more');

                    // Flip animation on scroll
                    ScrollTrigger.create({
                        trigger: card,
                        start: "top 85%",
                        onEnter: () => {
                            gsap.fromTo(card, {
                                opacity: 0,
                                y: 50
                            }, {
                                opacity: 1,
                                y: 0,
                                duration: 0.8,
                                delay: index * 0.1,
                                ease: "power4.out",
                                onComplete: () => {
                                    gsap.to(icon, {
                                        keyframes: [{
                                                scale: 1.2,
                                                rotation: 15,
                                                duration: 0.3
                                            },
                                            {
                                                scale: 1,
                                                rotation: 0,
                                                duration: 0.3
                                            }
                                        ],
                                        ease: "back.out(1.7)"
                                    });
                                }
                            });

                            // Stagger content animation
                            gsap.fromTo(content, {
                                opacity: 0,
                                y: 20
                            }, {
                                opacity: 1,
                                y: 0,
                                duration: 0.6,
                                stagger: 0.1,
                                ease: "power3.out",
                                delay: index * 0.1 + 0.4
                            });
                        },
                        once: true
                    });
                });

                // Team members animation with scale and fade
                gsap.utils.toArray('.team-member').forEach((member, index) => {
                    const img = member.querySelector('.member-img');
                    const info = member.querySelector('.member-info');

                    ScrollTrigger.create({
                        trigger: member,
                        start: "top 90%",
                        onEnter: () => {
                            gsap.timeline()
                                .fromTo(member, {
                                    opacity: 0,
                                    y: 50
                                }, {
                                    opacity: 1,
                                    y: 0,
                                    duration: 0.8,
                                    ease: "power4.out"
                                })
                                .fromTo(img, {
                                        scale: 0.9
                                    }, {
                                        scale: 1,
                                        duration: 0.6,
                                        ease: "back.out(1.7)"
                                    },
                                    "-=0.4"
                                )
                                .fromTo(info.querySelectorAll('*'), {
                                        opacity: 0,
                                        y: 20
                                    }, {
                                        opacity: 1,
                                        y: 0,
                                        duration: 0.5,
                                        stagger: 0.05,
                                        ease: "power3.out"
                                    },
                                    "-=0.3"
                                );
                        },
                        once: true
                    });
                });

                // Partners animation
                const partnersSection = document.querySelector('.partners-section');
                if (partnersSection) {
                    const partnerTitle = partnersSection.querySelector('.section-title');

                    ScrollTrigger.create({
                        trigger: partnerTitle,
                        start: "top 80%",
                        onEnter: () => {
                            gsap.fromTo(partnerTitle.querySelectorAll('h2, p'), {
                                opacity: 0,
                                y: 50
                            }, {
                                opacity: 1,
                                y: 0,
                                duration: 0.8,
                                stagger: 0.2,
                                ease: "power3.out"
                            });
                        },
                        once: true
                    });
                }

                // Sedes cards animation with perspective
                gsap.utils.toArray('.sede-card').forEach((card, index) => {
                    ScrollTrigger.create({
                        trigger: card,
                        start: "top 85%",
                        onEnter: () => {
                            gsap.timeline()
                                .fromTo(card, {
                                    opacity: 0,
                                    y: 50,
                                    scale: 0.95
                                }, {
                                    opacity: 1,
                                    y: 0,
                                    scale: 1,
                                    duration: 0.9,
                                    ease: "back.out(1.7)"
                                })
                                .fromTo(card.querySelectorAll('.sede-content *'), {
                                        opacity: 0,
                                        y: 20
                                    }, {
                                        opacity: 1,
                                        y: 0,
                                        duration: 0.5,
                                        stagger: 0.1,
                                        ease: "power3.out"
                                    },
                                    "-=0.5"
                                );
                        },
                        once: true
                    });
                });

                // Programas cards animation with enhanced filtering
                // Reemplaza la sección de Programas cards animation con esta versión actualizada
                const programaCards = document.querySelectorAll('.programa-card');
                const filterButtons = document.querySelectorAll('.filter-btn');

                // Función para mostrar mensaje cuando no hay resultados
                function showNoResultsMessage(sucursalNombre = '') {
                    const grid = document.querySelector('.programas-grid');
                    grid.innerHTML = `
        <div class="no-ofertas-message" style="grid-column: span 3; text-align: center; padding: 40px;">
            <i class="fas fa-exclamation-triangle" style="font-size: 48px; color: #ffc107; margin-bottom: 15px; display: block;"></i>
            <h3>No hay ofertas académicas disponibles</h3>
            <p>Actualmente no tenemos programas académicos disponibles para <strong>${sucursalNombre}</strong>. 
               Por favor, selecciona otra sede o visita más tarde.</p>
            <button class="cta-button ver-todos-btn" style="margin-top: 20px;">Ver todos los programas</button>
        </div>
    `;

                    // Agregar evento al botón para volver a ver todos
                    document.querySelector('.ver-todos-btn').addEventListener('click', function() {
                        document.querySelector('.filter-btn[data-filter="todos"]').click();
                    });
                }

                // Manejar los filtros
                filterButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        // Actualizar botones activos
                        filterButtons.forEach(btn => btn.classList.remove('active'));
                        this.classList.add('active');

                        const filterValue = this.getAttribute('data-filter');
                        let visibleCount = 0;

                        // Mostrar/ocultar tarjetas según el filtro
                        programaCards.forEach(card => {
                            const cardSede = card.getAttribute('data-sede');
                            if (filterValue === 'todos' || cardSede === filterValue) {
                                card.style.display = 'block';
                                visibleCount++;
                            } else {
                                card.style.display = 'none';
                            }
                        });

                        // Si no hay resultados y no es el filtro "todos"
                        if (visibleCount === 0 && filterValue !== 'todos') {
                            const sucursalNombre = this.textContent;
                            showNoResultsMessage(sucursalNombre);
                        }
                    });
                });

                // Sede cards click to filter functionality
                const sedeCards = document.querySelectorAll('.sede-card');
                sedeCards.forEach(card => {
                    card.addEventListener('click', function() {
                        const sede = this.getAttribute('data-sede');

                        // Highlight corresponding filter button
                        filterButtons.forEach(btn => btn.classList.remove('active'));
                        const targetButton = document.querySelector(
                            `.filter-btn[data-filter="${sede}"]`);
                        if (targetButton) targetButton.classList.add('active');

                        // Create FLIP animation for filtering
                        const state = Flip.getState(programaCards);

                        // Filter cards
                        programaCards.forEach(programaCard => {
                            const cardSede = programaCard.getAttribute('data-sede');
                            const shouldShow = cardSede === sede;

                            if (shouldShow) {
                                programaCard.style.display = 'block';
                            } else {
                                programaCard.style.display = 'none';
                            }
                        });

                        // Animate the change
                        Flip.from(state, {
                            duration: 0.8,
                            stagger: 0.05,
                            ease: "power3.out",
                            absolute: true,
                            onComplete: () => {
                                // Scroll to programas section
                                gsap.to(window, {
                                    duration: 1,
                                    scrollTo: {
                                        y: '#programas-detalle',
                                        offsetY: 120
                                    },
                                    ease: "power2.inOut"
                                });

                                // Apply staggered entrance for visible cards
                                gsap.utils.toArray(document.querySelectorAll(
                                        '.programa-card[style="display: block;"]'))
                                    .forEach((card, index) => {
                                        gsap.fromTo(card, {
                                            opacity: 0,
                                            y: 30
                                        }, {
                                            opacity: 1,
                                            y: 0,
                                            duration: 0.6,
                                            delay: index * 0.08,
                                            ease: "power3.out"
                                        });
                                    });
                            }
                        });
                    });
                });

                // CTA section animation
                const ctaSection = document.querySelector('.cta-section');
                if (ctaSection) {
                    ScrollTrigger.create({
                        trigger: ctaSection,
                        start: "top 80%",
                        onEnter: () => {
                            gsap.timeline()
                                .fromTo(ctaSection.querySelector('h2'), {
                                    opacity: 0,
                                    y: 50
                                }, {
                                    opacity: 1,
                                    y: 0,
                                    duration: 1,
                                    ease: "power4.out"
                                })
                                .fromTo(ctaSection.querySelector('p'), {
                                        opacity: 0,
                                        y: 30
                                    }, {
                                        opacity: 1,
                                        y: 0,
                                        duration: 0.8,
                                        ease: "power3.out"
                                    },
                                    "-=0.5"
                                )
                                .fromTo(ctaSection.querySelector('.cta-button'), {
                                        opacity: 0,
                                        scale: 0.8,
                                        rotation: -10
                                    }, {
                                        opacity: 1,
                                        scale: 1,
                                        rotation: 0,
                                        duration: 0.8,
                                        ease: "back.out(1.7)"
                                    },
                                    "-=0.4"
                                );
                        },
                        once: true
                    });
                }

                // Footer animation
                const footer = document.querySelector('footer');
                if (footer) {
                    ScrollTrigger.create({
                        trigger: footer,
                        start: "top 90%",
                        onEnter: () => {
                            gsap.utils.toArray('.footer-column').forEach((column, index) => {
                                gsap.fromTo(column, {
                                    opacity: 0,
                                    y: 50
                                }, {
                                    opacity: 1,
                                    y: 0,
                                    duration: 0.8,
                                    delay: index * 0.15,
                                    ease: "power3.out"
                                });
                            });

                            gsap.utils.toArray('.social-link').forEach((link, index) => {
                                gsap.fromTo(link, {
                                    opacity: 0,
                                    scale: 0.5,
                                    rotation: -45
                                }, {
                                    opacity: 1,
                                    scale: 1,
                                    rotation: 0,
                                    duration: 0.6,
                                    delay: 0.8 + index * 0.1,
                                    ease: "back.out(1.7)"
                                });
                            });
                        },
                        once: true
                    });
                }

                // Parallax effects
                gsap.to('.hero-visual', {
                    yPercent: -15,
                    ease: "none",
                    scrollTrigger: {
                        trigger: '.hero',
                        start: 'top top',
                        end: 'bottom top',
                        scrub: 2
                    }
                });

                // Add floating animation to hero logo
                gsap.to('.hero-logo', {
                    y: -20,
                    rotation: 5,
                    ease: "sine.inOut",
                    repeat: -1,
                    yoyo: true,
                    duration: 4
                });

                // Add floating animation to CTA button in hero
                gsap.to('.hero .cta-button', {
                    keyframes: [{
                            y: 0,
                            ease: "power1.in"
                        },
                        {
                            y: -15,
                            ease: "power2.out"
                        },
                        {
                            y: 0,
                            ease: "power2.in"
                        }
                    ],
                    duration: 3,
                    repeat: -1
                });

                // Enhanced card hover effects with GSAP
                document.querySelectorAll('.program-card, .team-member, .sede-card, .programa-card').forEach(
                    card => {
                        card.addEventListener('mouseenter', () => {
                            gsap.to(card, {
                                y: -10,
                                boxShadow: '0 25px 50px rgba(0, 0, 0, 0.3)',
                                duration: 0.3,
                                ease: "power2.out"
                            });
                        });

                        card.addEventListener('mouseleave', () => {
                            gsap.to(card, {
                                y: 0,
                                boxShadow: '0 10px 25px rgba(0, 0, 0, 0.15)',
                                duration: 0.3,
                                ease: "power2.out"
                            });
                        });
                    });

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

                        // Animate hamburger icon
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

                        // Prevent body scroll when menu is open
                        if (isActive) {
                            document.body.style.overflow = 'hidden';
                        } else {
                            document.body.style.overflow = '';
                        }
                    });

                    // Close menu when clicking on overlay
                    if (mobileOverlay) {
                        mobileOverlay.addEventListener('click', function() {
                            mobileMenuToggle.classList.remove('active');
                            navLinks.classList.remove('active');
                            this.classList.remove('active');
                            document.body.style.overflow = '';

                            // Reset hamburger icon color
                            gsap.to('.mobile-menu-toggle span', {
                                backgroundColor: 'var(--white)',
                                duration: 0.3
                            });
                        });
                    }

                    // Close menu when clicking on a link
                    const menuLinks = navLinks.querySelectorAll('a');
                    menuLinks.forEach(link => {
                        link.addEventListener('click', function(e) {
                            mobileMenuToggle.classList.remove('active');
                            navLinks.classList.remove('active');
                            if (mobileOverlay) mobileOverlay.classList.remove('active');
                            document.body.style.overflow = '';

                            // Reset hamburger icon color
                            gsap.to('.mobile-menu-toggle span', {
                                backgroundColor: 'var(--white)',
                                duration: 0.3
                            });
                        });
                    });
                }

                // Header scroll animation with GSAP
                let lastScrollTop = 0;
                const navbar = document.querySelector('.navbar');

                window.addEventListener('scroll', function() {
                    let scrollTop = window.pageYOffset || document.documentElement.scrollTop;

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

                // Enhanced CTA button hover effect
                document.querySelectorAll('.cta-button').forEach(button => {
                    button.addEventListener('mouseenter', function() {
                        gsap.to(this, {
                            scale: 1.08,
                            boxShadow: '0 15px 35px rgba(94, 201, 177, 0.5)',
                            duration: 0.4,
                            ease: "back.out(1.7)"
                        });
                    });

                    button.addEventListener('mouseleave', function() {
                        gsap.to(this, {
                            scale: 1,
                            boxShadow: '0 10px 25px rgba(94, 201, 177, 0.3)',
                            duration: 0.4,
                            ease: "power2.out"
                        });
                    });
                });

                // === TEAM CAROUSEL FUNCTIONALITY ===
                const teamCarousel = document.getElementById('teamCarousel');
                const teamWrapper = document.getElementById('teamCarouselWrapper');
                const teamPrevBtn = document.getElementById('teamPrev');
                const teamNextBtn = document.getElementById('teamNext');

                if (teamCarousel && teamWrapper) {
                    let teamCurrentIndex = 0;
                    let teamIsDragging = false;
                    let teamStartX = 0;
                    let teamCurrentTranslate = 0;
                    let teamPrevTranslate = 0;
                    const teamCards = teamCarousel.querySelectorAll('.team-member');
                    const teamCardWidth = Math.floor(teamWrapper.offsetWidth / 3);
                    let teamMaxIndex = Math.max(0, teamCards.length - 3);

                    // Recalculate max index and card width on resize
                    window.addEventListener('resize', () => {
                        teamCardWidth = Math.floor(teamWrapper.offsetWidth / 3);
                        teamMaxIndex = Math.max(0, teamCards.length - 3);
                        updateTeamCarousel();
                    });

                    function updateTeamCarousel() {
                        teamCurrentTranslate = -teamCurrentIndex * teamCardWidth;
                        gsap.to(teamCarousel, {
                            x: teamCurrentTranslate,
                            duration: 0.5,
                            ease: "power2.out"
                        });

                        // Deshabilitar botones en los límites
                        teamPrevBtn.disabled = teamCurrentIndex <= 0;
                        teamNextBtn.disabled = teamCurrentIndex >= teamMaxIndex;

                        // Update navigation button appearance
                        gsap.to(teamPrevBtn, {
                            opacity: teamCurrentIndex <= 0 ? 0.3 : 1,
                            duration: 0.3
                        });
                        gsap.to(teamNextBtn, {
                            opacity: teamCurrentIndex >= teamMaxIndex ? 0.3 : 1,
                            duration: 0.3
                        });
                    }

                    teamPrevBtn.addEventListener('click', () => {
                        if (teamCurrentIndex > 0) {
                            teamCurrentIndex--;
                            updateTeamCarousel();

                            // Animate the card that comes into view
                            const cardIndex = teamCurrentIndex;
                            if (teamCards[cardIndex]) {
                                gsap.fromTo(teamCards[cardIndex], {
                                    opacity: 0.7,
                                    scale: 0.95
                                }, {
                                    opacity: 1,
                                    scale: 1,
                                    duration: 0.5,
                                    ease: "back.out(1.7)"
                                });
                            }
                        }
                    });

                    teamNextBtn.addEventListener('click', () => {
                        if (teamCurrentIndex < teamMaxIndex) {
                            teamCurrentIndex++;
                            updateTeamCarousel();

                            // Animate the card that comes into view
                            const cardIndex = teamCurrentIndex + Math.floor(teamWrapper.offsetWidth /
                                teamCardWidth) - 1;
                            if (teamCards[cardIndex]) {
                                gsap.fromTo(teamCards[cardIndex], {
                                    opacity: 0.7,
                                    scale: 0.95
                                }, {
                                    opacity: 1,
                                    scale: 1,
                                    duration: 0.5,
                                    ease: "back.out(1.7)"
                                });
                            }
                        }
                    });

                    // Drag functionality
                    teamWrapper.addEventListener('mousedown', (e) => {
                        teamIsDragging = true;
                        teamStartX = e.clientX;
                        teamPrevTranslate = teamCurrentTranslate;
                        teamCarousel.style.transition = 'none';
                    });

                    teamWrapper.addEventListener('mousemove', (e) => {
                        if (!teamIsDragging) return;
                        const currentX = e.clientX;
                        const diff = currentX - teamStartX;
                        const newTranslate = teamPrevTranslate + diff;

                        // Limitar el desplazamiento
                        const minTranslate = -teamMaxIndex * teamCardWidth;
                        const maxTranslate = 0;
                        teamCurrentTranslate = Math.max(minTranslate, Math.min(maxTranslate, newTranslate));

                        teamCarousel.style.transform = `translateX(${teamCurrentTranslate}px)`;
                    });

                    teamWrapper.addEventListener('mouseup', (e) => {
                        if (!teamIsDragging) return;
                        teamIsDragging = false;
                        teamCarousel.style.transition = 'transform 0.5s ease';

                        const movedBy = e.clientX - teamStartX;
                        if (movedBy < -100 && teamCurrentIndex < teamMaxIndex) {
                            teamCurrentIndex++;
                        } else if (movedBy > 100 && teamCurrentIndex > 0) {
                            teamCurrentIndex--;
                        }

                        // Prevenir desplazamiento más allá de los límites
                        if (teamCurrentIndex < 0) teamCurrentIndex = 0;
                        if (teamCurrentIndex > teamMaxIndex) teamCurrentIndex = teamMaxIndex;

                        updateTeamCarousel();
                    });

                    teamWrapper.addEventListener('mouseleave', () => {
                        if (teamIsDragging) {
                            teamIsDragging = false;
                            teamCarousel.style.transition = 'transform 0.5s ease';
                            updateTeamCarousel();
                        }
                    });

                    // Disable text selection while dragging
                    teamCarousel.addEventListener('selectstart', (e) => {
                        if (teamIsDragging) e.preventDefault();
                    });

                    updateTeamCarousel();
                }

                // === SEDE CAROUSEL FUNCTIONALITY ===
                const sedeCarousel = document.getElementById('sedeCarousel');
                const sedeWrapper = document.getElementById('sedeCarouselWrapper');
                const sedePrevBtn = document.getElementById('sedePrev');
                const sedeNextBtn = document.getElementById('sedeNext');

                if (sedeCarousel && sedeWrapper) {
                    let sedeCurrentIndex = 0;
                    let sedeIsDragging = false;
                    let sedeStartX = 0;
                    let sedeCurrentTranslate = 0;
                    let sedePrevTranslate = 0;
                    const sedeCardsElements = sedeCarousel.querySelectorAll('.sede-card');
                    const sedeCardWidth = Math.floor(sedeWrapper.offsetWidth / 3);
                    let sedeMaxIndex = Math.max(0, sedeCardsElements.length - 3);

                    // Recalculate max index on resize
                    window.addEventListener('resize', () => {
                        sedeMaxIndex = Math.max(0, sedeCardsElements.length - 3);
                        updateSedeCarousel();
                    });

                    function updateSedeCarousel() {
                        sedeCurrentTranslate = -sedeCurrentIndex * sedeCardWidth;
                        gsap.to(sedeCarousel, {
                            x: sedeCurrentTranslate,
                            duration: 0.5,
                            ease: "power2.out"
                        });

                        // Deshabilitar botones en los límites
                        sedePrevBtn.disabled = sedeCurrentIndex <= 0;
                        sedeNextBtn.disabled = sedeCurrentIndex >= sedeMaxIndex;

                        // Update navigation button appearance
                        gsap.to(sedePrevBtn, {
                            opacity: sedeCurrentIndex <= 0 ? 0.3 : 1,
                            duration: 0.3
                        });
                        gsap.to(sedeNextBtn, {
                            opacity: sedeCurrentIndex >= sedeMaxIndex ? 0.3 : 1,
                            duration: 0.3
                        });
                    }

                    sedePrevBtn.addEventListener('click', () => {
                        if (sedeCurrentIndex > 0) {
                            sedeCurrentIndex--;
                            updateSedeCarousel();

                            // Animate the card that comes into view
                            const cardIndex = sedeCurrentIndex;
                            if (sedeCardsElements[cardIndex]) {
                                gsap.fromTo(sedeCardsElements[cardIndex], {
                                    opacity: 0.7,
                                    scale: 0.95
                                }, {
                                    opacity: 1,
                                    scale: 1,
                                    duration: 0.5,
                                    ease: "back.out(1.7)"
                                });
                            }
                        }
                    });

                    sedeNextBtn.addEventListener('click', () => {
                        if (sedeCurrentIndex < sedeMaxIndex) {
                            sedeCurrentIndex++;
                            updateSedeCarousel();

                            // Animate the card that comes into view
                            const cardIndex = sedeCurrentIndex + Math.floor(sedeWrapper.offsetWidth /
                                sedeCardWidth) - 1;
                            if (sedeCardsElements[cardIndex]) {
                                gsap.fromTo(sedeCardsElements[cardIndex], {
                                    opacity: 0.7,
                                    scale: 0.95
                                }, {
                                    opacity: 1,
                                    scale: 1,
                                    duration: 0.5,
                                    ease: "back.out(1.7)"
                                });
                            }
                        }
                    });

                    // Drag functionality
                    sedeWrapper.addEventListener('mousedown', (e) => {
                        sedeIsDragging = true;
                        sedeStartX = e.clientX;
                        sedePrevTranslate = sedeCurrentTranslate;
                        sedeCarousel.style.transition = 'none';
                    });

                    sedeWrapper.addEventListener('mousemove', (e) => {
                        if (!sedeIsDragging) return;
                        const currentX = e.clientX;
                        const diff = currentX - sedeStartX;
                        const newTranslate = sedePrevTranslate + diff;

                        // Limitar el desplazamiento
                        const minTranslate = -sedeMaxIndex * sedeCardWidth;
                        const maxTranslate = 0;
                        sedeCurrentTranslate = Math.max(minTranslate, Math.min(maxTranslate, newTranslate));

                        sedeCarousel.style.transform = `translateX(${sedeCurrentTranslate}px)`;
                    });

                    sedeWrapper.addEventListener('mouseup', (e) => {
                        if (!sedeIsDragging) return;
                        sedeIsDragging = false;
                        sedeCarousel.style.transition = 'transform 0.5s ease';

                        const movedBy = e.clientX - sedeStartX;
                        if (movedBy < -100 && sedeCurrentIndex < sedeMaxIndex) {
                            sedeCurrentIndex++;
                        } else if (movedBy > 100 && sedeCurrentIndex > 0) {
                            sedeCurrentIndex--;
                        }

                        // Prevenir desplazamiento más allá de los límites
                        if (sedeCurrentIndex < 0) sedeCurrentIndex = 0;
                        if (sedeCurrentIndex > sedeMaxIndex) sedeCurrentIndex = sedeMaxIndex;

                        updateSedeCarousel();
                    });

                    sedeWrapper.addEventListener('mouseleave', () => {
                        if (sedeIsDragging) {
                            sedeIsDragging = false;
                            sedeCarousel.style.transition = 'transform 0.5s ease';
                            updateSedeCarousel();
                        }
                    });

                    // Disable text selection while dragging
                    sedeCarousel.addEventListener('selectstart', (e) => {
                        if (sedeIsDragging) e.preventDefault();
                    });

                    updateSedeCarousel();
                }

                // Add subtle floating animation to elements
                gsap.utils.toArray('.floating').forEach(element => {
                    gsap.to(element, {
                        y: -15,
                        repeat: -1,
                        yoyo: true,
                        duration: 2.5,
                        ease: "sine.inOut"
                    });
                });

                // Animate decorative elements in CTA section
                gsap.to('.cta-bg-element', {
                    scale: 1.2,
                    opacity: 0.7,
                    duration: 8,
                    ease: "none",
                    repeat: -1,
                    yoyo: true
                });

                gsap.to('.cta-bg-element-2', {
                    scale: 1.1,
                    opacity: 0.6,
                    duration: 10,
                    ease: "none",
                    repeat: -1,
                    yoyo: true,
                    delay: 1
                });

                // Mostrar modal de ofertas al cargar la página
                setTimeout(function() {
                    const modal = document.getElementById('offerModal');
                    if (modal) {
                        modal.style.display = 'block';
                        document.body.style.overflow = 'hidden'; // Prevenir scroll
                    }
                }, 2000); // Mostrar después de 2 segundos

                // Cerrar modal
                const closeModal = document.getElementById('closeModal');
                if (closeModal) {
                    closeModal.addEventListener('click', function() {
                        const modal = document.getElementById('offerModal');
                        if (modal) {
                            modal.style.display = 'none';
                            document.body.style.overflow = '';
                        }
                    });
                }

                // Cerrar modal al hacer clic fuera del contenido
                const modal = document.getElementById('offerModal');
                if (modal) {
                    modal.addEventListener('click', function(e) {
                        if (e.target === modal) {
                            modal.style.display = 'none';
                            document.body.style.overflow = '';
                        }
                    });
                }

                // Manejar formulario de preinscripción
                const preinscriptionForm = document.getElementById('preinscriptionForm');
                if (preinscriptionForm) {
                    preinscriptionForm.addEventListener('submit', function(e) {
                        e.preventDefault();

                        // Obtener valores del formulario
                        const fullName = document.getElementById('fullName').value;
                        const email = document.getElementById('email').value;
                        const phone = document.getElementById('phone').value;
                        const programSelect = document.getElementById('program');
                        const program = programSelect.options[programSelect.selectedIndex].text;
                        const programId = programSelect.value;

                        // Validar que se haya seleccionado un programa
                        if (!programId) {
                            alert('Por favor, seleccione un programa');
                            return;
                        }

                        // Validar que se haya aceptado los términos
                        const terms = document.getElementById('terms');
                        if (!terms.checked) {
                            alert('Por favor, acepte los términos y condiciones');
                            return;
                        }

                        // Simular envío del formulario (aquí deberías hacer una petición AJAX real)
                        alert(`¡Gracias por su pre-inscripción, ${fullName}!

Hemos recibido su solicitud para: ${program}
Nuestro equipo se pondrá en contacto con usted pronto.

Correo: ${email}
Teléfono: ${phone}`);

                        // Cerrar modal
                        const modal = document.getElementById('offerModal');
                        if (modal) {
                            modal.style.display = 'none';
                            document.body.style.overflow = '';
                        }

                        // Resetear formulario
                        preinscriptionForm.reset();
                    });
                }
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Activar tooltips en los botones de contacto
            const contactButtons = document.querySelectorAll('.contact-btn');

            contactButtons.forEach(button => {
                button.addEventListener('mouseenter', function() {
                    const tooltip = this.querySelector('.contact-tooltip');
                    if (tooltip) {
                        tooltip.style.opacity = '1';
                    }
                });

                button.addEventListener('mouseleave', function() {
                    const tooltip = this.querySelector('.contact-tooltip');
                    if (tooltip) {
                        tooltip.style.opacity = '0';
                    }
                });
            });
        });
    </script>
</body>

</html>
