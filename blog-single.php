<?php
$pageTitle = 'Blog Post';
include 'includes/header.php';

// Get the blog ID from the URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Complete placeholder data for all 6 blogs
$blogs = [
    1 => [
        'title' => '5 signs your business needs a firewall upgrade',
        'category' => 'Security',
        'content' => '<p>It starts small. A few odd login attempts at 3 AM. An employee gets a weird email with a link they swear they didn\'t click. And then one day, your network grinds to a halt.</p>
        <p>Every business thinks they\'re safe until they aren\'t. Here are the 5 warning signs we see before a client\'s network gets compromised—and how to catch them early.</p>
        <h3>1. Unexpected outbound traffic</h3>
        <p>If your server is talking to foreign IPs at 2 AM, that\'s a major red flag. Malware often calls home to command-and-control servers to download further instructions.</p>
        <h3>2. Frequent system crashes</h3>
        <p>Malware often destabilizes your operating system by consuming high CPU or memory resources. If your servers are crashing more than usual without a clear explanation, investigate immediately.</p>
        <h3>3. Ransom notes</h3>
        <p>If you see a pop-up demanding Bitcoin to unlock your files, you\'ve already been compromised. By the time you see this, the malware has already encrypted your sensitive data.</p>
        <h3>4. Unusual user behavior</h3>
        <p>If employees are reporting that their accounts are locked out, or you see logins from foreign countries at odd hours, your credentials have likely been leaked or brute-forced.</p>
        <h3>5. Sudden slow network speeds</h3>
        <p>High volumes of outbound traffic can choke your internet pipe. If your network is inexplicably slow, malware might be using your bandwidth to transmit stolen data.</p>
        <p><strong>Prevention:</strong> A next-generation firewall (NGFW) can stop these threats before they reach your internal network. <a href="quote.php">Get a free consultation today.</a></p>'
    ],
    2 => [
        'title' => 'WordPress vs. Laravel: which fits your project?',
        'category' => 'Web',
        'content' => '<p>If you are building a new website, the choice between WordPress and Laravel is one of the first decisions you\'ll make. Both are excellent tools, but they serve very different purposes.</p>
        <h3>WordPress (The CMS approach)</h3>
        <p>WordPress powers over 40% of the web. It is great for speed: you can launch a blog, brochure site, or e-commerce store in hours. It relies heavily on plugins and themes, making it incredibly easy for non-technical users to manage content.</p>
        <h3>Laravel (The Framework approach)</h3>
        <p>Laravel is a PHP framework designed for building scalable, custom web applications. It requires deep coding knowledge but offers total control over your logic, database structure, and security.</p>
        <p><strong>Our advice:</strong> Start with WordPress if you need a simple marketing presence. Choose Laravel if you need a complex, data-driven application (like a custom CRM, ERP, or fintech platform).</p>
        <p>Kilele Tech specializes in both. <a href="quote.php">Get in touch</a> and we\'ll recommend the best stack for your needs.</p>'
    ],
    3 => [
        'title' => 'IP vs. analog CCTV — what NGOs should know',
        'category' => 'Security',
        'content' => '<p>Security systems can be expensive, especially for non-profits and NGOs operating on tight budgets. Knowing the difference between Analog and IP CCTV can save you thousands while still securing your premises.</p>
        <h3>Analog CCTV</h3>
        <p>Analog systems are the older, cheaper technology. They use coaxial cables to transmit video to a DVR (Digital Video Recorder). The video quality is limited to standard definition (usually 720p), but they are incredibly cost-effective and easier to install retroactively.</p>
        <h3>IP CCTV</h3>
        <p>IP (Internet Protocol) cameras transmit video over an Ethernet cable or Wi-Fi. They offer high-definition (1080p to 4K) resolution, pan-tilt-zoom controls, and remote access via your smartphone. However, they cost significantly more and require a stable network infrastructure.</p>
        <p><strong>Recommendation:</strong> If you are securing a small office on a budget, Analog is perfectly fine. If you need high-resolution footage and remote monitoring capabilities, IP is the way to go.</p>
        <p><a href="quote.php">Contact Kilele Tech</a> for a free security audit and system design.</p>'
    ],
    4 => [
        'title' => 'Planning a database migration without downtime',
        'category' => 'Infrastructure',
        'content' => '<p>Database migration is one of the most stressful operations for any IT team. The risk of data loss or application downtime often forces teams to delay vital upgrades. However, with the right strategy, zero-downtime migrations are achievable.</p>
        <h3>The Blue-Green Deployment Strategy</h3>
        <p>This involves running two identical production environments. You migrate the data to the new environment (Green) while the old one (Blue) handles live traffic. Once migration is complete, you simply switch the load balancer to point to Green.</p>
        <h3>Read Replicas</h3>
        <p>For large databases, you can set up a read replica of your master database. You can perform the schema changes on the replica without affecting the master. Once synced, you promote the replica to become the new master.</p>
        <h3>Backup and Rollback</h3>
        <p>Always have a verified, full backup of your database before starting. If anything goes wrong, the ability to rollback in minutes is more valuable than speed.</p>
        <p><strong>Need help with your migration?</strong> <a href="quote.php">Schedule a consultation with our infrastructure team.</a></p>'
    ],
    5 => [
        'title' => 'What a digital transformation roadmap actually looks like',
        'category' => 'Strategy',
        'content' => '<p>Digital transformation is one of the most overused buzzwords in business, but what does it actually look like when you stop talking and start building?</p>
        <p>At Kilele Tech, we break digital transformation down into 4 actionable phases:</p>
        <h3>Phase 1: Assessment & Discovery</h3>
        <p>We sit down with your stakeholders to understand your current systems, pain points, and long-term business goals. This phase answers the question: "Why do we need to change?"</p>
        <h3>Phase 2: Strategic Design</h3>
        <p>We create a blueprint. This includes the architecture of your new systems, the data migration plan, and the user experience design.</p>
        <h3>Phase 3: Agile Implementation</h3>
        <p>We build your solution in sprints. You see working software every 2 weeks, providing continuous feedback. This reduces risk and ensures the final product meets your exact needs.</p>
        <h3>Phase 4: Training & Handover</h3>
        <p>We train your team on the new systems and provide comprehensive documentation, ensuring your business can run independently.</p>
        <p><a href="quote.php">Download our full Digital Transformation Framework</a> or reach out to us for a free discovery call.</p>'
    ],
    6 => [
        'title' => 'Inside our 24/7 security monitoring desk',
        'category' => 'Monitoring',
        'content' => '<p>When you sign up for Kilele Tech\'s 24/7 Security Monitoring service, you aren\'t just buying software—you are hiring a team of security analysts who never sleep.</p>
        <h3>Step 1: The SIEM Alarm</h3>
        <p>Our Security Information and Event Management (SIEM) system ingests thousands of log events per second from your network. It uses AI to identify anomalies.</p>
        <h3>Step 2: Human Analysis</h3>
        <p>When the SIEM flags a threat, it is immediately routed to our on-call Security Analyst. They review the context to determine if it is a false positive or a true threat.</p>
        <h3>Step 3: Response & Resolution</h3>
        <p>If the threat is real, we initiate the Incident Response Plan. This may involve isolating the affected endpoint, blocking IP addresses, or resetting compromised credentials.</p>
        <p>Our average response time is under 5 minutes. <a href="quote.php">Get a free security audit</a> and see how we can protect your business.</p>'
    ]
];

// If the ID doesn't exist, show a 404-style message
if (!isset($blogs[$id])) {
    echo '<h1 style="text-align:center; padding:60px 0;">Blog post not found.</h1>';
    include 'includes/footer.php';
    exit;
}

$blog = $blogs[$id];
?>

<section class="page-hero" style="background: linear-gradient(135deg, #0f1e33 0%, #1a2d47 100%);">
    <div class="container">
        <div class="page-hero__breadcrumb"><a href="index.php">Home</a> / <a href="blogs.php">Blogs</a> / <?php echo $blog['title']; ?></div>
        <h1 class="page-hero__title"><?php echo $blog['title']; ?></h1>
        <p class="page-hero__subtitle"><?php echo $blog['category']; ?></p>
    </div>
</section>

<section class="content-section" style="padding: 60px 0; background: #ffffff;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div style="background: #fff; border-radius: 18px; padding: 36px; box-shadow: 0 5px 25px rgba(0,0,0,0.06);">
                    <?php echo $blog['content']; ?>
                    <hr style="margin: 30px 0;">
                    <a href="blogs.php" class="btn btn-kilele-primary" style="padding: 10px 30px; border-radius: 50px;">← Back to Blogs</a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>