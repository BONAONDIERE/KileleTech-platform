<?php
require_once 'includes/db.php';

$filename = $_GET['file'] ?? '';
$safe_filename = basename($filename);
$file_path = __DIR__ . '/downloads/' . $safe_filename;

if (empty($safe_filename) || !file_exists($file_path)) {
    die('File not found.');
}

$stmt = $pdo->prepare("INSERT INTO download_counts (filename, count) VALUES (?, 1) ON DUPLICATE KEY UPDATE count = count + 1");
$stmt->execute([$safe_filename]);

header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . $safe_filename . '"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($file_path));
readfile($file_path);
exit;
?>