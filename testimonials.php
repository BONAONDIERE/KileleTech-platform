<?php
$pageTitle = 'Testimonials – KileleTech';
include 'includes/header.php';
?>
<style>
    .test-hero {
        background: linear-gradient(135deg, #0f1e33 0%, #1a2d47 100%);
        padding: 100px 0 80px;
        position: relative;
        overflow: hidden;
    }
    .test-hero .orb {
        position: absolute;
        border-radius: 50%;
        background: rgba(41, 160, 142, 0.15);
        animation: float 8s ease-in-out infinite;
    }
    .test-hero .orb-1 { width: 300px; height: 300px; right: -80px; top: -80px; animation-delay: 0s; }
    .test-hero .orb-2 { width: 200px; height: 200px; left: -80px; bottom: -80px; animation-delay: 3s; }
    @keyframes float {
        0%, 100% { transform: translateY(0px) scale(1); }
        50% { transform: translateY(-30px) scale(1.05); }
    }

    .rating-input {
        font-size: 2rem;
        color: #ddd;
        cursor: pointer;
        transition: color 0.3s;
    }
    .rating-input.active {
        color: #F2B91E;
    }
    .testimonial-card {
        background: #ffffff;
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        transition: all 0.3s;
        height: 100%;
    }
    .testimonial-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.08);
    }
    .stars {
        color: #F2B91E;
    }
</style>

<section class="test-hero">
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="container position-relative" style="z-index: 2;">
        <div class="text-center">
            <span style="display: inline-block; font-size: 13px; font-weight: 700; color: #F2B91E; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 12px;">WHAT OUR CLIENTS SAY</span>
            <h1 class="fw-bold" style="font-size: 3.5rem; color: #ffffff; margin-bottom: 20px;">Client Testimonials</h1>
            <p style="font-size: 1.2rem; color: rgba(255,255,255,0.8); max-width: 600px; margin: 0 auto;">
                Real feedback from the organizations we serve. Your voice matters—rate your experience with us.
            </p>
        </div>
    </div>
</section>

<!-- RATE US SECTION -->
<section class="content-section" style="padding: 80px 0; background: #ffffff;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="text-center mb-5">
                    <span class="why-choose-us__eyebrow">Share Your Experience</span>
                    <h2 class="why-choose-us__heading">Rate KileleTech</h2>
                    <p style="color: var(--kilele-text-light);">We value your feedback. Tap a star to rate us below.</p>
                </div>

                <div class="text-center mb-4">
                    <div class="rating-input d-flex justify-content-center gap-2" id="ratingStars">
                        <i class="far fa-star" data-value="1"></i>
                        <i class="far fa-star" data-value="2"></i>
                        <i class="far fa-star" data-value="3"></i>
                        <i class="far fa-star" data-value="4"></i>
                        <i class="far fa-star" data-value="5"></i>
                    </div>
                    <div id="ratingMessage" style="font-size: 1rem; font-weight: 600; margin-top: 10px; display: none;"></div>
                </div>

                <form id="testimonialForm">
                    <div class="mb-3">
                        <label class="form-label">Your Name</label>
                        <input type="text" class="form-control" id="clientName" placeholder="Enter your name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Your Testimonial</label>
                        <textarea class="form-control" id="clientTestimonial" rows="4" placeholder="Tell us about your experience..." required></textarea>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-kilele-primary" style="padding: 15px; border-radius: 50px; font-weight: 700;">Submit Your Testimonial</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- EXISTING CLIENT REVIEWS -->
<section class="content-section content-section--alt" style="padding: 80px 0; background: #f8fafb;">
    <div class="container">
        <div class="text-center mb-5">
            <span class="why-choose-us__eyebrow">Recent Reviews</span>
            <h2 class="why-choose-us__heading">What Clients Have Said</h2>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="testimonial-card">
                    <div class="stars mb-2">★★★★★</div>
                    <p style="color: #555; font-style: italic;">"KileleTech transformed our IT infrastructure. Their team is professional, responsive, and truly innovative."</p>
                    <h5 style="font-weight: 700; color: #0f1e33; margin-top: 15px;">— IT Manager, Coca-Cola</h5>
                </div>
            </div>
            <div class="col-md-4">
                <div class="testimonial-card">
                    <div class="stars mb-2">★★★★★</div>
                    <p style="color: #555; font-style: italic;">"From campus Wi-Fi to custom e-learning solutions, they delivered beyond our expectations."</p>
                    <h5 style="font-weight: 700; color: #0f1e33; margin-top: 15px;">— Director, University of Nairobi</h5>
                </div>
            </div>
            <div class="col-md-4">
                <div class="testimonial-card">
                    <div class="stars mb-2">★★★★★</div>
                    <p style="color: #555; font-style: italic;">"We needed reliable security systems and got exactly that. Highly recommend their services."</p>
                    <h5 style="font-weight: 700; color: #0f1e33; margin-top: 15px;">— Operations Lead, NGO Partner</h5>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="content-section text-center" style="padding: 70px 0; background: #0f1e33;">
    <div class="container">
        <h2 style="color: #ffffff; font-weight: 800; font-size: 2.5rem; margin-bottom: 15px;">Share Your Story With Us</h2>
        <p style="color: rgba(255,255,255,0.7); max-width: 600px; margin: 0 auto 30px;">
            Ready to transform your business? Let's talk.
        </p>
        <a href="quote.php" class="btn btn-kilele-primary" style="padding: 15px 40px; border-radius: 50px; font-weight: 700;">Get a Free Consultation →</a>
    </div>
</section>

<script>
    // STAR RATING SYSTEM
    const stars = document.querySelectorAll('#ratingStars i');
    let selectedRating = 0;

    stars.forEach(star => {
        star.addEventListener('click', function() {
            selectedRating = this.getAttribute('data-value');
            updateStars();
        });
    });

    function updateStars() {
        stars.forEach(star => {
            if (parseInt(star.getAttribute('data-value')) <= parseInt(selectedRating)) {
                star.classList.remove('far');
                star.classList.add('fas', 'active');
            } else {
                star.classList.remove('fas', 'active');
                star.classList.add('far');
            }
        });
        document.getElementById('ratingMessage').style.display = 'block';
        document.getElementById('ratingMessage').textContent = `You rated us ${selectedRating}/5 stars. Thank you!`;
    }

    // TESTIMONIAL FORM
    document.getElementById('testimonialForm').addEventListener('submit', function(e) {
        e.preventDefault();
        alert('Thank you for your feedback! Your testimonial has been received.');
        document.getElementById('clientName').value = '';
        document.getElementById('clientTestimonial').value = '';
        selectedRating = 0;
        updateStars();
        document.getElementById('ratingMessage').style.display = 'none';
    });
</script>

<?php include 'includes/footer.php'; ?>