<?php
$pageTitle = 'Get a Quote – KileleTech';
include 'includes/header.php';
?>

<!-- ============================================================
   PAGE HERO
   ============================================================ -->
<section class="page-hero">
    <div class="container">
        <div class="page-hero__breadcrumb"><a href="index.php">Home</a> / Get a Quote</div>
        <h1 class="page-hero__title">Request a Free Quote</h1>
        <p class="page-hero__subtitle">
            Tell us about your project and we'll get back to you with a tailored quote — most of our consultations are free.
        </p>
    </div>
</section>

<!-- ============================================================
   QUOTE FORM (Single Service)
   ============================================================ -->
<section class="content-section" style="padding: 30px 0 50px; background: #f8fafb;">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-7">
                <div class="contact-form-card" style="background: #fff; border-radius: 18px; padding: 36px; box-shadow: 0 5px 25px rgba(0,0,0,0.06);">
                    <h4 style="font-weight:700; color: var(--kilele-navy); margin-bottom: 6px;">Request a Quote</h4>
                    <p style="color: var(--kilele-text-light); font-size: 0.9rem; margin-bottom: 22px;">Fill this in and our sales team will reach out within 1 business day.</p>

                    <div id="formMessage" style="display: none; padding: 16px 20px; border-radius: 8px; margin-bottom: 20px;"></div>

                    <form id="quoteForm" method="POST">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" style="font-weight: 600; color: var(--kilele-navy);">Full Name *</label>
                                <input type="text" name="name" id="quoteName" class="form-control" placeholder="Your name" required style="border-radius: 8px; padding: 12px 14px; border: 1px solid #ddd;">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" style="font-weight: 600; color: var(--kilele-navy);">Company Name</label>
                                <input type="text" name="company" id="quoteCompany" class="form-control" placeholder="Your organization" style="border-radius: 8px; padding: 12px 14px; border: 1px solid #ddd;">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" style="font-weight: 600; color: var(--kilele-navy);">Phone Number *</label>
                                <input type="tel" name="phone" id="quotePhone" class="form-control" placeholder="+254 7..." required style="border-radius: 8px; padding: 12px 14px; border: 1px solid #ddd;">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" style="font-weight: 600; color: var(--kilele-navy);">Email Address *</label>
                                <input type="email" name="email" id="quoteEmail" class="form-control" placeholder="you@example.com" required style="border-radius: 8px; padding: 12px 14px; border: 1px solid #ddd;">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" style="font-weight: 600; color: var(--kilele-navy);">Service Needed *</label>
                                <select name="service" id="quoteService" class="form-control" required style="border-radius: 8px; padding: 12px 14px; border: 1px solid #ddd;">
                                    <option value="">-- Select a service --</option>
                                    <option value="Software Development">Software Development</option>
                                    <option value="Web Development & Hosting">Web Development & Hosting</option>
                                    <option value="Hardware Supply & Installation">Hardware Supply & Installation</option>
                                    <option value="CCTV & Security Systems">CCTV & Security Systems</option>
                                    <option value="ICT Consulting">ICT Consulting</option>
                                    <option value="Database Management">Database Management</option>
                                    <option value="Security Monitoring">Security Monitoring</option>
                                    <option value="Firewall Solutions">Firewall Solutions</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" style="font-weight: 600; color: var(--kilele-navy);">Package Interested In</label>
                                <select name="package" id="quotePackage" class="form-control" style="border-radius: 8px; padding: 12px 14px; border: 1px solid #ddd;">
                                    <option value="Starter">Starter</option>
                                    <option value="Business">Business</option>
                                    <option value="Enterprise">Enterprise</option>
                                    <option value="Not sure yet">Not sure yet</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label" style="font-weight: 600; color: var(--kilele-navy);">Project Details *</label>
                                <textarea name="message" id="quoteMessage" class="form-control" rows="5" placeholder="Tell us a bit about what you need..." required style="border-radius: 8px; padding: 12px 14px; border: 1px solid #ddd;"></textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" id="submitBtn" style="padding: 14px 40px; border-radius: 50px; font-weight: 700; background: var(--kilele-primary); color: #fff; border: none; cursor: pointer; transition: 0.3s; width: 100%;">
                                    <i class="fas fa-paper-plane me-2"></i> Request Quote
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-lg-5">
                <span class="why-choose-us__eyebrow" style="display: inline-block; font-size: 13px; font-weight: 700; color: var(--kilele-primary); text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 10px;">Why Get a Quote</span>
                <h2 class="why-choose-us__heading" style="text-align:left; font-size: 2rem; font-weight: 700; color: var(--kilele-navy);">No pressure, just clarity</h2>
                <ul style="list-style:none; padding:0; margin-top: 20px;">
                    <li style="display:flex; gap:14px; margin-bottom:18px;">
                        <span style="background:var(--kilele-primary); color:white; border-radius:50%; width:30px; height:30px; display:flex; align-items:center; justify-content:center; font-weight:700; flex-shrink:0;">1</span>
                        <span style="color: var(--kilele-text-light); padding-top:3px;">Tell us what you need — even a rough idea is fine.</span>
                    </li>
                    <li style="display:flex; gap:14px; margin-bottom:18px;">
                        <span style="background:var(--kilele-primary); color:white; border-radius:50%; width:30px; height:30px; display:flex; align-items:center; justify-content:center; font-weight:700; flex-shrink:0;">2</span>
                        <span style="color: var(--kilele-text-light); padding-top:3px;">We schedule a free consultation to scope the work.</span>
                    </li>
                    <li style="display:flex; gap:14px; margin-bottom:18px;">
                        <span style="background:var(--kilele-primary); color:white; border-radius:50%; width:30px; height:30px; display:flex; align-items:center; justify-content:center; font-weight:700; flex-shrink:0;">3</span>
                        <span style="color: var(--kilele-text-light); padding-top:3px;">You receive a clear, itemized quote — no hidden fees.</span>
                    </li>
                    <li style="display:flex; gap:14px;">
                        <span style="background:var(--kilele-primary); color:white; border-radius:50%; width:30px; height:30px; display:flex; align-items:center; justify-content:center; font-weight:700; flex-shrink:0;">4</span>
                        <span style="color: var(--kilele-text-light); padding-top:3px;">We start work once you approve, with a dedicated team.</span>
                    </li>
                </ul>

                <div class="hero-joinus-overlay" style="position: static; max-width: none; padding: 22px 24px; margin-top: 20px; background: var(--kilele-dark-navy); border: 1px solid rgba(41,160,142,0.4); border-radius: 12px; color: white;">
                    <div class="hero-joinus-overlay__title" style="font-size: 1rem; font-weight: 700; margin-bottom: 2px;">Prefer to just call?</div>
                    <ul style="list-style: none; padding: 0; margin: 10px 0 0;">
                        <li style="margin-bottom: 3px;"><span style="color: var(--kilele-primary); font-weight: 600;">Sales:</span> +254 741 139 887</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================
   JAVASCRIPT (Submits to process_quote.php)
   ============================================================ -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('quoteForm');
        const messageDiv = document.getElementById('formMessage');
        const submitBtn = document.getElementById('submitBtn');

        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(form);

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Sending...';

            fetch('process_quote.php', {
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
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-paper-plane me-2"></i> Request Quote';
            });
        });
    });
</script>

<?php include 'includes/footer.php'; ?>