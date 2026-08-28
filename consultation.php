<?php
$pageTitle = 'Free Consultation – KileleTech';
include 'includes/header.php';

// Get existing database connection if needed
require_once __DIR__ . '/includes/db.php';

$success = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $company = trim($_POST['company'] ?? '');
    $challenge = trim($_POST['challenge'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (!empty($name) && !empty($email) && !empty($message)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO consultation_requests (name, email, phone, company, challenge, message) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$name, $email, $phone, $company, $challenge, $message]);
            $success = true;
        } catch (PDOException $e) {
            $error = 'Database error: ' . $e->getMessage();
        }
    }
}
?>

<style>
    .consult-hero {
        background: linear-gradient(135deg, #0f1e33 0%, #1a2d47 100%);
        padding: 80px 0 60px;
        position: relative;
        overflow: hidden;
    }
    .consult-hero .orb {
        position: absolute;
        border-radius: 50%;
        background: rgba(41, 160, 142, 0.15);
        animation: float 8s ease-in-out infinite;
    }
    .consult-hero .orb-1 { width: 300px; height: 300px; right: -80px; top: -80px; animation-delay: 0s; }
    @keyframes float {
        0%, 100% { transform: translateY(0px) scale(1); }
        50% { transform: translateY(-30px) scale(1.05); }
    }
</style>

<section class="consult-hero">
    <div class="orb orb-1"></div>
    <div class="container position-relative" style="z-index: 2;">
        <div class="text-center">
            <span style="display: inline-block; font-size: 13px; font-weight: 700; color: #F2B91E; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 12px;">LET'S TALK</span>
            <h1 class="fw-bold" style="font-size: 3.5rem; color: #ffffff; margin-bottom: 20px;">Book a Free Consultation</h1>
            <p style="font-size: 1.2rem; color: rgba(255,255,255,0.8); max-width: 700px; margin: 0 auto;">
                Not sure what you need? Talk to our experts for free and we'll help you identify the best solutions for your business.
            </p>
        </div>
    </div>
</section>

<section class="content-section" style="padding: 80px 0; background: #ffffff;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <?php if ($success): ?>
                    <div class="alert alert-success text-center" role="alert">
                        <h4 class="alert-heading">Request Received!</h4>
                        <p>Thank you for booking a consultation. One of our experts will contact you within 24 hours.</p>
                    </div>
                <?php else: ?>
                <form method="POST" class="p-4" style="background: #f8fafb; border-radius: 20px; box-shadow: 0 8px 24px rgba(15, 30, 51, 0.06);">
                    <h3 style="font-weight: 700; color: #0f1e33; margin-bottom: 30px;">Tell us about your business</h3>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Full Name *</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email *</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone Number</label>
                            <input type="tel" name="phone" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Company / Organization</label>
                            <input type="text" name="company" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label">What is your biggest ICT challenge?</label>
                            <select name="challenge" class="form-control">
                                <option value="web">I need a website or web application</option>
                                <option value="security">I need security systems (CCTV, Firewall)</option>
                                <option value="consulting">I need ICT consulting/strategy</option>
                                <option value="infrastructure">I need infrastructure/hardware help</option>
                                <option value="software">I need custom software</option>
                                <option value="other">Other / Not sure yet</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Briefly describe your challenge *</label>
                            <textarea name="message" class="form-control" rows="4" required></textarea>
                        </div>
                        <div class="col-12 text-center mt-4">
                            <button type="submit" class="btn btn-kilele-primary" style="padding: 15px 40px; border-radius: 50px; font-weight: 700; width: 100%;">
                                Book My Free Consultation
                            </button>
                        </div>
                    </div>
                </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>