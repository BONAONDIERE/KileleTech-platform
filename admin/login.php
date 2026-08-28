<?php
session_start();

// Prevent redirect loops and clear broken sessions
if ($_SERVER['REQUEST_METHOD'] !== 'POST' && isset($_SESSION['admin_logged_in'])) {
    session_unset();
    session_destroy();
    session_start(); 
}

require_once '../includes/db.php';

if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
$logout_msg = '';

// Check for the logout success message
if (isset($_GET['logout']) && $_GET['logout'] == 'success') {
    $logout_msg = '<div class="alert alert-success border-0 shadow-sm rounded-3 py-2"><i class="fas fa-check-circle me-2"></i> You have successfully logged out.</div>';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_user'] = $user['username'];
        $_SESSION['admin_role'] = $user['role'];
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Invalid username or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Kilele Tech</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #0f1e33 0%, #1a2d47 100%); /* KileleTech Navy Blue Gradient */
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Poppins', sans-serif;
        }
        .login-card {
            background: #ffffff;
            border-radius: 24px;
            padding: 45px 40px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 25px 70px rgba(0,0,0,0.4);
        }
        .login-card h3 {
            color: #0f1e33;
            font-weight: 700;
            letter-spacing: -0.5px;
        }
        .login-card .btn-primary {
            background: #29A08E;
            border: none;
            padding: 12px;
            font-weight: 600;
            transition: 0.3s;
            border-radius: 12px;
        }
        .login-card .btn-primary:hover {
            background: #1e7a6b;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(41, 160, 142, 0.3);
        }
        .form-control {
            border-radius: 12px;
            padding: 12px 15px;
            border: 1px solid #e1e5e9;
        }
        .form-control:focus {
            border-color: #29A08E;
            box-shadow: 0 0 0 0.25rem rgba(41, 160, 142, 0.15);
        }
        .input-group-text {
            background: #fff;
            border-radius: 0 12px 12px 0;
            border: 1px solid #e1e5e9;
            cursor: pointer;
            transition: 0.3s;
        }
        .input-group-text:hover {
            color: #29A08E;
        }
        
        /* --- New Beautiful Back-to-Site Link Styles --- */
        .back-to-site-link {
            color: #29A08E;
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .back-to-site-link:hover {
            color: #1e7a6b;
            transform: translateX(-4px); /* Slight slide-left effect on hover */
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="text-center mb-4">
            <h3><i class="fas fa-shield-halved me-2" style="color: #29A08E;"></i>Admin Login</h3>
            <p class="text-muted" style="font-size: 0.9rem;">Enter credentials to manage your site</p>
        </div>
        
        <?php if ($logout_msg): ?>
            <?php echo $logout_msg; ?>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-danger border-0 shadow-sm rounded-3 py-2">
                <i class="fas fa-exclamation-circle me-2"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label fw-semibold text-secondary">Username</label>
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0 rounded-start-3" style="border-radius: 12px 0 0 12px;"><i class="fas fa-user text-muted"></i></span>
                    <input type="text" name="username" class="form-control border-start-0 ps-0" placeholder="Enter username" required>
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold text-secondary">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0 rounded-start-3" style="border-radius: 12px 0 0 12px;"><i class="fas fa-lock text-muted"></i></span>
                    <input type="password" name="password" id="passwordInput" class="form-control border-start-0 ps-0" placeholder="Enter password" required>
                    <button class="input-group-text bg-transparent border-start-0 rounded-end-3" type="button" id="togglePassword" style="border-radius: 0 12px 12px 0;">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100">Log In</button>
        </form>

        <!-- New "Back to Main Site" Link -->
        <div class="text-center mt-4">
            <a href="../index.php" class="back-to-site-link">
                <i class="fas fa-arrow-left"></i> Back to Main Website
            </a>
        </div>
    </div>

    <script>
        // Password Toggle Visibility
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.querySelector('#togglePassword');
            const passwordInput = document.querySelector('#passwordInput');

            togglePassword.addEventListener('click', function (e) {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.querySelector('i').classList.toggle('fa-eye');
                this.querySelector('i').classList.toggle('fa-eye-slash');
            });
        });
    </script>
</body>
</html>