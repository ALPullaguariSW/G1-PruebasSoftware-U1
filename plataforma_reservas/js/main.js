// js/main.js
document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.querySelector('.menu-toggle');
    const mainNav = document.querySelector('.main-nav');

    if (menuToggle && mainNav) {
        menuToggle.addEventListener('click', function() {
            const isActive = mainNav.classList.toggle('is-active');
            menuToggle.classList.toggle('is-active');
            menuToggle.setAttribute('aria-expanded', isActive ? 'true' : 'false');
        });
    }

    // Cerrar menú si se hace clic fuera (opcional)
    document.addEventListener('click', function(event) {
        if (mainNav && mainNav.classList.contains('is-active')) {
            const isClickInsideNav = mainNav.contains(event.target);
            const isClickOnToggle = menuToggle.contains(event.target);
            if (!isClickInsideNav && !isClickOnToggle) {
                mainNav.classList.remove('is-active');
                menuToggle.classList.remove('is-active');
                menuToggle.setAttribute('aria-expanded', 'false');
            }
        }
    });

    // Smooth scroll for anchor links (opcional)
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const hrefAttribute = this.getAttribute('href');
            if (hrefAttribute.length > 1) { // Asegura que no sea solo "#"
                const targetElement = document.querySelector(hrefAttribute);
                if (targetElement) {
                    e.preventDefault();
                    targetElement.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            }
        });
    });
});