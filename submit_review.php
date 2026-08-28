<?php
require_once __DIR__ . '/includes/db.php'; // Connects to your existing database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $rating = (int)$_POST['rating'];
    $review = htmlspecialchars($_POST['review']);

    // Insert into database
    $stmt = $pdo->prepare("INSERT INTO reviews (name, rating, review) VALUES (?, ?, ?)");
    $stmt->execute([$name, $rating, $review]);

    // Redirect back to the page to prevent double submits
    header('Location: index.php?success=1');
    exit;
}
?>