document.addEventListener('DOMContentLoaded', () => {
    const headerWrapper = document.querySelector('.v-header-wrapper');
    const mainNav = document.querySelector('.v-mainnav');
    const mobileTrigger = document.getElementById('mobile-toggle');
    const mobileClose = document.getElementById('mobile-close');
    const mobilePanel = document.getElementById('mobile-panel');
    const mobileOverlay = document.getElementById('mobile-overlay');

    const setScrolledState = () => {
        if (!headerWrapper || !mainNav) {
            return;
        }

        if (window.scrollY > 24) {
            headerWrapper.classList.add('scrolled');
            mainNav.classList.add('scrolled');
        } else {
            headerWrapper.classList.remove('scrolled');
            mainNav.classList.remove('scrolled');
        }
    };

    const setMobileMenu = (isOpen) => {
        if (!mobilePanel || !mobileOverlay) {
            return;
        }

        mobilePanel.classList.toggle('is-open', isOpen);
        mobileOverlay.classList.toggle('is-open', isOpen);
        mobilePanel.setAttribute('aria-hidden', String(!isOpen));
        document.body.style.overflow = isOpen ? 'hidden' : '';
    };

    if (mobileTrigger) {
        mobileTrigger.addEventListener('click', () => setMobileMenu(true));
    }

    if (mobileClose) {
        mobileClose.addEventListener('click', () => setMobileMenu(false));
    }

    if (mobileOverlay) {
        mobileOverlay.addEventListener('click', () => setMobileMenu(false));
    }

    document.querySelectorAll('.v-mobile-nav a').forEach((link) => {
        link.addEventListener('click', () => setMobileMenu(false));
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            setMobileMenu(false);
        }
    });

    window.addEventListener('scroll', setScrolledState);
    setScrolledState();

    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                revealObserver.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.12,
        rootMargin: '0px 0px -64px 0px',
    });

    document.querySelectorAll('.v-reveal, .v-reveal-left, .v-reveal-right').forEach((element) => {
        revealObserver.observe(element);
    });

    let ticking = false;

    const updateParallax = () => {
        document.querySelectorAll('.v-parallax-bg').forEach((background) => {
            const speed = Number(background.dataset.speed || 0.18);
            const parent = background.parentElement;

            if (!parent) {
                return;
            }

            const rect = parent.getBoundingClientRect();

            if (rect.bottom < 0 || rect.top > window.innerHeight) {
                return;
            }

            const offset = rect.top * speed;
            background.style.transform = `translate3d(0, ${offset}px, 0)`;
        });
    };

    window.addEventListener('scroll', () => {
        if (ticking) {
            return;
        }

        ticking = true;
        window.requestAnimationFrame(() => {
            updateParallax();
            ticking = false;
        });
    });

    updateParallax();

    document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
        anchor.addEventListener('click', (event) => {
            const href = anchor.getAttribute('href');

            if (!href || href === '#') {
                return;
            }

            const target = document.querySelector(href);

            if (!target) {
                return;
            }

            event.preventDefault();
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    });
});
