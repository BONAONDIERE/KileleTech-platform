<?php
$pageTitle = 'Solutions – KileleTech';
include 'includes/header.php';
?>

<style>
    .solutions-hero {
        background: linear-gradient(135deg, #0f1e33 0%, #1a2d47 100%);
        padding: 80px 0 60px;
        position: relative;
        overflow: hidden;
    }
    .solutions-hero .orb {
        position: absolute;
        border-radius: 50%;
        background: rgba(41, 160, 142, 0.1);
        animation: float 6s ease-in-out infinite;
    }
    .solutions-hero .orb-1 { width: 300px; height: 300px; right: -50px; top: -50px; animation-delay: 0s; }
    .solutions-hero .orb-2 { width: 200px; height: 200px; left: -50px; bottom: -50px; animation-delay: 2s; }
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
    }
    
    .solution-card {
        background: #ffffff;
        border-radius: 20px;
        padding: 35px;
        box-shadow: 0 8px 24px rgba(15, 30, 51, 0.08);
        transition: all 0.4s ease;
        height: 100%;
        border: 1px solid transparent;
    }
    .solution-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(15, 30, 51, 0.12);
        border-color: rgba(41, 160, 142, 0.3);
    }
    .solution-icon {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        background: rgba(41, 160, 142, 0.1);
        color: #29A08E;
        margin-bottom: 20px;
    }
    
    .action-btn-group {
        display: flex;
        gap: 10px;
        justify-content: center;
        flex-wrap: wrap;
        margin-top: 20px;
    }
    .action-btn {
        display: inline-block;
        padding: 10px 20px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.85rem;
        text-decoration: none;
        transition: 0.3s;
    }
    .action-btn.primary {
        background: #29A08E;
        color: #fff;
    }
    .action-btn.primary:hover {
        background: #1e7a6b;
    }
    .action-btn.outline {
        background: #fff;
        color: #29A08E;
        border: 1px solid #29A08E;
    }
    .action-btn.outline:hover {
        background: #29A08E;
        color: #fff;
    }
</style>

<section class="solutions-hero">
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="container">
        <div class="text-center">
            <span style="display: inline-block; font-size: 13px; font-weight: 700; color: #F2B91E; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 10px;">PROFESSIONAL SERVICES</span>
            <h1 class="fw-bold" style="font-size: 3.5rem; color: #ffffff; margin-bottom: 20px;">Solutions</h1>
            <p style="font-size: 1.2rem; color: rgba(255,255,255,0.7); max-width: 600px; margin: 0 auto;">
                End-to-end professional services designed to take your business from idea to implementation.
            </p>
        </div>
    </div>
</section>

<section class="content-section" style="padding: 80px 0; background: #ffffff;">
    <div class="container">
        <div class="row g-4">
            <!-- Business Solutions -->
            <div class="col-md-6 col-lg-4">
                <div class="solution-card">
                    <div class="solution-icon"><i class="fas fa-briefcase"></i></div>
                    <h3 style="font-weight: 700; color: #0f1e33; margin-bottom: 10px; font-size: 1.2rem;">Business Solutions</h3>
                    <p style="color: #777; font-size: 0.95rem; line-height: 1.7;">Tailored ICT strategies and system integrations to streamline your operations.</p>
                    <div class="action-btn-group">
                        <a href="consultation.php" class="action-btn primary"> Consult</a>
                        <a href="quote.php" class="action-btn outline">📄 Quote</a>
                    </div>
                </div>
            </div>

            <!-- Cloud Solutions -->
            <div class="col-md-6 col-lg-4">
                <div class="solution-card">
                    <div class="solution-icon"><i class="fas fa-cloud"></i></div>
                    <h3 style="font-weight: 700; color: #0f1e33; margin-bottom: 10px; font-size: 1.2rem;">Cloud Solutions</h3>
                    <p style="color: #777; font-size: 0.95rem; line-height: 1.7;">Migrate, manage, and optimize your infrastructure on the cloud.</p>
                    <div class="action-btn-group">
                        <a href="consultation.php" class="action-btn primary"> Consult</a>
                        <a href="quote.php" class="action-btn outline">📄 Quote</a>
                    </div>
                </div>
            </div>

            <!-- Security Solutions -->
            <div class="col-md-6 col-lg-4">
                <div class="solution-card">
                    <div class="solution-icon"><i class="fas fa-shield-halved"></i></div>
                    <h3 style="font-weight: 700; color: #0f1e33; margin-bottom: 10px; font-size: 1.2rem;">Security Solutions</h3>
                    <p style="color: #777; font-size: 0.95rem; line-height: 1.7;">Protect your physical and digital assets with CCTV, firewalls, and access control.</p>
                    <div class="action-btn-group">
                        <a href="consultation.php" class="action-btn primary"> Consult</a>
                        <a href="quote.php" class="action-btn outline">📄 Quote</a>
                    </div>
                </div>
            </div>

            <!-- Custom Software Solutions -->
            <div class="col-md-6 col-lg-4">
                <div class="solution-card">
                    <div class="solution-icon"><i class="fas fa-code"></i></div>
                    <h3 style="font-weight: 700; color: #0f1e33; margin-bottom: 10px; font-size: 1.2rem;">Custom Software</h3>
                    <p style="color: #777; font-size: 0.95rem; line-height: 1.7;">Build bespoke applications that perfectly match your business requirements.</p>
                    <div class="action-btn-group">
                        <a href="consultation.php" class="action-btn primary"> Consult</a>
                        <a href="quote.php" class="action-btn outline">📄 Quote</a>
                    </div>
                </div>
            </div>

            <!-- Infrastructure Solutions -->
            <div class="col-md-6 col-lg-4">
                <div class="solution-card">
                    <div class="solution-icon"><i class="fas fa-server"></i></div>
                    <h3 style="font-weight: 700; color: #0f1e33; margin-bottom: 10px; font-size: 1.2rem;">Infrastructure</h3>
                    <p style="color: #777; font-size: 0.95rem; line-height: 1.7;">Set up and maintain robust networks, servers, and hardware infrastructure.</p>
                    <div class="action-btn-group">
                        <a href="consultation.php" class="action-btn primary"> Consult</a>
                        <a href="quote.php" class="action-btn outline">📄 Quote</a>
                    </div>
                </div>
            </div>

            <!-- E-Commerce Solutions -->
            <div class="col-md-6 col-lg-4">
                <div class="solution-card">
                    <div class="solution-icon"><i class="fas fa-shopping-cart"></i></div>
                    <h3 style="font-weight: 700; color: #0f1e33; margin-bottom: 10px; font-size: 1.2rem;">E-Commerce</h3>
                    <p style="color: #777; font-size: 0.95rem; line-height: 1.7;">Launch and manage online stores with secure payment gateways.</p>
                    <div class="action-btn-group">
                        <a href="consultation.php" class="action-btn primary"> Consult</a>
                        <a href="quote.php" class="action-btn outline">📄 Quote</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>