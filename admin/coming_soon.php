<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Coming Soon – KileleTech Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f8fafb; font-family: 'Poppins', sans-serif; }
        .coming-soon {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.04);
            padding: 80px;
            text-align: center;
            margin-top: 100px;
        }
        .coming-soon i { font-size: 4rem; color: #29A08E; }
        .coming-soon h2 { font-weight: 800; color: #0f1e33; margin-top: 20px; font-size: 2rem; }
        .coming-soon p { color: #777; font-size: 1.1rem; }
        .coming-soon .btn { border-radius: 50px; padding: 12px 30px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="coming-soon">
            <i class="fas fa-cogs"></i>
            <h2>This Section is Being Built</h2>
            <p>Our team is working hard to bring this feature to you.</p>
            <a href="dashboard.php" class="btn btn-primary mt-3">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>