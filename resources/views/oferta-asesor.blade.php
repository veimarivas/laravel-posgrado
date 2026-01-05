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
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Animaciones CSS (opcional) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
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

        .descripcion-section {
            background: #f8f9fa;
            border-radius: 20px;
            padding: 50px;
            margin: 60px 0;
        }

        .descripcion-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 40px;
        }

        .descripcion-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            border-left: 5px solid var(--accent);
        }

        .descripcion-card h3 {
            color: var(--primary-dark);
            font-size: 1.8rem;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .descripcion-card h3 i {
            color: var(--accent);
        }

        .descripcion-card p {
            line-height: 1.8;
            color: #555;
            font-size: 1.1rem;
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
            transition: all 0.3s ease;
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

        /* Estilos para la sección de asesor */
        .asesor-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 80px 0;
            border-radius: 20px;
            margin: 60px 0;
            position: relative;
            overflow: hidden;
        }

        .asesor-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none"><path d="M0,0 L100,0 L100,100 Z" fill="rgba(255,255,255,0.1)"/></svg>');
            background-size: cover;
        }

        .asesor-content {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            gap: 50px;
            flex-wrap: wrap;
        }

        .asesor-info {
            flex: 1;
            min-width: 300px;
        }

        .asesor-form {
            flex: 1;
            min-width: 300px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
        }

        .asesor-section h2 {
            font-size: 2.8rem;
            margin-bottom: 20px;
            color: white;
        }

        .asesor-section h2 span {
            color: #ffd700;
        }

        .asesor-section p {
            font-size: 1.2rem;
            margin-bottom: 30px;
            opacity: 0.9;
        }

        .asesor-card {
            display: flex;
            align-items: center;
            gap: 20px;
            background: rgba(255, 255, 255, 0.2);
            padding: 25px;
            border-radius: 15px;
            backdrop-filter: blur(10px);
            margin-bottom: 30px;
        }

        .asesor-card img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid white;
        }

        .asesor-card-info h4 {
            margin: 0 0 10px;
            font-size: 1.5rem;
        }

        .asesor-card-info p {
            margin: 0;
            font-size: 1rem;
            opacity: 0.9;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: var(--accent);
            outline: none;
            box-shadow: 0 0 0 3px rgba(94, 201, 177, 0.2);
        }

        .checkbox-group {
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .checkbox-group input {
            width: auto;
            margin-top: 5px;
        }

        .checkbox-group label {
            font-size: 0.9rem;
            color: #666;
            line-height: 1.5;
        }

        .btn-asesor {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 18px 40px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.1rem;
            cursor: pointer;
            width: 100%;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-asesor:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
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

            .asesor-content {
                flex-direction: column;
            }

            .asesor-form {
                padding: 30px;
            }

            .asesor-section h2 {
                font-size: 2.2rem;
            }
        }

        /* Estilos para la sección de asesor - ACTUALIZADO CON PALETA DE COLORES */
        .asesor-section {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            padding: 80px 0;
            border-radius: 20px;
            margin: 60px 0;
            position: relative;
            overflow: hidden;
            border: 2px solid rgba(94, 201, 177, 0.3);
            box-shadow: 0 20px 60px rgba(3, 42, 74, 0.3);
        }

        .asesor-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background:
                radial-gradient(circle at 20% 30%, rgba(94, 201, 177, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 70%, rgba(94, 201, 177, 0.1) 0%, transparent 50%);
            z-index: 1;
        }

        .asesor-content {
            position: relative;
            z-index: 2;
            display: flex;
            align-items: center;
            gap: 50px;
            flex-wrap: wrap;
        }

        .asesor-info {
            flex: 1;
            min-width: 300px;
        }

        .asesor-form {
            flex: 1;
            min-width: 300px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(94, 201, 177, 0.3);
            backdrop-filter: blur(10px);
        }

        .asesor-section h2 {
            font-size: 2.8rem;
            margin-bottom: 20px;
            color: white;
            position: relative;
            display: inline-block;
        }

        .asesor-section h2 span {
            color: var(--accent);
            position: relative;
        }

        .asesor-section h2 span::after {
            content: '';
            position: absolute;
            bottom: 8px;
            left: 0;
            width: 100%;
            height: 10px;
            background-color: rgba(94, 201, 177, 0.25);
            z-index: -1;
            border-radius: 4px;
        }

        .asesor-section p {
            font-size: 1.2rem;
            margin-bottom: 30px;
            opacity: 0.9;
            line-height: 1.6;
        }

        .asesor-card {
            display: flex;
            align-items: center;
            gap: 20px;
            background: rgba(255, 255, 255, 0.15);
            padding: 25px;
            border-radius: 15px;
            backdrop-filter: blur(10px);
            margin-bottom: 30px;
            border: 1px solid rgba(94, 201, 177, 0.3);
            transition: all 0.3s ease;
        }

        .asesor-card:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        }

        .asesor-card img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--accent);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .asesor-card-info h4 {
            margin: 0 0 10px;
            font-size: 1.5rem;
            color: white;
        }

        .asesor-card-info p {
            margin: 0;
            font-size: 1rem;
            opacity: 0.9;
            color: rgba(255, 255, 255, 0.9);
        }

        /* Formulario mejorado */
        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--primary-dark);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-group label i {
            color: var(--accent);
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 15px 20px;
            border: 2px solid rgba(94, 201, 177, 0.2);
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
            font-family: 'Inter', sans-serif;
            background: white;
            color: var(--primary-dark);
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: var(--accent);
            outline: none;
            box-shadow: 0 0 0 3px rgba(94, 201, 177, 0.2);
            background: rgba(94, 201, 177, 0.05);
        }

        .form-group input::placeholder {
            color: rgba(3, 42, 74, 0.5);
        }

        /* Checkbox mejorado */
        .checkbox-group {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-top: 20px;
        }

        .checkbox-group input[type="checkbox"] {
            width: 20px;
            height: 20px;
            margin-top: 3px;
            accent-color: var(--accent);
            cursor: pointer;
        }

        .checkbox-group label {
            font-size: 0.9rem;
            color: #666;
            line-height: 1.5;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .checkbox-group label:hover {
            color: var(--primary-dark);
        }

        .checkbox-group a {
            color: var(--accent);
            text-decoration: none;
            font-weight: 600;
        }

        .checkbox-group a:hover {
            text-decoration: underline;
        }

        /* Botón de asesor actualizado */
        .btn-asesor {
            background: linear-gradient(135deg, var(--accent), var(--accent-dark));
            color: white;
            border: none;
            padding: 18px 40px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.1rem;
            cursor: pointer;
            width: 100%;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            position: relative;
            overflow: hidden;
            margin-top: 20px;
        }

        .btn-asesor::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: 0.6s;
        }

        .btn-asesor:hover::before {
            left: 100%;
        }

        .btn-asesor:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(94, 201, 177, 0.4);
        }

        .btn-asesor:active {
            transform: translateY(0);
        }

        /* Tarjeta de beneficios actualizada */
        .asesor-beneficios {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 25px;
            margin-top: 30px;
            border: 1px solid rgba(94, 201, 177, 0.3);
            backdrop-filter: blur(5px);
        }

        .asesor-beneficios h4 {
            color: white;
            margin-bottom: 15px;
            font-size: 1.3rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .asesor-beneficios h4 i {
            color: var(--accent);
        }

        .asesor-beneficios ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .asesor-beneficios li {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 12px;
            font-size: 0.95rem;
            color: rgba(255, 255, 255, 0.9);
        }

        .asesor-beneficios li i {
            color: var(--accent);
            font-size: 0.9rem;
            min-width: 20px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .asesor-content {
                flex-direction: column;
            }

            .asesor-form {
                padding: 30px 20px;
            }

            .asesor-section h2 {
                font-size: 2.2rem;
            }

            .asesor-card {
                flex-direction: column;
                text-align: center;
                padding: 20px;
            }
        }

        .asesor-avatar-placeholder {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--accent), var(--accent-dark));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2.5rem;
            border: 3px solid var(--accent);
        }

        .form-section {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 25px;
            border-left: 4px solid var(--accent);
        }

        .form-section h4 {
            color: var(--primary-dark);
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid rgba(94, 201, 177, 0.2);
        }

        .form-row {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
        }

        .form-row .form-group {
            flex: 1;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid rgba(94, 201, 177, 0.2);
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--accent);
            outline: none;
            box-shadow: 0 0 0 3px rgba(94, 201, 177, 0.2);
        }

        .form-control-file {
            width: 100%;
            padding: 10px;
            border: 2px dashed rgba(94, 201, 177, 0.3);
            border-radius: 8px;
            background: rgba(94, 201, 177, 0.05);
        }

        .form-text {
            font-size: 0.85rem;
            margin-top: 5px;
            color: #6c757d;
        }

        /* Para selects */
        select.form-control {
            background-color: white;
            cursor: pointer;
        }

        select.form-control:disabled {
            background-color: #f8f9fa;
            cursor: not-allowed;
        }

        /* Validación */
        input:invalid,
        select:invalid {
            border-color: #ff6b6b;
        }

        input:valid,
        select:valid {
            border-color: #28a745;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <header id="header">
        <div class="container">
            <nav class="navbar">
                <a href="{{ url('/') }}" class="logo">
                    <img src="{{ asset('frontend/assets/img/logo_principal.png') }}" alt="Logo UNIP" class="logo-img">
                </a>
                <ul class="nav-links" id="navLinks">
                    <li><a href="{{ url('/') }}#inicio">Inicio</a></li>
                    <li><a href="{{ url('/') }}#programs">Programas</a></li>
                    <li><a href="{{ url('/') }}#team">Equipo</a></li>
                    <li><a href="{{ url('/') }}#sedes">Sedes</a></li>
                    <li><a href="{{ url('/') }}#contacto">Contacto</a></li>
                </ul>
                <a href="{{ url('/') }}" class="cta-button">
                    <i class="fas fa-home"></i> Volver al inicio
                </a>
                <div class="mobile-menu-toggle" id="mobileMenuToggle">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="oferta-hero">
        <div class="container">
            <div class="oferta-hero-content">
                <h1>{{ $oferta->posgrado->nombre ?? 'Programa Académico' }}</h1>
                <p>{{ $oferta->posgrado->descripcion_corta ?? 'Descripción del programa académico' }}</p>
                <div class="hero-actions">
                    <button class="btn-inscribirse"
                        onclick="document.getElementById('asesorForm').scrollIntoView({behavior: 'smooth'})">
                        <i class="fas fa-user-tie"></i> Inscripción con Asesor
                    </button>
                    <a href="#contacto" class="btn-contactar">
                        <i class="fas fa-phone-alt"></i> Contactar
                    </a>
                </div>
            </div>
            <div class="oferta-hero-image">
                @if ($oferta->portada)
                    <img src="{{ asset($oferta->portada) }}" alt="{{ $oferta->posgrado->nombre ?? 'Programa' }}">
                @else
                    <img src="{{ asset('frontend/assets/img/afiches/default.jpg') }}" alt="Programa UNIP">
                @endif
            </div>
        </div>
    </section>

    <!-- Inscripción con Asesor Personal - MEJORADO -->
    <!-- Inscripción con Asesor Personal - MEJORADO -->
    <section class="asesor-section" id="asesorForm">
        <div class="container">
            <div class="asesor-content">
                <!-- SECCIÓN DEL ASESOR - SE MANTIENE IGUAL -->
                <div class="asesor-info">
                    <h2>Inscripción con <span>Asesor Personal</span></h2>
                    <p>Completa el formulario y nuestro asesor se pondrá en contacto contigo para guiarte en todo el
                        proceso de inscripción y resolver todas tus dudas.</p>

                    <div class="asesor-card">
                        @if ($asesor->trabajador->persona->fotografia)
                            <img src="{{ asset($asesor->trabajador->persona->fotografia) }}"
                                alt="{{ $asesor->trabajador->persona->nombres }}">
                        @else
                            @if ($asesor->trabajador->persona->sexo == 'Hombre')
                                <img src="{{ asset('frontend/assets/img/personal/hombre.png') }}" alt="">
                            @else
                                <img src="{{ asset('frontend/assets/img/personal/mujer.png') }}" alt="">
                            @endif
                        @endif
                        <div class="asesor-card-info">
                            <h4>{{ $asesor->trabajador->persona->nombres }}
                                {{ $asesor->trabajador->persona->apellido_paterno }}</h4>
                            <p>{{ $asesor->cargo->nombre ?? 'Asesor Académico' }}</p>
                            @if ($asesor->trabajador->persona->celular)
                                <p><i class="fas fa-phone"></i> {{ $asesor->trabajador->persona->celular }}</p>
                            @endif
                            @if ($asesor->trabajador->persona->correo)
                                <p><i class="fas fa-envelope"></i> {{ $asesor->trabajador->persona->correo }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="asesor-beneficios">
                        <h4><i class="fas fa-star"></i> Beneficios de la asesoría personalizada</h4>
                        <ul>
                            <li><i class="fas fa-check-circle"></i> Guía paso a paso en la inscripción</li>
                            <li><i class="fas fa-check-circle"></i> Información detallada del programa</li>
                            <li><i class="fas fa-check-circle"></i> Asesoramiento financiero personalizado</li>
                            <li><i class="fas fa-check-circle"></i> Resolución de dudas inmediata</li>
                            <li><i class="fas fa-check-circle"></i> Seguimiento continuo durante tu formación</li>
                        </ul>
                    </div>
                </div>

                <!-- FORMULARIO REORGANIZADO (SOLO ESTA SECCIÓN SE MODIFICA) -->
                <div class="asesor-form">
                    <form id="formInscripcionAsesor" method="POST" action="{{ route('api.inscripcion.asesor') }}">
                        @csrf
                        <input type="hidden" name="oferta_id" value="{{ $oferta->id }}">
                        <input type="hidden" name="asesor_id" value="{{ $asesor->id }}">

                        <!-- Información Personal -->
                        <div class="form-section">
                            <h4><i class="fas fa-user"></i> Información Personal</h4>

                            <!-- Primera fila: CI, Expedido, Sexo -->
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="carnet"><i class="fas fa-id-card"></i> CI / Carnet *</label>
                                    <input type="text" id="carnet" name="carnet" required
                                        placeholder="Ej: 12345678" maxlength="15">
                                </div>

                                <div class="form-group">
                                    <label for="expedido"><i class="fas fa-map-marker-alt"></i> Expedido</label>
                                    <select id="expedido" name="expedido" class="form-control">
                                        <option value="">Seleccionar</option>
                                        <option value="LP">LP - La Paz</option>
                                        <option value="CB">CB - Cochabamba</option>
                                        <option value="SC">SC - Santa Cruz</option>
                                        <option value="OR">OR - Oruro</option>
                                        <option value="PT">PT - Potosí</option>
                                        <option value="TJ">TJ - Tarija</option>
                                        <option value="CH">CH - Chuquisaca</option>
                                        <option value="BE">BE - Beni</option>
                                        <option value="PD">PD - Pando</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="sexo"><i class="fas fa-venus-mars"></i> Sexo</label>
                                    <select id="sexo" name="sexo" class="form-control">
                                        <option value="">Seleccionar</option>
                                        <option value="Hombre">Hombre</option>
                                        <option value="Mujer">Mujer</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Segunda fila: Nombres, Apellido Paterno, Apellido Materno -->
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="nombres"><i class="fas fa-user"></i> Nombres *</label>
                                    <input type="text" id="nombres" name="nombres" required
                                        placeholder="Ingresa tus nombres">
                                </div>

                                <div class="form-group">
                                    <label for="apellido_paterno"><i class="fas fa-user"></i> Apellido Paterno
                                        *</label>
                                    <input type="text" id="apellido_paterno" name="apellido_paterno" required
                                        placeholder="Apellido paterno">
                                </div>

                                <div class="form-group">
                                    <label for="apellido_materno"><i class="fas fa-user"></i> Apellido Materno</label>
                                    <input type="text" id="apellido_materno" name="apellido_materno"
                                        placeholder="Apellido materno">
                                </div>
                            </div>

                            <!-- Tercera fila: Estado Civil, Fecha de Nacimiento -->
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="estado_civil"><i class="fas fa-heart"></i> Estado Civil</label>
                                    <select id="estado_civil" name="estado_civil" class="form-control">
                                        <option value="">Seleccionar</option>
                                        <option value="Soltero">Soltero/a</option>
                                        <option value="Casado">Casado/a</option>
                                        <option value="Divorciado">Divorciado/a</option>
                                        <option value="Viudo">Viudo/a</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="fecha_nacimiento"><i class="fas fa-birthday-cake"></i> Fecha de
                                        Nacimiento</label>
                                    <input type="date" id="fecha_nacimiento" name="fecha_nacimiento"
                                        max="{{ date('Y-m-d') }}">
                                </div>
                            </div>
                        </div>

                        <!-- Información de Contacto -->
                        <div class="form-section">
                            <h4><i class="fas fa-address-book"></i> Información de Contacto</h4>

                            <!-- Cuarta fila: Correo Electrónico, Celular -->
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="correo"><i class="fas fa-envelope"></i> Correo Electrónico *</label>
                                    <input type="email" id="correo" name="correo" required
                                        placeholder="ejemplo@correo.com">
                                </div>

                                <div class="form-group">
                                    <label for="celular"><i class="fas fa-mobile-alt"></i> Celular *</label>
                                    <input type="tel" id="celular" name="celular" required
                                        placeholder="Ej: 71234567">
                                </div>
                            </div>

                            <!-- Quinta fila: Teléfono, Dirección -->
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="telefono"><i class="fas fa-phone"></i> Teléfono</label>
                                    <input type="tel" id="telefono" name="telefono"
                                        placeholder="Teléfono fijo">
                                </div>

                                <div class="form-group">
                                    <label for="direccion"><i class="fas fa-home"></i> Dirección</label>
                                    <input type="text" id="direccion" name="direccion"
                                        placeholder="Dirección completa">
                                </div>
                            </div>
                        </div>

                        <!-- Ubicación -->
                        <div class="form-section">
                            <h4><i class="fas fa-map-marked-alt"></i> Ubicación</h4>

                            <!-- Sexta fila: Departamento, Ciudad -->
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="departamento_id"><i class="fas fa-map"></i> Departamento *</label>
                                    <select id="departamento_id" name="departamento_id" class="form-control"
                                        required>
                                        <option value="">Seleccionar departamento</option>
                                        @foreach ($departamentos as $departamento)
                                            <option value="{{ $departamento->id }}">{{ $departamento->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="ciudade_id"><i class="fas fa-city"></i> Ciudad *</label>
                                    <select id="ciudade_id" name="ciudade_id" class="form-control" required disabled>
                                        <option value="">Primero selecciona un departamento</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Términos y Condiciones -->
                        <div class="form-section">
                            <div class="checkbox-group">
                                <input type="checkbox" id="terminos" name="terminos" required>
                                <label for="terminos">
                                    Acepto los <a href="#" target="_blank" class="text-accent">términos y
                                        condiciones</a>
                                    y autorizo el tratamiento de mis datos personales para fines académicos y de
                                    contacto.
                                </label>
                            </div>

                            <div class="checkbox-group">
                                <input type="checkbox" id="newsletter" name="newsletter">
                                <label for="newsletter">
                                    Deseo recibir información sobre otros programas académicos, promociones y noticias
                                    de UNIP.
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn-asesor">
                            <i class="fas fa-paper-plane"></i> Solicitar pre-inscripción
                        </button>

                        <p style="text-align: center; margin-top: 15px; font-size: 0.9rem; color: #666;">
                            <i class="fas fa-shield-alt"></i> Tus datos están protegidos según la Ley de Protección de
                            Datos
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Información General -->
    <section class="section">
        <div class="container">
            <div class="section-title">
                <h2>Información <span>General</span></h2>
                <p>Conoce los detalles esenciales de este programa académico</p>
            </div>

            <div class="info-grid">
                <div class="info-card">
                    <i class="fas fa-university"></i>
                    <h3>Institución</h3>
                    <p>{{ $oferta->posgrado->convenio->nombre ?? 'UNIP' }}</p>
                </div>

                <div class="info-card">
                    <i class="fas fa-map-marker-alt"></i>
                    <h3>Sede</h3>
                    <p>{{ $oferta->sucursal->nombre ?? 'Sede no especificada' }}</p>
                </div>

                <div class="info-card">
                    <i class="fas fa-clock"></i>
                    <h3>Duración</h3>
                    <p>
                        @if (isset($oferta->posgrado->duracion_numero) && isset($oferta->posgrado->duracion_unidad))
                            {{ $oferta->posgrado->duracion_numero }} {{ $oferta->posgrado->duracion_unidad }}
                        @else
                            Duración no especificada
                        @endif
                    </p>
                </div>

                <div class="info-card">
                    <i class="fas fa-graduation-cap"></i>
                    <h3>Modalidad</h3>
                    <p>{{ $oferta->modalidad->nombre ?? 'Modalidad no especificada' }}</p>
                </div>

                <div class="info-card">
                    <i class="fas fa-calendar-alt"></i>
                    <h3>Inicio</h3>
                    <p>{{ $oferta->fecha_inicio_programa->format('d M Y') }}</p>
                </div>

                <div class="info-card">
                    <i class="fas fa-users"></i>
                    <h3>Cupos Disponibles</h3>
                    <p>{{ $oferta->inscripciones->count() }} inscritos</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Descripción del Programa -->
    <section class="descripcion-section">
        <div class="container">
            <div class="section-title">
                <h2>Descripción del <span>Programa</span></h2>
                <p>Información detallada sobre los objetivos y destinatarios de este programa</p>
            </div>

            <div class="descripcion-grid">
                <div class="descripcion-card">
                    <h3><i class="fas fa-bullseye"></i> Objetivo</h3>
                    <p>{{ $oferta->posgrado->objetivo ?? 'Objetivo no especificado.' }}</p>
                </div>

                <div class="descripcion-card">
                    <h3><i class="fas fa-user-graduate"></i> Dirigido a</h3>
                    <p>{{ $oferta->posgrado->dirigido_a ?? 'Dirigido a profesionales interesados en el área.' }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Módulos Académicos -->
    <section class="section">
        <div class="container">
            <div class="section-title">
                <h2>Módulos <span>Académicos</span></h2>
                <p>Estructura curricular del programa</p>
            </div>

            <div class="modulos-list">
                @forelse($oferta->modulos as $modulo)
                    <div class="modulo-item">
                        <div class="modulo-header">
                            <div class="modulo-title">
                                <h3>Módulo {{ $modulo->n_modulo }}: {{ $modulo->nombre }}</h3>
                                <p><i class="far fa-calendar-alt"></i>
                                    {{ \Carbon\Carbon::parse($modulo->fecha_inicio)->format('d M Y') }} -
                                    {{ \Carbon\Carbon::parse($modulo->fecha_fin)->format('d M Y') }}
                                </p>
                            </div>
                        </div>

                        @if ($modulo->docente)
                            <div class="modulo-docente">
                                @if ($modulo->docente->persona->fotografia)
                                    <img src="{{ asset($modulo->docente->persona->fotografia) }}"
                                        alt="{{ $modulo->docente->persona->nombres }}">
                                @else
                                    <img src="{{ asset('frontend/assets/img/personal/default.png') }}"
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
                                        {{ \Carbon\Carbon::parse($horario->fecha)->format('d M Y') }} |
                                        {{ $horario->hora_inicio }} - {{ $horario->hora_fin }}
                                        <span class="badge badge-{{ strtolower($horario->estado) }}">
                                            {{ $horario->estado }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> No hay módulos registrados para este programa.
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Opciones de Financiamiento -->
    <section class="section">
        <div class="container">
            <div class="section-title">
                <h2>Opciones de <span>Financiamiento</span></h2>
                <p>Selecciona el plan de pago que mejor se adapte a tus necesidades</p>
            </div>

            <div class="planes-pago-grid">
                @if ($planesCredito->isNotEmpty())
                    <div class="plan-credito-card">
                        <div class="plan-header">
                            <div class="plan-icon">
                                <i class="fas fa-credit-card"></i>
                            </div>
                            <h3>Plan al Crédito</h3>
                            <div class="plan-badge">RECOMENDADO</div>
                        </div>

                        <div class="plan-cuota">
                            <div class="cuota-label">CUOTA MENSUAL</div>
                            <div class="cuota-monto">Bs. {{ number_format($totalMensualCredito, 0, ',', '.') }}</div>
                            <div class="cuota-periodo">Por {{ $totalCuotasCredito }} meses</div>
                        </div>

                        <div class="plan-detalle">
                            <div class="detalle-item">
                                <i class="fas fa-money-bill-wave"></i>
                                <div>
                                    <div class="detalle-label">Inversión Total</div>
                                    <div class="detalle-valor">Bs.
                                        {{ number_format($totalProgramaCredito, 0, ',', '.') }}</div>
                                </div>
                            </div>
                            <div class="detalle-item">
                                <i class="fas fa-calendar-check"></i>
                                <div>
                                    <div class="detalle-label">Duración del Financiamiento</div>
                                    <div class="detalle-valor">{{ $totalCuotasCredito }} meses</div>
                                </div>
                            </div>
                            <div class="detalle-item">
                                <i class="fas fa-percentage"></i>
                                <div>
                                    <div class="detalle-label">Tasa de interés</div>
                                    <div class="detalle-valor text-success">0%</div>
                                </div>
                            </div>
                        </div>

                        @if ($totalContado > 0)
                            <div class="plan-nota">
                                <i class="fas fa-lightbulb"></i>
                                <strong>¡Ahorra Bs. {{ number_format($ahorroMensual, 0, ',', '.') }}
                                    mensuales!</strong>
                                Comparado con el pago al contado de Bs. {{ number_format($totalContado, 0, ',', '.') }}
                            </div>
                        @endif

                        <div class="conceptos-detalle">
                            <h4>Conceptos incluidos</h4>
                            <div class="conceptos-list">
                                @foreach ($planesCredito as $planConcepto)
                                    <div class="concepto-item">
                                        <div class="concepto-nombre">
                                            <i class="fas fa-check-circle"></i>
                                            {{ $planConcepto->concepto->nombre ?? 'Concepto' }}
                                        </div>
                                        <div class="concepto-precio">
                                            Bs. {{ number_format($planConcepto->pago_bs, 0, ',', '.') }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <div class="plan-info-card">
                    <div class="info-icon">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <h4>Asesoría Financiera Personalizada</h4>
                    <p>Nuestro asesor está disponible para ayudarte a elegir la mejor opción de pago según tu situación
                        financiera.</p>

                    <ul>
                        <li><i class="fas fa-check"></i> Análisis de perfil financiero</li>
                        <li><i class="fas fa-check"></i> Opciones de becas y descuentos</li>
                        <li><i class="fas fa-check"></i> Planes personalizados</li>
                        <li><i class="fas fa-check"></i> Asesoramiento continuo</li>
                    </ul>

                    <button class="btn-inscribirse full-width"
                        onclick="document.getElementById('asesorForm').scrollIntoView({behavior: 'smooth'})">
                        <i class="fas fa-user-tie"></i> Hablar con asesor
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Responsables del Programa -->
    @if ($responsableMarketing || $responsableAcademico)
        <section class="section">
            <div class="container">
                <div class="section-title">
                    <h2>Responsables del <span>Programa</span></h2>
                    <p>Conoce al equipo que hará posible tu experiencia académica</p>
                </div>

                <div class="responsables-grid">
                    @if ($responsableMarketing)
                        <div class="responsable-card">
                            @if ($responsableMarketing->trabajador->persona->fotografia)
                                <img src="{{ asset($responsableMarketing->trabajador->persona->fotografia) }}"
                                    alt="{{ $responsableMarketing->trabajador->persona->nombres }}">
                            @else
                                @if ($responsableMarketing->trabajador->persona->sexo == 'Hombre')
                                    <img src="{{ asset('frontend/assets/img/personal/hombre.png') }}" alt="">
                                @else
                                    <img src="{{ asset('frontend/assets/img/personal/mujer.png') }}" alt="">
                                @endif
                            @endif
                            <div class="responsable-info">
                                <h3>{{ $responsableMarketing->trabajador->persona->nombres }}
                                    {{ $responsableMarketing->trabajador->persona->apellido_paterno }}</h3>
                                <p>{{ $responsableMarketing->cargo->nombre ?? 'Responsable' }}</p>
                                <div class="responsable-contact">
                                    @if ($responsableMarketing->trabajador->persona->correo)
                                        <a href="mailto:{{ $responsableMarketing->trabajador->persona->correo }}">
                                            <i class="fas fa-envelope"></i>
                                        </a>
                                    @endif
                                    @if ($responsableMarketing->trabajador->persona->celular)
                                        <a
                                            href="https://wa.me/591{{ $responsableMarketing->trabajador->persona->celular }}">
                                            <i class="fab fa-whatsapp"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($responsableAcademico)
                        <div class="responsable-card">
                            @if ($responsableAcademico->trabajador->persona->fotografia)
                                <img src="{{ asset($responsableAcademico->trabajador->persona->fotografia) }}"
                                    alt="{{ $responsableAcademico->trabajador->persona->nombres }}">
                            @else
                                @if ($responsableAcademico->trabajador->persona->sexo == 'Hombre')
                                    <img src="{{ asset('frontend/assets/img/personal/hombre.png') }}" alt="">
                                @else
                                    <img src="{{ asset('frontend/assets/img/personal/mujer.png') }}" alt="">
                                @endif
                            @endif
                            <div class="responsable-info">
                                <h3>{{ $responsableAcademico->trabajador->persona->nombres }}
                                    {{ $responsableAcademico->trabajador->persona->apellido_paterno }}</h3>
                                <p>{{ $responsableAcademico->cargo->nombre ?? 'Responsable' }}</p>
                                <div class="responsable-contact">
                                    @if ($responsableAcademico->trabajador->persona->correo)
                                        <a href="mailto:{{ $responsableAcademico->trabajador->persona->correo }}">
                                            <i class="fas fa-envelope"></i>
                                        </a>
                                    @endif
                                    @if ($responsableAcademico->trabajador->persona->celular)
                                        <a
                                            href="https://wa.me/591{{ $responsableAcademico->trabajador->persona->celular }}">
                                            <i class="fab fa-whatsapp"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    @endif



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
                        <li><a href="{{ url('/') }}#inicio">Inicio</a></li>
                        <li><a href="{{ url('/') }}#programs">Programas</a></li>
                        <li><a href="{{ url('/') }}#team">Equipo Académico</a></li>
                        <li><a href="{{ url('/') }}#sedes">Nuestras Sedes</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Programas Destacados</h3>
                    <ul class="footer-links">
                        <li><a href="{{ url('/') }}#programas-detalle">Diplomados</a></li>
                        <li><a href="{{ url('/') }}#programas-detalle">Maestrías</a></li>
                        <li><a href="{{ url('/') }}#programas-detalle">Cursos Especializados</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Contacto Directo</h3>
                    <ul class="footer-contact">
                        <li>
                            <i class="fas fa-map-marker-alt"></i>
                            {{ $oferta->sucursal->direccion ?? 'Dirección no disponible' }}
                        </li>
                        <li>
                            <i class="fas fa-phone"></i>
                            {{ $oferta->sucursal->telefono ?? 'Teléfono no disponible' }}
                        </li>
                        <li>
                            <i class="fas fa-envelope"></i>
                            info@unip.edu
                        </li>
                        <li>
                            <i class="fas fa-clock"></i>
                            Lun - Vie: 8:00 - 18:00
                        </li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} UNIP. Todos los derechos reservados. | <a href="#"
                        style="color: rgba(255,255,255,0.6); text-decoration: underline;">Términos y
                        Condiciones</a> | <a href="#"
                        style="color: rgba(255,255,255,0.6); text-decoration: underline;">Política de
                        Privacidad</a></p>
            </div>
        </div>
    </footer>

    <!-- Botones flotantes -->
    <div class="sticky-inscripcion">
        <button class="sticky-btn whatsapp-btn"
            onclick="window.open('https://wa.me/591{{ $asesor->trabajador->persona->celular ?? '' }}', '_blank')">
            <i class="fab fa-whatsapp"></i> WhatsApp
        </button>
        <button class="sticky-btn"
            onclick="document.getElementById('asesorForm').scrollIntoView({behavior: 'smooth'})">
            <i class="fas fa-user-tie"></i> Asesoría
        </button>
    </div>

    <script src="{{ asset('frontend/assets/js/script.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ============================================
            // 1. CARGAR CIUDADES SEGÚN DEPARTAMENTO
            // ============================================
            const departamentoSelect = document.getElementById('departamento_id');
            const ciudadSelect = document.getElementById('ciudade_id');

            // Datos de ciudades por departamento (pasados desde el controlador)
            const ciudadesPorDepartamento = @json($ciudadesPorDepartamento);

            departamentoSelect.addEventListener('change', function() {
                const departamentoId = this.value;

                // Limpiar y habilitar select de ciudades
                ciudadSelect.innerHTML = '<option value="">Seleccionar ciudad</option>';
                ciudadSelect.disabled = !departamentoId;

                if (departamentoId) {
                    // Filtrar ciudades por departamento
                    const ciudades = ciudadesPorDepartamento.filter(ciudad =>
                        ciudad.departamento_id == departamentoId
                    );

                    // Ordenar ciudades alfabéticamente
                    ciudades.sort((a, b) => a.nombre.localeCompare(b.nombre));

                    // Agregar opciones
                    ciudades.forEach(ciudad => {
                        const option = document.createElement('option');
                        option.value = ciudad.id;
                        option.textContent = ciudad.nombre;
                        ciudadSelect.appendChild(option);
                    });
                }
            });

            // ============================================
            // 2. MANEJO DEL ENVÍO DEL FORMULARIO PÚBLICO
            // ============================================
            const form = document.getElementById('formInscripcionAsesor');

            form.addEventListener('submit', async function(e) {
                e.preventDefault();

                // Referencias a elementos importantes
                const submitBtn = form.querySelector('button[type="submit"]');
                const originalBtnText = submitBtn.innerHTML;

                // 1. Validación de campos requeridos
                const requiredFields = form.querySelectorAll('[required]');
                let isValid = true;
                let firstInvalidField = null;

                // Resetear estilos de validación
                requiredFields.forEach(field => {
                    field.style.borderColor = '#e0e0e0';
                });

                // Validar cada campo requerido
                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        isValid = false;
                        field.style.borderColor = '#ff6b6b';

                        if (!firstInvalidField) {
                            firstInvalidField = field;
                        }
                    }
                });

                // Validar formato de correo electrónico
                const emailField = document.getElementById('correo');
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (emailField.value && !emailRegex.test(emailField.value)) {
                    isValid = false;
                    emailField.style.borderColor = '#ff6b6b';
                    alert('Por favor, ingresa un correo electrónico válido.');
                }

                // Validar que se haya seleccionado una ciudad
                if (ciudadSelect.disabled || !ciudadSelect.value) {
                    isValid = false;
                    ciudadSelect.style.borderColor = '#ff6b6b';
                    alert('Por favor, selecciona una ciudad.');
                }

                // Validar fecha de nacimiento (no puede ser futura)
                const fechaNacimiento = document.getElementById('fecha_nacimiento');
                if (fechaNacimiento.value) {
                    const fecha = new Date(fechaNacimiento.value);
                    const hoy = new Date();
                    if (fecha > hoy) {
                        isValid = false;
                        fechaNacimiento.style.borderColor = '#ff6b6b';
                        alert('La fecha de nacimiento no puede ser futura.');
                    }
                }

                // Validar términos y condiciones
                const terminosCheck = document.getElementById('terminos');
                if (!terminosCheck.checked) {
                    isValid = false;
                    terminosCheck.style.outline = '2px solid #ff6b6b';
                    alert('Debes aceptar los términos y condiciones para continuar.');
                }

                // Si hay campos inválidos, mostrar alerta y enfocar el primero
                if (!isValid) {
                    if (firstInvalidField) {
                        firstInvalidField.focus();
                    }
                    alert('Por favor, completa todos los campos requeridos correctamente.');
                    return;
                }

                // 2. Deshabilitar botón y mostrar loading
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';

                // 3. Preparar datos del formulario
                const formData = new FormData(form);

                // Agregar token CSRF si no está incluido automáticamente
                const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute(
                    'content') || '{{ csrf_token() }}';
                formData.append('_token', token);

                // 4. Enviar datos via AJAX
                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': token
                        }
                    });

                    const result = await response.json();

                    if (response.ok) {
                        // ÉXITO: Mostrar mensaje de éxito con SweetAlert2
                        Swal.fire({
                            icon: 'success',
                            title: '¡Pre-inscripción Exitosa!',
                            html: `
                            <div class="text-left" style="font-size: 1rem;">
                                <p style="margin-bottom: 15px;">${result.message}</p>
                                <hr style="margin: 15px 0;">
                                <p><strong>Estudiante:</strong> ${result.data?.estudiante?.nombre_completo || 'No disponible'}</p>
                                <p><strong>Carnet:</strong> ${result.data?.estudiante?.carnet || 'No disponible'}</p>
                                <p><strong>Programa:</strong> ${result.data?.programa?.nombre || 'No disponible'}</p>
                                <p><strong>Código:</strong> ${result.data?.programa?.codigo || 'No disponible'}</p>
                                <p><strong>Sucursal:</strong> ${result.data?.programa?.sucursal || 'No disponible'}</p>
                                <hr style="margin: 15px 0;">
                                <p><strong>Asesor asignado:</strong> ${result.data?.asesor?.nombre || 'No disponible'}</p>
                                <p><strong>Teléfono del asesor:</strong> ${result.data?.asesor?.celular || 'No disponible'}</p>
                                <p><strong>Estado:</strong> <span style="background-color: #032A4A; color: white; padding: 2px 8px; border-radius: 4px;">${result.data?.estado || 'Pre-Inscrito'}</span></p>
                                <p><strong>Fecha y hora:</strong> ${result.data?.fecha_registro || 'No disponible'}</p>
                            </div>
                        `,
                            confirmButtonText: 'Aceptar',
                            confirmButtonColor: '#5EC9B1',
                            width: '600px',
                            customClass: {
                                popup: 'animated bounceIn'
                            }
                        }).then(() => {
                            // Limpiar formulario después del éxito
                            form.reset();

                            // Resetear select de ciudad y departamento
                            ciudadSelect.innerHTML =
                                '<option value="">Primero selecciona un departamento</option>';
                            ciudadSelect.disabled = true;
                            departamentoSelect.value = '';

                            // Restablecer checkboxes
                            document.getElementById('terminos').checked = false;
                            document.getElementById('newsletter').checked = false;

                            // Restablecer estilos de validación
                            requiredFields.forEach(field => {
                                field.style.borderColor = '#e0e0e0';
                            });
                        });

                    } else {
                        // ERROR: Mostrar mensaje de error según el tipo
                        if (result.message && result.message.includes('Ya se tiene registro')) {
                            // Ya está inscrito
                            Swal.fire({
                                icon: 'info',
                                title: 'Registro Existente',
                                html: `
                                <div style="text-align: left;">
                                    <p>${result.message}</p>
                                    <hr style="margin: 15px 0;">
                                    <p><strong>Estado actual:</strong> ${result.estado || 'No disponible'}</p>
                                    <p><strong>Fecha de registro:</strong> ${result.fecha_registro || 'No disponible'}</p>
                                    <p style="margin-top: 15px;">Nuestro asesor se pondrá en contacto contigo para brindarte más información.</p>
                                </div>
                            `,
                                confirmButtonText: 'Entendido',
                                confirmButtonColor: '#032A4A',
                                width: '500px'
                            });
                        } else if (result.errors) {
                            // Errores de validación del servidor
                            let errorMessages = '<ul style="text-align: left; padding-left: 20px;">';
                            Object.values(result.errors).forEach(errors => {
                                errors.forEach(error => {
                                    errorMessages += `<li>${error}</li>`;
                                });
                            });
                            errorMessages += '</ul>';

                            Swal.fire({
                                icon: 'error',
                                title: 'Error de Validación',
                                html: errorMessages,
                                confirmButtonText: 'Corregir',
                                confirmButtonColor: '#FF6B6B'
                            });
                        } else {
                            // Otro error genérico
                            Swal.fire({
                                icon: 'error',
                                title: 'Error en la Pre-inscripción',
                                text: result.message ||
                                    'Ocurrió un error inesperado. Por favor, inténtalo nuevamente.',
                                confirmButtonText: 'Reintentar',
                                confirmButtonColor: '#FF6B6B'
                            });
                        }
                    }
                } catch (error) {
                    console.error('Error en la solicitud:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de Conexión',
                        text: 'No se pudo conectar con el servidor. Verifica tu conexión a internet e inténtalo nuevamente.',
                        confirmButtonText: 'Reintentar',
                        confirmButtonColor: '#FF6B6B'
                    });
                } finally {
                    // Restaurar botón siempre
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                }
            });

            // ============================================
            // 3. VALIDACIÓN EN TIEMPO REAL
            // ============================================

            // Remover estilo de error al empezar a escribir
            form.querySelectorAll('input, select').forEach(field => {
                field.addEventListener('input', function() {
                    this.style.borderColor = '#e0e0e0';
                });
                field.addEventListener('change', function() {
                    this.style.borderColor = '#e0e0e0';
                });
            });

            // Validación de correo en tiempo real
            const emailField = document.getElementById('correo');
            if (emailField) {
                emailField.addEventListener('blur', function() {
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (this.value && !emailRegex.test(this.value)) {
                        this.style.borderColor = '#ff6b6b';
                        // Mostrar mensaje pequeño debajo del campo
                        let errorMsg = this.parentNode.querySelector('.email-error');
                        if (!errorMsg) {
                            errorMsg = document.createElement('div');
                            errorMsg.className = 'email-error';
                            errorMsg.style.color = '#ff6b6b';
                            errorMsg.style.fontSize = '0.85rem';
                            errorMsg.style.marginTop = '5px';
                            this.parentNode.appendChild(errorMsg);
                        }
                        errorMsg.textContent = 'Formato de correo inválido';
                    } else {
                        const errorMsg = this.parentNode.querySelector('.email-error');
                        if (errorMsg) {
                            errorMsg.remove();
                        }
                    }
                });
            }

            // ============================================
            // 4. MEJORAS DE USABILIDAD
            // ============================================

            // Formatear carnet automáticamente (solo números)
            const carnetField = document.getElementById('carnet');
            if (carnetField) {
                carnetField.addEventListener('input', function(e) {
                    // Remover cualquier carácter no numérico
                    let value = this.value.replace(/\D/g, '');

                    // Limitar a 15 dígitos
                    if (value.length > 15) {
                        value = value.substring(0, 15);
                    }

                    this.value = value;
                });
            }

            // Formatear celular automáticamente
            const celularField = document.getElementById('celular');
            if (celularField) {
                celularField.addEventListener('input', function(e) {
                    let value = this.value.replace(/\D/g, '');

                    // Formato para Bolivia: 7XXXXXXXX
                    if (value.length > 8) {
                        value = value.substring(0, 8);
                    }

                    // Agregar formato visual: 7123 4567
                    if (value.length >= 4) {
                        value = value.replace(/(\d{4})(\d{0,4})/, '$1 $2');
                    }

                    this.value = value.trim();
                });
            }

            // Formatear teléfono fijo
            const telefonoField = document.getElementById('telefono');
            if (telefonoField) {
                telefonoField.addEventListener('input', function(e) {
                    let value = this.value.replace(/\D/g, '');

                    // Formato para teléfono fijo en Bolivia
                    if (value.length > 8) {
                        value = value.substring(0, 8);
                    }

                    // Agregar formato visual: 4-1234567
                    if (value.length >= 1) {
                        value = value.replace(/(\d{1})(\d{0,7})/, '$1-$2');
                    }

                    this.value = value.trim();
                });
            }

            // Auto-rellenar nombres y apellidos en formato título
            const nombreFields = ['nombres', 'apellido_paterno', 'apellido_materno'];
            nombreFields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (field) {
                    field.addEventListener('blur', function() {
                        // Convertir a mayúsculas solo la primera letra de cada palabra
                        if (this.value) {
                            this.value = this.value.toLowerCase()
                                .split(' ')
                                .map(word => word.charAt(0).toUpperCase() + word.slice(1))
                                .join(' ');
                        }
                    });
                }
            });

            // ============================================
            // 5. MANEJO DE BOTONES FLOTANTES
            // ============================================

            // Botón de WhatsApp flotante
            const whatsappBtn = document.querySelector('.whatsapp-btn');
            if (whatsappBtn) {
                whatsappBtn.addEventListener('click', function() {
                    const asesorCelular = '{{ $asesor->trabajador->persona->celular ?? '' }}';
                    if (asesorCelular) {
                        // Limpiar número (remover espacios, guiones, etc.)
                        const celularLimpio = asesorCelular.replace(/\D/g, '');
                        window.open(`https://wa.me/591${celularLimpio}`, '_blank');
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'WhatsApp no disponible',
                            text: 'El asesor no tiene número de WhatsApp registrado.',
                            confirmButtonText: 'Entendido'
                        });
                    }
                });
            }

            // Botón flotante de asesoría
            const asesoriaBtn = document.querySelector('.sticky-btn:not(.whatsapp-btn)');
            if (asesoriaBtn) {
                asesoriaBtn.addEventListener('click', function() {
                    document.getElementById('asesorForm').scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                });
            }

            // ============================================
            // 6. SCROLL SUAVE Y OTROS EFECTOS
            // ============================================

            // Efecto de scroll suave para enlaces internos
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('href');
                    if (targetId === '#') return;

                    const targetElement = document.querySelector(targetId);
                    if (targetElement) {
                        window.scrollTo({
                            top: targetElement.offsetTop - 100,
                            behavior: 'smooth'
                        });
                    }
                });
            });

            // Efecto de header al hacer scroll
            const header = document.getElementById('header');
            if (header) {
                window.addEventListener('scroll', function() {
                    if (window.scrollY > 100) {
                        header.classList.add('scrolled');
                    } else {
                        header.classList.remove('scrolled');
                    }
                });
            }

            // ============================================
            // 7. CONFIRMACIÓN ANTES DE CERRAR PÁGINA
            // ============================================
            let formChanged = false;

            // Detectar cambios en el formulario
            form.querySelectorAll('input, select, textarea').forEach(element => {
                element.addEventListener('input', () => formChanged = true);
                element.addEventListener('change', () => formChanged = true);
            });

            // Advertir antes de cerrar si hay cambios sin guardar
            window.addEventListener('beforeunload', (event) => {
                if (formChanged) {
                    event.preventDefault();
                    event.returnValue = 'Tienes cambios sin guardar. ¿Estás seguro de querer salir?';
                    return 'Tienes cambios sin guardar. ¿Estás seguro de querer salir?';
                }
            });

            // Resetear flag cuando se envía el formulario
            form.addEventListener('submit', () => formChanged = false);

            // ============================================
            // 8. FUNCIONES AUXILIARES
            // ============================================

            // Función para generar mensaje de confirmación
            function generarConfirmacion() {
                const nombres = document.getElementById('nombres').value;
                const carnet = document.getElementById('carnet').value;
                const programa = '{{ $oferta->posgrado->nombre ?? 'Programa' }}';

                return `¿Confirmas que los datos ingresados son correctos?\n\n` +
                    `Nombre: ${nombres}\n` +
                    `CI: ${carnet}\n` +
                    `Programa: ${programa}`;
            }

            // Mostrar información adicional al hacer clic en iconos de ayuda
            document.querySelectorAll('.form-group label i.fa-question-circle').forEach(icon => {
                icon.addEventListener('click', function(e) {
                    e.preventDefault();
                    const fieldName = this.parentNode.textContent.trim();
                    const messages = {
                        'CI / Carnet': 'Ingresa tu número de carnet de identidad sin espacios ni guiones.',
                        'Expedido': 'Selecciona el departamento donde fue expedido tu carnet.',
                        'Sexo': 'Selecciona tu género.',
                        'Nombres': 'Ingresa tus nombres completos.',
                        'Apellido Paterno': 'Ingresa tu primer apellido.',
                        'Apellido Materno': 'Ingresa tu segundo apellido (opcional).',
                        'Estado Civil': 'Selecciona tu estado civil actual.',
                        'Fecha de Nacimiento': 'Ingresa tu fecha de nacimiento.',
                        'Correo Electrónico': 'Ingresa un correo electrónico válido donde podamos contactarte.',
                        'Celular': 'Ingresa tu número de celular (8 dígitos).',
                        'Teléfono': 'Ingresa tu número de teléfono fijo (opcional).',
                        'Dirección': 'Ingresa tu dirección completa de residencia.',
                        'Departamento': 'Selecciona el departamento donde resides.',
                        'Ciudad': 'Selecciona la ciudad donde resides.'
                    };

                    const message = messages[fieldName] || 'Información del campo.';
                    Swal.fire({
                        icon: 'info',
                        title: fieldName,
                        text: message,
                        confirmButtonText: 'Entendido',
                        confirmButtonColor: '#032A4A'
                    });
                });
            });

            // ============================================
            // 9. INICIALIZACIÓN ADICIONAL
            // ============================================

            // Verificar si hay parámetros de error en la URL
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('success')) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Gracias por tu interés!',
                    text: 'Tu pre-inscripción ha sido registrada exitosamente.',
                    confirmButtonText: 'Aceptar'
                });

                // Limpiar parámetros de la URL
                window.history.replaceState({}, document.title, window.location.pathname);
            }

            // Inicializar tooltips (si usas Bootstrap)
            if (typeof bootstrap !== 'undefined') {
                const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            }
        });
    </script>
</body>

</html>
