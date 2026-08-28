<?php
// ==========================================================
// DIRECT DATABASE CONNECTION (Never breaks, always works)
// ==========================================================
$host = 'localhost';
$dbname = 'kilelete_kilele_tech';
$username = 'kilelete_admin'; 
$password = 'Kilele2023!!'; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $projects = $pdo->query("SELECT * FROM projects ORDER BY created_at DESC")->fetchAll();
} catch (Exception $e) {
    die("Database Connection Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projects - KileleTech</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f4f6f8; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        
        /* ========== SMALLER, TIGHTER HERO ========== */
        .proj-hero {
            background: linear-gradient(135deg, #0f1e33 0%, #1a2d47 100%);
            padding: 50px 0 40px;
            position: relative;
            overflow: hidden;
        }
        .proj-hero .orb {
            position: absolute;
            border-radius: 50%;
            background: rgba(41, 160, 142, 0.15);
            animation: float 8s ease-in-out infinite;
        }
        .proj-hero .orb-1 { width: 200px; height: 200px; right: -60px; top: -60px; animation-delay: 0s; }
        .proj-hero .orb-2 { width: 150px; height: 150px; left: -60px; bottom: -60px; animation-delay: 3s; }
        @keyframes float {
            0%, 100% { transform: translateY(0px) scale(1); }
            50% { transform: translateY(-30px) scale(1.05); }
        }

        /* ========== PROFESSIONAL OFF-WHITE CARDS ========== */
        .proj-card {
            background: #fbfbfc; /* Subtle off-white, not pure white */
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(15, 30, 51, 0.05);
            transition: all 0.4s ease;
            height: 100%;
            border: 1px solid #eef0f2;
            border-top: 4px solid transparent; /* Color appears on top of card */
            position: relative;
            overflow: hidden;
        }
        .proj-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(15, 30, 51, 0.10);
            border-color: #e0e6e9;
        }

        /* ========== CATEGORY COLORED TOP BORDERS ========== */
        .proj-item[data-category="web"] .proj-card { border-top-color: #29A08E; } /* Teal */
        .proj-item[data-category="security"] .proj-card { border-top-color: #F2B91E; } /* Yellow */
        .proj-item[data-category="infrastructure"] .proj-card { border-top-color: #C95873; } /* Coral */
        .proj-item[data-category="other"] .proj-card { border-top-color: #0f1e33; } /* Navy */

        /* ========== CATEGORY COLORED ICON BACKGROUNDS ========== */
        .proj-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 18px;
        }
        .proj-item[data-category="web"] .proj-icon { background: #e0f7f4; color: #29A08E; }
        .proj-item[data-category="security"] .proj-icon { background: #fdf6df; color: #F2B91E; }
        .proj-item[data-category="infrastructure"] .proj-icon { background: #fceaf0; color: #C95873; }
        .proj-item[data-category="other"] .proj-icon { background: #e6ebf0; color: #0f1e33; }

        .status-tag {
            position: absolute;
            top: 15px;
            right: 15px;
            padding: 4px 10px;
            border-radius: 50px;
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .status-tag.completed { background: #d4edda; color: #155724; }
        .status-tag.ongoing { background: #fff3cd; color: #856404; }
        
        /* ========== FILTER BUTTONS ========== */
        .filter-btn {
            background: #fff;
            border: 1px solid #ddd;
            padding: 10px 24px;
            border-radius: 50px;
            font-weight: 600;
            color: #0f1e33;
            cursor: pointer;
            transition: 0.3s;
            font-size: 0.9rem;
        }
        .filter-btn:hover, .filter-btn.active {
            background: #29A08E;
            color: #ffffff;
            border-color: #29A08E;
        }

        /* ========== SMALL, NO-BACKGROUND TEXT LINK ========== */
        .btn-view-details {
            display: inline-block;
            color: #29A08E;
            font-size: 0.85rem;
            font-weight: 700;
            text-decoration: none;
            transition: 0.3s;
            margin-top: 15px;
        }
        .btn-view-details:hover {
            color: #0f1e33;
            text-decoration: underline;
        }
    </style>
</head>
<body style="background: #f4f6f8;">
    <!-- HERO SECTION (Smaller, less blue) -->
    <section class="proj-hero">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
        <div class="container position-relative" style="z-index: 2;">
            <div class="text-center">
                <span style="display: inline-block; font-size: 12px; font-weight: 700; color: #F2B91E; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 10px;">OUR WORK</span>
                <h1 class="fw-bold" style="font-size: 2.5rem; color: #ffffff; margin-bottom: 15px;">Projects We've Delivered</h1>
                <p style="font-size: 1.1rem; color: rgba(255,255,255,0.8); max-width: 600px; margin: 0 auto;">
                    Real-world solutions built for businesses, institutions, and organizations across Africa.
                </p>
            </div>
        </div>
    </section>

    <!-- FILTER BUTTONS -->
    <section class="content-section" style="padding: 30px 0 15px; background: #f4f6f8;">
        <div class="container text-center">
            <button class="filter-btn active" onclick="filterProjects('all')">All Projects</button>
            <button class="filter-btn" onclick="filterProjects('web')">Web & Software</button>
            <button class="filter-btn" onclick="filterProjects('security')">Security</button>
            <button class="filter-btn" onclick="filterProjects('infrastructure')">Infrastructure</button>
        </div>
    </section>

    <!-- PROJECTS GRID -->
    <section class="content-section" style="padding: 15px 0 80px; background: #f4f6f8;">
        <div class="container">
            <div class="row g-4">
                <?php if (count($projects) > 0): ?>
                    <?php foreach ($projects as $project): ?>
                        <div class="col-md-6 col-lg-4 proj-item" data-category="<?php echo strtolower(htmlspecialchars($project['category'] ?? 'other')); ?>">
                            <a href="project-details.php?id=<?php echo $project['id']; ?>" style="text-decoration: none; display: block; height: 100%;">
                                <div class="proj-card">
                                    <span class="status-tag completed">Completed</span>
                                    <div class="proj-icon"><i class="fas fa-code"></i></div>
                                    <h3 style="font-weight: 700; color: #0f1e33; margin-bottom: 12px; font-size: 1.2rem;"><?php echo htmlspecialchars($project['title']); ?></h3>
                                    <p style="color: #777; font-size: 0.9rem; line-height: 1.6;"><?php echo htmlspecialchars($project['description']); ?></p>
                                    
                                    <!-- SMALL, NO-BACKGROUND VIEW DETAILS LINK -->
                                    <div style="margin-top: 10px;">
                                        <span class="btn-view-details">
                                            View Project Details <i class="fas fa-arrow-right" style="font-size: 0.7rem;"></i>
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center py-5">
                        <p style="color: #777;">No projects available yet. Please check back soon.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <script>
        function filterProjects(category) {
            const items = document.querySelectorAll('.proj-item');
            const buttons = document.querySelectorAll('.filter-btn');
            buttons.forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');
            items.forEach(item => {
                if (category === 'all' || item.getAttribute('data-category') === category) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }
    </script>
</body>
</html>