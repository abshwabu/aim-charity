import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

// IntersectionObserver for subtle scroll-reveal animations
document.addEventListener('DOMContentLoaded', () => {
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const elementsToReveal = document.querySelectorAll('.reveal-on-scroll');

    if (prefersReducedMotion) {
        elementsToReveal.forEach((el) => el.classList.add('is-revealed'));
        return;
    }

    if ('IntersectionObserver' in window) {
        const revealObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-revealed');
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.12,
            rootMargin: '0px 0px -40px 0px',
        });

        elementsToReveal.forEach((el) => revealObserver.observe(el));
    } else {
        // Fallback for older browsers
        elementsToReveal.forEach((el) => el.classList.add('is-revealed'));
    }
});
