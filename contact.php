<?php
$pageTitle = 'Contact Us – KileleTech';
include 'includes/header.php';
?>

<section class="page-hero" style="background: linear-gradient(135deg, #0f1e33 0%, #1a2d47 100%);">
    <div class="container">
        <div class="page-hero__breadcrumb"><a href="index.php">Home</a> / Contact</div>
        <h1 class="page-hero__title">Get in Touch</h1>
        <p class="page-hero__subtitle">
            Have a project in mind, or a question about our services? We're here to help.
        </p>
    </div>
</section>

<section class="content-section">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-7">
                <div class="contact-form-card" style="background: #fff; border-radius: 18px; padding: 36px; box-shadow: 0 5px 25px rgba(0,0,0,0.06);">
                    <h4 style="font-weight:700; color: var(--kilele-navy); margin-bottom: 22px;">Send us a message</h4>

                    <!-- Message display area -->
                    <div id="formMessage" style="display: none; padding: 16px 20px; border-radius: 8px; margin-bottom: 20px;"></div>

                    <form id="contactForm" method="POST">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" style="font-weight: 600; color: var(--kilele-navy);">Full Name *</label>
                                <input type="text" name="name" id="contactName" class="form-control" placeholder="Your name" required style="border-radius: 8px; padding: 12px 14px; border: 1px solid #ddd;">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" style="font-weight: 600; color: var(--kilele-navy);">Phone Number</label>
                                <input type="tel" name="phone" id="contactPhone" class="form-control" placeholder="+254 7..." style="border-radius: 8px; padding: 12px 14px; border: 1px solid #ddd;">
                            </div>
                            <div class="col-12">
                                <label class="form-label" style="font-weight: 600; color: var(--kilele-navy);">Email Address *</label>
                                <input type="email" name="email" id="contactEmail" class="form-control" placeholder="you@example.com" required style="border-radius: 8px; padding: 12px 14px; border: 1px solid #ddd;">
                            </div>
                            <div class="col-12">
                                <label class="form-label" style="font-weight: 600; color: var(--kilele-navy);">Subject</label>
                                <input type="text" name="subject" id="contactSubject" class="form-control" placeholder="Which service are you interested in?" style="border-radius: 8px; padding: 12px 14px; border: 1px solid #ddd;">
                            </div>
                            <div class="col-12">
                                <label class="form-label" style="font-weight: 600; color: var(--kilele-navy);">Message *</label>
                                <textarea name="message" id="contactMessage" class="form-control" rows="5" placeholder="Tell us about your project..." required style="border-radius: 8px; padding: 12px 14px; border: 1px solid #ddd;"></textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" id="submitBtn" style="padding: 14px 40px; border-radius: 50px; font-weight: 700; background: var(--kilele-primary); color: #fff; border: none; cursor: pointer; transition: 0.3s; width: 100%;">
                                    <i class="fas fa-paper-plane me-2"></i> Send Message
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-lg-5">
                <ul class="contact-info-list" style="list-style: none; padding: 0;">
                    <li style="display: flex; align-items: flex-start; gap: 16px; margin-bottom: 22px;">
                        <div class="contact-info-list__icon" style="width: 44px; height: 44px; border-radius: 12px; background: rgba(41,160,142,0.1); color: var(--kilele-primary); display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0;">
                            <i class="fas fa-location-dot"></i>
                        </div>
                        <div>
                            <h5 style="font-size: 0.95rem; font-weight: 700; color: var(--kilele-navy); margin-bottom: 3px;">Visit Us</h5>
                            <p style="font-size: 0.85rem; color: var(--kilele-text-light); margin: 0;">University of Nairobi, Main Campus</p>
                        </div>
                    </li>
                    <li style="display: flex; align-items: flex-start; gap: 16px; margin-bottom: 22px;">
                        <div class="contact-info-list__icon" style="width: 44px; height: 44px; border-radius: 12px; background: rgba(41,160,142,0.1); color: var(--kilele-primary); display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0;">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div>
                            <h5 style="font-size: 0.95rem; font-weight: 700; color: var(--kilele-navy); margin-bottom: 3px;">Call Us</h5>
                            <p style="font-size: 0.85rem; color: var(--kilele-text-light); margin: 0;">+254 741 139 887</p>
                        </div>
                    </li>
                    <li style="display: flex; align-items: flex-start; gap: 16px; margin-bottom: 22px;">
                        <div class="contact-info-list__icon" style="width: 44px; height: 44px; border-radius: 12px; background: rgba(41,160,142,0.1); color: var(--kilele-primary); display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0;">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div>
                            <h5 style="font-size: 0.95rem; font-weight: 700; color: var(--kilele-navy); margin-bottom: 3px;">Email Us</h5>
                            <p style="font-size: 0.85rem; color: var(--kilele-text-light); margin: 0;">kileletechofficial@gmail.com</p>
                        </div>
                    </li>
                    <li style="display: flex; align-items: flex-start; gap: 16px; margin-bottom: 22px;">
                        <div class="contact-info-list__icon" style="width: 44px; height: 44px; border-radius: 12px; background: rgba(41,160,142,0.1); color: var(--kilele-primary); display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0;">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div>
                            <h5 style="font-size: 0.95rem; font-weight: 700; color: var(--kilele-navy); margin-bottom: 3px;">Working Hours</h5>
                            <p style="font-size: 0.85rem; color: var(--kilele-text-light); margin: 0;">Mon–Fri: 8:00 AM – 5:00 PM</p>
                        </div>
                    </li>
                </ul>

                <div style="width: 100%; height: 250px; border-radius: 15px; overflow: hidden; background: #e9ecef; margin-top: 20px;">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3988.8043!2d36.8168!3d-1.2799!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x182f1f5b3e5c7c1d%3A0x8f8a2b8c0e1e8f8a!2sUniversity%20of%20Nairobi%20Main%20Campus!5e0!3m2!1sen!2ske!4v1700000000000"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    title="KileleTech location map"
                    style="width: 100%; height: 100%; border: 0;">
                </iframe>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    // ============================================================
    // CONTACT FORM HANDLER – AJAX submission with fetch
    // ============================================================
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('contactForm');
        const messageDiv = document.getElementById('formMessage');
        const submitBtn = document.getElementById('submitBtn');

        form.addEventListener('submit', function(e) {
            e.preventDefault();

            // Collect form data
            const formData = new FormData(form);

            // Disable button and show loading
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Sending...';

            // Send the data
            fetch('process_contact.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                messageDiv.style.display = 'block';

                if (data.success) {
                    messageDiv.style.background = '#d4edda';
                    messageDiv.style.color = '#155724';
                    messageDiv.style.borderLeft = '4px solid #28a745';
                    messageDiv.innerHTML = '<i class="fas fa-check-circle me-2"></i> ' + data.message;
                    form.reset();
                } else {
                    messageDiv.style.background = '#f8d7da';
                    messageDiv.style.color = '#721c24';
                    messageDiv.style.borderLeft = '4px solid #dc3545';
                    messageDiv.innerHTML = '<i class="fas fa-exclamation-circle me-2"></i> ' + data.message;
                }

                // Scroll to message
                messageDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
            })
            .catch(error => {
                messageDiv.style.display = 'block';
                messageDiv.style.background = '#f8d7da';
                messageDiv.style.color = '#721c24';
                messageDiv.style.borderLeft = '4px solid #dc3545';
                messageDiv.innerHTML = '<i class="fas fa-exclamation-circle me-2"></i> Could not reach the server. Please try again later.';
                console.error('Error:', error);
            })
            .finally(() => {
                // Re-enable button
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-paper-plane me-2"></i> Send Message';
            });
        });
    });
</script>

<?php include 'includes/footer.php'; ?>