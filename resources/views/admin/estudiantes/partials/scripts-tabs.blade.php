<script>
    $(document).ready(function() {
        // Acordeón para programas - mantener estado abierto/cerrado
        $('.accordion-button').on('click', function() {
            $(this).toggleClass('collapsed');
        });

        // Animación suave para acordeón
        $('.accordion-collapse').on('show.bs.collapse', function() {
            $(this).prev('.accordion-header').find('.accordion-button').removeClass('collapsed');
        }).on('hide.bs.collapse', function() {
            $(this).prev('.accordion-header').find('.accordion-button').addClass('collapsed');
        });

        // Activar tab al hacer clic en enlaces internos (si los hay)
        $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
            // Guardar el tab activo en localStorage para persistencia
            localStorage.setItem('activeTab', $(e.target).attr('href'));
        });

        // Recuperar tab activo si existe en localStorage
        var activeTab = localStorage.getItem('activeTab');
        if (activeTab) {
            $('.nav-tabs a[href="' + activeTab + '"]').tab('show');
        }

        // Inicializar tooltips si los hay
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
