<?php
// Fetch settings from database (makes Site Settings work across the site)
$settings = [];
if (file_exists(__DIR__ . '/../db.php')) {
    require_once __DIR__ . '/../db.php';
    try {
        $stmt = $pdo->query("SELECT * FROM site_settings");
        foreach ($stmt->fetchAll() as $row) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
    } catch (Exception $e) {}
}

if (!isset($pageTitle)) {
    $pageTitle = 'Kilele Tech';
}
$currentPage = basename($_SERVER['PHP_SELF']);
function navActive($file, $current) {
    return $file === $current ? ' active' : '';
}

/* ---- Values pulled from Site Settings, each with a safe fallback ---- */
$metaDescription = $settings['meta_description'] ?? 'Kilele Tech delivers end-to-end ICT solutions — software, hardware, security systems and infrastructure support for businesses across Kenya.';
$metaKeywords    = $settings['meta_keywords'] ?? '';
$favicon         = $settings['favicon'] ?? '';
$ogImage         = $settings['og_image'] ?? '';
$gaId            = $settings['google_analytics_id'] ?? '';
$siteVerification = $settings['google_site_verification'] ?? '';
$primaryColor    = $settings['theme_primary_color'] ?? '#29A08E';
$secondaryColor  = $settings['theme_secondary_color'] ?? '#0f1e33';
$bodyFont        = $settings['theme_font'] ?? 'Poppins';

$contactEmail   = $settings['contact_email'] ?? 'kileletechofficial@gmail.com';
$contactPhone   = $settings['contact_phone'] ?? '+254 741 139 887';
$contactAddress = $settings['contact_address'] ?? 'University of Nairobi, Main Campus';

$facebookUrl  = $settings['facebook_url'] ?? '#';
$twitterUrl   = $settings['twitter_url'] ?? '#';
$linkedinUrl  = $settings['linkedin_url'] ?? '#';
$instagramUrl = $settings['instagram_url'] ?? '#';

