<!-- Estilos -->
<style>
    .card {
        border-radius: 12px;
        border: 1px solid var(--bs-border-color);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        margin-bottom: 1.5rem;
    }

    .card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .nav-tabs-custom .nav-link {
        border: none;
        color: var(--bs-gray-600);
        font-weight: 500;
        padding: 12px 20px;
        transition: all 0.2s;
    }

    .nav-tabs-custom .nav-link.active {
        color: var(--bs-primary);
        border-bottom: 3px solid var(--bs-primary);
        background-color: transparent;
    }

    .nav-tabs-custom .nav-link:hover {
        color: var(--bs-primary);
    }

    .avatar-title {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .badge {
        font-weight: 500;
        letter-spacing: 0.3px;
    }

    .progress {
        border-radius: 6px;
    }

    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(var(--bs-primary-rgb), 0.04);
    }

    .modulo-header:hover,
    .modulo-cell:hover {
        background-color: rgba(var(--bs-primary-rgb), 0.1) !important;
    }

    .fs-10 { font-size: 0.7rem !important; }
    .fs-12 { font-size: 0.75rem !important; }
    .fs-13 { font-size: 0.8125rem !important; }
    .fs-14 { font-size: 0.875rem !important; }
    .fs-16 { font-size: 1rem !important; }

    .text-pink { color: #e83e8c !important; }
    .bg-pink-subtle { background-color: rgba(232, 62, 140, 0.1) !important; }

    .fs-11 { font-size: 0.6875rem !important; }

    .table-finanzas th { white-space: nowrap; }
    .progress-thin { height: 4px !important; }

    @media (max-width: 768px) {
        .table-finanzas {
            display: block;
            overflow-x: auto;
            white-space: nowrap;
        }
        .badge-responsive {
            font-size: 0.65rem;
            padding: 0.25em 0.5em;
        }
    }

    .bg-primary-subtle { background-color: rgba(13, 110, 253, 0.1) !important; }
    .bg-success-subtle { background-color: rgba(25, 135, 84, 0.1) !important; }
    .bg-info-subtle { background-color: rgba(13, 202, 240, 0.1) !important; }
    .bg-warning-subtle { background-color: rgba(255, 193, 7, 0.1) !important; }
    .bg-danger-subtle { background-color: rgba(220, 53, 69, 0.1) !important; }

    .card-concepto {
        transition: all 0.3s ease;
    }

    .card-concepto:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .progress-bar-striped {
        background-image: linear-gradient(45deg,
            rgba(255, 255, 255, 0.15) 25%,
            transparent 25%,
            transparent 50%,
            rgba(255, 255, 255, 0.15) 50%,
            rgba(255, 255, 255, 0.15) 75%,
            transparent 75%,
            transparent);
        background-size: 1rem 1rem;
    }

    .progress-bar-animated {
        animation: progress-bar-stripes 1s linear infinite;
    }

    @keyframes progress-bar-stripes {
        0% { background-position-x: 1rem; }
    }

    .chart-container {
        position: relative;
        margin: auto;
    }

    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    .table-hover tbody tr {
        transition: background-color 0.2s ease;
    }

    .badge {
        font-weight: 500;
        letter-spacing: 0.3px;
        padding: 0.35em 0.65em;
    }

    .avatar-title i {
        font-size: 1.2em;
    }

    @media (max-width: 768px) {
        .display-4 { font-size: 2.5rem; }
        .avatar-xxl { width: 100px !important; height: 100px !important; }
    }
</style>