
const menuButton = document.querySelector('.mobile-menu-button');
const nav = document.querySelector('.site-nav');

if (menuButton && nav) {
    const closeMenu = () => {
        nav.classList.remove('open');
        menuButton.setAttribute('aria-expanded', 'false');
        menuButton.setAttribute('aria-label', 'Buka menu');
    };

    menuButton.addEventListener('click', () => {
        const isOpen = nav.classList.toggle('open');
        menuButton.setAttribute('aria-expanded', String(isOpen));
        menuButton.setAttribute('aria-label', isOpen ? 'Tutup menu' : 'Buka menu');
    });

    nav.addEventListener('click', (event) => {
        if (event.target instanceof Element && event.target.closest('a')) {
            closeMenu();
        }
    });

    document.addEventListener('click', (event) => {
        if (!nav.contains(event.target) && !menuButton.contains(event.target)) {
            closeMenu();
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeMenu();
        }
    });

    window.matchMedia('(min-width: 701px)').addEventListener('change', (event) => {
        if (event.matches) {
            closeMenu();
        }
    });
}

const partnerCarousel = document.querySelector('[data-partner-carousel]');

const motionTargets = document.querySelectorAll(
    'main > section:not(.hero), main > section > .container > h2, main > section > .container > .section-subtitle, main > section > .container > .stats-grid, main > section > .container > .card-grid, main > section > .container > .partner-carousel, main > section > .container > .publication-detail-grid, main > section > .container > .join-choice-grid, main > section > .container > .event-card, main > section > .container > .event-detail-card, main > section > .container > .publication-article, main > section > .container > .book-detail-card, .publication-grid > .publication-card, .book-catalog-grid > .book-catalog-card, .library-section .card-grid > .book-card, .publication-section .card-grid > .publication-card'
);

const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

if (motionTargets.length > 0 && 'IntersectionObserver' in window && !prefersReducedMotion.matches) {
    const motionObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

    motionTargets.forEach((target) => {
        target.classList.add('scroll-motion');

        if (target.matches('.publication-card, .book-card, .book-catalog-card')) {
            const siblings = Array.from(target.parentElement.children);
            const index = siblings.indexOf(target);
            target.style.setProperty('--motion-delay', `${Math.min(index, 5) * 90}ms`);
        }

        motionObserver.observe(target);
    });
}

const statisticCounters = document.querySelectorAll('[data-count-to]');

if (statisticCounters.length > 0) {
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

    const showFinalCount = (counter) => {
        counter.textContent = Number(counter.dataset.countTo).toLocaleString('id-ID');
    };

    const animateCount = (counter) => {
        const target = Number(counter.dataset.countTo);

        if (reducedMotion.matches || !Number.isFinite(target) || target < 0) {
            showFinalCount(counter);
            return;
        }

        const duration = 1400;
        const startTime = performance.now();
        const updateCount = (currentTime) => {
            const progress = Math.min((currentTime - startTime) / duration, 1);
            const easedProgress = 1 - ((1 - progress) ** 3);

            counter.textContent = Math.floor(target * easedProgress).toLocaleString('id-ID');

            if (progress < 1) {
                requestAnimationFrame(updateCount);
            } else {
                showFinalCount(counter);
            }
        };

        requestAnimationFrame(updateCount);
    };

    const counterObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                animateCount(entry.target);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });

    statisticCounters.forEach((counter) => counterObserver.observe(counter));
}

document.querySelectorAll('[data-copy-link]').forEach((button) => {
    button.addEventListener('click', async () => {
        const feedback = button.closest('.publication-share')?.querySelector('.copy-feedback');

        try {
            await navigator.clipboard.writeText(window.location.href);
            if (feedback) {
                feedback.textContent = 'Tautan berhasil disalin.';
            }
        } catch {
            if (feedback) {
                feedback.textContent = 'Salin tautan dari bilah alamat browser.';
            }
        }
    });
});

document.querySelectorAll('[data-event-share]').forEach((button) => {
    button.addEventListener('click', async () => {
        const feedback = button.closest('.event-copy')?.querySelector('[data-event-feedback]');
        const shareData = {
            title: 'Membaca Bersama Gerobak Angkasa',
            text: 'Lihat informasi volunteer event Komunitas Literasi Remaja Tambun Selatan.',
            url: window.location.href,
        };

        try {
            if (navigator.share) {
                await navigator.share(shareData);
            } else {
                await navigator.clipboard.writeText(shareData.url);
                if (feedback) {
                    feedback.textContent = 'Tautan event berhasil disalin.';
                }
            }
        } catch {
            if (feedback) {
                feedback.textContent = 'Tautan event belum dapat dibagikan dari perangkat ini.';
            }
        }
    });
});

if (partnerCarousel) {
    const viewport = partnerCarousel.querySelector('[data-partner-viewport]');
    const track = partnerCarousel.querySelector('[data-partner-track]');
    const partnerSet = partnerCarousel.querySelector('[data-partner-set]');
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

    if (viewport && track && partnerSet) {
        const prepareCarousel = () => {
            track.querySelectorAll('[data-partner-clone]').forEach((clone) => clone.remove());
            track.style.removeProperty('--partner-distance');
            track.style.removeProperty('--partner-duration');

            if (reducedMotion.matches) {
                return;
            }

            const partnerSetWidth = partnerSet.getBoundingClientRect().width;

            if (partnerSetWidth === 0 || viewport.clientWidth === 0) {
                return;
            }

            while (track.scrollWidth < viewport.clientWidth + partnerSetWidth) {
                const clone = partnerSet.cloneNode(true);
                clone.removeAttribute('data-partner-set');
                clone.setAttribute('data-partner-clone', '');
                clone.setAttribute('aria-hidden', 'true');
                track.append(clone);
            }

            track.style.setProperty('--partner-distance', `-${partnerSetWidth}px`);
            track.style.setProperty('--partner-duration', `${Math.max(partnerSetWidth / 35, 12)}s`);
        };

        let resizeFrame;
        const resizeObserver = new ResizeObserver(() => {
            cancelAnimationFrame(resizeFrame);
            resizeFrame = requestAnimationFrame(prepareCarousel);
        });

        resizeObserver.observe(viewport);
        reducedMotion.addEventListener('change', prepareCarousel);
        prepareCarousel();

        partnerCarousel.querySelectorAll('[data-partner-direction]').forEach((button) => {
            button.addEventListener('click', () => {
                const direction = button.dataset.partnerDirection;
                const animation = track.getAnimations()[0];

                if (animation) {
                    animation.pause();
                    animation.currentTime = direction === 'next' ? 0 : animation.effect.getTiming().duration;
                    animation.playbackRate = 1;
                    animation.play();
                    return;
                }

                viewport.scrollBy({
                    left: direction === 'next' ? viewport.clientWidth : -viewport.clientWidth,
                    behavior: reducedMotion.matches ? 'auto' : 'smooth',
                });
            });
        });
    }
}
