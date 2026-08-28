<?php
$pageTitle = 'Kilele Tech – Your Trusted ICT Partner';

// Fetch settings from database (from Site Settings)
require_once __DIR__ . '/includes/db.php';
$settings = [];
try {
    $stmt = $pdo->query("SELECT * FROM site_settings");
    foreach ($stmt->fetchAll() as $row) {
        $settings[$row['setting_key']] = $row['setting_value'];
    }
} catch (Exception $e) {}

include 'includes/header.php';
?>

<!-- ============================================================
HERO SECTION
============================================================ -->
<section class="hero-carousel-section">
    <div class="hero-split">

        <div class="hero-panel hero-panel--left">

            <div class="hero-carousel-track" id="heroTrack">

                <!-- Slide 0 -->
                <div class="hero-slide active" data-index="0">
                    <div class="hero-slide__media hero-slide__media--tint1"></div>
                    <div class="hero-bubble">
                        <h3><?php echo $settings['hero_title'] ?? 'End-to-end ICT solutions'; ?></h3>
                        <p><?php echo $settings['hero_description'] ?? 'From software development to security monitoring — we combine technical expertise with business insight to deliver innovative, reliable and secure technology solutions.'; ?></p>
                        <ul class="hero-bubble__steps">
                            <li><span>1</span> Tell us about your business and current systems.</li>
                            <li><span>2</span> We assess and recommend the right ICT solution.</li>
                            <li><span>3</span> Our team designs, builds and deploys it.</li>
                            <li><span>4</span> You get ongoing support and monitoring.</li>
                        </ul>
                        <a href="what-we-offer.php" class="btn-hero-primary mt-2">Explore Services →</a>
                    </div>
                </div>

                <!-- Slide 1 -->
                <div class="hero-slide" data-index="1">
                    <div class="hero-slide__media hero-slide__media--tint2"></div>
                    <div class="hero-bubble">
                        <h3 style="color: var(--kilele-yellow);">CCTV & Security Systems</h3>
                        <p style="font-style: italic;">"Security built into every solution — from development to deployment."</p>
                        <p>IP and analog CCTV, access control, biometrics and intrusion detection, with remote monitoring.</p>
                        <p style="color: var(--kilele-yellow); font-weight: 600;">Free consultation & system design.</p>
                        <a href="hardware.php" class="btn-hero-primary mt-2">Request a Quote →</a>
                    </div>
                </div>

                <!-- Slide 2 -->
                <div class="hero-slide" data-index="2">
                    <div class="hero-slide__media hero-slide__media--tint3"></div>
                    <div class="hero-bubble">
                        <h3>Pick the right service package</h3>
                        <p>We tailor our support to how your business runs — explore <strong style="color: var(--kilele-yellow);">Starter, Business and Enterprise</strong> packages to find the fit.</p>
                        <div class="hero-tier-row">
                            <div class="hero-tier-chip"><div>🌱</div><div>Starter</div><small>Small Business</small></div>
                            <div class="hero-tier-chip"><div>💼</div><div>Business</div><small>Growing Teams</small></div>
                            <div class="hero-tier-chip"><div>🏢</div><div>Enterprise</div><small>Full ICT Partner</small></div>
                        </div>
                        <a href="services.php" class="btn-hero-primary mt-2">Compare Packages →</a>
                    </div>
                </div>

            </div>

            <button class="hero-arrow hero-arrow--prev" id="heroPrev" aria-label="Previous slide"><i class="fas fa-chevron-left"></i></button>
            <button class="hero-arrow hero-arrow--next" id="heroNext" aria-label="Next slide"><i class="fas fa-chevron-right"></i></button>

            <div class="hero-dots">
                <span class="hero-dot active" data-index="0"></span>
                <span class="hero-dot" data-index="1"></span>
                <span class="hero-dot" data-index="2"></span>
            </div>

            <div class="hero-limit-badge">
                <small>SINCE</small>
                <strong>2025</strong>
            </div>

            <div class="hero-joinus-overlay">
                <div class="hero-joinus-overlay__title">Talk to Kilele Tech</div>
                <div class="hero-joinus-overlay__subtitle">Call:</div>
                <ul>
                    <li><span>Sales:</span> <?php echo $settings['contact_phone'] ?? '+254 741 139 887'; ?></li>
                    <li><span>Support:</span> <?php echo $settings['contact_phone'] ?? '+254 741 139 887'; ?></li>
                </ul>
            </div>
        </div>

        <div class="hero-panel hero-panel--right">
            <div class="hero-video-wrapper">
            <?php if (!empty($settings['hero_image'])): ?>
                <!-- DISPLAY UPLOADED HERO IMAGE INSTEAD OF VIDEO -->
                <img src="<?php echo $settings['hero_image']; ?>" alt="Kilele Tech" style="width: 100%; height: auto; border-radius: 16px; box-shadow: 0 8px 24px rgba(0,0,0,0.2);">
            <?php else: ?>
                <!-- DISPLAY DEFAULT YOUTUBE VIDEO (This original link is SAFE! It will stay if you don't upload a new video) -->
                <iframe 
    src="<?php echo $settings['hero_video'] ?? 'https://www.youtube.com/embed/pRQGR91AQdU?autoplay=1&mute=1&loop=1&playlist=pRQGR91AQdU&controls=1&modestbranding=1&rel=0&showinfo=0'; ?>"
    allow="autoplay; encrypted-media"
    allowfullscreen
    title="KileleTech - Financial Freedom Starts Early">
</iframe>
            <?php endif; ?>

                <span class="hero-now-playing"><i class="fas fa-play-circle me-1"></i> Now Playing</span>

                <div class="hero-balance-overlay">
                    <div class="hero-balance-overlay__label">Client Satisfaction</div>
                    <div class="hero-balance-overlay__amount">100%</div>
                </div>
            </div>
            <div class="hero-more-videos">
                <a href="#"><i class="fas fa-arrow-right me-1"></i> More on our channel</a>
            </div>
        </div>

    </div>
</section>

<!-- ============================================================
GENERAL STATS STRIP
============================================================ -->
<section class="general-store-section">
    <div class="container">
        <div class="balance-card">
            <div>
                <div class="balance-label">PROJECTS DELIVERED</div>
                <div class="balance-amount">100+</div>
            </div>
            <div>
                <div class="store-icon">
                    <i class="fas fa-headset"></i>
                </div>
                <div style="text-align: right;">
                    <div style="font-size: 12px; color: #888;">Need help fast?</div>
                    <a href="contact.php" style="color: var(--kilele-primary); font-size: 13px; font-weight: 600;">Contact Support →</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================
REVIEWS SECTION
============================================================ -->
<section class="reviews-section" style="padding: 60px 0; background: #f8fafb;">
    <div class="container">
        <div class="text-center mb-5">
            <span style="display: inline-block; font-size: 13px; font-weight: 700; color: var(--kilele-primary); text-transform: uppercase; letter-spacing: 2px; margin-bottom: 8px;">TESTIMONIALS</span>
            <h2 style="font-size: 2.4rem; font-weight: 700; color: var(--kilele-navy);">What Our Clients Say</h2>
        </div>

        <!-- Display Reviews from Database -->
        <div class="row g-4 mb-5">
            <?php
            try {
                $stmt = $pdo->query("SELECT * FROM reviews ORDER BY created_at DESC");
                $reviews = $stmt->fetchAll();
                
                if (count($reviews) > 0) {
                    foreach ($reviews as $r) {
                        echo '<div class="col-md-4">';
                        echo '<div style="background: #fff; border-radius: 16px; padding: 25px; height: 100%; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">';
                        echo '<div style="color: #FFD700; font-size: 20px; margin-bottom: 10px;">' . str_repeat("★", $r['rating']) . str_repeat("☆", 5 - $r['rating']) . '</div>';
                        echo '<p style="font-style: italic; color: #555; margin-bottom: 15px;">"' . htmlspecialchars($r['review']) . '"</p>';
                        echo '<strong style="color: var(--kilele-navy);">' . htmlspecialchars($r['name']) . '</strong>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo '<div class="col-12 text-center" style="color: #888;">No reviews yet. Be the first to leave one!</div>';
                }
            } catch (Exception $e) {
                echo '<div class="col-12 text-center" style="color: #888;">Unable to load reviews right now.</div>';
            }
            ?>
        </div>

        <!-- Review Submission Form -->
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div style="background: #fff; border-radius: 16px; padding: 30px; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
                    <h3 style="color: var(--kilele-navy); margin-bottom: 20px; text-align: center;">Leave a Review</h3>
                    
                    <?php if (isset($_GET['success'])): ?>
                        <div style="background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 20px; text-align: center;">
                            Thank you! Your review has been submitted.
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="submit_review.php">
                        <div class="mb-3">
                            <input type="text" name="name" class="form-control" placeholder="Your Name" required style="padding: 12px; border-radius: 8px;">
                        </div>
                        <div class="mb-3">
                            <select name="rating" class="form-control" required style="padding: 12px; border-radius: 8px;">
                                <option value="">Select Rating</option>
                                <option value="5">★★★★★ (5 Stars)</option>
                                <option value="4">★★★★☆ (4 Stars)</option>
                                <option value="3">★★★☆☆ (3 Stars)</option>
                                <option value="2">★★☆☆☆ (2 Stars)</option>
                                <option value="1">★☆☆☆☆ (1 Star)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <textarea name="review" class="form-control" rows="4" placeholder="Your Review" required style="padding: 12px; border-radius: 8px;"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100" style="background: var(--kilele-primary); border: none; padding: 12px; border-radius: 8px; font-weight: 600;">Submit Review</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================
WHAT WE OFFER
============================================================ -->
<section class="products-grid-section" style="padding: 60px 0 40px; background: #ffffff;">
    <div class="container">
        <div class="text-center mb-4">
            <span class="products-eyebrow" style="display: inline-block; font-size: 13px; font-weight: 700; color: var(--kilele-primary); text-transform: uppercase; letter-spacing: 2px; margin-bottom: 8px;">WHAT WE DO</span>
            <h2 class="products-title" style="font-size: 2.4rem; font-weight: 700; color: var(--kilele-navy);">Our Core Solutions</h2>
        </div>

        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <a href="what-we-offer.php" style="text-decoration: none; display: block; height: 100%;">
                    <div class="product-offer-card" style="background: #f8fafb; border-radius: 16px; padding: 30px 20px 25px; text-align: center; height: 100%;">
                        <div class="product-offer-icon" style="font-size: 2.8rem; color: var(--kilele-primary); margin-bottom: 16px;"><i class="fas fa-lightbulb"></i></div>
                        <h3 class="product-offer-title" style="font-size: 1.1rem; font-weight: 700; color: var(--kilele-navy); margin-bottom: 12px;">Solutions</h3>
                        <span style="font-size: 0.85rem; font-weight: 700; color: var(--kilele-primary);">EXPLORE ALL →</span>
                    </div>
                </a>
            </div>

            <div class="col-md-6 col-lg-3">
                <a href="what-we-offer.php" style="text-decoration: none; display: block; height: 100%;">
                    <div class="product-offer-card" style="background: #f8fafb; border-radius: 16px; padding: 30px 20px 25px; text-align: center; height: 100%;">
                        <div class="product-offer-icon" style="font-size: 2.8rem; color: var(--kilele-primary); margin-bottom: 16px;"><i class="fas fa-cogs"></i></div>
                        <h3 class="product-offer-title" style="font-size: 1.1rem; font-weight: 700; color: var(--kilele-navy); margin-bottom: 12px;">Services</h3>
                        <span style="font-size: 0.85rem; font-weight: 700; color: var(--kilele-primary);">EXPLORE ALL →</span>
                    </div>
                </a>
            </div>

            <div class="col-md-6 col-lg-3">
                <a href="what-we-offer.php" style="text-decoration: none; display: block; height: 100%;">
                    <div class="product-offer-card" style="background: #f8fafb; border-radius: 16px; padding: 30px 20px 25px; text-align: center; height: 100%;">
                        <div class="product-offer-icon" style="font-size: 2.8rem; color: var(--kilele-primary); margin-bottom: 16px;"><i class="fas fa-network-wired"></i></div>
                        <h3 class="product-offer-title" style="font-size: 1.1rem; font-weight: 700; color: var(--kilele-navy); margin-bottom: 12px;">Systems</h3>
                        <span style="font-size: 0.85rem; font-weight: 700; color: var(--kilele-primary);">EXPLORE ALL →</span>
                    </div>
                </a>
            </div>

            <div class="col-md-6 col-lg-3">
                <a href="what-we-offer.php" style="text-decoration: none; display: block; height: 100%;">
                    <div class="product-offer-card" style="background: #f8fafb; border-radius: 16px; padding: 30px 20px 25px; text-align: center; height: 100%;">
                        <div class="product-offer-icon" style="font-size: 2.8rem; color: var(--kilele-primary); margin-bottom: 16px;"><i class="fas fa-server"></i></div>
                        <h3 class="product-offer-title" style="font-size: 1.1rem; font-weight: 700; color: var(--kilele-navy); margin-bottom: 12px;">Hardware</h3>
                        <span style="font-size: 0.85rem; font-weight: 700; color: var(--kilele-primary);">EXPLORE ALL →</span>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>