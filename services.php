<?php
$pageTitle = 'Services – KileleTech';
include 'includes/header.php';
?>

<style>
    .services-hero {
        background: linear-gradient(135deg, #0f1e33 0%, #1a2d47 100%);
        padding: 80px 0 60px;
        position: relative;
        overflow: hidden;
    }
    .services-hero .orb {
        position: absolute;
        border-radius: 50%;
        background: rgba(41, 160, 142, 0.1);
        animation: float 6s ease-in-out infinite;
    }
    .services-hero .orb-1 { width: 300px; height: 300px; right: -50px; top: -50px; animation-delay: 0s; }
    .services-hero .orb-2 { width: 200px; height: 200px; left: -50px; bottom: -50px; animation-delay: 2s; }
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
    }
    
    .service-card {
        background: #ffffff;
        border-radius: 20px;
        padding: 35px;
        box-shadow: 0 8px 24px rgba(15, 30, 51, 0.08);
        transition: all 0.4s ease;
        height: 100%;
        border: 1px solid transparent;
    }
    .service-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(15, 30, 51, 0.12);
        border-color: rgba(41, 160, 142, 0.3);
    }
    .service-icon {
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

<section class="services-hero">
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="container">
        <div class="text-center">
            <span style="display: inline-block; font-size: 13px; font-weight: 700; color: #F2B91E; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 10px;">INFRASTRUCTURE SERVICES</span>
            <h1 class="fw-bold" style="font-size: 3.5rem; color: #ffffff; margin-bottom: 20px;">Our Services</h1>
            <p style="font-size: 1.2rem; color: rgba(255,255,255,0.7); max-width: 600px; margin: 0 auto;">
                From installation to ongoing maintenance, we deliver comprehensive infrastructure services.
            </p>
        </div>
    </div>
</section>

<section class="content-section" style="padding: 80px 0; background: #ffffff;">
    <div class="container">
        <div class="row g-4">
            <!-- Software Development -->
            <div class="col-md-6 col-lg-3">
                <div class="service-card">
                    <div class="service-icon"><i class="fas fa-code"></i></div>
                    <h3 style="font-weight: 700; color: #0f1e33; margin-bottom: 10px; font-size: 1.1rem;">Software Development</h3>
                    <p style="color: #777; font-size: 0.95rem; line-height: 1.7;">Custom-built applications and systems designed for your exact business needs.</p>
                    <div class="action-btn-group">
                        <a href="quote.php" class="action-btn primary">📄 Quote</a>
                        <a href="consultation.php" class="action-btn outline"> Consult</a>
                    </div>
                </div>
            </div>

            <!-- Web Development & Hosting -->
            <div class="col-md-6 col-lg-3">
                <div class="service-card">
                    <div class="service-icon"><i class="fas fa-globe"></i></div>
                    <h3 style="font-weight: 700; color: #0f1e33; margin-bottom: 10px; font-size: 1.1rem;">Web Development & Hosting</h3>
                    <p style="color: #777; font-size: 0.95rem; line-height: 1.7;">Responsive websites, secure hosting, and continuous performance monitoring.</p>
                    <div class="action-btn-group">
                        <a href="quote.php" class="action-btn primary">📄 Quote</a>
                        <a href="consultation.php" class="action-btn outline"> Consult</a>
                    </div>
                </div>
            </div>

            <!-- Hardware Supply & Installation -->
            <div class="col-md-6 col-lg-3">
                <div class="service-card">
                    <div class="service-icon"><i class="fas fa-server"></i></div>
                    <h3 style="font-weight: 700; color: #0f1e33; margin-bottom: 10px; font-size: 1.1rem;">Hardware Supply & Installation</h3>
                    <p style="color: #777; font-size: 0.95rem; line-height: 1.7;">Enterprise-grade equipment, sourced, configured, and installed by our experts.</p>
                    <div class="action-btn-group">
                        <a href="quote.php" class="action-btn primary">📄 Quote</a>
                        <a href="consultation.php" class="action-btn outline"> Consult</a>
                    </div>
                </div>
            </div>

            <!-- CCTV & Security Systems -->
            <div class="col-md-6 col-lg-3">
                <div class="service-card">
                    <div class="service-icon"><i class="fas fa-video"></i></div>
                    <h3 style="font-weight: 700; color: #0f1e33; margin-bottom: 10px; font-size: 1.1rem;">CCTV & Security Systems</h3>
                    <p style="color: #777; font-size: 0.95rem; line-height: 1.7;">Advanced surveillance, access control, and monitoring for total peace of mind.</p>
                    <div class="action-btn-group">
                        <a href="quote.php" class="action-btn primary">📄 Quote</a>
                        <a href="consultation.php" class="action-btn outline"> Consult</a>
                    </div>
                </div>
            </div>

            <!-- ICT Consulting -->
            <div class="col-md-6 col-lg-3">
                <div class="service-card">
                    <div class="service-icon"><i class="fas fa-clipboard-list"></i></div>
                    <h3 style="font-weight: 700; color: #0f1e33; margin-bottom: 10px; font-size: 1.1rem;">ICT Consulting</h3>
                    <p style="color: #777; font-size: 0.95rem; line-height: 1.7;">Strategic guidance, digital transformation, and expert technology planning.</p>
                    <div class="action-btn-group">
                        <a href="consultation.php" class="action-btn primary"> Consult</a>
                        <a href="quote.php" class="action-btn outline">📄 Quote</a>
                    </div>
                </div>
            </div>

            <!-- Database Management -->
            <div class="col-md-6 col-lg-3">
                <div class="service-card">
                    <div class="service-icon"><i class="fas fa-database"></i></div>
                    <h3 style="font-weight: 700; color: #0f1e33; margin-bottom: 10px; font-size: 1.1rem;">Database Management</h3>
                    <p style="color: #777; font-size: 0.95rem; line-height: 1.7;">Design, optimization, and secure management of your mission-critical data.</p>
                    <div class="action-btn-group">
                        <a href="quote.php" class="action-btn primary">📄 Quote</a>
                        <a href="consultation.php" class="action-btn outline"> Consult</a>
                    </div>
                </div>
            </div>

            <!-- Security Monitoring -->
            <div class="col-md-6 col-lg-3">
                <div class="service-card">
                    <div class="service-icon"><i class="fas fa-shield-halved"></i></div>
                    <h3 style="font-weight: 700; color: #0f1e33; margin-bottom: 10px; font-size: 1.1rem;">Security Monitoring</h3>
                    <p style="color: #777; font-size: 0.95rem; line-height: 1.7;">24/7 threat detection and rapid response to protect your business.</p>
                    <div class="action-btn-group">
                        <a href="consultation.php" class="action-btn primary"> Consult</a>
                        <a href="quote.php" class="action-btn outline">📄 Quote</a>
                    </div>
                </div>
            </div>

            <!-- Firewall Solutions -->
            <div class="col-md-6 col-lg-3">
                <div class="service-card">
                    <div class="service-icon"><i class="fas fa-fire"></i></div>
                    <h3 style="font-weight: 700; color: #0f1e33; margin-bottom: 10px; font-size: 1.1rem;">Firewall Solutions</h3>
                    <p style="color: #777; font-size: 0.95rem; line-height: 1.7;">Advanced network security to keep unauthorized access out and data safe.</p>
                    <div class="action-btn-group">
                        <a href="quote.php" class="action-btn primary">📄 Quote</a>
                        <a href="consultation.php" class="action-btn outline"> Consult</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>