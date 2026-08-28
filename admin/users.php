<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Handle Add Admin form submission
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_admin'])) {
    $newUser = trim($_POST['new_username'] ?? '');
    $newPass = $_POST['new_password'] ?? '';
    if (!empty($newUser) && !empty($newPass)) {
        $hash = password_hash($newPass, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO admin_users (username, password_hash, role, created_at) VALUES (?, ?, 'admin', NOW())");
        $stmt->execute([$newUser, $hash]);
        $msg = "<div class='alert alert-success'>New admin '{$newUser}' added successfully!</div>";
    } else {
        $msg = "<div class='alert alert-danger'>Please fill in both fields.</div>";
    }
}

// Fetch all admins
$admins = $pdo->query("SELECT * FROM admin_users ORDER BY id ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Admins – KileleTech Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f8fafb; font-family: 'Poppins', sans-serif; }
        .sidebar { background: #0f1e33; min-height: 100vh; padding: 20px 0; }
        .sidebar .logo h4 { color: #29A08E; font-weight: 700; text-align: center; padding: 10px 0; }
        .sidebar .nav-link { color: rgba(255,255,255,0.7); padding: 12px 24px; border-radius: 8px; margin: 4px 12px; }
        .sidebar .nav-link:hover { background: rgba(255,255,255,0.05); color: #fff; }
        .sidebar .nav-link.active { background: #29A08E; color: white; }
        .main-content { padding: 24px; }
        .table-card { background: #fff; border-radius: 12px; padding: 20px; box-shadow: 0 2px 12px rgba(0,0,0,0.05); }
        .top-bar { background: #fff; padding: 12px 24px; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.05); margin-bottom: 24px; }
        .add-card { background: #fff; border-radius: 12px; padding: 20px; box-shadow: 0 2px 12px rgba(0,0,0,0.05); margin-bottom: 24px; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 col-lg-2 sidebar">
                <div class="logo"><h4>KileleTech</h4></div>
                <nav class="nav flex-column mt-3">
                    <a href="dashboard.php" class="nav-link"><i class="fas fa-th-large"></i> Dashboard</a>
                    <a href="messages.php" class="nav-link"><i class="fas fa-envelope"></i> Messages</a>
                    <a href="subscribers.php" class="nav-link"><i class="fas fa-users"></i> Subscribers</a>
                    <a href="quotes.php" class="nav-link"><i class="fas fa-file-invoice"></i> Quotes</a>
                    <a href="users.php" class="nav-link active"><i class="fas fa-user-shield"></i> Admins</a>
                    <a href="logout.php" class="nav-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 col-lg-10 main-content">
                <div class="top-bar">
                    <h5 class="mb-0">Admin User Management</h5>
                    <small class="text-muted">Total: <?php echo count($admins); ?> admins</small>
                </div>

                <!-- Add Admin Form -->
                <div class="add-card">
                    <h6 class="fw-bold mb-3"><i class="fas fa-plus-circle brand-color me-2"></i> Add New Admin</h6>
                    <?php echo $msg; ?>
                    <form method="POST" class="row g-2">
                        <div class="col-md-4">
                            <input type="text" name="new_username" class="form-control" placeholder="New Username" required>
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="new_password" class="form-control" placeholder="New Password" required>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" name="add_admin" class="btn btn-primary w-100">Add Admin</button>
                        </div>
                    </form>
                </div>

                <!-- Admin List -->
                <div class="table-card">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Username</th>
                                    <th>Role</th>
                                    <th>Created At</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($admins as $admin): ?>
                                    <tr>
                                        <td><?php echo $admin['id']; ?></td>
                                        <td><strong><?php echo htmlspecialchars($admin['username']); ?></strong></td>
                                        <td><span class="badge bg-info text-dark"><?php echo htmlspecialchars($admin['role']); ?></span></td>
                                        <td><?php echo date('M j, Y', strtotime($admin['created_at'])); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>