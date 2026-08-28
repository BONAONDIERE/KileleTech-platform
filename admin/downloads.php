<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Add new download file (metadata only for now)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_download'])) {
    $filename = trim($_POST['filename'] ?? '');
    $title = trim($_POST['title'] ?? '');
    $size = trim($_POST['size'] ?? '0 KB');
    if (!empty($filename) && !empty($title)) {
        $stmt = $pdo->prepare("INSERT INTO admin_downloads (filename, title, file_size) VALUES (?, ?, ?)");
        $stmt->execute([$filename, $title, $size]);
    }
}

$downloads = $pdo->query("SELECT * FROM admin_downloads ORDER BY created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Downloads Admin – KileleTech</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f8fafb; font-family: 'Poppins', sans-serif; }
        .sidebar { background: #ffffff; border-right: 1px solid #e9ecef; min-height: 100vh; width: 250px; position: fixed; left: 0; top: 0; padding: 20px; }
        .sidebar h5 { font-weight: 700; color: #0f1e33; }
        .sidebar .nav-link { color: #555; padding: 10px 15px; border-radius: 8px; }
        .sidebar .nav-link.active { background: #f0f9f7; color: #29A08E; border-left: 3px solid #29A08E; }
        .main-content { margin-left: 250px; padding: 20px; }
        .top-bar { background: #fff; padding: 15px; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.05); margin-bottom: 20px; }
        .card { background: #fff; border-radius: 16px; padding: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.04); }
    </style>
</head>
<body>
    <div class="sidebar">
        <h5>KileleTech Admin</h5>
        <nav class="nav flex-column mt-3">
            <a href="dashboard.php" class="nav-link"><i class="fas fa-th-large me-2"></i> Dashboard</a>
            <a href="downloads.php" class="nav-link active"><i class="fas fa-download me-2"></i> Downloads</a>
            <a href="logout.php" class="nav-link"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
        </nav>
    </div>

    <div class="main-content">
        <div class="top-bar">
            <h4>Manage Downloads</h4>
        </div>

        <div class="card mb-4">
            <h6 class="fw-bold mb-3"><i class="fas fa-plus-circle text-primary me-2"></i> Add New Download</h6>
            <form method="POST" class="row g-2">
                <div class="col-md-4">
                    <input type="text" name="filename" class="form-control" placeholder="File Name (e.g. company-profile.pdf)" required>
                </div>
                <div class="col-md-3">
                    <input type="text" name="title" class="form-control" placeholder="Display Title" required>
                </div>
                <div class="col-md-3">
                    <input type="text" name="size" class="form-control" placeholder="File Size (e.g. 1.2 MB)">
                </div>
                <div class="col-md-2">
                    <button type="submit" name="add_download" class="btn btn-primary w-100">Add</button>
                </div>
            </form>
        </div>

        <div class="card">
            <h6 class="fw-bold mb-3"><i class="fas fa-list me-2"></i> Available Downloads</h6>
            <table class="table table-hover">
                <thead><tr><th>ID</th><th>File Name</th><th>Title</th><th>Size</th><th>Downloads</th><th>Date</th></tr></thead>
                <tbody>
                    <?php foreach ($downloads as $d): ?>
                    <tr>
                        <td><?php echo $d['id']; ?></td>
                        <td><?php echo htmlspecialchars($d['filename']); ?></td>
                        <td><?php echo htmlspecialchars($d['title']); ?></td>
                        <td><?php echo htmlspecialchars($d['file_size']); ?></td>
                        <td><?php echo $d['download_count']; ?></td>
                        <td><?php echo date('M d, Y', strtotime($d['created_at'])); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>