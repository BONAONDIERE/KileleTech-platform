<?php
require_once __DIR__ . '/includes/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $company = trim($_POST['company'] ?? '');
    $package = trim($_POST['package'] ?? '');
    $budget = trim($_POST['budget'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (empty($name) || empty($email) || empty($message)) {
        echo json_encode(['success' => false, 'message' => 'Please fill in all required fields.']);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Please enter a valid email address.']);
        exit;
    }

    try {
        // ==========================================
        // BUNDLE QUOTE (Multiple Services)
        // ==========================================
        if (isset($_POST['services']) && is_array($_POST['services'])) {
            $services = implode(', ', $_POST['services']);

            $stmt = $pdo->prepare("INSERT INTO bundle_quotes (name, company, phone, email, services, package, budget, message) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$name, $company, $phone, $email, $services, $package, $budget, $message]);

            echo json_encode(['success' => true, 'message' => '✅ Bundle quote request sent successfully!']);
        } 
        // ==========================================
        // STANDARD QUOTE (Single Service)
        // ==========================================
        else {
            $service = trim($_POST['service'] ?? '');

            $stmt = $pdo->prepare("INSERT INTO quote_requests (name, company, phone, email, service, package, message) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$name, $company, $phone, $email, $service, $package, $message]);

            echo json_encode(['success' => true, 'message' => '✅ Quote request sent successfully!']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>