<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$username = $_SESSION['admin_user'];
$stmt = $pdo->prepare("SELECT * FROM admin_users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile – KileleTech Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { background: #f8fafb; font-family: 'Poppins', sans-serif; }

        /* SIDEBAR */
        .sidebar {
            background: #ffffff;
            border-right: 1px solid #e9ecef;
            min-height: 100vh;
            width: 250px;
            position: fixed;
            left: 0;
            top: 0;
            padding: 20px;
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
            margin: 0 auto 15px;
        }
        .sidebar h5 { font-weight: 700; color: #0f1e33; text-align: center; }
        .sidebar .nav-link { color: #555; padding: 10px 15px; border-radius: 8px; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { background: #f0f9f7; color: #29A08E; }
        .sidebar .nav-link i { width: 25px; }

        /* MAIN CONTENT */
        .main-content { margin-left: 250px; padding: 20px; }
        .top-bar {
            background: #ffffff;
            padding: 15px 30px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .top-bar h4 { font-weight: 800; color: #0f1e33; margin: 0; }

        /* PROFILE CARD */
        .profile-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.04);
            padding: 50px;
            max-width: 550px;
            margin: 20px auto;
            text-align: center;
        }
        .avatar {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, #29A08E, #0f1e33);
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3.5rem;
            font-weight: 800;
            margin: 0 auto 25px;
        }
        .btn-primary { background: #29A08E; border: none; border-radius: 50px; padding: 12px 30px; }
        .btn-primary:hover { background: #1e7a6b; }
        .btn-outline-secondary { border-radius: 50px; padding: 10px 25px; }
    </style>
</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <div class="logo-icon">K</div>
    <h5>KileleTech Admin</h5>
    <nav class="nav flex-column mt-3">
        <a href="dashboard.php" class="nav-link"><i class="fas fa-th-large me-2"></i> Dashboard</a>
        <a href="profile.php" class="nav-link active"><i class="fas fa-user me-2"></i> My Profile</a>
        <a href="settings.php" class="nav-link"><i class="fas fa-cog me-2"></i> Settings</a>
        <a href="logout.php" class="nav-link"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
    </nav>
</div>

<!-- MAIN CONTENT -->
<div class="main-content">
    <div class="top-bar">
        <h4>My Profile</h4>
        <a href="dashboard.php" class="btn btn-sm btn-outline-secondary">← Back to Dashboard</a>
    </div>

    <div class="profile-card">
        <div class="avatar"><?php echo strtoupper(substr($user['username'], 0, 1)); ?></div>
        <h3 style="font-weight: 700; color: #0f1e33;"><?php echo htmlspecialchars($user['username']); ?></h3>
        
        <div class="row mt-4">
            <div class="col-6">
                <div class="p-3 rounded-3" style="background: #f0f9f7; border-radius: 12px !important;">
                    <small style="color: #777;">Role</small>
                    <h5 style="font-weight: 700; color: #0f1e33; margin: 0;"><?php echo htmlspecialchars($user['role']); ?></h5>
                </div>
            </div>
            <div class="col-6">
                <div class="p-3 rounded-3" style="background: #f0f9f7; border-radius: 12px !important;">
                    <small style="color: #777;">Joined</small>
                    <h5 style="font-weight: 700; color: #0f1e33; margin: 0;"><?php echo date('M d, Y', strtotime($user['created_at'])); ?></h5>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <a href="settings.php" class="btn btn-primary">Change Password</a>
        </div>
    </div>
</div>

</body>
</html>