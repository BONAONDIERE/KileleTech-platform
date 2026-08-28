<?php
echo "<h2>PDO MySQL Test</h2>";

// Check if PDO is loaded
if (class_exists('PDO')) {
    echo "✅ PDO is loaded<br>";
} else {
    echo "❌ PDO is NOT loaded<br>";
}

// Check if pdo_mysql driver is available
$drivers = PDO::getAvailableDrivers();
echo "<br>Available PDO drivers:<br>";
echo "<ul>";
foreach ($drivers as $driver) {
    echo "<li>" . $driver . "</li>";
}
echo "</ul>";

// Try to connect to MySQL
if (in_array('mysql', $drivers)) {
    echo "<br>✅ pdo_mysql driver is available!<br>";
    try {
        $pdo = new PDO('mysql:host=localhost', 'root', '');
        echo "✅ Database connection successful!";
    } catch (PDOException $e) {
        echo "❌ Database connection failed: " . $e->getMessage();
    }
} else {
    echo "<br>❌ pdo_mysql driver is NOT available!";
}
?>