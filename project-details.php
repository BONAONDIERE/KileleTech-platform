<?php
$pageTitle = 'Project Details – KileleTech';
include 'includes/header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$projects = [
    1 => [
        'title' => 'CCTV & Access Control Deployment',
        'category' => 'Security',
        'description' => '<p>We designed and installed a complete security system for a corporate client in Nairobi, delivering peace of mind and total visibility over their premises.</p>
        <h4>What We Did:</h4>
        <ul>
            <li>Installed 24 high-definition IP cameras.</li>
            <li>Configured biometric access control systems.</li>
            <li>Set up remote monitoring from smartphones.</li>
        </ul>
        <h4>Technologies Used:</h4>
        <p>IP Cameras, Biometrics, DVR/NVR, Remote Access.</p>'
    ],
    2 => [
        'title' => 'Network & Server Setup',
        'category' => 'Infrastructure',
        'description' => '<p>We designed and deployed a robust server infrastructure for a growing NGO, ensuring their data is secure, accessible, and reliable.</p>
        <h4>What We Did:</h4>
        <ul>
            <li>Installed and configured enterprise-grade servers.</li>
            <li>Set up Active Directory and file sharing.</li>
            <li>Configured VPN for remote employees.</li>
        </ul>
        <h4>Technologies Used:</h4>
        <p>Windows Server, Linux, VPN, Active Directory.</p>'
    ],
    3 => [
        'title' => 'Kilele Market E-Commerce Platform',
        'category' => 'Web & Software',
        'description' => '<p>We built a custom e-commerce platform for sourcing and selling ICT components, making it easy for customers to find and buy what they need.</p>
        <h4>What We Did:</h4>
        <ul>
            <li>Created a full product catalog with search and filters.</li>
            <li>Integrated secure payment gateways.</li>
            <li>Developed an admin dashboard for inventory management.</li>
        </ul>
        <h4>Technologies Used:</h4>
        <p>PHP, MySQL, Bootstrap, Payment API.</p>'
    ],
    4 => [
        'title' => 'Firewall & Security Monitoring',
        'category' => 'Security',
        'description' => '<p>We implemented 24/7 network monitoring and firewall solutions for a financial services firm, protecting their critical data and systems.</p>
        <h4>What We Did:</h4>
        <ul>
            <li>Deployed next-generation firewall systems.</li>
            <li>Set up real-time alerting for security threats.</li>
            <li>Created a security operations dashboard.</li>
        </ul>
        <h4>Technologies Used:</h4>
        <p>Fortinet, SIEM, Threat Detection.</p>'
    ],
    5 => [
        'title' => 'Campus Wi-Fi Rollout',
        'category' => 'Infrastructure',
        'description' => '<p>We deployed high-density Wi-Fi for a university campus with 5,000+ students, ensuring fast and reliable internet access across all buildings.</p>
        <h4>What We Did:</h4>
        <ul>
            <li>Site survey and RF planning for full coverage.</li>
            <li>Installed enterprise access points across 8 buildings.</li>
            <li>Configured secure user authentication for students and staff.</li>
        </ul>
        <h4>Technologies Used:</h4>
        <p>Enterprise Wi-Fi, RADIUS, Network Management.</p>'
    ]
];

if (!isset($projects[$id])) {
    echo '<div style="text-align:center; padding:100px 0;"><h2>Project not found.</h2><a href="projects.php">← Back to Projects</a></div>';
    include 'includes/footer.php';
    exit;
}

$project = $projects[$id];
?>

<style>
    .project-hero {
        background: linear-gradient(135deg, #0f1e33 0%, #1a2d47 100%);
        padding: 100px 0 80px;
        position: relative;
        overflow: hidden;
    }
    .project-hero .orb {
        position: absolute;
        border-radius: 50%;
        background: rgba(41, 160, 142, 0.15);
        animation: float 8s ease-in-out infinite;
    }
    .project-hero .orb-1 { width: 300px; height: 300px; right: -80px; top: -80px; animation-delay: 0s; }
    .project-hero .orb-2 { width: 200px; height: 200px; left: -80px; bottom: -80px; animation-delay: 3s; }
    @keyframes float {
        0%, 100% { transform: translateY(0px) scale(1); }
        50% { transform: translateY(-30px) scale(1.05); }
    }
    
    .detail-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 25px;
        box-shadow: 0 4px 12px rgba(15, 30, 51, 0.06);
        display: flex;
        gap: 15px;
        align-items: flex-start;
    }
    .detail-card i {
        font-size: 1.8rem;
        color: #29A08E;
        flex-shrink: 0;
        margin-top: 3px;
    }
    
    .tech-pill {
        background: rgba(41, 160, 142, 0.08);
        color: #29A08E;
        padding: 5px 14px;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-block;
        margin: 4px;
    }
