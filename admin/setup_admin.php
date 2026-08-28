<?php
require_once '../includes/db.php';

$username = 'admin';
$password = 'admin@2026!!';

// Delete the old, broken hash
$pdo->exec("DELETE FROM admin_users WHERE username = '$username'");

// Generate a brand new, 100% correct hash automatically
$hash = password_hash($password, PASSWORD_DEFAULT);

// Insert it perfectly into the database
$stmt = $pdo->prepare("INSERT INTO admin_users (username, password_hash, role, created_at) VALUES (?, ?, 'super_admin', NOW())");
$stmt->execute([$username, $hash]);

echo "✅ Admin user successfully recreated! <br>";
echo "Username: <strong>$username</strong><br>";
echo "Password: <strong>$password</strong><br>";
echo "<br><a href='login.php'>Click here to Log In</a>";
?>