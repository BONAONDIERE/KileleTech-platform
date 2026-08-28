<?php
$pageTitle = 'Systems – KileleTech';
include 'includes/header.php';
?>
<!-- [COPY THE EXACT SAME STRUCTURE AS solutions.php BUT REPLACE THE CARDS WITH THE SYSTEM NAMES AND ADD THE MULTI-BUTTONS] -->

<style>
    .systems-hero {
        background: linear-gradient(135deg, #0f1e33 0%, #1a2d47 100%);
        padding: 80px 0 60px;
        position: relative;
        overflow: hidden;
    }
    .systems-hero .orb {
        position: absolute;
        border-radius: 50%;
        background: rgba(41, 160, 142, 0.1);
        animation: float 6s ease-in-out infinite;
    }
    .systems-hero .orb-1 { width: 300px; height: 300px; right: -50px; top: -50px; animation-delay: 0s; }
    .systems-hero .orb-2 { width: 200px; height: 200px; left: -50px; bottom: -50px; animation-delay: 2s; }
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
    }
    
    .system-card {
        background: #ffffff;
        border-radius: 20px;
        padding: 35px;
        box-shadow: 0 8px 24px rgba(15, 30, 51, 0.08);
        transition: all 0.4s ease;
        height: 100%;
        border: 1px solid transparent;
    }
    .system-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(15, 30, 51, 0.12);
        border-color: rgba(41, 160, 142, 0.3);
    }
    .system-icon {
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

<section class="systems-hero">
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="container">
        <div class="text-center">
            <span style="display: inline-block; font-size: 13px; font-weight: 700; color: #F2B91E; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 10px;">CUSTOM-BUILT SOFTWARE</span>
            <h1 class="fw-bold" style="font-size: 3.5rem; color: #ffffff; margin-bottom: 20px;">Systems</h1>
            <p style="font-size: 1.2rem; color: rgba(255,255,255,0.7); max-width: 600px; margin: 0 auto;">
                Purpose-built systems we have designed and shipped for real organizations.
            </p>
        </div>
    </div>
</section>

<section class="content-section" style="padding: 80px 0; background: #ffffff;">
    <div class="container">
        <div class="row g-4">
            <!-- Monitoring System -->
            <div class="col-md-6 col-lg-4">
                <div class="system-card">
                    <div class="system-icon"><i class="fas fa-chart-line"></i></div>
                    <h3 style="font-weight: 700; color: #0f1e33; margin-bottom: 10px; font-size: 1.2rem;">Monitoring System</h3>
                    <p style="color: #777; font-size: 0.95rem; line-height: 1.7;">Real-time dashboards and alerts for your critical infrastructure and operations.</p>
                    <div class="action-btn-group">
                        <a href="consultation.php" class="action-btn primary"> Consult</a>
                        <a href="quote.php" class="action-btn outline">📄 Quote</a>
                    </div>
                </div>
            </div>

            <!-- Helpdesk System -->
            <div class="col-md-6 col-lg-4">
                <div class="system-card">
                    <div class="system-icon"><i class="fas fa-headset"></i></div>
                    <h3 style="font-weight: 700; color: #0f1e33; margin-bottom: 10px; font-size: 1.2rem;">Helpdesk System</h3>
                    <p style="color: #777; font-size: 0.95rem; line-height: 1.7;">Streamlined ticketing and support workflows to enhance customer satisfaction.</p>
                    <div class="action-btn-group">
                        <a href="consultation.php" class="action-btn primary"> Consult</a>
                        <a href="quote.php" class="action-btn outline">📄 Quote</a>
                    </div>
                </div>
            </div>

            <!-- Intranet System -->
            <div class="col-md-6 col-lg-4">
                <div class="system-card">
                    <div class="system-icon"><i class="fas fa-building"></i></div>
                    <h3 style="font-weight: 700; color: #0f1e33; margin-bottom: 10px; font-size: 1.2rem;">Intranet System</h3>
                    <p style="color: #777; font-size: 0.95rem; line-height: 1.7;">Secure internal portals for communication, documents, and employee tools.</p>
                    <div class="action-btn-group">
                        <a href="consultation.php" class="action-btn primary"> Consult</a>
                        <a href="quote.php" class="action-btn outline">📄 Quote</a>
                    </div>
                </div>
            </div>

            <!-- ERP System -->
            <div class="col-md-6 col-lg-4">
                <div class="system-card">
                    <div class="system-icon"><i class="fas fa-cubes"></i></div>
                    <h3 style="font-weight: 700; color: #0f1e33; margin-bottom: 10px; font-size: 1.2rem;">ERP System</h3>
                    <p style="color: #777; font-size: 0.95rem; line-height: 1.7;">Integrated resource planning to manage finances, inventory, and HR in one place.</p>
                    <div class="action-btn-group">
                        <a href="consultation.php" class="action-btn primary"> Consult</a>
                        <a href="quote.php" class="action-btn outline">📄 Quote</a>
                    </div>
                </div>
            </div>

            <!-- CRM System -->
            <div class="col-md-6 col-lg-4">
                <div class="system-card">
                    <div class="system-icon"><i class="fas fa-users"></i></div>
                    <h3 style="font-weight: 700; color: #0f1e33; margin-bottom: 10px; font-size: 1.2rem;">CRM System</h3>
                    <p style="color: #777; font-size: 0.95rem; line-height: 1.7;">Manage leads, customer relationships, and sales pipelines efficiently.</p>
                    <div class="action-btn-group">
                        <a href="consultation.php" class="action-btn primary"> Consult</a>
                        <a href="quote.php" class="action-btn outline">📄 Quote</a>
                    </div>
                </div>
            </div>

            <!-- Custom Mobile App -->
            <div class="col-md-6 col-lg-4">
                <div class="system-card">
                    <div class="system-icon"><i class="fas fa-mobile-alt"></i></div>
                    <h3 style="font-weight: 700; color: #0f1e33; margin-bottom: 10px; font-size: 1.2rem;">Custom Mobile App</h3>
                    <p style="color: #777; font-size: 0.95rem; line-height: 1.7;">Native and cross-platform mobile applications tailored to your business.</p>
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