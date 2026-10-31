(() => {
    'use strict';

    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const fallbackImage = 'assets/images/mypic.png';

    const isValidEmail = (email) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);

    function setMenuState(navToggle, navMenu, open) {
        navMenu.classList.toggle('active', open);
        navToggle.classList.toggle('active', open);
        navToggle.setAttribute('aria-expanded', String(open));
        document.body.style.overflow = open ? 'hidden' : '';
    }

    function initNavigation() {
        const navToggle = document.getElementById('nav-toggle');
        const navMenu = document.getElementById('nav-menu');
        const navLinks = document.querySelectorAll('.nav-link');

        navLinks.forEach((link) => {
            link.addEventListener('click', (event) => {
                const target = document.querySelector(link.getAttribute('href'));
                if (!target) return;
                event.preventDefault();
                target.scrollIntoView({ behavior: reduceMotion ? 'auto' : 'smooth', block: 'start' });
                if (navToggle && navMenu) setMenuState(navToggle, navMenu, false);
            });
        });

        if (!navToggle || !navMenu) return;
        navToggle.addEventListener('click', () => {
            setMenuState(navToggle, navMenu, !navMenu.classList.contains('active'));
        });
        document.addEventListener('click', (event) => {
            if (navMenu.classList.contains('active') && !navMenu.contains(event.target) && !navToggle.contains(event.target)) {
                setMenuState(navToggle, navMenu, false);
            }
        });
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && navMenu.classList.contains('active')) {
                setMenuState(navToggle, navMenu, false);
                navToggle.focus();
            }
        });
        window.addEventListener('resize', () => {
            if (window.innerWidth > 768 && navMenu.classList.contains('active')) setMenuState(navToggle, navMenu, false);
        });
    }

    function initActiveNavigation() {
        const sections = document.querySelectorAll('section[id]');
        const navLinks = document.querySelectorAll('.nav-link');
        if (!sections.length || !navLinks.length) return;

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;
                navLinks.forEach((link) => link.classList.toggle('active', link.getAttribute('href') === `#${entry.target.id}`));
            });
        }, { rootMargin: '-25% 0px -65% 0px', threshold: 0 });
        sections.forEach((section) => observer.observe(section));
    }

    function initScrollEffects() {
        const navbar = document.querySelector('.navbar');
        if (!navbar) return;
        let ticking = false;
        const update = () => {
            navbar.style.background = window.scrollY > 50 ? 'rgba(0,0,0,0.95)' : 'rgba(0,0,0,0.85)';
            ticking = false;
        };
        window.addEventListener('scroll', () => {
            if (!ticking) {
                window.requestAnimationFrame(update);
                ticking = true;
            }
        }, { passive: true });
        update();
    }

    function initRevealAnimations() {
        const elements = document.querySelectorAll('.service-card, .project-card, .skills-category, .testimonial-card');
        if (reduceMotion || !('IntersectionObserver' in window)) {
            elements.forEach((element) => { element.style.opacity = '1'; element.style.transform = 'none'; });
            return;
        }
        elements.forEach((element) => {
            element.style.opacity = '0';
            element.style.transform = 'translateY(30px)';
            element.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        });
        const observer = new IntersectionObserver((entries, observerInstance) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
                observerInstance.unobserve(entry.target);
            });
        }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });
        elements.forEach((element) => observer.observe(element));
    }

    function initSkillBars() {
        const bars = document.querySelectorAll('.skill-progress');
        const reveal = (bar) => {
            const percentage = Math.min(100, Math.max(0, Number(bar.dataset.percentage) || 0));
            bar.style.width = `${percentage}%`;
            bar.style.opacity = '1';
        };
        if (reduceMotion || !('IntersectionObserver' in window)) {
            bars.forEach(reveal);
            return;
        }
        const observer = new IntersectionObserver((entries, observerInstance) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;
                reveal(entry.target);
                observerInstance.unobserve(entry.target);
            });
        }, { threshold: 0.5 });
        bars.forEach((bar) => observer.observe(bar));
    }

    function initImages() {
        document.querySelectorAll('img').forEach((image) => {
            if (!image.loading) image.loading = 'lazy';
            image.addEventListener('error', () => {
                if (image.dataset.fallbackApplied) return;
                image.dataset.fallbackApplied = 'true';
                image.src = fallbackImage;
            });
        });
    }

    function initContactForm() {
        const form = document.getElementById('contact-form');
        const status = document.getElementById('contact-form-status');
        if (!form || !status) return;
        form.noValidate = true;

        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            const submit = form.querySelector('button[type="submit"]');
            const originalText = submit.textContent;
            const formData = new FormData(form);
            const name = String(formData.get('name') || '').trim();
            const email = String(formData.get('email') || '').trim();
            const subject = String(formData.get('subject') || '').trim();
            const message = String(formData.get('message') || '').trim();

            status.className = 'form-status';
            if (name.length < 2 || !isValidEmail(email) || subject.length < 2 || message.length < 10) {
                status.textContent = 'Please check your name, email, subject, and message before sending.';
                status.classList.add('error');
                return;
            }

            submit.disabled = true;
            submit.textContent = 'Sending…';
            status.textContent = 'Sending your message…';
            status.classList.add('pending');

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                    credentials: 'same-origin'
                });
                const rawResponse = await response.text();
                let result;
                try {
                    result = JSON.parse(rawResponse);
                } catch (parseError) {
                    result = { success: false, message: `The server returned an unexpected response (${response.status}). Please try again.` };
                }
                status.className = 'form-status ' + (result.success ? 'success' : 'error');
                status.textContent = result.message || 'Something went wrong. Please try again.';
                if (result.success) form.reset();
            } catch (error) {
                status.className = 'form-status error';
                status.textContent = 'We could not send your message. Please try again later.';
            } finally {
                submit.disabled = false;
                submit.textContent = originalText;
            }
        });
    }

    function initTouchSupport() {
        const isTouchDevice = 'ontouchstart' in window || navigator.maxTouchPoints > 0;
        if (isTouchDevice) document.body.classList.add('touch-device');
    }

    function initServiceWorker() {
        if (!('serviceWorker' in navigator)) return;
        // Disable old cache-first workers until the site has a deployment-aware cache strategy.
        navigator.serviceWorker.getRegistrations()
            .then((registrations) => Promise.all(registrations.map((registration) => registration.unregister())))
            .catch(() => {});
    }

    document.addEventListener('DOMContentLoaded', () => {
        initNavigation();
        initActiveNavigation();
        initScrollEffects();
        initRevealAnimations();
        initSkillBars();
        initImages();
        initContactForm();
        initTouchSupport();
        initServiceWorker();
    });
})();
