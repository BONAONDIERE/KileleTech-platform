<?php
/**
 * Admin Setup – Creates admin user and verifies database connection.
 * Run this once, then delete or protect it.
 */

require_once __DIR__ . '/includes/functions.php';

// Only allow localhost access for security
if ($_SERVER['REMOTE_ADDR'] !== '127.0.0.1' && $_SERVER['REMOTE_ADDR'] !== '::1') {
    die('Setup only allowed on localhost.');
}

// Check if admin already exists
$stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'admin'");
$adminExists = $stmt->fetchColumn() > 0;

$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $fullName = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if (empty($username) || empty($password) || empty($fullName) || empty($email)) {
        $error = 'Please fill in all fields.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        try {
            // Check if username exists
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
            $stmt->execute([$username]);
            if ($stmt->fetchColumn() > 0) {
                $error = 'Username already exists.';
            } else {
                // Hash password and insert
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (username, password, full_name, email, role) VALUES (?, ?, ?, ?, 'admin')");
                $stmt->execute([$username, $hashed, $fullName, $email]);
                $success = true;
            }
        } catch (PDOException $e) {
            $error = 'Database error: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Setup – KileleTech</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #0f1e33 0%, #1a2d47 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Poppins', sans-serif;
            padding: 20px;
        }
        .setup-card {
            background: #fff;
            border-radius: 18px;
            padding: 40px 36px;
            max-width: 500px;
            width: 100%;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .setup-card .logo { text-align: center; margin-bottom: 24px; }
        .setup-card .logo h2 { color: #29A08E; font-weight: 700; }
        .setup-card .logo p { color: #888; font-size: 13px; }
        .form-control {
            border-radius: 10px;
            padding: 12px 16px;
            border: 1px solid #ddd;
        }
        .form-control:focus {
            border-color: #29A08E;
            box-shadow: 0 0 0 3px rgba(41,160,142,0.12);
        }
        .btn-primary {
            background: #29A08E;
            border: none;
            border-radius: 50px;
            padding: 12px;
            font-weight: 600;
            color: #fff;
            width: 100%;
            transition: 0.3s;
        }
        .btn-primary:hover { background: #1f7a6f; color: #fff; }
        .alert { border-radius: 10px; }
        .success-box {
            background: #d4edda;
            color: #155724;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }
        .success-box i { font-size: 3rem; color: #28a745; display: block; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="setup-card">
        <div class="logo">
            <h2>KileleTech</h2>
            <p>Admin Setup</p>
        </div>

        <?php if ($success): ?>
            <div class="success-box">
                <i class="fas fa-check-circle"></i>
                <h5>✅ Admin user created successfully!</h5>
                <p>You can now <a href="login.php" style="color: #29A08E; font-weight: 600;">login</a> with your credentials.</p>
                <p><small class="text-muted">For security, delete this file after setup.</small></p>
            </div>
        <?php else: ?>
            
            <?php if ($adminExists): ?>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    An admin user already exists. Use this form to create an additional admin, or <a href="login.php" style="color: #29A08E; font-weight: 600;">login</a>.
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i> <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Username *</label>
                    <input type="text" name="username" class="form-control" placeholder="Enter admin username" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Full Name *</label>
                    <input type="text" name="full_name" class="form-control" placeholder="John Doe" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Email *</label>
                    <input type="email" name="email" class="form-control" placeholder="admin@example.com" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Password *</label>
                    <input type="password" name="password" class="form-control" placeholder="Min 6 characters" required>
                    <small class="text-muted">Password must be at least 6 characters.</small>
                </div>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-user-plus me-2"></i> Create Admin User
                </button>
            </form>
            <div class="text-center mt-3">
                <small class="text-muted">Already have an account? <a href="login.php" style="color: #29A08E; font-weight: 600;">Login</a></small>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>