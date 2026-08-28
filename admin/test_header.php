<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Testing header...<br>";

// Simulate what subscribers.php does
$pageTitle = 'Test Page';
require_once 'header.php';

echo "Header loaded successfully!<br>";
echo "Session data: ";
var_dump($_SESSION);
?>