$logoUrl = $settings['logo_uploaded'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?> - Kilele Tech</title>

    <meta name="description" content="<?php echo htmlspecialchars($metaDescription); ?>">
    <?php if ($metaKeywords): ?><meta name="keywords" content="<?php echo htmlspecialchars($metaKeywords); ?>"><?php endif; ?>
    <?php if ($favicon): ?><link rel="icon" href="<?php echo htmlspecialchars($favicon); ?>"><?php endif; ?>

    <meta property="og:title" content="<?php echo htmlspecialchars($pageTitle); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($metaDescription); ?>">
    <?php if ($ogImage): ?><meta property="og:image" content="<?php echo htmlspecialchars($ogImage); ?>"><?php endif; ?>
    <?php if ($siteVerification): ?><meta name="google-site-verification" content="<?php echo htmlspecialchars($siteVerification); ?>"><?php endif; ?>

    <?php if ($gaId): ?>
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo htmlspecialchars($gaId); ?>"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '<?php echo htmlspecialchars($gaId); ?>');
    </script>
    <?php endif; ?>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/layout.css">

    <style>
        /* Theme values from Site Settings override the base stylesheet */
        :root {
            --kilele-primary: <?php echo htmlspecialchars($primaryColor); ?>;
            --kilele-navy: <?php echo htmlspecialchars($secondaryColor); ?>;
        }
        body { font-family: '<?php echo htmlspecialchars($bodyFont); ?>', sans-serif; }

        /* ========== PREMIUM NAVIGATION LAYOUT ========== */
        .kilele-nav-container {
            display: flex;
            align-items: center;
            max-width: 1280px; 
            margin: 0 auto;
            padding-left: 15px;
            padding-right: 15px; 
        }
        
        .navbar-brand {
            margin-right: 40px; /* Better spacing after logo */
        }
        
        .navbar-nav {
            flex: 1;
            display: flex;
            justify-content: flex-start; /* Aligns pages to the left, but with room */
            gap: 12px;
        }
        
        .navbar-nav .nav-link {
            font-size: 0.92rem;
            padding: 8px 10px;
            color: #0f1e33;
        }
        
        .nav-item.dropdown .dropdown-menu {
            min-width: 240px;
            padding: 12px;
            border-radius: 12px;
            box-shadow: 0 12px 40px rgba(0,0,0,0.12);
            border: 1px solid #f0f0f0;
        }
        
        .nav-item.dropdown .dropdown-item {
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 0.92rem;
            font-weight: 500;
            color: #0f1e33;
            transition: all 0.2s ease;
        }
        
        .nav-item.dropdown .dropdown-item:hover {
            background: rgba(41, 160, 142, 0.08);
            color: #29A08E;
        }
        
        .nav-item.dropdown .dropdown-item small {
            display: block;
            font-size: 0.78rem;
            font-weight: 400;
            color: #888;
            margin-top: 2px;
        }

        /* ========== PERFECT RIGHT BUTTON ========== */
        .iconnect-row {
            margin-left: 30px;
            margin-right: 0;
            flex-shrink: 0;
        }
        
        .iconnect-nav-link {
            display: inline-block;
            background: #29A08E;
            color: #ffffff !important;
            padding: 10px 22px;
            border-radius: 0px !important;
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            white-space: nowrap;
            transition: 0.3s;
        }
        
        .iconnect-nav-link:hover {
            background: #1e7a6b;
        }

        .kilele-logo-img { max-height: 42px; width: auto; }
    </style>
    
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />

</head>
<body>

<!-- TOP BAR -->
<div class="top-bar-wrapper">
    <div class="top-bar-spacer"></div>
    <div class="top-bar-inner">
        <div class="top-contact">
            <span><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($contactEmail); ?></span>
            <span><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($contactAddress); ?></span>
        </div>
        <div class="top-right">
            <div class="top-links">
                <a href="about.php">About</a>
                <span class="sep">|</span>
                <a href="hub.php">FAQ</a>
                <span class="sep">|</span>
                <a href="contact.php">Contact</a>
            </div>
            <div class="top-social-icons">
                <a href="<?php echo htmlspecialchars($twitterUrl); ?>" target="_blank" rel="noopener" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                <a href="<?php echo htmlspecialchars($facebookUrl); ?>" target="_blank" rel="noopener" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                <a href="<?php echo htmlspecialchars($linkedinUrl); ?>" target="_blank" rel="noopener" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                <a href="<?php echo htmlspecialchars($instagramUrl); ?>" target="_blank" rel="noopener" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
    </div>
</div>

<!-- MAIN NAVIGATION -->
<nav class="navbar navbar-expand-lg kilele-navbar sticky-top">
    <div class="container kilele-nav-container">
        <a class="navbar-brand kilele-wordmark" href="index.php">
            <?php if ($logoUrl): ?>
                <img src="<?php echo htmlspecialchars($logoUrl); ?>" alt="Kilele Tech" class="kilele-logo-img">
            <?php else: ?>
                <span class="kilele-wordmark__text">
                    <span class="wm-k">K</span><span class="wm-i">i</span><span class="wm-l1">l</span><span class="wm-e1">e</span><span class="wm-l2">l</span><span class="wm-e2">e</span><span style="color: var(--kilele-primary); font-weight: 700;">Tech</span>
                </span>
            <?php endif; ?>
            <span class="kilele-logo-tagline"><?php echo htmlspecialchars($settings['site_tagline'] ?? 'Your Trusted ICT Partner'); ?></span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav mx-auto align-items-lg-center">
                <li class="nav-item"><a class="nav-link<?php echo navActive('index.php', $currentPage); ?>" href="index.php">Home</a></li>

                <!-- WHAT WE OFFER -->
                  <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="whatWeOfferDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                           What We Offer
                </a>
                <ul class="dropdown-menu" aria-labelledby="whatWeOfferDropdown">
                  <li><a class="dropdown-item" href="solutions.php">Solutions <small>Professional Services</small></a></li>
                  <li><a class="dropdown-item" href="services.php">Services <small>Infrastructure Services</small></a></li>
                  <li><a class="dropdown-item" href="systems.php">Systems <small>Custom-Built Software</small></a></li>
                  <li><a class="dropdown-item" href="hardware.php">Hardware <small>Equipment & Machines</small></a></li>
                   <li><a class="dropdown-item" href="market.php">Kilele Market <small>Component E-Commerce</small></a></li>
                </ul>
            </li>

                <!-- ABOUT US -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="aboutDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        About Us
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="aboutDropdown">
                        <li><a class="dropdown-item" href="about.php">About Us</a></li>
                        <li><a class="dropdown-item" href="team.php">Our Team</a></li>
                        <li><a class="dropdown-item" href="kilele1.php">Kilele@1</a></li>
                    </ul>
                </li>

                <!-- KILELE HUB -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="hubDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Kilele Hub
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="hubDropdown">
                        <li><a class="dropdown-item" href="hub.php">Kilele Hub</a></li>
                        <li><a class="dropdown-item" href="blogs.php">Blogs</a></li>
                        
                        <li><a class="dropdown-item" href="projects.php">Projects</a></li>
                        <li><a class="dropdown-item" href="industries.php">Industries</a></li>
                    </ul>
                </li>

                <li class="nav-item"><a class="nav-link" href="downloads.php">Downloads</a></li>
                <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>

                <li class="nav-item"><a class="nav-link join-link" href="quote.php">Get a Quote</a></li>
                <li class="nav-item">
                    <button class="search-button ms-3" type="button" aria-label="Search"><i class="fas fa-search"></i></button>
                </li>
            </ul>
        </div>

        <!-- PERFECT RIGHT BUTTON -->
        <div class="iconnect-row">
            <a class="iconnect-nav-link" href="consultation.php">Free Consultation</a>
        </div>
    </div>
</nav>

<!-- FLOATING SIDE PANEL -->
<div class="floating-side-panel">
    <a href="index.php" class="side-panel-item" data-hover-color="teal">
        <span class="side-panel-icon"><i class="fas fa-home"></i></span>
        <span class="side-panel-label">Home</span>
        <span class="side-panel-dot"></span>
    </a>
    <a href="quote.php" class="side-panel-item" data-hover-color="teal">
        <span class="side-panel-icon"><i class="fas fa-file-invoice"></i></span>
        <span class="side-panel-label">Get Quote</span>
        <span class="side-panel-dot"></span>
    </a>
    <a href="contact.php" class="side-panel-item" data-hover-color="yellow">
        <span class="side-panel-icon"><i class="fas fa-comment"></i></span>
        <span class="side-panel-label">Feedback</span>
        <span class="side-panel-dot"></span>
    </a>
    <a href="contact.php#map" class="side-panel-item" data-hover-color="coral">
        <span class="side-panel-icon"><i class="fas fa-map-marker-alt"></i></span>
        <span class="side-panel-label">Location</span>
        <span class="side-panel-dot"></span>
    </a>
    
</div>

<!-- MAIN CONTENT STARTS -->
<main>