</style>

<!-- PROJECT HERO -->
<section class="project-hero">
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="container position-relative" style="z-index: 2;">
        <div class="text-center">
            <span style="display: inline-block; font-size: 13px; font-weight: 700; color: #F2B91E; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 12px;"><?php echo $project['category']; ?></span>
            <h1 class="fw-bold" style="font-size: 3.5rem; color: #ffffff; margin-bottom: 15px;"><?php echo $project['title']; ?></h1>
            <a href="projects.php" class="btn btn-light" style="border-radius: 50px; padding: 10px 30px; font-size: 0.9rem; margin-top: 15px;">← Back to Projects</a>
        </div>
    </div>
</section>

<!-- MAIN PROJECT DETAILS -->
<section class="content-section" style="padding: 80px 0; background: #ffffff;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                
                <!-- PROJECT OVERVIEW -->
                <div class="text-center mb-5">
                    <span style="display: inline-block; font-size: 13px; font-weight: 700; color: #29A08E; text-transform: uppercase; letter-spacing: 2px;">Case Study</span>
                    <h2 style="font-weight: 800; color: #0f1e33; font-size: 2.2rem; margin-top: 10px;">Project Overview</h2>
                </div>
                
                <div class="row g-4">
                    <!-- LEFT: Description -->
                    <div class="col-lg-7">
                        <div class="p-4" style="background: #f8fafb; border-radius: 20px;">
                            <h4 style="font-weight: 700; color: #0f1e33; margin-bottom: 15px;"><i class="fas fa-file-alt me-2" style="color: #29A08E;"></i> The Brief</h4>
                            <p style="color: #555; line-height: 1.8;"><?php echo $project['description']; ?></p>
                            
                            <div class="mt-4">
                                <h5 style="font-weight: 700; color: #0f1e33; margin-bottom: 10px;">Technologies Used</h5>
                                <div class="tech-pill">PHP</div>
                                <div class="tech-pill">MySQL</div>
                                <div class="tech-pill">JavaScript</div>
                                <div class="tech-pill">API Integration</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- RIGHT: What We Did (Stacks naturally) -->
                    <div class="col-lg-5">
                        <div class="detail-card mb-3">
                            <i class="fas fa-check-circle"></i>
                            <div>
                                <h5 style="font-weight: 700; color: #0f1e33; margin-bottom: 5px;">Our Scope</h5>
                                <p style="color: #777; font-size: 0.95rem;">We handled everything from planning and design to deployment and training.</p>
                            </div>
                        </div>
                        <div class="detail-card mb-3">
                            <i class="fas fa-tools"></i>
                            <div>
                                <h5 style="font-weight: 700; color: #0f1e33; margin-bottom: 5px;">Our Approach</h5>
                                <p style="color: #777; font-size: 0.95rem;">We worked closely with the client to understand their needs and deliver a tailored solution.</p>
                            </div>
                        </div>
                        <div class="detail-card">
                            <i class="fas fa-chart-line"></i>
                            <div>
                                <h5 style="font-weight: 700; color: #0f1e33; margin-bottom: 5px;">The Result</h5>
                                <p style="color: #777; font-size: 0.95rem;">The client now enjoys improved security, efficiency, and peace of mind.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CTA -->
                <div class="text-center mt-5">
                    <p style="color: #777; margin-bottom: 15px;">Want a similar solution for your business?</p>
                    <a href="consultation.php" class="btn btn-kilele-primary" style="padding: 15px 40px; border-radius: 50px; font-weight: 700;">Book a Free Consultation →</a>
                </div>

            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>