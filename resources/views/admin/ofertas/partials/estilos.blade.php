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

    .plan-card {
        border-left: 4px solid var(--bs-primary);
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
        color: var(--bs-success);
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
        border-top-color: var(--bs-primary);
    }

    .card {
        border-radius: 10px;
        overflow: hidden;
    }

    .card-header {
        border-bottom: none;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.05);
    }

    .table tfoot tr {
        background-color: #f8f9fa;
    }

    .badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }

    .border-primary {
        border-width: 2px !important;
    }

    .border-secondary {
        border-width: 1px !important;
    }

    .card-body.border-dashed {
        border-style: dashed !important;
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

    .btn-outline-secondary:hover {
        background-color: #6c757d;
        border-color: #6c757d;
        color: white;
    }

    .table-hover tbody tr:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }

    .btn-sm {
        border-radius: 4px !important;
        font-weight: 500 !important;
    }

    .btn.btn-teal {
        background-color: #20c997 !important;
        border-color: #1baa7e !important;
        color: #fff !important;
    }

    .btn.btn-teal:hover {
        background-color: #1baa7e !important;
        border-color: #18966e !important;
    }

    .btn.btn-purple {
        background-color: #6f42c1 !important;
        border-color: #5e33a6 !important;
        color: #fff !important;
    }

    .btn.btn-purple:hover {
        background-color: #5e33a6 !important;
        border-color: #4d2a8b !important;
    }

    .btn.btn-orange {
        background-color: #fd7e14 !important;
        border-color: #e56b00 !important;
        color: #fff !important;
    }

    .btn.btn-orange:hover {
        background-color: #e56b00 !important;
        border-color: #cc5f00 !important;
    }

    .btn.btn-indigo {
        background-color: #6610f2 !important;
        border-color: #560bd0 !important;
        color: #fff !important;
    }

    .btn.btn-indigo:hover {
        background-color: #560bd0 !important;
    }

    .badge {
        font-weight: 500;
        letter-spacing: 0.3px;
    }

    .table>tbody>tr>td {
        vertical-align: middle;
        padding: 8px 8px;
    }

    .acciones-celda .btn-sm {
        height: 28px;
        min-width: 30px;
        padding: 0.15rem 0.4rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .acciones-celda .btn-sm i {
        font-size: 13px;
        line-height: 1;
    }

    .btn-group .btn:hover {
        z-index: 2;
        transform: none;
    }

    .table img {
        border-radius: 4px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .pagination .page-link {
        border-radius: 4px;
        margin: 0 3px;
        font-weight: 500;
    }

    .table tbody tr td[colspan] {
        background-color: #f8f9fa;
    }

    .convenio-img {
        width: 40px;
        height: 40px;
        object-fit: cover;
        border-radius: 50%;
        border: 2px solid #e9ecef;
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
</style>
