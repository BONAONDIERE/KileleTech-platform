<?php
// Connect to database
require_once __DIR__ . '/includes/db.php';

// Tell browser to expect JSON
header('Content-Type: application/json');

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

// Get all form data
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$company = trim($_POST['company'] ?? '');
$services = isset($_POST['services']) ? implode(', ', $_POST['services']) : '';
$message = trim($_POST['message'] ?? '');

// Validate required fields
if (empty($name) || empty($email) || empty($services)) {
    echo json_encode(['success' => false, 'message' => 'Please fill in your name, email, and select at least one service.']);
    exit;
}

// Validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Please enter a valid email address.']);
    exit;
}

// Insert into database
try {
    $stmt = $pdo->prepare("INSERT INTO bundle_quotes (name, email, phone, company, services, message) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$name, $email, $phone, $company, $services, $message]);
    echo json_encode(['success' => true, 'message' => '✅ Bundled quote request sent successfully!']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>