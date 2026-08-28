<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$msg = '';

// Handle Add Admin form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_admin'])) {
    $newUser = trim($_POST['new_username'] ?? '');
    $newPass = $_POST['new_password'] ?? '';
    $role = $_POST['role'] ?? 'admin';

    if (!empty($newUser) && !empty($newPass)) {
        try {
            $hash = password_hash($newPass, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO admin_users (username, password_hash, role) VALUES (?, ?, ?)");
            $stmt->execute([$newUser, $hash, $role]);
            $msg = "<div class='alert alert-success'>Admin '{$newUser}' added successfully!</div>";
        } catch (PDOException $e) {
            if ($e->errorInfo[1] == 1062) {
                $msg = "<div class='alert alert-danger'>Username already exists.</div>";
            } else {
                $msg = "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
            }
        }
    }
}

// Fetch all admins
$admins = $pdo->query("SELECT * FROM admin_users ORDER BY id ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Admins – KileleTech</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f8fafb; font-family: 'Poppins', sans-serif; }
        .sidebar { background: #ffffff; border-right: 1px solid #e9ecef; min-height: 100vh; width: 250px; position: fixed; left: 0; top: 0; padding: 20px; }
        .sidebar h5 { font-weight: 700; color: #0f1e33; }
        .sidebar .nav-link { color: #555; padding: 10px 15px; border-radius: 8px; }
        .sidebar .nav-link.active { background: #f0f9f7; color: #29A08E; }
        .main-content { margin-left: 250px; padding: 20px; }
        .top-bar { background: #fff; padding: 15px; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.05); margin-bottom: 20px; }
        .card { background: #fff; border-radius: 16px; padding: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.04); }
        .sidebar .nav-link i { width: 25px; color: #aaa; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { background: #f0f9f7; color: #29A08E; border-left: 3px solid #29A08E; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h5>KileleTech Admin</h5>
        <nav class="nav flex-column mt-3">
            <a href="dashboard.php" class="nav-link"><i class="fas fa-th-large me-2"></i> Dashboard</a>
            <a href="users.php" class="nav-link active"><i class="fas fa-user-shield me-2"></i> Admins</a>
            <a href="logout.php" class="nav-link"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
        </nav>
    </div>

    <div class="main-content">
        <div class="top-bar">
            <h4>Admin User Management</h4>
        </div>

        <!-- Add Admin Form -->
        <div class="card mb-4">
            <h6 class="fw-bold mb-3">Add New Admin</h6>
            <?php echo $msg; ?>
            <form method="POST" class="row g-2">
                <div class="col-md-4">
                    <input type="text" name="new_username" class="form-control" placeholder="New Username" required>
                </div>
                <div class="col-md-4">
                    <input type="password" name="new_password" class="form-control" placeholder="New Password" required>
                </div>
                <div class="col-md-2">
                    <select name="role" class="form-control">
                        <option value="admin">Admin</option>
                        <option value="super_admin">Super Admin</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" name="add_admin" class="btn btn-primary w-100">Add</button>
                </div>
            </form>
        </div>

        <!-- List of Admins -->
        <div class="card">
            <h6 class="fw-bold mb-3">Admin List</h6>
            <table class="table table-hover">
                <thead><tr><th>ID</th><th>Username</th><th>Role</th><th>Date Added</th></tr></thead>
                <tbody>
                    <?php foreach ($admins as $admin): ?>
                    <tr>
                        <td><?php echo $admin['id']; ?></td>
                        <td><strong><?php echo htmlspecialchars($admin['username']); ?></strong></td>
                        <td><span class="badge bg-info text-dark"><?php echo htmlspecialchars($admin['role']); ?></span></td>
                        <td><?php echo date('M d, Y', strtotime($admin['created_at'])); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>