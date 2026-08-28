<?php
echo "<h2>Loaded Extensions</h2>";
$extensions = get_loaded_extensions();
sort($extensions);
echo "<ul>";
foreach ($extensions as $ext) {
    echo "<li>" . $ext . "</li>";
}
echo "</ul>";

echo "<br><br>";
if (extension_loaded('pdo_mysql')) {
    echo "✅ <strong>pdo_mysql is LOADED!</strong>";
} else {
    echo "❌ <strong>pdo_mysql is NOT loaded.</strong>";
}
?>