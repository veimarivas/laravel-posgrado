<style>
    .profile-card {
        transition: all 0.3s ease;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .profile-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
    }

    .profile-avatar {
        width: 150px;
        height: 150px;
        object-fit: cover;
        border: 5px solid #fff;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    }

    .nav-tabs .nav-link {
        border: none;
        color: #6c757d;
        font-weight: 500;
        padding: 12px 20px;
        border-bottom: 3px solid transparent;
    }

    .nav-tabs .nav-link.active {
        color: #4361ee;
        border-bottom: 3px solid #4361ee;
        background-color: transparent;
    }

    .nav-tabs .nav-link:hover:not(.active) {
        color: #4361ee;
        border-bottom: 3px solid rgba(67, 97, 238, 0.3);
    }

    .marketing-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 12px;
        border: none;
    }

    .filter-card {
        background: #f8f9fa;
        border-radius: 10px;
        border: 1px solid #e9ecef;
    }

    .stats-card {
        border-left: 4px solid;
        transition: all 0.3s ease;
        height: 100%;
    }

    .stats-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 3px 15px rgba(0, 0, 0, 0.1);
    }

    .stats-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .chart-container {
        position: relative;
        animation: fadeIn 0.5s ease-in;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .inscription-row:hover {
        background-color: rgba(67, 97, 238, 0.05);
        transform: translateX(2px);
    }

    .badge-status {
        padding: 0.35em 0.65em;
        font-size: 0.75em;
        font-weight: 600;
        border-radius: 6px;
    }

    .programa-badge {
        background-color: rgba(67, 97, 238, 0.1);
        color: #4361ee;
        border: 1px solid rgba(67, 97, 238, 0.2);
    }

    .sede-badge {
        background-color: rgba(40, 167, 69, 0.1);
        color: #28a745;
        border: 1px solid rgba(40, 167, 69, 0.2);
    }

    /* Mejoras para los filtros */
    .filter-group {
        background: white;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
        border: 1px solid #e9ecef;
    }

    .filter-label {
        font-weight: 600;
        margin-bottom: 8px;
        color: #495057;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .profile-avatar {
            width: 120px;
            height: 120px;
        }

        .stats-card .d-flex {
            flex-direction: column;
            text-align: center;
        }

        .stats-card .flex-shrink-0 {
            margin-top: 10px;
        }

        .nav-tabs {
            flex-wrap: nowrap;
            overflow-x: auto;
        }

        .nav-tabs .nav-link {
            white-space: nowrap;
            padding: 10px 15px;
        }
    }

    /* Mejoras para tablas */
    .table-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        object-fit: cover;
    }

    .table-actions {
        min-width: 100px;
    }

    /* Animaciones de carga */
    .loading-spinner {
        display: inline-block;
        width: 1rem;
        height: 1rem;
        border: 2px solid currentColor;
        border-right-color: transparent;
        border-radius: 50%;
        animation: spinner-border 0.75s linear infinite;
    }

    @keyframes spinner-border {
        to {
            transform: rotate(360deg);
        }
    }

    /* Estilos para la sección de ofertas activas */
    .oferta-card {
        transition: all 0.3s ease;
        border-left: 4px solid #4361ee;
        border-radius: 10px;
        overflow: hidden;
    }

    .oferta-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(67, 97, 238, 0.15);
    }

    .qr-container {
        width: 100px;
        height: 100px;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 10px;
        background: white;
    }

    .enlace-badge {
        font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
        font-size: 0.85em;
        background: #f8f9fa;
        border: 1px dashed #dee2e6;
        border-radius: 6px;
        padding: 4px 8px;
    }

    .programa-tag {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 0.75em;
        font-weight: 600;
        margin-right: 5px;
    }

    .sucursal-badge {
        background-color: rgba(40, 167, 69, 0.1);
        color: #28a745;
        border: 1px solid rgba(40, 167, 69, 0.2);
    }

    .fase-badge {
        background-color: rgba(255, 193, 7, 0.1);
        color: #ffc107;
        border: 1px solid rgba(255, 193, 7, 0.2);
    }

    /* Responsive para tarjetas de oferta */
    @media (max-width: 768px) {
        .oferta-card .row>div {
            margin-bottom: 10px;
        }

        .qr-container {
            width: 80px;
            height: 80px;
        }
    }

    /* Añade al inicio de la sección de estilos */
    .container-fluid {
        max-width: 1400px;
        margin: 0 auto;
    }

    /* Mejorar las tabs para muchas pestañas */
    .nav-tabs {
        flex-wrap: nowrap;
        overflow-x: auto;
        overflow-y: hidden;
        -webkit-overflow-scrolling: touch;
        -ms-overflow-style: -ms-autohiding-scrollbar;
    }

    .nav-tabs .nav-link {
        white-space: nowrap;
        min-width: 120px;
        text-align: center;
    }

    /* Mejorar responsive */
    @media (max-width: 768px) {
        .nav-tabs .nav-link span.d-none.d-md-inline {
            display: inline !important;
            font-size: 0.85em;
        }

        .nav-tabs .nav-link {
            min-width: 100px;
            padding: 10px 12px;
        }

        .stats-card h3 {
            font-size: 1.5rem;
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
        }
    }

    /* Estilos para el botón de formulario PDF */
    .btn-outline-primary:hover .ri-file-text-line {
        animation: pulse 1s infinite;
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.1);
        }

        100% {
            transform: scale(1);
        }
    }

    /* Estilos responsivos para la tabla */
    @media (max-width: 768px) {
        .table-actions .btn {
            padding: 0.25rem 0.4rem;
            font-size: 0.75rem;
        }
    }

    /* Estilo para el botón de mostrar/ocultar contraseña */
    .toggle-password {
        border-left: 0;
    }

    .toggle-password:hover {
        background-color: #e9ecef;
    }

    /* Estilo para la barra de fortaleza de contraseña */
    .progress {
        border-radius: 3px;
    }

    .progress-bar {
        transition: width 0.3s ease;
    }

    /* Nuevos estilos para planes de pago */
    .plan-card {
        border: 2px solid #e9ecef;
        border-radius: 10px;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .plan-card:hover {
        border-color: #4361ee;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(67, 97, 238, 0.1);
    }

    .plan-card.selected {
        border-color: #4361ee;
        background-color: rgba(67, 97, 238, 0.05);
    }

    .plan-card .plan-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 15px;
        border-radius: 8px 8px 0 0;
    }

    .plan-card .plan-price {
        font-size: 2rem;
        font-weight: bold;
        color: #4361ee;
    }

    .plan-feature-list {
        list-style: none;
        padding: 0;
    }

    .plan-feature-list li {
        padding: 8px 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .plan-feature-list li:last-child {
        border-bottom: none;
    }

    /* Estilos para el modal de planes */
    .plan-option {
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 10px;
        transition: all 0.2s ease;
    }

    .plan-option:hover {
        background-color: #f8f9fa;
        border-color: #4361ee;
    }

    .plan-option.selected {
        background-color: rgba(67, 97, 238, 0.1);
        border-color: #4361ee;
    }

    /* Botón especial para generar enlace con plan */
    .btn-plan-generator {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 6px 12px;
        border-radius: 6px;
        transition: all 0.3s ease;
    }

    .btn-plan-generator:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    /* Estilo para el enlace generado */
    .generated-link {
        background: #f8f9fa;
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        padding: 15px;
        font-family: 'Monaco', 'Menlo', monospace;
        font-size: 14px;
        word-break: break-all;
    }
</style>
