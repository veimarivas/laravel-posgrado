document.addEventListener("DOMContentLoaded", function() {
    var elements = document.querySelectorAll("[toast-list], [data-choices], [data-provider]");
    if (elements.length > 0) {
        loadScript("https://cdn.jsdelivr.net/npm/toastify-js");
        loadScript("assets/libs/choices.js/public/assets/scripts/choices.min.js");
        loadScript("assets/libs/flatpickr/flatpickr.min.js");
    }
});

function loadScript(src) {
    var script = document.createElement("script");
    script.src = src;
    script.async = false;
    document.head.appendChild(script);
}
