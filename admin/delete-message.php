<?php
// ============================================================
// DELETE MESSAGE
// ============================================================

require_once 'includes/config.php';
require_once 'includes/auth.php';
require_once 'includes/functions.php';

requireLogin();

$type = $_GET['type'] ?? 'contact';
$id = (int)($_GET['id'] ?? 0);

$tableMap = [
    'contact' => 'contact_submissions',
    'quote' => 'quote_requests',
    'join' => 'join_requests'
];

$table = $tableMap[$type] ?? 'contact_submissions';

deleteSubmission($db, $table, $id);

header('Location: messages.php?type=' . $type);
exit;