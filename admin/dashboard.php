<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Set timezone
date_default_timezone_set('Africa/Nairobi');

require_once '../includes/db.php';

// Count unread messages for the notification badge
$unreadMessages = 0;
try {
    $stmt = $pdo->query("SELECT COUNT(*) FROM contact_messages WHERE is_read = 0");
    $unreadMessages = $stmt->fetchColumn();
} catch (Exception $e) {
    // Ignore if column doesn't exist
}

// Set database timezone
try {
    $pdo->exec("SET time_zone = '+03:00'");
} catch (Exception $e) {
    // Timezone might not be supported, use PHP time instead
}

// === ALL COUNTS ===
$totalMessages = $pdo->query("SELECT COUNT(*) FROM contact_messages")->fetchColumn();
$totalSubscribers = $pdo->query("SELECT COUNT(*) FROM newsletter_subscribers")->fetchColumn();
$totalBundleQuotes = $pdo->query("SELECT COUNT(*) FROM bundle_quotes")->fetchColumn();
$totalUpdates = $pdo->query("SELECT COUNT(*) FROM updates")->fetchColumn();
$totalProjects = $pdo->query("SELECT COUNT(*) FROM projects")->fetchColumn();
$totalDownloads = $pdo->query("SELECT COUNT(*) FROM download_counts")->fetchColumn();
$totalReviews = $pdo->query("SELECT COUNT(*) FROM reviews")->fetchColumn(); 
$totalOrders = $pdo->query("SELECT COUNT(*) FROM market_orders")->fetchColumn();  // NEW

// === SEARCH FUNCTION ===
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';

if (!empty($searchTerm)) {
    $recentMessages = $pdo->prepare("SELECT * FROM contact_messages WHERE name LIKE ? OR email LIKE ? ORDER BY created_at DESC LIMIT 4");
    $recentMessages->execute(['%' . $searchTerm . '%', '%' . $searchTerm . '%']);
    $recentMessages = $recentMessages->fetchAll();

    $recentQuotes = $pdo->prepare("SELECT * FROM bundle_quotes WHERE name LIKE ? OR email LIKE ? ORDER BY created_at DESC LIMIT 4");
    $recentQuotes->execute(['%' . $searchTerm . '%', '%' . $searchTerm . '%']);
    $recentQuotes = $recentQuotes->fetchAll();
} else {
    $recentMessages = $pdo->query("SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT 4")->fetchAll();
    $recentQuotes = $pdo->query("SELECT * FROM bundle_quotes ORDER BY created_at DESC LIMIT 4")->fetchAll();
}

$recentUpdates = $pdo->query("SELECT * FROM updates ORDER BY created_at DESC LIMIT 4")->fetchAll();
$recentSubs = $pdo->query("SELECT * FROM newsletter_subscribers ORDER BY subscribed_at DESC LIMIT 4")->fetchAll();
$recentReviews = $pdo->query("SELECT * FROM reviews ORDER BY created_at DESC LIMIT 4")->fetchAll(); // NEW

// Helper function to format time
function formatTime($timestamp) {
    $time = strtotime($timestamp);
    $now = time();
    $diff = $now - $time;
    
    if ($diff < 60) {
        return 'Just now';
    } elseif ($diff < 3600) {
        $mins = floor($diff / 60);
        return $mins . ' minute' . ($mins > 1 ? 's' : '') . ' ago';
    } elseif ($diff < 86400) {
        $hours = floor($diff / 3600);
        return $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
    } elseif ($diff < 604800) {
        $days = floor($diff / 86400);
        return $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
    } else {
        return date('M d, Y g:i A', $time);
    }
}

