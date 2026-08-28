<?php
$pageTitle = 'Job Details';
include 'includes/header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$jobs = [
    1 => [
        'title' => 'Security Systems Engineer',
        'type' => 'Full-time',
        'location' => 'Nairobi, Kenya',
        'description' => '<p>We are looking for a skilled Security Systems Engineer to design, deploy, and maintain CCTV, access control, firewall, and network security solutions for our enterprise clients. You will work directly with our consulting team to deliver robust security infrastructure.</p>
        <h4>Key Responsibilities:</h4>
        <ul>
            <li>Design and implement comprehensive security systems for clients.</li>
            <li>Configure and manage firewall solutions (Fortinet, Cisco, etc.).</li>
            <li>Install and maintain IP and analog CCTV systems.</li>
            <li>Perform security audits and vulnerability assessments.</li>
            <li>Provide technical support and training to client teams.</li>
        </ul>
        <h4>Requirements:</h4>
        <ul>
            <li>3+ years experience in network security and hardware installation.</li>
            <li>Strong knowledge of IP networking, firewalls, and access control.</li>
            <li>Certifications such as CCNA, CompTIA Security+, or equivalent are a strong plus.</li>
            <li>Excellent problem-solving and client communication skills.</li>
        </ul>
        <h4>What We Offer:</h4>
        <ul>
            <li>Competitive salary and performance bonuses.</li>
            <li>Opportunities for professional growth and certifications.</li>
            <li>A collaborative and innovative work environment.</li>
        </ul>'
    ],
    2 => [
        'title' => 'Digital Marketing & SEO Specialist',
        'type' => 'Full-time',
        'location' => 'Nairobi, Kenya (Hybrid)',
        'description' => '<p>We are looking for a creative and data-driven Digital Marketing & SEO Specialist to drive traffic, generate leads, and build the KileleTech brand online. You will be responsible for our content, social media, and search engine strategy.</p>
        <h4>Key Responsibilities:</h4>
        <ul>
            <li>Plan and execute SEO strategies to rank the website on Google.</li>
            <li>Write and manage high-quality blog content (like our Kilele Journal).</li>
            <li>Manage social media channels and engagement.</li>
            <li>Track and report on website analytics using Google Analytics.</li>
        </ul>
        <h4>Requirements:</h4>
        <ul>
            <li>2+ years experience in SEO and digital marketing.</li>
            <li>Excellent English writing and editing skills.</li>
            <li>Experience with Google Analytics, Search Console, and WordPress.</li>
            <li>Knowledge of social media trends and engagement strategies.</li>
        </ul>
        <h4>What We Offer:</h4>
        <ul>
            <li>Competitive salary and growth opportunities.</li>
            <li>Flexible hybrid working arrangement.</li>
            <li>Opportunity to shape and grow the company brand.</li>
        </ul>'
    ]
];

if (!isset($jobs[$id])) {
    echo '<div style="text-align:center; padding:80px 0;"><h2>Job not found.</h2><a href="careers.php">← Back to Careers</a></div>';
    include 'includes/footer.php';
    exit;
}

$job = $jobs[$id];
?>

<section class="page-hero" style="background: linear-gradient(135deg, #0f1e33 0%, #1a2d47 100%);">
    <div class="container">
        <div class="page-hero__breadcrumb"><a href="index.php">Home</a> / <a href="careers.php">Careers</a> / <?php echo $job['title']; ?></div>
        <h1 class="page-hero__title"><?php echo $job['title']; ?></h1>
        <p class="page-hero__subtitle">
            <span style="background: rgba(255,255,255,0.15); padding: 4px 14px; border-radius: 50px; display: inline-block; font-size: 0.8rem;"><?php echo $job['type']; ?></span>
            <span style="margin-left: 8px; font-size: 0.9rem;"><i class="fas fa-map-marker-alt"></i> <?php echo $job['location']; ?></span>
        </p>
    </div>
</section>

<section class="content-section" style="padding: 60px 0; background: #ffffff;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div style="background: #fff; border-radius: 18px; padding: 36px; box-shadow: 0 5px 25px rgba(0,0,0,0.06);">
                    <h3 style="font-weight: 700; color: var(--kilele-navy); margin-bottom: 16px;">Job Description</h3>
                    <?php echo $job['description']; ?>
                    <hr style="margin: 30px 0;">
                    <div style="background: #f8fafb; padding: 20px; border-radius: 12px; text-align: center;">
                        <h5 style="font-weight: 600; color: var(--kilele-navy); margin-bottom: 6px;">Ready to apply?</h5>
                        <p style="color: var(--kilele-text-light); margin-bottom: 12px;">Send your CV and cover letter to:</p>
                        
                        <div style="display: inline-flex; align-items: center; gap: 10px; background: var(--kilele-primary); padding: 10px 28px; border-radius: 50px; color: #fff;">
                            <i class="fas fa-envelope"></i>
                            <span id="emailText" style="font-weight: 600; cursor: pointer;">kileletechofficial@gmail.com</span>
                            <button onclick="copyEmail()" style="background: rgba(255,255,255,0.2); border: none; color: #fff; border-radius: 50%; width: 28px; height: 28px; font-size: 13px; cursor: pointer; transition: 0.2s;">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                        <div id="copyFeedback" style="font-size: 0.8rem; color: #155724; margin-top: 4px; height: 20px;"></div>
                    </div>
                    <div style="margin-top: 20px; text-align: center;">
                        <a href="careers.php" class="btn btn-kilele-outline" style="color: var(--kilele-primary); border-color: var(--kilele-primary); border-radius: 50px; padding: 8px 28px;">← Back to All Jobs</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    function copyEmail() {
        const email = "kileletechofficial@gmail.com";
        navigator.clipboard.writeText(email).then(() => {
            const feedback = document.getElementById('copyFeedback');
            feedback.textContent = "✅ Copied !";
            setTimeout(() => {
                feedback.textContent = "";
            }, 3000);
        });
    }
</script>

<?php include 'includes/footer.php'; ?>