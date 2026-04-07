<style>
    .spin {
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }

    /* Plan de pago modal styles */
    .plan-card {
        border-left: 4px solid var(--ofertas-primary, var(--bs-primary));
        transition: transform 0.2s ease;
    }

    .plan-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .concepto-item {
        border-bottom: 1px solid #f0f0f0;
        padding: 0.75rem 0;
    }

    .concepto-item:last-child {
        border-bottom: none;
    }

    .cuota-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }

    .monto-total {
        font-weight: 600;
        color: var(--ofertas-success, var(--bs-success));
    }

    .plan-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-bottom: 1px solid #dee2e6;
    }

    .accordion-button:not(.collapsed) {
        background-color: rgba(var(--bs-primary-rgb), 0.1);
        color: var(--bs-primary);
    }

    .accordion-button:focus {
        box-shadow: 0 0 0 0.25rem rgba(var(--bs-primary-rgb), 0.25);
    }

    .table-group-divider {
        border-top-color: var(--ofertas-primary, var(--bs-primary));
    }

    .form-select {
        background-color: #fff;
        border: 1px solid #ced4da;
        transition: border-color 0.15s ease-in-out;
    }

    .form-select:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }

    .btn-group .btn:hover {
        z-index: 2;
        transform: none;
    }

    .input-group-sm .input-group-text {
        font-size: 0.875rem;
    }

    .concepto-select-editar,
    .n-cuotas-editar,
    .monto-cuota-editar {
        min-width: 100px;
    }

    .eliminarConceptoBtn:hover {
        background-color: #dc3545;
        color: white;
    }

    .agregarConceptoPlanBtn:hover {
        background-color: #198754;
        color: white;
    }

    .loading {
        opacity: 0.7;
        pointer-events: none;
        position: relative;
    }

    .loading::after {
        content: 'Cargando...';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: white;
        padding: 10px 20px;
        border-radius: 5px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        z-index: 1000;
    }

    /* Phase badge color override */
    .fase-badge {
        transition: background-color 0.2s ease;
    }
</style>
