<?php
/**
 * Authentication guard. Include this at the very top of every
 * admin page that requires a logged-in session (everything except
 * login.php and setup.php).
 *
 * Usage:
 *   require_once __DIR__ . '/includes/auth.php';
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}