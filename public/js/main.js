// nest/public/js/main.js
// Animations globales du thème Nest

document.addEventListener('DOMContentLoaded', function () {

    // Navbar scroll
    const nav = document.querySelector('.site-nav');
    if (nav) {
        window.addEventListener('scroll', () => {
            nav.classList.toggle('scrolled', window.scrollY > 40);
        });
    }

    // Menu mobile
    const burger = document.getElementById('navBurger');
    const links = document.getElementById('navLinks');
    if (burger && links) {
        burger.addEventListener('click', () => links.classList.toggle('open'));
        links.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => links.classList.remove('open'));
        });
    }

    // Particules
    const particlesEl = document.getElementById('particles');
    if (particlesEl) {
        for (let i = 0; i < 18; i++) {
            const p = document.createElement('div');
            p.className = 'particle';
            const size = Math.random() * 5 + 2;
            p.style.width = size + 'px';
            p.style.height = size + 'px';
            p.style.left = Math.random() * 100 + '%';
            p.style.top = Math.random() * 100 + '%';
            p.style.animationDelay = Math.random() * 5 + 's';
            p.style.opacity = Math.random() * 0.35 + 0.1;
            particlesEl.appendChild(p);
        }
    }

    // Compteurs animés
    const counters = document.querySelectorAll('.counter[data-target]');
    counters.forEach(counter => {
        const target = parseInt(counter.getAttribute('data-target'));
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const duration = 1500;
                    const start = performance.now();
                    const step = (now) => {
                        const progress = Math.min((now - start) / duration, 1);
                        counter.textContent = Math.ceil(progress * target);
                        if (progress < 1) requestAnimationFrame(step);
                        else counter.textContent = target;
                    };
                    requestAnimationFrame(step);
                    observer.unobserve(counter);
                }
            });
        }, { threshold: 0.3 });
        observer.observe(counter);
    });

    // Révélation au scroll
    const revealEls = document.querySelectorAll('.animate-on-scroll');
    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                revealObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });
    revealEls.forEach(el => revealObserver.observe(el));

    // Smooth scroll ancres
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const targetId = this.getAttribute('href');
            if (targetId === '#' || targetId.length < 2) return;
            const target = document.querySelector(targetId);
            if (target) {
                e.preventDefault();
                window.scrollTo({ top: target.offsetTop - 90, behavior: 'smooth' });
            }
        });
    });
});
