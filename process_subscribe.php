<?php
// Force PHP to show errors directly in the network response
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/includes/db.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Please enter a valid email address.']);
        exit;
    }

    try {
        // Prevent duplicates
        $checkStmt = $pdo->prepare("SELECT id FROM newsletter_subscribers WHERE email = ?");
        $checkStmt->execute([$email]);
        if ($checkStmt->fetch()) {
            echo json_encode(['success' => false, 'message' => 'This email is already subscribed.']);
            exit;
        }

        // Insert the new email
        $stmt = $pdo->prepare("INSERT INTO newsletter_subscribers (email) VALUES (?)");
        $stmt->execute([$email]);
        
        echo json_encode(['success' => true, 'message' => '✅ Subscribed successfully!']);
        
    } catch (PDOException $e) {
        if ($e->errorInfo[1] == 1062) {
            echo json_encode(['success' => false, 'message' => 'This email is already subscribed.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        }
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>