/* ==========================================================
   Kilele Sacco — site-wide JavaScript
   Loaded on every page via includes/footer.php
   ========================================================== */

document.addEventListener('DOMContentLoaded', function() {

    /* ------------------------------------------------------
       1. Announcement bar marquee
       Scrolls continuously, pauses on hover (desktop) and on
       tap (mobile), resumes shortly after.
       ------------------------------------------------------ */
    (function announcementMarquee() {
        const track = document.getElementById('announceTrack');
        if (!track) return;

        let resumeTimeout;

        function pause() {
            track.style.animationPlayState = 'paused';
        }

        function resume() {
            track.style.animationPlayState = 'running';
        }

        track.addEventListener('mouseenter', pause);
        track.addEventListener('mouseleave', resume);

        track.addEventListener('touchstart', function() {
            pause();
            clearTimeout(resumeTimeout);
        }, { passive: true });

        track.addEventListener('touchend', function() {
            clearTimeout(resumeTimeout);
            resumeTimeout = setTimeout(resume, 3000);
        }, { passive: true });
    })();

    /* ------------------------------------------------------
       2. Homepage hero carousel (left panel)
       Only runs on pages that actually have the hero markup
       (index.php), guarded so it's safe to include on every page.
       ------------------------------------------------------ */
    (function heroCarousel() {
        const heroSlides = document.querySelectorAll('.hero-slide');
        const heroDots = document.querySelectorAll('.hero-dot');
        const heroNext = document.getElementById('heroNext');
        const heroPrev = document.getElementById('heroPrev');

        if (!heroSlides.length || !heroNext || !heroPrev) return;

        let heroIndex = 0;
        let heroInterval;

        function showHeroSlide(index) {
            heroSlides.forEach(s => s.classList.remove('active'));
            heroDots.forEach(d => d.classList.remove('active'));
            heroSlides[index].classList.add('active');
            if (heroDots[index]) heroDots[index].classList.add('active');
            heroIndex = index;
        }

        function nextHeroSlide() {
            showHeroSlide((heroIndex + 1) % heroSlides.length);
        }

        function prevHeroSlide() {
            showHeroSlide((heroIndex - 1 + heroSlides.length) % heroSlides.length);
        }

        function resetHeroAuto() {
            clearInterval(heroInterval);
            heroInterval = setInterval(nextHeroSlide, 8000);
        }

        heroNext.addEventListener('click', () => { nextHeroSlide();
            resetHeroAuto(); });
        heroPrev.addEventListener('click', () => { prevHeroSlide();
            resetHeroAuto(); });

        heroDots.forEach(dot => {
            dot.addEventListener('click', function() {
                showHeroSlide(parseInt(this.getAttribute('data-index'), 10));
                resetHeroAuto();
            });
        });

        resetHeroAuto();
    })();

    /* ------------------------------------------------------
       3. Newsletter form (footer) — submits via fetch to
       php/newsletter-handler.php without leaving the page.
       ------------------------------------------------------ */
    (function newsletterForm() {
        const form = document.getElementById('newsletterForm');
        const msg = document.getElementById('newsletterMsg');
        if (!form || !msg) return;

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(form);

            msg.textContent = 'Submitting...';
            msg.style.color = 'rgba(255,255,255,0.6)';

            fetch(form.action, {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        msg.textContent = data.message || 'Subscribed! Thank you.';
                        msg.style.color = 'var(--kilele-primary)';
                        form.reset();
                    } else {
                        msg.textContent = data.message || 'Something went wrong. Try again.';
                        msg.style.color = 'var(--kilele-coral)';
                    }
                })
                .catch(() => {
                    msg.textContent = 'Could not reach the server. Try again later.';
                    msg.style.color = 'var(--kilele-coral)';
                });
        });
    })();

    /* ------------------------------------------------------
       4. Contact form (contact.php) — submits via fetch to
       php/contact-handler.php without leaving the page.
       ------------------------------------------------------ */
    (function contactForm() {
        const form = document.getElementById('contactForm');
        const msg = document.getElementById('contactMsg');
        if (!form || !msg) return;

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(form);

            msg.textContent = 'Sending your message...';
            msg.style.color = 'var(--kilele-text-light)';

            fetch(form.action, {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        msg.textContent = data.message || 'Message sent! We\'ll be in touch soon.';
                        msg.style.color = 'var(--kilele-primary)';
                        form.reset();
                    } else {
                        msg.textContent = data.message || 'Something went wrong. Please check the form and try again.';
                        msg.style.color = 'var(--kilele-coral)';
                    }
                })
                .catch(() => {
                    msg.textContent = 'Could not reach the server. Try again later.';
                    msg.style.color = 'var(--kilele-coral)';
                });
        });
    })();

    /* ------------------------------------------------------
       5. Quote request form (quote.php) — submits via fetch to
       php/quote-handler.php without leaving the page.
       ------------------------------------------------------ */
    (function quoteForm() {
        const form = document.getElementById('quoteForm');
        const msg = document.getElementById('quoteMsg');
        if (!form || !msg) return;

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(form);

            msg.textContent = 'Submitting your request...';
            msg.style.color = 'var(--kilele-text-light)';

            fetch(form.action, {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        msg.textContent = data.message || 'Request received! We\'ll be in touch soon.';
                        msg.style.color = 'var(--kilele-primary)';
                        form.reset();
                    } else {
                        msg.textContent = data.message || 'Something went wrong. Please check the form and try again.';
                        msg.style.color = 'var(--kilele-coral)';
                    }
                })
                .catch(() => {
                    msg.textContent = 'Could not reach the server. Try again later.';
                    msg.style.color = 'var(--kilele-coral)';
                });
        });
    })();

});