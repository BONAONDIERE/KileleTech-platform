<?php
$pageTitle = 'Bundled Quote – KileleTech';
include 'includes/header.php';
?>
<style>
    .bundle-hero {
        background: linear-gradient(135deg, #0f1e33 0%, #1a2d47 100%);
        padding: 80px 0 60px;
        position: relative;
        overflow: hidden;
    }
    .bundle-hero .orb {
        position: absolute;
        border-radius: 50%;
        background: rgba(41, 160, 142, 0.15);
        animation: float 8s ease-in-out infinite;
    }
    .bundle-hero .orb-1 { width: 300px; height: 300px; right: -80px; top: -80px; animation-delay: 0s; }
    @keyframes float {
        0%, 100% { transform: translateY(0px) scale(1); }
        50% { transform: translateY(-30px) scale(1.05); }
    }
</style>

<section class="bundle-hero">
    <div class="orb orb-1"></div>
    <div class="container position-relative" style="z-index: 2;">
        <div class="text-center">
            <span style="display: inline-block; font-size: 13px; font-weight: 700; color: #F2B91E; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 12px;">SAVE WITH A BUNDLE</span>
            <h1 class="fw-bold" style="font-size: 3.5rem; color: #ffffff; margin-bottom: 20px;">Request a Bundled Quote</h1>
            <p style="font-size: 1.2rem; color: rgba(255,255,255,0.8); max-width: 700px; margin: 0 auto;">
                Combine Software, Hardware, Security, and Support into one contract—save money and simplify your IT management.
            </p>
        </div>
    </div>
</section>

<section class="content-section" style="padding: 80px 0; background: #ffffff;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div id="formMessage" style="display: none; padding: 15px; border-radius: 8px; margin-bottom: 20px;"></div>
                <form id="bundleForm" method="POST" class="p-4" style="background: #f8fafb; border-radius: 20px; box-shadow: 0 8px 24px rgba(15, 30, 51, 0.06);">
                    <div class="text-center mb-4">
                        <h3 style="font-weight: 700; color: #0f1e33;">Tell us what you need bundled</h3>
                        <p style="color: #777;">We'll give you one combined price for everything.</p>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Full Name *</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email *</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="tel" name="phone" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Company</label>
                            <input type="text" name="company" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Which solutions do you want bundled? (Select all that apply) *</label>
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <div class="form-check border rounded p-2">
                                        <input class="form-check-input" type="checkbox" name="services[]" value="Software Development">
                                        <label class="form-check-label">Software Development</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check border rounded p-2">
                                        <input class="form-check-input" type="checkbox" name="services[]" value="Web Development & Hosting">
                                        <label class="form-check-label">Web Development & Hosting</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check border rounded p-2">
                                        <input class="form-check-input" type="checkbox" name="services[]" value="Hardware Supply & Installation">
                                        <label class="form-check-label">Hardware Supply & Installation</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check border rounded p-2">
                                        <input class="form-check-input" type="checkbox" name="services[]" value="CCTV & Security Systems">
                                        <label class="form-check-label">CCTV & Security Systems</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check border rounded p-2">
                                        <input class="form-check-input" type="checkbox" name="services[]" value="ICT Consulting">
                                        <label class="form-check-label">ICT Consulting</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check border rounded p-2">
                                        <input class="form-check-input" type="checkbox" name="services[]" value="Database Management">
                                        <label class="form-check-label">Database Management</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check border rounded p-2">
                                        <input class="form-check-input" type="checkbox" name="services[]" value="Security Monitoring">
                                        <label class="form-check-label">Security Monitoring</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check border rounded p-2">
                                        <input class="form-check-input" type="checkbox" name="services[]" value="Firewall Solutions">
                                        <label class="form-check-label">Firewall Solutions</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Additional project details</label>
                            <textarea name="message" class="form-control" rows="4" placeholder="Tell us about your project..."></textarea>
                        </div>
                        <div class="col-12 text-center">
                            <button type="submit" id="submitBtn" class="btn btn-kilele-primary" style="padding: 15px 40px; border-radius: 50px; font-weight: 700;">Submit Bundled Quote Request</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================
AJAX SCRIPT (Prevents Page Reload)
============================================================ -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('bundleForm');
        const messageDiv = document.getElementById('formMessage');
        const submitBtn = document.getElementById('submitBtn');

        form.addEventListener('submit', function(e) {
            e.preventDefault(); // Prevents the page from reloading!

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Sending...';

            const formData = new FormData(form);

            fetch('process_bundle_quote.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
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
            })
            .catch(() => {
                messageDiv.style.display = 'block';
                messageDiv.style.background = '#f8d7da';
                messageDiv.style.color = '#721c24';
                messageDiv.style.borderLeft = '4px solid #dc3545';
                messageDiv.innerHTML = '<i class="fas fa-exclamation-circle me-2"></i> Could not reach the server. Please try again later.';
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Submit Bundled Quote Request';
            });
        });
    });
</script>

<?php include 'includes/footer.php'; ?>