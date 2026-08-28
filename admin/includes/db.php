<?php
/**
 * Shared database connection.
 * Included by root pages and admin files.
 */

$DB_HOST = 'localhost';
$DB_NAME = 'kilelete_kilele_tech';
$DB_USER = 'YOUR_DATABASE_user';
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

    // Kenya uses East Africa Time (UTC+3)
    $pdo->exec("SET time_zone = '+03:00'");

} catch (PDOException $e) {
    http_response_code(500);
    die('Database connection failed. Check that MySQL is running and the database credentials are correct. Error: ' . $e->getMessage());
}
