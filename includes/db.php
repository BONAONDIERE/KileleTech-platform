<?php
/**
 * Shared database connection.
 * Included by root pages and admin files.
 */

// Kenya timezone
date_default_timezone_set('Africa/Nairobi');

$DB_HOST = 'localhost';
$DB_NAME = 'kilelete_kilele_tech';
$DB_USER = 'YOUR_DATABASE_USER';
$DB_PASS = 'YOUR_DATABASE_PASSWORD_HERE';

try {
    $pdo = new PDO(
        "mysql:host={$DB_HOST};dbname={$DB_NAME};charset=utf8mb4",
        $DB_USER,
        $DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );

    // Set this database connection to Kenya time (UTC+3)
    $pdo->exec("SET time_zone = '+03:00'");

} catch (PDOException $e) {
    http_response_code(500);
    die('Database connection failed. Error: ' . $e->getMessage());
}