// === USER ROLE ===
$userRole = isset($_SESSION['admin_role']) ? $_SESSION['admin_role'] : 'admin';
$canManageAdmins = ($userRole === 'super_admin');
$canManageUpdates = ($userRole === 'super_admin' || $userRole === 'admin');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard – KileleTech</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body { background: #f8fafb; font-family: 'Poppins', sans-serif; }
        
        .sidebar {
            background: #ffffff;
            border-right: 1px solid #e9ecef;
            min-height: 100vh;
            width: 250px;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
            padding-top: 20px;
            overflow-y: auto;
        }
        .sidebar .logo-area {
            padding: 0 24px 20px;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
        }
        .sidebar .logo-icon {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #29A08E, #0f1e33);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.2rem;
            font-weight: 800;
        }
        .sidebar .logo-text h5 { font-weight: 700; color: #0f1e33; font-size: 1rem; margin-bottom: 0; line-height: 1.2; }
        .sidebar .logo-text small { color: #999; font-size: 0.7rem; }
        
        .sidebar .nav-section-title {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #bbb;
            padding: 10px 24px 5px;
        }
        
        .sidebar .nav-link {
            color: #555;
            padding: 11px 24px;
            font-size: 0.9rem;
            font-weight: 500;
            transition: 0.2s;
            border-left: 3px solid transparent;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background: #f0f9f7;
            color: #29A08E;
            border-left-color: #29A08E;
        }
        .sidebar .nav-link i { width: 25px; color: #aaa; }
        .sidebar .nav-link:hover i, .sidebar .nav-link.active i { color: #29A08E; }
        
        .main-content { margin-left: 250px; padding: 0; }
        
        .top-bar {
            background: #ffffff;
            padding: 12px 30px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
        }
        .top-bar .page-title { font-weight: 800; color: #0f1e33; margin-bottom: 0; font-size: 1.2rem; }
        
        .top-bar .search-box {
            flex: 1;
            max-width: 350px;
            position: relative;
        }
        .top-bar .search-box input {
            width: 100%;
            padding: 10px 15px 10px 38px;
            border: 1px solid #e9ecef;
            border-radius: 50px;
            font-size: 0.85rem;
            outline: none;
            background: #f8fafb;
        }
        .top-bar .search-box i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            font-size: 0.8rem;
        }
        
        .top-bar .right-icons {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .top-bar .icon-btn {
            background: none;
            border: none;
            font-size: 1.1rem;
            color: #777;
            position: relative;
            cursor: pointer;
        }
        .top-bar .icon-btn .badge {
            position: absolute;
            top: -5px;
            right: -8px;
            background: #dc3545;
            color: #fff;
            font-size: 0.6rem;
            border-radius: 50%;
            padding: 2px 5px;
        }
        
        .user-profile-btn {
            background: #ffffff;
            border: 1px solid #e9ecef;
            border-radius: 50px;
            padding: 5px 10px;
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer !important;
            transition: 0.2s;
        }
        .user-profile-btn:hover { background: #f8fafb; }
        
        .top-bar .avatar {
            width: 40px;
            height: 40px;
            background: #29A08E;
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 1rem;
        }
        .user-profile-btn .user-info h6 { font-size: 0.85rem; margin: 0; font-weight: 700; color: #0f1e33; }
        .user-profile-btn .user-info small { color: #999; font-size: 0.7rem; }
        
        .content-wrapper { padding: 25px 30px; }
        .welcome-text { font-size: 1.6rem; font-weight: 800; color: #0f1e33; margin-bottom: 5px; }
        
        .stat-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.04);
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 100%;
            transition: all 0.3s ease;
            text-decoration: none;
        }
        .stat-card:hover { transform: translateY(-5px); box-shadow: 0 12px 24px rgba(0,0,0,0.08); }
        .stat-card .stat-info h6 { font-size: 0.85rem; color: #777; margin-bottom: 5px; }
        .stat-card .stat-info h3 { font-size: 2rem; font-weight: 800; color: #0f1e33; margin-bottom: 0; }
        .stat-card .stat-icon { width: 55px; height: 55px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; }
        
        .card-messages { background: #e0f2fe; color: #0284c7; }
        .card-subscribers { background: #dcfce7; color: #16a34a; }
        .card-quotes { background: #fef3c7; color: #d97706; }
        .card-updates { background: #f3e8ff; color: #9333ea; }
        .card-downloads { background: #fee2e2; color: #dc2626; }
        .card-reviews { background: #fce7f3; color: #db2777; }
        .card-orders { background: #d1fae5; color: #059669; } /* NEW */
        
        .quick-action-btn {
            background: #f0f9f7;
            border: 1px solid #d1f0eb;
            border-radius: 12px;
            padding: 15px;
            text-align: center;
            text-decoration: none;
            transition: 0.2s;
            display: block;
        }
        .quick-action-btn:hover { transform: translateY(-3px); box-shadow: 0 8px 16px rgba(0,0,0,0.05); border-color: #29A08E; }
        .quick-action-btn i { font-size: 1.3rem; color: #29A08E; margin-bottom: 8px; display: block; }
        .quick-action-btn h6 { font-size: 0.8rem; font-weight: 600; color: #0f1e33; margin: 0; }
        
        .section-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.04);
            height: 100%;
        }
        .section-card .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .section-card .section-header h5 { font-weight: 700; color: #0f1e33; margin: 0; }
        .section-card .section-header a { font-size: 0.85rem; font-weight: 600; color: #29A08E; text-decoration: none; }
        
        .list-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .list-item:last-child { border-bottom: none; }
        .list-item .list-icon { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 0.9rem; flex-shrink: 0; }
        .list-item .list-content h6 { font-size: 0.9rem; font-weight: 600; color: #0f1e33; margin-bottom: 2px; }
        .list-item .list-content small { color: #999; }
        .list-item .list-content .time-ago { color: #29A08E; font-weight: 500; font-size: 0.7rem; }
        
        .empty-state {
            text-align: center;
            padding: 30px 20px;
        }
        .empty-state i {
            font-size: 2.5rem;
            color: #ddd;
            margin-bottom: 10px;
        }
        .empty-state p {
            color: #999;
            font-size: 0.9rem;
        }
        
        .dropdown-menu {
            border-radius: 12px;
            box-shadow: 0 12px 30px rgba(0,0,0,0.15);
            border: none;
            padding: 10px;
        }
        .dropdown-item { border-radius: 8px; padding: 10px 14px; font-size: 0.9rem; font-weight: 600; }
        .dropdown-item:hover { background: #f0f9f7; color: #29A08E; }
    </style>
</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <div class="logo-area">
        <div class="logo-icon">K</div>
        <div class="logo-text">
            <h5>KileleTech</h5>
            <small>Admin Panel</small>
        </div>
    </div>

    <div class="nav-section-title">Main</div>
    <nav class="nav flex-column mt-2">
        <a href="site_settings.php" class="nav-link"><i class="fas fa-cog"></i> Site Settings</a>
        <a href="dashboard.php" class="nav-link active"><i class="fas fa-th-large"></i> Dashboard</a>
        <a href="manage_updates.php" class="nav-link"><i class="fas fa-bullhorn"></i> Updates</a>
        <a href="messages.php" class="nav-link"><i class="fas fa-envelope"></i> Messages</a>
        <a href="bundle_quotes.php" class="nav-link"><i class="fas fa-file-invoice"></i> Quote Requests</a>
        <a href="subscribers.php" class="nav-link"><i class="fas fa-users"></i> Subscribers</a>
        <a href="downloads.php" class="nav-link"><i class="fas fa-download"></i> Downloads</a>
        <a href="projects.php" class="nav-link"><i class="fas fa-briefcase"></i> Projects</a>
        <a href="kilele_market.php" class="nav-link"><i class="fas fa-shopping-cart"></i> Kilele Market</a>
        <a href="market_orders.php" class="nav-link"><i class="fas fa-receipt"></i> Market Orders</a> <!-- NEW -->
        <a href="manage_reviews.php" class="nav-link"><i class="fas fa-star"></i> Reviews</a>
    </nav>

    <?php if ($canManageUpdates): ?>
    <div class="nav-section-title">Content</div>
    <nav class="nav flex-column mt-2">
        <a href="publish_news.php" class="nav-link"><i class="fas fa-plus-circle"></i> Publish News</a>
        <a href="blog_posts.php" class="nav-link"><i class="fas fa-newspaper"></i> Blog Posts</a>
    </nav>
    <?php endif; ?>

    <?php if ($canManageAdmins): ?>
    <div class="nav-section-title">System</div>
    <nav class="nav flex-column mt-2">
        <a href="users.php" class="nav-link"><i class="fas fa-user-shield"></i> Admin Users</a>
        <a href="logout.php" class="nav-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </nav>
    <?php endif; ?>
</div>

<!-- MAIN CONTENT -->
<div class="main-content">
    <!-- TOP BAR -->
    <div class="top-bar">
        <h4 class="page-title">Dashboard</h4>
        
        <div class="search-box">
            <form action="dashboard.php" method="GET" style="position: relative;">
                <i class="fas fa-search"></i>
                <input type="text" name="search" placeholder="Search messages, quotes..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            </form>
        </div>
        
        <div class="right-icons">
          <button class="icon-btn" onclick="window.location.href='messages.php'">
            <i class="fas fa-bell"></i>
            <?php if ($unreadMessages > 0): ?>
                <span class="badge"><?php echo $unreadMessages; ?></span>
            <?php endif; ?>
          </button>
            
            <div class="dropdown">
                <button class="user-profile-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="avatar"><?php echo strtoupper(substr($_SESSION['admin_user'], 0, 1)); ?></div>
                    <div class="user-info">
                        <h6><?php echo htmlspecialchars($_SESSION['admin_user']); ?></h6>
                        <small><?php echo ($userRole === 'super_admin') ? 'Super Admin' : 'Admin'; ?></small>
                    </div>
                    <i class="fas fa-chevron-down" style="font-size: 0.8rem; color: #999;"></i>
                </button>
                
                <ul class="dropdown-menu dropdown-menu-end" style="right: 0; left: auto;">
                    <div style="padding: 8px 12px; background: #f0f9f7; border-radius: 8px; margin-bottom: 8px; text-align: center;">
                        <small style="color: #0f1e33; font-weight: 700;">Signed in as<br><span style="color: #29A08E;"><?php echo htmlspecialchars($_SESSION['admin_user']); ?></span></small>
                    </div>
                    
                    <li><a class="dropdown-item" href="profile.php"><i class="fas fa-user-circle me-2" style="color: #29A08E;"></i> Profile</a></li>
                    
                    <?php if ($canManageAdmins): ?>
                    <li><a class="dropdown-item" href="settings.php"><i class="fas fa-cog me-2" style="color: #29A08E;"></i> Settings</a></li>
                    <?php endif; ?>
                    
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="logout.php"><i class="fas fa-sign-out-alt me-2" style="color: #dc3545;"></i> Logout</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- CONTENT -->
    <div class="content-wrapper">
        <h2 class="welcome-text">Welcome back, <?php echo htmlspecialchars($_SESSION['admin_user']); ?>!</h2>
        <p style="color: #777;">Here's what's happening in your business today. <span id="currentTime" style="color: #29A08E; font-weight: 600;"></span></p>

        <?php if (!empty($searchTerm)): ?>
        <div class="alert alert-info mb-4">
            <strong><i class="fas fa-search me-2"></i> Search Results for: "<?php echo htmlspecialchars($searchTerm); ?>"</strong>
            <a href="dashboard.php" class="float-end text-decoration-none">Clear Search</a>
        </div>
        <?php endif; ?>

        <!-- STAT CARDS -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <a href="messages.php" class="stat-card">
                    <div class="stat-info">
                        <h6>Messages</h6>
                        <h3><?php echo $totalMessages; ?></h3>
                    </div>
                    <div class="stat-icon card-messages"><i class="fas fa-envelope"></i></div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="manage_reviews.php" class="stat-card">
                    <div class="stat-info">
                        <h6>Reviews</h6>
                        <h3><?php echo $totalReviews; ?></h3>
                    </div>
                    <div class="stat-icon card-reviews"><i class="fas fa-star"></i></div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="subscribers.php" class="stat-card">
                    <div class="stat-info">
                        <h6>Subscribers</h6>
                        <h3><?php echo $totalSubscribers; ?></h3>
                    </div>
                    <div class="stat-icon card-subscribers"><i class="fas fa-users"></i></div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="bundle_quotes.php" class="stat-card">
                    <div class="stat-info">
                        <h6>Quote Requests</h6>
                        <h3><?php echo $totalBundleQuotes; ?></h3>
                    </div>
                    <div class="stat-icon card-quotes"><i class="fas fa-file-invoice"></i></div>
                </a>
            </div>
        </div>

        <!-- STAT CARDS ROW 2 (NEW) -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <a href="market_orders.php" class="stat-card"> <!-- NEW -->
                    <div class="stat-info">
                        <h6>Market Orders</h6>
                        <h3><?php echo $totalOrders; ?></h3>
                    </div>
                    <div class="stat-icon card-orders"><i class="fas fa-receipt"></i></div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="projects.php" class="stat-card">
                    <div class="stat-info">
                        <h6>Projects</h6>
                        <h3><?php echo $totalProjects; ?></h3>
                    </div>
                    <div class="stat-icon card-updates"><i class="fas fa-briefcase"></i></div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="manage_updates.php" class="stat-card">
                    <div class="stat-info">
                        <h6>Updates</h6>
                        <h3><?php echo $totalUpdates; ?></h3>
                    </div>
                    <div class="stat-icon card-downloads"><i class="fas fa-bullhorn"></i></div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="kilele_market.php" class="stat-card">
                    <div class="stat-info">
                        <h6>Market Products</h6>
                        <h3><?php echo count($pdo->query("SELECT * FROM market_products")->fetchAll()); ?></h3>
                    </div>
                    <div class="stat-icon card-messages"><i class="fas fa-shopping-cart"></i></div>
                </a>
            </div>
        </div>

        <!-- QUICK ACTIONS -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <a href="publish_news.php" class="quick-action-btn">
                    <i class="fas fa-plus-circle"></i>
                    <h6>Publish News</h6>
                </a>
            </div>
            <div class="col-md-3">
                <a href="manage_reviews.php" class="quick-action-btn">
                    <i class="fas fa-star"></i>
                    <h6>Manage Reviews</h6>
                </a>
            </div>
            <div class="col-md-3">
                <a href="bundle_quotes.php" class="quick-action-btn">
                    <i class="fas fa-file-invoice"></i>
                    <h6>View Quotes</h6>
                </a>
            </div>
            <div class="col-md-3">
                <a href="market_orders.php" class="quick-action-btn">
                    <i class="fas fa-receipt"></i>
                    <h6>View Orders</h6>
                </a>
            </div>
        </div>

        <!-- RECENT ACTIVITY (3 Columns) -->
        <div class="row g-4">
            <!-- RECENT REVIEWS -->
            <div class="col-md-4">
                <div class="section-card">
                    <div class="section-header">
                        <h5>Recent Reviews</h5>
                        <a href="manage_reviews.php">View All</a>
                    </div>
                    <?php if (count($recentReviews) > 0): ?>
                        <?php foreach ($recentReviews as $rev): ?>
                            <div class="list-item">
                                <div class="list-icon card-reviews"><i class="fas fa-star"></i></div>
                                <div class="list-content">
                                    <h6><?php echo htmlspecialchars($rev['name']); ?> 
                                        <small style="color: #FFD700;"><?php echo str_repeat("★", $rev['rating']) . str_repeat("☆", 5 - $rev['rating']); ?></small>
                                    </h6>
                                    <small><?php echo substr(htmlspecialchars($rev['review']), 0, 60) . '...'; ?></small>
                                    <div class="time-ago"><?php echo formatTime($rev['created_at']); ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="empty-state">
                            <i class="fas fa-star"></i>
                            <p>No reviews yet. Check back soon!</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- RECENT UPDATES -->
            <div class="col-md-4">
                <div class="section-card">
                    <div class="section-header">
                        <h5>Recent Updates</h5>
                        <a href="manage_updates.php">View All</a>
                    </div>
                    <?php if (count($recentUpdates) > 0): ?>
                        <?php foreach ($recentUpdates as $u): ?>
                            <div class="list-item">
                                <div class="list-icon card-updates"><i class="fas fa-bullhorn"></i></div>
                                <div class="list-content">
                                    <h6><?php echo htmlspecialchars($u['title']); ?></h6>
                                    <small><?php echo formatTime($u['created_at']); ?></small>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="empty-state">
                            <i class="fas fa-bullhorn"></i>
                            <p>No updates published yet.</p>
                            <a href="publish_news.php" class="btn btn-sm btn-outline-primary">Publish Your First News</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- RECENT MESSAGES -->
            <div class="col-md-4">
                <div class="section-card">
                    <div class="section-header">
                        <h5>Recent Messages</h5>
                        <a href="messages.php">View All</a>
                    </div>
                    <?php if (count($recentMessages) > 0): ?>
                        <?php foreach ($recentMessages as $m): ?>
                            <a href="messages.php" style="text-decoration: none;">
                                <div class="list-item">
                                    <div class="list-icon card-messages"><i class="fas fa-envelope"></i></div>
                                    <div class="list-content">
                                        <h6><?php echo htmlspecialchars($m['name']); ?></h6>
                                        <small><?php echo formatTime($m['created_at']); ?></small>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="empty-state">
                            <i class="fas fa-envelope"></i>
                            <p>No messages yet.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Display current time
function updateTime() {
    const now = new Date();
    const options = { 
        weekday: 'short', 
        year: 'numeric', 
        month: 'short', 
        day: 'numeric', 
        hour: '2-digit', 
        minute: '2-digit', 
        second: '2-digit',
        timeZone: 'Africa/Nairobi'
    };
    document.getElementById('currentTime').textContent = '🕐 ' + now.toLocaleString('en-US', options);
}
updateTime();
setInterval(updateTime, 1000);
</script>
</body>
</html>