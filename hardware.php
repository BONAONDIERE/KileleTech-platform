<?php
$pageTitle = 'Hardware – KileleTech';
include 'includes/header.php';
?>
<!-- [COPY THE SAME STRUCTURE AS solutions.php BUT REPLACE THE CARDS WITH HARDWARE ITEMS] -->

<style>
    .hardware-hero {
        background: linear-gradient(135deg, #0f1e33 0%, #1a2d47 100%);
        padding: 80px 0 60px;
        position: relative;
        overflow: hidden;
    }
    .hardware-hero .orb {
        position: absolute;
        border-radius: 50%;
        background: rgba(41, 160, 142, 0.1);
        animation: float 6s ease-in-out infinite;
    }
    .hardware-hero .orb-1 { width: 300px; height: 300px; right: -50px; top: -50px; animation-delay: 0s; }
    .hardware-hero .orb-2 { width: 200px; height: 200px; left: -50px; bottom: -50px; animation-delay: 2s; }
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
    }
    
    .hardware-card {
        background: #ffffff;
        border-radius: 20px;
        padding: 35px;
        box-shadow: 0 8px 24px rgba(15, 30, 51, 0.08);
        transition: all 0.4s ease;
        height: 100%;
        border: 1px solid transparent;
    }
    .hardware-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(15, 30, 51, 0.12);
        border-color: rgba(41, 160, 142, 0.3);
    }
    .hardware-icon {
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

<section class="hardware-hero">
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="container">
        <div class="text-center">
            <span style="display: inline-block; font-size: 13px; font-weight: 700; color: #F2B91E; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 10px;">EQUIPMENT & MACHINES</span>
            <h1 class="fw-bold" style="font-size: 3.5rem; color: #ffffff; margin-bottom: 20px;">Hardware</h1>
            <p style="font-size: 1.2rem; color: rgba(255,255,255,0.7); max-width: 600px; margin: 0 auto;">
                Enterprise-grade hardware, sourced, sold, and installed by people who also manage it.
            </p>
        </div>
    </div>
</section>

<section class="content-section" style="padding: 80px 0; background: #ffffff;">
    <div class="container">
        <div class="row g-4">
            <!-- Servers -->
            <div class="col-md-6 col-lg-3">
                <div class="hardware-card">
                    <div class="hardware-icon"><i class="fas fa-server"></i></div>
                    <h3 style="font-weight: 700; color: #0f1e33; margin-bottom: 10px; font-size: 1.1rem;">Servers</h3>
                    <p style="color: #777; font-size: 0.95rem; line-height: 1.7;">High-performance rack and tower servers for your data and applications.</p>
                    <div class="action-btn-group">
                        <a href="quote.php" class="action-btn primary">📄 Quote</a>
                        <a href="consultation.php" class="action-btn outline"> Consult</a>
                    </div>
                </div>
            </div>

            <!-- Workstations & Accessories -->
            <div class="col-md-6 col-lg-3">
                <div class="hardware-card">
                    <div class="hardware-icon"><i class="fas fa-desktop"></i></div>
                    <h3 style="font-weight: 700; color: #0f1e33; margin-bottom: 10px; font-size: 1.1rem;">Workstations & Accessories</h3>
                    <p style="color: #777; font-size: 0.95rem; line-height: 1.7;">Reliable desktops, laptops, keyboards, monitors, and more.</p>
                    <div class="action-btn-group">
                        <a href="quote.php" class="action-btn primary">📄 Quote</a>
                        <a href="consultation.php" class="action-btn outline"> Consult</a>
                    </div>
                </div>
            </div>

            <!-- CCTV Cameras -->
            <div class="col-md-6 col-lg-3">
                <div class="hardware-card">
                    <div class="hardware-icon"><i class="fas fa-video"></i></div>
                    <h3 style="font-weight: 700; color: #0f1e33; margin-bottom: 10px; font-size: 1.1rem;">CCTV Cameras</h3>
                    <p style="color: #777; font-size: 0.95rem; line-height: 1.7;">High-definition indoor and outdoor cameras for total surveillance coverage.</p>
                    <div class="action-btn-group">
                        <a href="quote.php" class="action-btn primary">📄 Quote</a>
                        <a href="consultation.php" class="action-btn outline"> Consult</a>
                    </div>
                </div>
            </div>

            <!-- Networking Equipment -->
            <div class="col-md-6 col-lg-3">
                <div class="hardware-card">
                    <div class="hardware-icon"><i class="fas fa-network-wired"></i></div>
                    <h3 style="font-weight: 700; color: #0f1e33; margin-bottom: 10px; font-size: 1.1rem;">Networking Equipment</h3>
                    <p style="color: #777; font-size: 0.95rem; line-height: 1.7;">Routers, switches, firewalls, and access points for a robust network.</p>
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