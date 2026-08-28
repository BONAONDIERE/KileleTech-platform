<?php
$pageTitle = 'Blogs';
include 'includes/header.php';
?>

<section class="page-hero">
    <div class="container">
        <div class="page-hero__breadcrumb"><a href="index.php">Home</a> / Blogs</div>
        <h1 class="page-hero__title">Kilele Journal</h1>
        <p class="page-hero__subtitle">
            ICT insights, security tips and project stories from the Kilele Tech team.
        </p>
    </div>
</section>

<section class="content-section">
    <div class="container">
        <div class="article-grid">
            <div class="article-card">
                <div class="article-card__thumb" style="background: linear-gradient(135deg, var(--kilele-primary), var(--kilele-primary-dark));">
                    <i class="fas fa-shield-halved"></i>
                </div>
                <div class="article-card__body">
                    <div class="article-card__meta">Security · 2 min read</div>
                    <h3>5 signs your business needs a firewall upgrade</h3>
                    <p>Common warning signs we see before a client's network gets compromised — and how to catch them early.</p>
                    <a href="blog-single.php?id=1" class="article-card__link">Read more →</a>
                </div>
            </div>

            <div class="article-card">
                <div class="article-card__thumb" style="background: linear-gradient(135deg, var(--kilele-yellow), #d99f10);">
                    <i class="fas fa-globe"></i>
                </div>
                <div class="article-card__body">
                    <div class="article-card__meta">Web · 2 min read</div>
                    <h3>WordPress vs. Laravel: which fits your project?</h3>
                    <p>A practical comparison for businesses deciding between a CMS and a custom-built web app.</p>
                    <a href="blog-single.php?id=2" class="article-card__link">Read more →</a>
                </div>
            </div>

            <div class="article-card">
                <div class="article-card__thumb" style="background: linear-gradient(135deg, var(--kilele-coral), #a94661);">
                    <i class="fas fa-video"></i>
                </div>
                <div class="article-card__body">
                    <div class="article-card__meta">Security · 2 min read</div>
                    <h3>IP vs. analog CCTV — what NGOs should know</h3>
                    <p>A buyer's guide for organizations upgrading their site security on a limited budget.</p>
                    <a href="blog-single.php?id=3" class="article-card__link">Read more →</a>
                </div>
            </div>

            <div class="article-card">
                <div class="article-card__thumb" style="background: linear-gradient(135deg, var(--kilele-navy), var(--kilele-dark-navy));">
                    <i class="fas fa-database"></i>
                </div>
                <div class="article-card__body">
                    <div class="article-card__meta">Infrastructure · 2 min read</div>
                    <h3>Planning a database migration without downtime</h3>
                    <p>How we approach zero-downtime migrations between MySQL, PostgreSQL, and cloud databases.</p>
                    <a href="blog-single.php?id=4" class="article-card__link">Read more →</a>
                </div>
            </div>

            <div class="article-card">
                <div class="article-card__thumb" style="background: linear-gradient(135deg, var(--kilele-primary), var(--kilele-navy));">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <div class="article-card__body">
                    <div class="article-card__meta">Strategy · 2 min read</div>
                    <h3>What a digital transformation roadmap actually looks like</h3>
                    <p>Breaking down the process we use with government agencies and larger enterprises.</p>
                    <a href="blog-single.php?id=5" class="article-card__link">Read more →</a>
                </div>
            </div>

            <div class="article-card">
                <div class="article-card__thumb" style="background: linear-gradient(135deg, #d99f10, var(--kilele-coral));">
                    <i class="fas fa-eye"></i>
                </div>
                <div class="article-card__body">
                    <div class="article-card__meta">Monitoring · 2 min read</div>
                    <h3>Inside our 24/7 security monitoring desk</h3>
                    <p>What actually happens when our SIEM flags a threat — from alert to resolution.</p>
                    <a href="blog-single.php?id=6" class="article-card__link">Read more →</a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>