<?php
session_start();
require_once 'includes/db.php';

// Security check
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin/login.php');
    exit;
}

// Fetch all subscribers
$stmt = $pdo->query("SELECT email, subscribed_at FROM newsletter_subscribers ORDER BY subscribed_at DESC");
$subs = $stmt->fetchAll();

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=kileletech_subscribers.csv');

$output = fopen('php://output', 'w');
fputcsv($output, ['Email', 'Subscribed Date']);

foreach ($subs as $sub) {
    fputcsv($output, [$sub['email'], $sub['subscribed_at']]);
}
fclose($output);
exit;
?>