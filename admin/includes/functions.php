<?php
/**
 * Shared helper functions for the admin portal.
 * Include after auth.php on every protected page:
 *   require_once __DIR__ . '/includes/auth.php';
 *   require_once __DIR__ . '/includes/functions.php';
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../includes/db.php';


/** Escape a string for safe HTML output. */
function e($str) {
    return htmlspecialchars((string) ($str ?? ''), ENT_QUOTES, 'UTF-8');
}

/** Human-friendly date, e.g. "Aug 18, 2026 at 3:42 PM" */
function formatDate($datetime) {
    if (!$datetime) return '—';
    return date('M j, Y \a\t g:i A', strtotime($datetime));
}

/** Renders a colored status pill for new/read/replied */
function statusBadge($status) {
    $map = [
        'new'     => ['New',     'admin-badge--new'],
        'read'    => ['Read',    'admin-badge--read'],
        'replied' => ['Replied', 'admin-badge--replied'],
    ];
    [$label, $class] = $map[$status] ?? [$status, 'admin-badge--new'];
    return '<span class="admin-badge ' . $class . '">' . e($label) . '</span>';
}

/** Currently logged-in admin, pulled from the session. */
function currentAdmin() {
    return [
        'id'        => $_SESSION['admin_id'] ?? null,
        'username'  => $_SESSION['admin_username'] ?? '',
        'full_name' => $_SESSION['admin_name'] ?? '',
    ];
}

/**
 * Opens the shared admin page shell: <!DOCTYPE>, sidebar nav, and
 * the start of the main content area. Call admin_footer() to close
 * everything back up.
 */
function admin_header($pageTitle, $active = '') {
    $admin = currentAdmin();
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($pageTitle); ?> - Kilele Tech Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/layout.css">
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body class="admin-body">
<div class="admin-shell">

    <aside class="admin-sidebar">
        <div class="admin-sidebar__brand">
            <span class="kilele-wordmark__text" style="font-size: 22px;">
                <span class="wm-k">K</span><span class="wm-i">i</span><span class="wm-l1">l</span><span class="wm-e1">e</span><span class="wm-l2">l</span><span class="wm-e2">e</span>
            </span>
            <div class="admin-sidebar__tag">Admin Portal</div>
        </div>

        <nav class="admin-sidebar__nav">
            <a href="dashboard.php" class="<?php echo $active === 'dashboard' ? 'active' : ''; ?>">
                <i class="fas fa-gauge"></i> Dashboard
            </a>
            <a href="users.php" class="<?php echo $active === 'users' ? 'active' : ''; ?>">
                <i class="fas fa-users-gear"></i> Admin Users
            </a>
            <a href="export.php?type=contact">
                <i class="fas fa-file-export"></i> Export Data
            </a>
            <a href="../index.php" target="_blank">
                <i class="fas fa-arrow-up-right-from-square"></i> View Site
            </a>
        </nav>

        <div class="admin-sidebar__footer">
            <a href="logout.php" class="admin-sidebar__logout">
                <i class="fas fa-right-from-bracket"></i> Logout
            </a>
        </div>
    </aside>

    <div class="admin-main">
        <header class="admin-topbar">
            <h1><?php echo e($pageTitle); ?></h1>
            <div class="admin-topbar__user">
                <i class="fas fa-user-circle"></i>
                <?php echo e($admin['full_name'] !== '' ? $admin['full_name'] : $admin['username']); ?>
            </div>
        </header>

        <div class="admin-content">
    <?php
}

/** Closes the shell opened by admin_header(). */
function admin_footer() {
    ?>
        </div>
    </div>
</div>
</body>
</html>
    <?php
}