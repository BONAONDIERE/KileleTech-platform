<?php
$pageTitle = 'About Us – KileleTech';
include 'includes/header.php';
?>

<style>
    /* ========== PREMIUM HERO ========== */
    .about-hero {
        background: linear-gradient(135deg, #0f1e33 0%, #1a2d47 100%);
        padding: 120px 0 90px;
        position: relative;
        overflow: hidden;
    }
    .about-hero::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background-image: 
            linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
        background-size: 40px 40px;
    }
    .about-hero .orb {
        position: absolute;
        border-radius: 50%;
        background: rgba(41, 160, 142, 0.15);
        animation: float 8s ease-in-out infinite;
        filter: blur(2px);
    }
    .about-hero .orb-1 { width: 350px; height: 350px; right: -80px; top: -80px; animation-delay: 0s; }
    .about-hero .orb-2 { width: 250px; height: 250px; left: -80px; bottom: -80px; animation-delay: 3s; }
    .about-hero .orb-3 { width: 150px; height: 150px; left: 50%; top: 20%; animation-delay: 6s; }
    @keyframes float {
        0%, 100% { transform: translateY(0px) scale(1); }
        50% { transform: translateY(-30px) scale(1.05); }
    }

    /* ========== GLASS CARDS ========== */
    .glass-card {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 24px;
        padding: 40px;
        transition: all 0.4s ease;
        height: 100%;
        position: relative;
        overflow: hidden;
    }
    .glass-card::after {
        content: '';
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 4px;
        background: linear-gradient(90deg, #29A08E, #F2B91E);
        opacity: 0;
        transition: opacity 0.4s;
    }
    .glass-card:hover {
        transform: translateY(-8px);
        background: rgba(255, 255, 255, 0.08);
        border-color: rgba(41, 160, 142, 0.3);
    }
    .glass-card:hover::after {
        opacity: 1;
    }
    .glass-card .icon {
        font-size: 2.5rem;
        color: #29A08E;
        margin-bottom: 20px;
    }

    /* ========== STAT ITEMS ========== */
    .stat-item {
        background: #ffffff;
        border-radius: 20px;
        padding: 30px 20px;
        text-align: center;
        box-shadow: 0 4px 12px rgba(0,0,0,0.04);
        transition: all 0.3s ease;
        border: 1px solid #f0f0f0;
    }
    .stat-item:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.08);
        border-color: rgba(41, 160, 142, 0.3);
    }
    .stat-item strong {
        font-size: 2.5rem;
        font-weight: 800;
        color: #29A08E;
        display: block;
    }

    /* ========== EXPERTISE CARDS ========== */
    .expertise-card {
        background: #ffffff;
        border-radius: 20px;
        padding: 40px 30px;
        box-shadow: 0 8px 24px rgba(15, 30, 51, 0.06);
        text-align: center;
        transition: all 0.4s ease;
        height: 100%;
        border: 1px solid transparent;
    }
    .expertise-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(15, 30, 51, 0.12);
        border-color: rgba(41, 160, 142, 0.3);
    }
    .expertise-card .exp-icon {
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

    /* ========== INDUSTRIES STRIP ========== */
    .industries-strip {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        justify-content: center;
    }
    .industry-pill {
        background: #ffffff;
        border: 1px solid #f0f0f0;
        padding: 12px 24px;
        border-radius: 50px;
        font-weight: 600;
        color: #0f1e33;
        box-shadow: 0 2px 6px rgba(0,0,0,0.04);
        transition: 0.3s;
    }
    .industry-pill:hover {
        background: #29A08E;
        color: #ffffff;
        border-color: #29A08E;
    }
</style>

<!-- ========== PREMIUM HERO ========== -->
<section class="about-hero">
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>
    <div class="container position-relative" style="z-index: 2;">
        <div class="text-center">
            <span style="display: inline-block; font-size: 13px; font-weight: 700; color: #F2B91E; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 12px;">WHO WE ARE</span>
            <h1 class="fw-bold" style="font-size: 3.8rem; color: #ffffff; margin-bottom: 20px;">Beyond Technology.<br>Your Trusted ICT Partner.</h1>
            <p style="font-size: 1.2rem; color: rgba(255,255,255,0.8); max-width: 700px; margin: 0 auto;">
                A Nairobi-based technology consultancy delivering end-to-end ICT solutions to businesses, government agencies, and non-profits.
            </p>
        </div>
    </div>
</section>

<!-- ========== WHO WE ARE ========== -->
<section class="content-section" style="padding: 90px 0;">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <span class="why-choose-us__eyebrow">Who We Are</span>
                <h2 class="why-choose-us__heading" style="text-align:left; font-size: 2.5rem;">More than just an IT company</h2>
                <p style="color: var(--kilele-text-light); line-height: 1.8; margin-bottom: 16px;">
                    Kilele Tech is a technology consultancy firm based in Nairobi, Kenya. We provide end-to-end ICT
                    solutions to businesses, government agencies, and non-profits. Our mission is to help
                    organizations leverage technology to drive growth, improve security, and streamline operations.
                </p>
                <p style="color: var(--kilele-text-light); line-height: 1.8;">
                    We work with you, understand your business, and build secure, reliable, and innovative solutions 
                    tailored to your unique needs. From custom software to advanced security systems, we are your 
                    one-stop partner for all things ICT.
                </p>
            </div>
            <div class="col-lg-6">
                <div class="row g-4">
                    <div class="col-6">
                        <div class="stat-item">
                            <strong>2025</strong>
                            <span style="color: #777;">Year Founded</span>
                        </div>
                    </div>
            <div class="col-6">
                 <a href="what-we-offer.php" style="text-decoration: none; display: block; height: 100%;">
                   <div class="stat-item">
                   <strong>8</strong>
                   <span style="color: #777;">Solution Areas</span>
                   </div>
                 </a>
            </div>
                    <div class="col-6">
                        <div class="stat-item">
                            <strong>100+</strong>
                            <span style="color: #777;">Projects Delivered</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-item">
                            <strong>100%</strong>
                            <span style="color: #777;">Client Satisfaction</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========== MISSION, VISION & VALUES ========== -->
<section class="content-section content-section--alt" style="padding: 90px 0; background: #0f1e33; position: relative; overflow: hidden;">
    <div class="container position-relative" style="z-index: 2;">
        <div class="text-center mb-5">
            <span style="display: inline-block; font-size: 13px; font-weight: 700; color: #F2B91E; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 10px;">Our Foundation</span>
            <h2 style="font-weight: 800; color: #ffffff; font-size: 2.8rem; margin-bottom: 15px;">What Drives Us</h2>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="glass-card">
                    <div class="icon"><i class="fas fa-bullseye"></i></div>
                    <h3 style="font-weight: 700; color: #ffffff; margin-bottom: 15px;">Our Mission</h3>
                    <p style="color: rgba(255,255,255,0.7); line-height: 1.7;">To deliver innovative, reliable, and secure ICT solutions that empower organizations to thrive in the digital age.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="glass-card">
                    <div class="icon"><i class="fas fa-eye"></i></div>
                    <h3 style="font-weight: 700; color: #ffffff; margin-bottom: 15px;">Our Vision</h3>
                    <p style="color: rgba(255,255,255,0.7); line-height: 1.7;">To be East Africa's most trusted ICT partner, known for technical excellence, security, and client-centric solutions.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="glass-card">
                    <div class="icon"><i class="fas fa-heart"></i></div>
                    <h3 style="font-weight: 700; color: #ffffff; margin-bottom: 15px;">Our Values</h3>
                    <p style="color: rgba(255,255,255,0.7); line-height: 1.7;">Integrity, Innovation, Security, and Client Success. These values drive everything we do.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========== THE KILELE DIFFERENCE (EXPERTISE) ========== -->
<section class="content-section" style="padding: 90px 0;">
    <div class="container">
        <div class="text-center mb-5">
            <span class="why-choose-us__eyebrow">The Kilele Difference</span>
            <h2 class="why-choose-us__heading" style="font-size: 2.5rem;">Why clients trust us</h2>
            <p style="color: var(--kilele-text-light); max-width: 600px; margin: 0 auto;">
                We don't just build technology—we build relationships, trust, and long-term success.
            </p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="expertise-card">
                    <div class="exp-icon"><i class="fas fa-shield-halved"></i></div>
                    <h3 style="font-weight: 700; color: #0f1e33; margin-bottom: 12px;">Security-First Approach</h3>
                    <p style="color: #777; font-size: 0.95rem;">We build security into every layer of your technology—from code to deployment.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="expertise-card">
                    <div class="exp-icon"><i class="fas fa-lightbulb"></i></div>
                    <h3 style="font-weight: 700; color: #0f1e33; margin-bottom: 12px;">Innovative Solutions</h3>
                    <p style="color: #777; font-size: 0.95rem;">We stay ahead of the curve, applying the latest technology to solve your unique business challenges.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="expertise-card">
                    <div class="exp-icon"><i class="fas fa-handshake"></i></div>
                    <h3 style="font-weight: 700; color: #0f1e33; margin-bottom: 12px;">Client-Centric</h3>
                    <p style="color: #777; font-size: 0.95rem;">We listen first, then build. Your success is our success, and we treat your business like our own.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========== INDUSTRIES WE SERVE ========== -->
<section class="content-section content-section--alt" style="padding: 70px 0; background: #f8fafb;">
    <div class="container">
        <div class="text-center mb-4">
            <span class="why-choose-us__eyebrow">Who We Serve</span>
            <h2 class="why-choose-us__heading" style="font-size: 2rem;">Industries We Serve</h2>
        </div>
        <div class="industries-strip">
            <span class="industry-pill">Businesses</span>
            <span class="industry-pill">Government Agencies</span>
            <span class="industry-pill">Non-Profits (NGOs)</span>
            <span class="industry-pill">Educational Institutions</span>
            <span class="industry-pill">Healthcare</span>
            <span class="industry-pill">Financial Services</span>
        </div>
    </div>
</section>

<!-- ========== CTA ========== -->
<section class="content-section text-center" style="padding: 90px 0; background: #0f1e33;">
    <div class="container">
        <h2 style="color: #ffffff; font-weight: 800; font-size: 2.8rem; margin-bottom: 15px;">Ready to work with us?</h2>
        <p style="color: rgba(255,255,255,0.7); max-width: 600px; margin: 0 auto 30px;">
            Let's talk about what your organization needs. From software to security, we have you covered.
        </p>
        <a href="quote.php" class="btn btn-kilele-primary" style="padding: 15px 40px; border-radius: 50px; font-weight: 700;">Get a Free Consultation →</a>
    </div>
</section>

<?php include 'includes/footer.php'; ?>