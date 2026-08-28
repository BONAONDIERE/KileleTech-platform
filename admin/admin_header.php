<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ../login.php');
    exit;
}

// ==========================================
// NEW: Connect to Database & Count Unread
// ==========================================
require_once __DIR__ . '/../includes/db.php';


$unreadCount = 0;
try {
    $stmt = $pdo->query("SELECT COUNT(*) FROM contact_messages WHERE is_read = 0");
    $unreadCount = $stmt->fetchColumn();
} catch (Exception $e) {
    // If the column doesn't exist yet, keep it at 0
}

$currentPage = basename($_SERVER['PHP_SELF']);
$userRole = isset($_SESSION['admin_role']) ? $_SESSION['admin_role'] : 'admin';
$canManageAdmins = ($userRole === 'super_admin');
$canManageUpdates = ($userRole === 'super_admin' || $userRole === 'admin');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle : 'Admin'; ?> – KileleTech</title>
    
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
            cursor: pointer;
            transition: 0.2s;
        }
        .user-profile-btn:hover { background: #f8fafb; }
        .user-profile-btn .avatar {
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
        
        .section-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.04);
            margin-bottom: 20px;
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
        <a href="dashboard.php" class="nav-link <?php echo $currentPage == 'dashboard.php' ? 'active' : ''; ?>"><i class="fas fa-th-large"></i> Dashboard</a>
        <a href="manage_updates.php" class="nav-link <?php echo $currentPage == 'manage_updates.php' ? 'active' : ''; ?>"><i class="fas fa-bullhorn"></i> Updates</a>
        <a href="messages.php" class="nav-link <?php echo $currentPage == 'messages.php' ? 'active' : ''; ?>"><i class="fas fa-envelope"></i> Messages</a>
        <a href="bundle_quotes.php" class="nav-link <?php echo $currentPage == 'bundle_quotes.php' ? 'active' : ''; ?>"><i class="fas fa-file-invoice"></i> Quote Requests</a>
        <a href="subscribers.php" class="nav-link <?php echo $currentPage == 'subscribers.php' ? 'active' : ''; ?>"><i class="fas fa-users"></i> Subscribers</a>
        <a href="downloads.php" class="nav-link <?php echo $currentPage == 'downloads.php' ? 'active' : ''; ?>"><i class="fas fa-download"></i> Downloads</a>
        <a href="projects.php" class="nav-link <?php echo $currentPage == 'projects.php' ? 'active' : ''; ?>"><i class="fas fa-briefcase"></i> Projects</a>
        <a href="kilele_market.php" class="nav-link <?php echo $currentPage == 'kilele_market.php' ? 'active' : ''; ?>"><i class="fas fa-shopping-cart"></i> Kilele Market</a>
    </nav>

    <?php if ($canManageUpdates): ?>
    <div class="nav-section-title">Content</div>
    <nav class="nav flex-column mt-2">
        <a href="publish_news.php" class="nav-link <?php echo $currentPage == 'publish_news.php' ? 'active' : ''; ?>"><i class="fas fa-plus-circle"></i> Publish News</a>
        <a href="blog_posts.php" class="nav-link <?php echo $currentPage == 'blog_posts.php' ? 'active' : ''; ?>"><i class="fas fa-newspaper"></i> Blog Posts</a>
    </nav>
    <?php endif; ?>

    <?php if ($canManageAdmins): ?>
    <div class="nav-section-title">System</div>
    <nav class="nav flex-column mt-2">
        <a href="users.php" class="nav-link <?php echo $currentPage == 'users.php' ? 'active' : ''; ?>"><i class="fas fa-user-shield"></i> Admin Users</a>
        <a href="logout.php" class="nav-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </nav>
    <?php endif; ?>
</div>

<!-- MAIN CONTENT -->
<div class="main-content">
    <!-- TOP BAR -->
    <div class="top-bar">
        <h4 class="page-title"><?php echo isset($pageTitle) ? $pageTitle : 'Admin'; ?></h4>
        
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Search...">
        </div>
        
        <div class="right-icons">
            <button class="icon-btn" onclick="window.location.href='messages.php'">
                <i class="fas fa-bell"></i>
                <?php if ($unreadCount > 0): ?>
                    <span class="badge"><?php echo $unreadCount; ?></span>
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
    
    <div class="content-wrapper">