<!-- UserWay Third-Party Web Accessibility Widget API -->
<style>
    body #userwayAccessibilityIcon,
    body .uai {
        top: auto !important;
        right: auto !important;
        bottom: 24px !important;
        left: 24px !important;
    }
</style>
<script>
    (function(d){
        var s = d.createElement("script");
        /* Load UserWay official CDN Widget Script API */
        s.setAttribute("data-account", "0m4b35W8A1");
        s.setAttribute("data-position", "5");
        s.setAttribute("src", "https://cdn.userway.org/widget.js");
        (d.body || d.head).appendChild(s);
    })(document);
</script>

<!-- Keyboard Shortcut (CTRL+U) and Link Event Handlers for Third-Party Accessibility API -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Listen for CTRL+U to trigger UserWay Accessibility Widget API
    document.addEventListener('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'u') {
            e.preventDefault();
            if (window.UserWay && typeof window.UserWay.widgetToggle === 'function') {
                window.UserWay.widgetToggle();
            }
        }
    });

    // Handle Navbar and Footer Aksesibilitas links to open UserWay API
    document.addEventListener('click', function(e) {
        const triggerBtn = e.target.closest('[data-bs-target="#accessibilityOffcanvas"], [href="#accessibilityOffcanvas"], .access-trigger-link');
        if (triggerBtn) {
            e.preventDefault();
            if (window.UserWay && typeof window.UserWay.widgetToggle === 'function') {
                window.UserWay.widgetToggle();
            } else {
                console.log('Loading UserWay Accessibility API...');
            }
        }
    });
});
</script>
