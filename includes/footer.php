</main>

<!-- FOOTER -->
<footer class="kilele-footer">
    <div class="container">
        <div class="kilele-footer__grid">
            <div>
                <div class="kilele-footer__logo">
                    <?php if (!empty($settings['logo_uploaded'])): ?>
                        <img src="<?php echo htmlspecialchars($settings['logo_uploaded']); ?>" alt="Kilele Tech Logo" style="max-height: 45px;">
                    <?php else: ?>
                        <span class="kilele-wordmark__text kilele-wordmark__text--footer">
                            <span class="wm-k">K</span><span class="wm-i">i</span><span class="wm-l1">l</span><span class="wm-e1">e</span><span class="wm-l2">l</span><span class="wm-e2">e</span>
                        </span>
                    <?php endif; ?>
                </div>
                <p class="kilele-footer__brand-text">
                    <?php echo $settings['footer_brand_text'] ?? 'End-to-end technology solutions — from software development to security monitoring. We combine technical expertise with business insight to deliver innovative, reliable, and secure ICT solutions.'; ?>
                </p>
                <div class="kilele-footer__social">
                    <a href="<?php echo htmlspecialchars($settings['facebook_url'] ?? '#'); ?>" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="<?php echo htmlspecialchars($settings['twitter_url'] ?? '#'); ?>" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                    <a href="<?php echo htmlspecialchars($settings['linkedin_url'] ?? '#'); ?>" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                    <a href="<?php echo htmlspecialchars($settings['instagram_url'] ?? '#'); ?>" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                </div>
            </div>

            <div>
                <h5 class="kilele-footer__title">Quick Links</h5>
                <ul class="kilele-footer__links">
                    <li><a href="about.php">About Us</a></li>
                    <li><a href="what-we-offer.php">What We Offer</a></li>
                    <li><a href="projects.php">Projects</a></li>
                    <li><a href="projects.php">Projects</a></li>
                    <li><a href="downloads.php">Downloads</a></li>
                    <li><a href="blogs.php">Blogs</a></li>
                    <li><a href="contact.php">Contact</a></li>
                </ul>
            </div>

            <div>
                <h5 class="kilele-footer__title">Get in Touch</h5>
                <ul class="kilele-footer__contact">
                    <li><i class="fas fa-location-dot"></i> <?php echo htmlspecialchars($settings['contact_address'] ?? 'University of Nairobi, Main Campus'); ?></li>
                    <li><i class="fas fa-phone"></i> <?php echo htmlspecialchars($settings['contact_phone'] ?? '+254 741 139 887'); ?></li>
                    <li><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($settings['contact_email'] ?? 'kileletechofficial@gmail.com'); ?></li>
                </ul>
            </div>

            <div>
                <h5 class="kilele-footer__title">Newsletter</h5>
                <p class="kilele-footer__newsletter-text">
                    Get ICT tips, security alerts and project updates from the Kilele Tech team.
                </p>
                <form id="newsletterForm">
                    <div class="input-group">
                        <input type="email" name="email" id="newsletterEmail" class="form-control" placeholder="Your email" required style="border-radius: 50px 0 0 50px;">
                        <button class="btn btn-kilele-primary" type="submit" id="newsletterBtn" style="border-radius: 0 50px 50px 0;">Subscribe</button>
                    </div>
                    <div id="newsletterMsg" class="mt-2" style="font-size: 12px;"></div>
                </form>
            </div>
        </div>

       <!-- Bottom Line & Copyright -->
        <div class="kilele-footer__bottom" style="border-top: 0.5px solid #6c757d; padding-top: 20px; margin-top: 20px; text-align: center;">
          <small><?php echo $settings['footer_text'] ?? '© ' . date('Y') . ' Kilele Tech.'; ?> Crafted by <a href="https://www.linkedin.com/in/ruth-ondiere-254009198" target="_blank" rel="noopener" style="text-decoration: none; font-weight: 800; font-size: 1rem; color: #29A08E;">Ruth</a>.</small>
       </div>
    </div>
</footer>

<!-- AJAX SCRIPT FOR NEWSLETTER -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('newsletterForm');
        const msg = document.getElementById('newsletterMsg');
        const btn = document.getElementById('newsletterBtn');

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(form);
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>';

            fetch('process_subscribe.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                msg.style.display = 'block';
                msg.style.padding = '5px 10px';
                msg.style.borderRadius = '5px';
                if (data.success) {
                    msg.style.background = '#d4edda'; 
                    msg.style.color = '#155724';
                    msg.textContent = data.message;
                    form.reset();
                } else {
                    msg.style.background = '#f8d7da'; 
                    msg.style.color = '#721c24';
                    msg.textContent = data.message;
                }
            })
            .catch(() => {
                msg.style.display = 'block';
                msg.style.padding = '5px 10px';
                msg.style.background = '#f8d7da'; 
                msg.style.color = '#721c24';
                msg.textContent = 'Could not reach the server. Please try again later.';
            })
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = 'Subscribe';
            });
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/main.js"></script>

</body>
</html>