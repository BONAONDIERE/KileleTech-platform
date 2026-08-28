<?php
// Include your actual database connection (this prevents the root error)
require_once __DIR__ . '/../includes/db.php';

// Fetch all subscribers
$stmt = $pdo->query("SELECT * FROM newsletter_subscribers ORDER BY subscribed_at DESC");
$subscribers = $stmt->fetchAll();

// Output CSV headers
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="subscribers_' . date('Y-m-d') . '.csv"');

$output = fopen('php://output', 'w');
fputcsv($output, ['ID', 'Email', 'Status', 'Date Subscribed']);

foreach ($subscribers as $sub) {
    fputcsv($output, [
        $sub['id'],
        $sub['email'],
        $sub['status'] ?? 'active',
        $sub['subscribed_at']
    ]);
}
fclose($output);
exit;
?>