<style>
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

    .card {
        border-radius: 12px;
        border: 1px solid var(--bs-border-color);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .avatar-title {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .text-purple {
        color: #6f42c1 !important;
    }

    .fs-12 {
        font-size: 0.75rem !important;
    }

    .fs-14 {
        font-size: 0.875rem !important;
    }

    .fs-16 {
        font-size: 1rem !important;
    }

    .documento-item {
        border-bottom: 1px solid var(--bs-border-color);
        padding-bottom: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .documento-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
        margin-bottom: 0;
    }

    .badge-success {
        background-color: rgba(25, 135, 84, 0.1) !important;
        color: #198754 !important;
        border: 1px solid rgba(25, 135, 84, 0.2);
    }

    .badge-warning {
        background-color: rgba(255, 193, 7, 0.1) !important;
        color: #ffc107 !important;
        border: 1px solid rgba(255, 193, 7, 0.2);
    }

    .badge-danger {
        background-color: rgba(220, 53, 69, 0.1) !important;
        color: #dc3545 !important;
        border: 1px solid rgba(220, 53, 69, 0.2);
    }

    .progress {
        border-radius: 10px;
        overflow: hidden;
    }

    .progress-bar {
        border-radius: 10px;
    }

    .table-success {
        background-color: rgba(25, 135, 84, 0.05) !important;
    }

    .table-warning {
        background-color: rgba(255, 193, 7, 0.05) !important;
    }

    .accordion-button {
        background-color: var(--bs-light);
        font-weight: 500;
        transition: all 0.2s;
    }

    .accordion-button:not(.collapsed) {
        background-color: var(--bs-light);
        color: var(--bs-primary);
        box-shadow: none;
    }

    .accordion-button:focus {
        box-shadow: none;
        border-color: var(--bs-border-color);
    }

    .accordion-item {
        border: 1px solid var(--bs-border-color);
        border-radius: 8px !important;
        margin-bottom: 8px;
    }

    .accordion-item:last-of-type {
        border-bottom: 1px solid var(--bs-border-color);
    }

    .badge-color-indicator {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 8px;
    }

    .table th {
        font-weight: 600;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-top: none;
    }

    .table td {
        vertical-align: middle;
        padding: 0.75rem;
    }
</style>
