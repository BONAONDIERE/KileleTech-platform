<?php
$pageTitle = 'What We Offer – KileleTech';
include 'includes/header.php';
?>

<style>
    .offer-hero {
        background: linear-gradient(135deg, #0f1e33 0%, #1a2d47 100%);
        padding: 100px 0 80px;
        position: relative;
        overflow: hidden;
    }
    .offer-hero .orb {
        position: absolute;
        border-radius: 50%;
        background: rgba(41, 160, 142, 0.15);
        animation: float 8s ease-in-out infinite;
    }
    .offer-hero .orb-1 { width: 300px; height: 300px; right: -80px; top: -80px; animation-delay: 0s; }
    .offer-hero .orb-2 { width: 200px; height: 200px; left: -80px; bottom: -80px; animation-delay: 3s; }
    @keyframes float {
        0%, 100% { transform: translateY(0px) scale(1); }
        50% { transform: translateY(-30px) scale(1.05); }
    }
    
    .offer-card {
        background: #ffffff;
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 8px 24px rgba(15, 30, 51, 0.06);
        text-align: center;
        transition: all 0.4s ease;
        height: 100%;
        border: 1px solid transparent;
    }
    .offer-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(15, 30, 51, 0.12);
        border-color: rgba(41, 160, 142, 0.3);
    }
    .offer-card .offer-icon {
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
</style>

<!-- HERO -->
<section class="offer-hero">
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="container position-relative" style="z-index: 2;">
        <div class="text-center">
            <span style="display: inline-block; font-size: 13px; font-weight: 700; color: #F2B91E; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 12px;">OUR CATEGORIES</span>
            <h1 class="fw-bold" style="font-size: 3.5rem; color: #ffffff; margin-bottom: 20px;">What We Offer</h1>
            <p style="font-size: 1.2rem; color: rgba(255,255,255,0.8); max-width: 700px; margin: 0 auto;">
                Explore our categorized solutions—from professional consulting to cutting-edge hardware. We have a comprehensive package for every business need.
            </p>
        </div>
    </div>
</section>

<!-- OFFER CARDS -->
<section class="content-section" style="padding: 80px 0;">
    <div class="container">
        <div class="row g-4">
            <!-- Solutions -->
            <div class="col-md-6 col-lg-4">
                <a href="solutions.php" style="text-decoration: none; display: block; height: 100%;">
                    <div class="offer-card">
                        <div class="offer-icon"><i class="fas fa-lightbulb"></i></div>
                        <h3 style="font-weight: 700; color: #0f1e33; margin-bottom: 12px;">Solutions</h3>
                        <p style="color: #777; font-size: 0.95rem;">Professional consulting, business strategies, and end-to-end ICT transformation.</p>
                        <span style="font-size: 0.85rem; font-weight: 700; color: #29A08E;">Explore Solutions →</span>
                    </div>
                </a>
            </div>

            <!-- Services -->
            <div class="col-md-6 col-lg-4">
                <a href="services.php" style="text-decoration: none; display: block; height: 100%;">
                    <div class="offer-card">
                        <div class="offer-icon"><i class="fas fa-cogs"></i></div>
                        <h3 style="font-weight: 700; color: #0f1e33; margin-bottom: 12px;">Services</h3>
                        <p style="color: #777; font-size: 0.95rem;">Our 8 core service areas—from software development to security monitoring.</p>
                        <span style="font-size: 0.85rem; font-weight: 700; color: #29A08E;">Explore Services →</span>
                    </div>
                </a>
            </div>

            <!-- Systems -->
            <div class="col-md-6 col-lg-4">
                <a href="systems.php" style="text-decoration: none; display: block; height: 100%;">
                    <div class="offer-card">
                        <div class="offer-icon"><i class="fas fa-network-wired"></i></div>
                        <h3 style="font-weight: 700; color: #0f1e33; margin-bottom: 12px;">Systems</h3>
                        <p style="color: #777; font-size: 0.95rem;">Custom-built software and integrated systems tailored to your organization.</p>
                        <span style="font-size: 0.85rem; font-weight: 700; color: #29A08E;">Explore Systems →</span>
                    </div>
                </a>
            </div>

            <!-- Hardware -->
            <div class="col-md-6 col-lg-4">
                <a href="hardware.php" style="text-decoration: none; display: block; height: 100%;">
                    <div class="offer-card">
                        <div class="offer-icon"><i class="fas fa-server"></i></div>
                        <h3 style="font-weight: 700; color: #0f1e33; margin-bottom: 12px;">Hardware</h3>
                        <p style="color: #777; font-size: 0.95rem;">Enterprise-grade equipment—from servers to CCTV cameras, supplied and installed.</p>
                        <span style="font-size: 0.85rem; font-weight: 700; color: #29A08E;">Explore Hardware →</span>
                    </div>
                </a>
            </div>

            <!-- Kilele Market -->
            <div class="col-md-6 col-lg-4">
                <a href="market.php" style="text-decoration: none; display: block; height: 100%;">
                    <div class="offer-card">
                        <div class="offer-icon"><i class="fas fa-shopping-cart"></i></div>
                        <h3 style="font-weight: 700; color: #0f1e33; margin-bottom: 12px;">Kilele Market</h3>
                        <p style="color: #777; font-size: 0.95rem;">Order individual components—RAM, SSDs, cables, and smart devices directly.</p>
                        <span style="font-size: 0.85rem; font-weight: 700; color: #29A08E;">Visit Market →</span>
                    </div>
                </a>
            </div>

            <!-- Projects -->
            <div class="col-md-6 col-lg-4">
                <a href="projects.php" style="text-decoration: none; display: block; height: 100%;">
                    <div class="offer-card">
                        <div class="offer-icon"><i class="fas fa-briefcase"></i></div>
                        <h3 style="font-weight: 700; color: #0f1e33; margin-bottom: 12px;">Our Projects</h3>
                        <p style="color: #777; font-size: 0.95rem;">See real-world examples of the work we have delivered for our clients.</p>
                        <span style="font-size: 0.85rem; font-weight: 700; color: #29A08E;">View Projects →</span>
                    </div>
                </a>
            </div>
        </div>

        <div class="text-center mt-5">
            <a href="consultation.php" class="btn btn-kilele-primary" style="padding: 15px 40px; border-radius: 50px; font-weight: 700;">Get a Free Consultation →</a>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>