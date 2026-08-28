<?php
// ============================================================
// DATABASE CONFIGURATION
// ============================================================

// Database settings
define('DB_HOST', 'localhost');
define('DB_NAME', 'kilele_tech');
define('DB_USER', 'root');
define('DB_PASS', '');  // Leave empty for XAMPP default

// Site settings
define('SITE_NAME', 'KileleTech Admin');
define('SITE_URL', 'http://localhost:8000/admin/');

// ============================================================
// DATABASE CONNECTION
// ============================================================
function getDBConnection() {
    try {
        $pdo = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]
        );
        return $pdo;
    } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }
}

$db = getDBConnection();
?>