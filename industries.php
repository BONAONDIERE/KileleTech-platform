<?php
$pageTitle = 'Industries – KileleTech';
include 'includes/header.php';
?>

<style>
    .ind-hero {
        background: linear-gradient(135deg, #0f1e33 0%, #1a2d47 100%);
        padding: 100px 0 80px;
        position: relative;
        overflow: hidden;
    }
    .ind-hero .orb {
        position: absolute;
        border-radius: 50%;
        background: rgba(41, 160, 142, 0.15);
        animation: float 8s ease-in-out infinite;
        filter: blur(2px);
    }
    .ind-hero .orb-1 { width: 300px; height: 300px; right: -80px; top: -80px; animation-delay: 0s; }
    .ind-hero .orb-2 { width: 200px; height: 200px; left: -80px; bottom: -80px; animation-delay: 3s; }
    @keyframes float {
        0%, 100% { transform: translateY(0px) scale(1); }
        50% { transform: translateY(-30px) scale(1.05); }
    }

    .ind-card {
        background: #ffffff;
        border-radius: 20px;
        padding: 40px 30px;
        box-shadow: 0 8px 24px rgba(15, 30, 51, 0.06);
        text-align: center;
        transition: all 0.4s ease;
        height: 100%;
        border: 1px solid transparent;
    }
    .ind-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(15, 30, 51, 0.12);
        border-color: rgba(41, 160, 142, 0.3);
    }
    .ind-card .ind-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, rgba(41,160,142,0.2), rgba(41,160,142,0.05));
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: #29A08E;
        margin: 0 auto 20px;
    }
    .trusted-logo-box {
        background: #f8fafb;
        border: 1px solid #f0f0f0;
        border-radius: 16px;
        padding: 24px;
        text-align: center;
        transition: 0.3s;
    }
    .trusted-logo-box:hover {
        border-color: rgba(41, 160, 142, 0.3);
        box-shadow: 0 8px 16px rgba(0,0,0,0.05);
    }
    .trusted-logo-box h5 {
        font-weight: 700;
        color: #0f1e33;
        margin: 0;
    }
</style>

<!-- HERO -->
<section class="ind-hero">
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="container position-relative" style="z-index: 2;">
        <div class="text-center">
            <span style="display: inline-block; font-size: 13px; font-weight: 700; color: #F2B91E; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 12px;">WHO WE SERVE</span>
            <h1 class="fw-bold" style="font-size: 3.5rem; color: #ffffff; margin-bottom: 20px;">Industries We Transform</h1>
            <p style="font-size: 1.2rem; color: rgba(255,255,255,0.8); max-width: 700px; margin: 0 auto;">
                From multinational corporations to leading universities, we deliver tailored ICT solutions that empower organizations across Africa.
            </p>
        </div>
    </div>
</section>

