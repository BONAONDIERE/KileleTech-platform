<?php
require_once 'includes/db.php';

echo "✅ Database connection successful!<br>";
echo "Database: " . $DB_NAME . "<br>";

// Show tables
try {
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll();
    echo "Tables in database:<br><ul>";
    foreach ($tables as $row) {
        echo "<li>" . implode('', $row) . "</li>";
    }
    echo "</ul>";
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>