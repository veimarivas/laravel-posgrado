<footer class="footer">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <script>
                    document.write(new Date().getFullYear())
                </script> © POSGRADO
            </div>
            <div class="col-sm-6">
                <div class="text-sm-end d-none d-sm-block">
                    Desarrollado con <i class="mdi mdi-heart text-danger"></i>
                </div>
            </div>
        </div>
    </div>
</footer>

<style>
    .footer {
        padding: 16px 24px;
        background-color: #f8f9fa;
        border-top: 1px solid #e9ecec;
        transition: all 0.3s ease;
    }

    [data-bs-theme="dark"] .footer {
        background-color: #212229 !important;
        border-top-color: #2d2d3a !important;
        color: #9ca3af;
    }

    @media (max-width: 575px) {
        .footer .row {
            text-align: center !important;
        }
        
        .footer .col-sm-6:last-child {
            margin-top: 4px;
        }
    }
</style>