<!-- INDUSTRY CARDS -->
<section class="content-section" style="padding: 80px 0;">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-6 col-lg-4">
                <div class="ind-card">
                    <div class="ind-icon"><i class="fas fa-building"></i></div>
                    <h3 style="font-weight: 700; color: #0f1e33; margin-bottom: 12px;">Corporate & Enterprise</h3>
                    <p style="color: #777; font-size: 0.95rem;">Custom software, cloud infrastructure, and security solutions for large organizations.</p>
                    <a href="quote.php" style="font-size: 0.85rem; font-weight: 700; color: #29A08E; text-decoration: none;">Get a Quote →</a>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="ind-card">
                    <div class="ind-icon"><i class="fas fa-landmark"></i></div>
                    <h3 style="font-weight: 700; color: #0f1e33; margin-bottom: 12px;">Government Agencies</h3>
                    <p style="color: #777; font-size: 0.95rem;">Secure, compliant, and reliable digital systems for public sector institutions.</p>
                    <a href="quote.php" style="font-size: 0.85rem; font-weight: 700; color: #29A08E; text-decoration: none;">Get a Quote →</a>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="ind-card">
                    <div class="ind-icon"><i class="fas fa-heart"></i></div>
                    <h3 style="font-weight: 700; color: #0f1e33; margin-bottom: 12px;">Non-Profits & NGOs</h3>
                    <p style="color: #777; font-size: 0.95rem;">Cost-effective ICT solutions to help organizations maximize their impact.</p>
                    <a href="quote.php" style="font-size: 0.85rem; font-weight: 700; color: #29A08E; text-decoration: none;">Get a Quote →</a>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="ind-card">
                    <div class="ind-icon"><i class="fas fa-graduation-cap"></i></div>
                    <h3 style="font-weight: 700; color: #0f1e33; margin-bottom: 12px;">Educational Institutions</h3>
                    <p style="color: #777; font-size: 0.95rem;">E-learning platforms, campus networks, and secure digital ecosystems for universities.</p>
                    <a href="quote.php" style="font-size: 0.85rem; font-weight: 700; color: #29A08E; text-decoration: none;">Get a Quote →</a>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="ind-card">
                    <div class="ind-icon"><i class="fas fa-hospital"></i></div>
                    <h3 style="font-weight: 700; color: #0f1e33; margin-bottom: 12px;">Healthcare</h3>
                    <p style="color: #777; font-size: 0.95rem;">Secure patient data systems, telemedicine infrastructure, and compliance solutions.</p>
                    <a href="quote.php" style="font-size: 0.85rem; font-weight: 700; color: #29A08E; text-decoration: none;">Get a Quote →</a>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="ind-card">
                    <div class="ind-icon"><i class="fas fa-chart-line"></i></div>
                    <h3 style="font-weight: 700; color: #0f1e33; margin-bottom: 12px;">Financial Services</h3>
                    <p style="color: #777; font-size: 0.95rem;">Secure payment gateways, risk management systems, and data analytics for financial institutions.</p>
                    <a href="quote.php" style="font-size: 0.85rem; font-weight: 700; color: #29A08E; text-decoration: none;">Get a Quote →</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- TRUSTED BY -->
<section class="content-section content-section--alt" style="padding: 70px 0; background: #f8fafb;">
    <div class="container">
        <div class="text-center mb-5">
            <span class="why-choose-us__eyebrow">Our Track Record</span>
            <h2 class="why-choose-us__heading" style="font-size: 2.2rem;">Trusted By Leading Organizations</h2>
        </div>
        <div class="row g-4 justify-content-center">
            <div class="col-md-3 col-6">
                <div class="trusted-logo-box">
                    <div style="font-size: 2rem; color: #29A08E; margin-bottom: 10px;"><i class="fas fa-building"></i></div>
                    <h5>Coca-Cola</h5>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="trusted-logo-box">
                    <div style="font-size: 2rem; color: #29A08E; margin-bottom: 10px;"><i class="fas fa-university"></i></div>
                    <h5>University of Nairobi</h5>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="trusted-logo-box">
                    <div style="font-size: 2rem; color: #29A08E; margin-bottom: 10px;"><i class="fas fa-users"></i></div>
                    <h5>Various NGOs</h5>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="trusted-logo-box">
                    <div style="font-size: 2rem; color: #29A08E; margin-bottom: 10px;"><i class="fas fa-money-bill-wave"></i></div>
                    <h5>Financial Partners</h5>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="content-section text-center" style="padding: 80px 0; background: #0f1e33;">
    <div class="container">
        <h2 style="color: #ffffff; font-weight: 800; font-size: 2.5rem; margin-bottom: 15px;">Want to see how we can help your industry?</h2>
        <p style="color: rgba(255,255,255,0.7); max-width: 600px; margin: 0 auto 30px;">
            We tailor every solution to your specific sector. Let's talk about your unique challenges.
        </p>
        <a href="quote.php" class="btn btn-kilele-primary" style="padding: 15px 40px; border-radius: 50px; font-weight: 700;">Request a Free Consultation</a>
    </div>
</section>

<?php include 'includes/footer.php'; ?>