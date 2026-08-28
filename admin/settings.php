<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Check if user is super_admin or admin
$userRole = isset($_SESSION['admin_role']) ? $_SESSION['admin_role'] : 'admin';
$isSuperAdmin = ($userRole === 'super_admin');

/* -------------------------------------------------------------
   CSRF token
------------------------------------------------------------- */
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

/* -------------------------------------------------------------
   Helpers
------------------------------------------------------------- */
function saveSetting(PDO $pdo, string $key, $value): void {
    // Convert arrays/objects to JSON
    if (is_array($value) || is_object($value)) {
        $value = json_encode($value);
    }
    $value = trim((string)$value);
    
    $check = $pdo->prepare("SELECT COUNT(*) FROM site_settings WHERE setting_key = ?");
    $check->execute([$key]);
    if ($check->fetchColumn() > 0) {
        $stmt = $pdo->prepare("UPDATE site_settings SET setting_value = ? WHERE setting_key = ?");
        $stmt->execute([$value, $key]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO site_settings (setting_key, setting_value) VALUES (?, ?)");
        $stmt->execute([$key, $value]);
    }
}

function handleUpload(PDO $pdo, string $fieldName, string $settingKey, array $allowed, string $prefix): void {
    if (!isset($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] !== UPLOAD_ERR_OK) {
        return;
    }
    $ext = strtolower(pathinfo($_FILES[$fieldName]['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed)) {
        return;
    }
    $new_name = $prefix . '_' . time() . '_' . bin2hex(random_bytes(3)) . '.' . $ext;
    $dest = __DIR__ . '/../uploads/' . $new_name;
    if (move_uploaded_file($_FILES[$fieldName]['tmp_name'], $dest)) {
        // Also add to media library
        try {
            $stmt = $pdo->prepare("INSERT INTO media_library (media_type, file_path, title) VALUES ('image', ?, ?)");
            $stmt->execute(['/uploads/' . $new_name, ucfirst($prefix) . ' Image']);
        } catch (Exception $e) {}
        saveSetting($pdo, $settingKey, '/uploads/' . $new_name);
    }
}

function getMediaLibrary(PDO $pdo, string $type = null): array {
    $sql = "SELECT * FROM media_library ORDER BY created_at DESC";
    if ($type) {
        $sql = "SELECT * FROM media_library WHERE media_type = ? ORDER BY created_at DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$type]);
    } else {
        $stmt = $pdo->query($sql);
    }
    return $stmt->fetchAll() ?: [];
}

/* -------------------------------------------------------------
   Handle form submissions
------------------------------------------------------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
        $response = ['status' => 'error', 'message' => 'Security token expired. Please try again.'];
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }
        header('Location: site_settings.php?status=error&msg=' . urlencode($response['message']));
        exit;
    }

    $activeTab = $_POST['active_tab'] ?? 'general';

    // ---- AJAX/JSON save ----
    if (isset($_POST['ajax_save']) || isset($_POST['save_settings_ajax'])) {
        try {
            if (isset($_POST['settings']) && is_array($_POST['settings'])) {
                foreach ($_POST['settings'] as $key => $value) {
                    saveSetting($pdo, $key, $value);
                }
            }
            
            // Handle file uploads
            handleUpload($pdo, 'logo_file', 'logo_uploaded', ['jpg','jpeg','png','svg','webp'], 'logo');
            handleUpload($pdo, 'hero_image_file', 'hero_image', ['jpg','jpeg','png','webp'], 'hero');
            handleUpload($pdo, 'favicon_file', 'favicon', ['jpg','jpeg','png','ico','svg'], 'favicon');
            handleUpload($pdo, 'og_image_file', 'og_image', ['jpg','jpeg','png','webp'], 'og');
            
            header('Content-Type: application/json');
            echo json_encode(['status' => 'success', 'message' => 'Settings saved successfully!']);
            exit;
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
            exit;
        }
    }

    // ---- Regular form save ----
    if (isset($_POST['save_settings'])) {
        foreach ($_POST['settings'] as $key => $value) {
            saveSetting($pdo, $key, $value);
        }

        handleUpload($pdo, 'logo_file', 'logo_uploaded', ['jpg','jpeg','png','svg','webp'], 'logo');
        handleUpload($pdo, 'hero_image_file', 'hero_image', ['jpg','jpeg','png','webp'], 'hero');
        handleUpload($pdo, 'favicon_file', 'favicon', ['jpg','jpeg','png','ico','svg'], 'favicon');
        handleUpload($pdo, 'og_image_file', 'og_image', ['jpg','jpeg','png','webp'], 'og');

        header('Location: site_settings.php?tab=' . urlencode($activeTab) . '&status=success&msg=' . urlencode('Settings updated successfully.'));
        exit;
    }
}

/* -------------------------------------------------------------
   Load data for rendering
------------------------------------------------------------- */
$settings = [];
foreach ($pdo->query("SELECT * FROM site_settings")->fetchAll() as $row) {
    $settings[$row['setting_key']] = $row['setting_value'];
}

$activeTab = $_GET['tab'] ?? 'general';
$status    = $_GET['status'] ?? '';
$msg       = $_GET['msg'] ?? '';

$val = function (string $key, string $default = '') use ($settings) {
    return htmlspecialchars($settings[$key] ?? $default, ENT_QUOTES);
};

// Get recent activity for dashboard
$recentUpdates = $pdo->query("SELECT * FROM updates ORDER BY created_at DESC LIMIT 3")->fetchAll();
$totalMessages = $pdo->query("SELECT COUNT(*) FROM contact_messages")->fetchColumn();
$totalSubscribers = $pdo->query("SELECT COUNT(*) FROM newsletter_subscribers")->fetchColumn();
$totalProjects = $pdo->query("SELECT COUNT(*) FROM projects")->fetchColumn();

$tabs = [
    'dashboard'    => ['label' => 'Dashboard',      'icon' => 'fa-gauge-high'],
    'general'      => ['label' => 'General',        'icon' => 'fa-globe'],
    'hero'         => ['label' => 'Hero Section',   'icon' => 'fa-image'],
    'branding'     => ['label' => 'Branding',       'icon' => 'fa-palette'],
    'seo'          => ['label' => 'SEO',            'icon' => 'fa-magnifying-glass'],
    'social'       => ['label' => 'Social & Links', 'icon' => 'fa-share-nodes'],
    'theme'        => ['label' => 'Theme',          'icon' => 'fa-brush'],
    'integrations' => ['label' => 'Integrations',   'icon' => 'fa-plug'],
];

// Get media library for media picker
$mediaItems = getMediaLibrary($pdo);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site Settings – KileleTech Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --brand: #29A08E;
            --brand-dark: #1e7a6b;
            --brand-light: #e8f5f2;
            --ink: #0f1e33;
            --gray-50: #f8fafb;
            --gray-100: #f1f3f5;
            --gray-200: #e9ecef;
            --gray-300: #dee2e6;
            --gray-400: #ced4da;
            --gray-500: #adb5bd;
            --gray-600: #6c757d;
            --gray-700: #495057;
            --gray-800: #343a40;
            --gray-900: #212529;
        }
        * { font-family: 'Inter', sans-serif; }
        body { background: var(--gray-50); }

        /* === SIDEBAR === */
        .sidebar {
            background: #ffffff;
            border-right: 1px solid var(--gray-200);
            min-height: 100vh;
            width: 260px;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
            padding-top: 20px;
            overflow-y: auto;
        }
        .sidebar .logo-area {
            padding: 0 24px 20px;
            border-bottom: 1px solid var(--gray-100);
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
        }
        .sidebar .logo-icon {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, var(--brand), var(--brand-dark));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.1rem;
            font-weight: 800;
        }
        .sidebar .logo-text h5 { font-weight: 700; color: var(--ink); font-size: 0.95rem; margin-bottom: 0; line-height: 1.2; }
        .sidebar .logo-text small { color: var(--gray-500); font-size: 0.65rem; }
        
        .sidebar .nav-section-title {
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--gray-500);
            padding: 10px 24px 5px;
        }
        .sidebar .nav-link {
            color: var(--gray-700);
            padding: 10px 24px;
            font-size: 0.85rem;
            font-weight: 500;
            transition: 0.2s;
            border-left: 3px solid transparent;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background: var(--brand-light);
            color: var(--brand);
            border-left-color: var(--brand);
        }
        .sidebar .nav-link i { width: 22px; color: var(--gray-500); font-size: 0.9rem; }
        .sidebar .nav-link:hover i, .sidebar .nav-link.active i { color: var(--brand); }

        /* === MAIN CONTENT === */
        .main-content { margin-left: 260px; padding: 0; }

        .top-bar {
            background: #ffffff;
            padding: 14px 30px;
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 999;
            backdrop-filter: blur(10px);
            background: rgba(255,255,255,0.95);
        }
        .top-bar .page-title { font-weight: 700; color: var(--ink); margin-bottom: 0; font-size: 1.1rem; }
        .top-bar .page-title small { font-weight: 400; color: var(--gray-500); font-size: 0.8rem; margin-left: 10px; }

        .top-bar .user-profile {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .top-bar .avatar {
            width: 36px;
            height: 36px;
            background: var(--brand);
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.85rem;
        }
        .top-bar .user-info h6 { font-size: 0.8rem; margin: 0; font-weight: 600; color: var(--ink); }
        .top-bar .user-info small { color: var(--gray-500); font-size: 0.7rem; }

        .content-wrapper { padding: 24px 30px; }

        /* === SETTINGS LAYOUT === */
        .settings-shell { display: flex; gap: 24px; align-items: flex-start; }

        .tab-rail {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            padding: 8px;
            width: 220px;
            flex-shrink: 0;
            position: sticky;
            top: 80px;
            border: 1px solid var(--gray-200);
        }
        .tab-rail button {
            width: 100%;
            text-align: left;
            border: none;
            background: transparent;
            padding: 10px 14px;
            border-radius: 10px;
            font-weight: 500;
            color: var(--gray-700);
            margin-bottom: 2px;
            transition: 0.2s;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.85rem;
        }
        .tab-rail button i { width: 20px; color: var(--gray-500); font-size: 0.85rem; transition: 0.2s; }
        .tab-rail button:hover { background: var(--gray-50); color: var(--brand); }
        .tab-rail button:hover i { color: var(--brand); }
        .tab-rail button.active {
            background: var(--brand);
            color: #fff;
            box-shadow: 0 2px 8px rgba(41, 160, 142, 0.25);
        }
        .tab-rail button.active i { color: #fff; }
        .tab-rail .tab-badge {
            margin-left: auto;
            background: var(--gray-200);
            color: var(--gray-600);
            font-size: 0.6rem;
            padding: 2px 8px;
            border-radius: 20px;
            font-weight: 600;
        }
        .tab-rail button.active .tab-badge { background: rgba(255,255,255,0.2); color: #fff; }

        .panel-area { flex: 1; min-width: 0; }

        .settings-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 28px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            border: 1px solid var(--gray-200);
            display: none;
            animation: fadeIn 0.3s ease;
        }
        .settings-card.active { display: block; }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .card-header-section {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--gray-100);
        }
        .card-header-section .card-title {
            font-weight: 700;
            color: var(--ink);
            font-size: 1.1rem;
            margin-bottom: 2px;
        }
        .card-header-section .card-subtitle {
            color: var(--gray-500);
            font-size: 0.85rem;
        }
        .card-header-section .card-status {
            font-size: 0.75rem;
            padding: 4px 12px;
            border-radius: 20px;
            background: var(--brand-light);
            color: var(--brand);
            font-weight: 600;
            white-space: nowrap;
        }

        .form-label {
            font-weight: 600;
            color: var(--ink);
            font-size: 0.85rem;
            margin-bottom: 4px;
        }
        .form-hint {
            color: var(--gray-500);
            font-size: 0.75rem;
            margin-top: 4px;
        }
        .form-control, .form-select {
            border-radius: 10px;
            padding: 10px 14px;
            border: 1px solid var(--gray-300);
            font-size: 0.9rem;
            transition: 0.2s;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--brand);
            box-shadow: 0 0 0 3px rgba(41, 160, 142, 0.12);
        }
        .form-control-sm { padding: 6px 12px; font-size: 0.8rem; }

        .btn-primary {
            background: var(--brand);
            border: none;
            border-radius: 10px;
            padding: 10px 28px;
            font-weight: 600;
            transition: 0.2s;
        }
        .btn-primary:hover { background: var(--brand-dark); transform: translateY(-1px); box-shadow: 0 4px 12px rgba(41, 160, 142, 0.3); }
        .btn-outline-brand {
            border: 1px solid var(--brand);
            color: var(--brand);
            border-radius: 10px;
            padding: 8px 20px;
            font-weight: 600;
            transition: 0.2s;
        }
        .btn-outline-brand:hover { background: var(--brand); color: #fff; }

        /* === MEDIA UPLOAD === */
        .media-upload-zone {
            border: 2px dashed var(--gray-300);
            border-radius: 12px;
            padding: 30px;
            text-align: center;
            cursor: pointer;
            transition: 0.3s;
            background: var(--gray-50);
        }
        .media-upload-zone:hover {
            border-color: var(--brand);
            background: var(--brand-light);
        }
        .media-upload-zone.dragover {
            border-color: var(--brand);
            background: var(--brand-light);
            transform: scale(1.01);
        }
        .media-upload-zone i { font-size: 2.5rem; color: var(--brand); margin-bottom: 10px; }
        .media-upload-zone p { margin: 0; color: var(--gray-600); font-size: 0.9rem; }
        .media-upload-zone small { color: var(--gray-500); font-size: 0.75rem; }

        .preview-box {
            background: var(--gray-50);
            border-radius: 12px;
            padding: 15px;
            text-align: center;
            min-height: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--gray-200);
        }
        .preview-box img { max-height: 80px; max-width: 100%; border-radius: 6px; }
        .preview-box video { max-height: 150px; max-width: 100%; border-radius: 6px; }
        .preview-box .empty-state { color: var(--gray-500); font-size: 0.85rem; }

        /* === MEDIA PICKER === */
        .media-picker-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 10px;
            max-height: 300px;
            overflow-y: auto;
            padding: 4px;
        }
        .media-picker-item {
            background: var(--gray-50);
            border-radius: 10px;
            padding: 8px;
            cursor: pointer;
            transition: 0.2s;
            border: 2px solid transparent;
            text-align: center;
        }
        .media-picker-item:hover { border-color: var(--brand); transform: translateY(-2px); }
        .media-picker-item.selected { border-color: var(--brand); background: var(--brand-light); }
        .media-picker-item img, .media-picker-item video {
            width: 100%;
            height: 80px;
            object-fit: cover;
            border-radius: 6px;
        }
        .media-picker-item small {
            font-size: 0.6rem;
            color: var(--gray-500);
            display: block;
            margin-top: 4px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* === DASHBOARD WIDGETS === */
        .stat-card {
            background: #fff;
            border-radius: 14px;
            padding: 20px;
            border: 1px solid var(--gray-200);
            transition: 0.2s;
        }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .stat-card .stat-number { font-size: 2rem; font-weight: 800; color: var(--ink); line-height: 1; }
        .stat-card .stat-label { font-size: 0.8rem; color: var(--gray-500); font-weight: 500; }
        .stat-card .stat-icon { font-size: 1.5rem; opacity: 0.3; }

        .activity-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 0;
            border-bottom: 1px solid var(--gray-100);
        }
        .activity-item:last-child { border-bottom: none; }
        .activity-item .activity-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .activity-item .activity-content h6 { font-size: 0.85rem; font-weight: 600; color: var(--ink); margin: 0; }
        .activity-item .activity-content small { color: var(--gray-500); font-size: 0.75rem; }

        /* === TOAST NOTIFICATIONS === */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
        }
        .toast-custom {
            background: #fff;
            border-radius: 12px;
            padding: 16px 20px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.12);
            border-left: 4px solid var(--brand);
            min-width: 300px;
            animation: slideInRight 0.4s ease;
            margin-bottom: 10px;
        }
        .toast-custom.error { border-left-color: #dc3545; }
        .toast-custom .toast-icon { margin-right: 12px; font-size: 1.2rem; }
        .toast-custom .toast-close { background: none; border: none; color: var(--gray-500); font-size: 1.2rem; cursor: pointer; }

        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        /* === SAVE BAR === */
        .save-bar {
            position: sticky;
            bottom: 0;
            background: #fff;
            border-radius: 16px;
            border: 1px solid var(--gray-200);
            padding: 14px 24px;
            margin-top: 16px;
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            box-shadow: 0 -4px 16px rgba(0,0,0,0.04);
        }
        .save-bar .saving-indicator {
            display: none;
            align-items: center;
            gap: 8px;
            color: var(--gray-500);
            font-size: 0.85rem;
        }
        .save-bar .saving-indicator.show { display: flex; }
        .save-bar .saving-indicator .spinner {
            width: 18px;
            height: 18px;
            border: 2px solid var(--gray-300);
            border-top-color: var(--brand);
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* === RESPONSIVE === */
        @media (max-width: 992px) {
            .settings-shell { flex-direction: column; }
            .tab-rail {
                width: 100%;
                position: static;
                display: flex;
                overflow-x: auto;
                gap: 4px;
                padding: 6px;
            }
            .tab-rail button { white-space: nowrap; width: auto; padding: 8px 16px; font-size: 0.8rem; }
            .tab-rail .tab-badge { display: none; }
            .sidebar { width: 60px; }
            .sidebar .logo-area { padding: 0 12px; justify-content: center; }
            .sidebar .logo-text { display: none; }
            .sidebar .nav-link { padding: 10px 12px; text-align: center; }
            .sidebar .nav-link span { display: none; }
            .sidebar .nav-section-title { display: none; }
            .sidebar .nav-link i { width: auto; font-size: 1.1rem; }
            .main-content { margin-left: 60px; }
            .top-bar { padding: 10px 16px; }
            .content-wrapper { padding: 16px; }
            .settings-card { padding: 20px; }
        }
        @media (max-width: 576px) {
            .top-bar .page-title { font-size: 0.9rem; }
            .top-bar .page-title small { display: none; }
            .top-bar .user-info { display: none; }
            .save-bar { flex-wrap: wrap; justify-content: center; }
            .save-bar .btn { width: 100%; }
        }
    </style>
</head>
<body>

<!-- Toast Container -->
<div class="toast-container" id="toastContainer"></div>

<!-- SIDEBAR -->
<div class="sidebar">
    <div class="logo-area">
        <div class="logo-icon">K</div>
        <div class="logo-text">
            <h5>KileleTech</h5>
            <small>Admin Panel</small>
        </div>
    </div>

    <div class="nav-section-title">Main</div>
    <nav class="nav flex-column mt-2">
        <a href="dashboard.php" class="nav-link"><i class="fas fa-th-large"></i><span> Dashboard</span></a>
        <a href="site_settings.php" class="nav-link active"><i class="fas fa-cog"></i><span> Site Settings</span></a>
        <a href="manage_updates.php" class="nav-link"><i class="fas fa-bullhorn"></i><span> Updates</span></a>
        <a href="messages.php" class="nav-link"><i class="fas fa-envelope"></i><span> Messages</span></a>
        <a href="bundle_quotes.php" class="nav-link"><i class="fas fa-file-invoice"></i><span> Quote Requests</span></a>
        <a href="subscribers.php" class="nav-link"><i class="fas fa-users"></i><span> Subscribers</span></a>
        <a href="media_library.php" class="nav-link"><i class="fas fa-photo-video"></i><span> Media Library</span></a>
    </nav>

    <?php if ($isSuperAdmin): ?>
    <div class="nav-section-title">System</div>
    <nav class="nav flex-column mt-2">
        <a href="users.php" class="nav-link"><i class="fas fa-user-shield"></i><span> Admin Users</span></a>
        <a href="logout.php" class="nav-link"><i class="fas fa-sign-out-alt"></i><span> Logout</span></a>
    </nav>
    <?php endif; ?>
</div>

<!-- MAIN CONTENT -->
<div class="main-content">

    <!-- TOP BAR -->
    <div class="top-bar">
        <div>
            <h5 class="page-title">
                <i class="fas fa-cog me-2" style="color: var(--brand);"></i>Site Settings
                <small>Control your entire website from one place</small>
            </h5>
        </div>
        <div class="user-profile">
            <div class="user-info">
                <h6><?php echo htmlspecialchars($_SESSION['admin_user'] ?? 'Admin'); ?></h6>
                <small><?php echo $isSuperAdmin ? 'Super Admin' : 'Admin'; ?></small>
            </div>
            <div class="avatar"><?php echo strtoupper(substr($_SESSION['admin_user'] ?? 'A', 0, 1)); ?></div>
        </div>
    </div>

    <div class="content-wrapper">

        <form method="POST" enctype="multipart/form-data" id="settingsForm">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
            <input type="hidden" name="active_tab" id="active_tab_input" value="<?php echo htmlspecialchars($activeTab); ?>">

            <div class="settings-shell">
                <!-- TAB RAIL -->
                <div class="tab-rail">
                    <?php foreach ($tabs as $key => $tab): ?>
                        <button type="button" class="tab-btn <?php echo $activeTab === $key ? 'active' : ''; ?>" data-tab="<?php echo $key; ?>">
                            <i class="fas <?php echo $tab['icon']; ?>"></i>
                            <?php echo $tab['label']; ?>
                            <?php if ($key === 'dashboard'): ?>
                                <span class="tab-badge">Live</span>
                            <?php endif; ?>
                        </button>
                    <?php endforeach; ?>
                </div>

                <!-- PANELS -->
                <div class="panel-area">

                    <!-- ============================================ -->
                    <!-- DASHBOARD PANEL -->
                    <!-- ============================================ -->
                    <div class="settings-card <?php echo $activeTab === 'dashboard' ? 'active' : ''; ?>" data-panel="dashboard">
                        <div class="card-header-section">
                            <div>
                                <div class="card-title"><i class="fas fa-gauge-high me-2" style="color: var(--brand);"></i>Dashboard</div>
                                <div class="card-subtitle">Overview of your website activity and quick actions</div>
                            </div>
                            <span class="card-status"><i class="fas fa-circle me-1" style="font-size: 0.5rem;"></i> Live</span>
                        </div>

                        <!-- Stats -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-3 col-6">
                                <div class="stat-card">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <div class="stat-number"><?php echo $totalMessages; ?></div>
                                            <div class="stat-label">Messages</div>
                                        </div>
                                        <div class="stat-icon"><i class="fas fa-envelope"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="stat-card">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <div class="stat-number"><?php echo $totalSubscribers; ?></div>
                                            <div class="stat-label">Subscribers</div>
                                        </div>
                                        <div class="stat-icon"><i class="fas fa-users"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="stat-card">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <div class="stat-number"><?php echo $totalProjects; ?></div>
                                            <div class="stat-label">Projects</div>
                                        </div>
                                        <div class="stat-icon"><i class="fas fa-briefcase"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="stat-card">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <div class="stat-number"><?php echo count($mediaItems); ?></div>
                                            <div class="stat-label">Media Files</div>
                                        </div>
                                        <div class="stat-icon"><i class="fas fa-photo-video"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-3 col-6">
                                <a href="manage_updates.php" class="btn btn-outline-brand w-100">
                                    <i class="fas fa-plus me-1"></i> New Update
                                </a>
                            </div>
                            <div class="col-md-3 col-6">
                                <a href="media_library.php" class="btn btn-outline-brand w-100">
                                    <i class="fas fa-upload me-1"></i> Upload Media
                                </a>
                            </div>
                            <div class="col-md-3 col-6">
                                <a href="messages.php" class="btn btn-outline-brand w-100">
                                    <i class="fas fa-envelope me-1"></i> View Messages
                                </a>
                            </div>
                            <div class="col-md-3 col-6">
                                <a href="../index.php" target="_blank" class="btn btn-outline-brand w-100">
                                    <i class="fas fa-external-link-alt me-1"></i> View Site
                                </a>
                            </div>
                        </div>

                        <!-- Recent Activity -->
                        <h6 class="fw-bold mb-3" style="color: var(--ink);"><i class="fas fa-clock me-2" style="color: var(--brand);"></i>Recent Activity</h6>
                        <?php if (count($recentUpdates) > 0): ?>
                            <?php foreach ($recentUpdates as $update): ?>
                            <div class="activity-item">
                                <div class="activity-icon" style="background: #fef3c7; color: #d97706;">
                                    <i class="fas fa-bullhorn"></i>
                                </div>
                                <div class="activity-content">
                                    <h6><?php echo htmlspecialchars($update['title']); ?></h6>
                                    <small><?php echo date('M d, Y g:i A', strtotime($update['created_at'])); ?></small>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-muted text-center py-3" style="font-size: 0.9rem;">No recent activity. Publish your first update!</p>
                        <?php endif; ?>
                    </div>

                    <!-- ============================================ -->
                    <!-- GENERAL PANEL -->
                    <!-- ============================================ -->
                    <div class="settings-card <?php echo $activeTab === 'general' ? 'active' : ''; ?>" data-panel="general">
                        <div class="card-header-section">
                            <div>
                                <div class="card-title"><i class="fas fa-globe me-2" style="color: var(--brand);"></i>General Settings</div>
                                <div class="card-subtitle">Core identity and contact details used across the site</div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Site Name</label>
                                <input type="text" name="settings[site_name]" class="form-control" value="<?php echo $val('site_name'); ?>" placeholder="Kilele Tech">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tagline</label>
                                <input type="text" name="settings[site_tagline]" class="form-control" value="<?php echo $val('site_tagline'); ?>" placeholder="Your Trusted ICT Partner">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Contact Email</label>
                                <input type="email" name="settings[contact_email]" class="form-control" value="<?php echo $val('contact_email'); ?>" placeholder="info@kileletech.com">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Contact Phone</label>
                                <input type="text" name="settings[contact_phone]" class="form-control" value="<?php echo $val('contact_phone'); ?>" placeholder="+254 700 000 000">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Office Address</label>
                                <input type="text" name="settings[contact_address]" class="form-control" value="<?php echo $val('contact_address'); ?>" placeholder="Nairobi, Kenya">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Footer Text</label>
                                <input type="text" name="settings[footer_text]" class="form-control" value="<?php echo $val('footer_text'); ?>" placeholder="Kilele Tech - Your Trusted ICT Partner">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Copyright Text</label>
                                <input type="text" name="settings[copyright_text]" class="form-control" value="<?php echo $val('copyright_text'); ?>" placeholder="© 2025 KileleTech. All rights reserved.">
                            </div>
                            <div class="col-12">
                                <label class="form-label">WhatsApp Number</label>
                                <input type="text" name="settings[whatsapp_number]" class="form-control" value="<?php echo $val('whatsapp_number'); ?>" placeholder="+254700000000">
                                <div class="form-hint">Used for the WhatsApp chat button on the site</div>
                            </div>
                        </div>
                    </div>

                    <!-- ============================================ -->
                    <!-- HERO PANEL -->
                    <!-- ============================================ -->
                    <div class="settings-card <?php echo $activeTab === 'hero' ? 'active' : ''; ?>" data-panel="hero">
                        <div class="card-header-section">
                            <div>
                                <div class="card-title"><i class="fas fa-image me-2" style="color: var(--brand);"></i>Hero Section</div>
                                <div class="card-subtitle">The first thing visitors see on your homepage</div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Hero Title</label>
                                <input type="text" name="settings[hero_title]" class="form-control" value="<?php echo $val('hero_title'); ?>" placeholder="End-to-end ICT solutions">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Hero Description</label>
                                <textarea name="settings[hero_description]" class="form-control" rows="3" placeholder="Describe your company's value proposition..."><?php echo $val('hero_description'); ?></textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Hero Video URL</label>
                                <input type="text" name="settings[hero_video]" class="form-control" id="heroVideoInput" value="<?php echo $val('hero_video'); ?>" placeholder="https://www.youtube.com/embed/...">
                                <div class="form-hint">Paste the embed URL from YouTube or Vimeo</div>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Video Preview</label>
                                <div class="preview-box" id="heroVideoPreview">
                                    <?php if (!empty($settings['hero_video'])): ?>
                                        <iframe src="<?php echo htmlspecialchars($settings['hero_video']); ?>" width="100%" height="200" style="border-radius: 10px; border:0;" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                                    <?php else: ?>
                                        <span class="empty-state">No video URL provided</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Current Hero Image</label>
                                <div class="preview-box">
                                    <?php if (!empty($settings['hero_image'])): ?>
                                        <img src="<?php echo htmlspecialchars($settings['hero_image']); ?>" alt="Hero Image">
                                    <?php else: ?>
                                        <span class="empty-state">No hero image uploaded</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Upload New Hero Image</label>
                                <div class="media-upload-zone" onclick="document.getElementById('heroImageInput').click()">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <p>Click to upload or drag &amp; drop</p>
                                    <small>JPG, PNG, WEBP (Max 2MB)</small>
                                    <input type="file" name="hero_image_file" id="heroImageInput" accept="image/*" class="d-none">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ============================================ -->
                    <!-- BRANDING PANEL -->
                    <!-- ============================================ -->
                    <div class="settings-card <?php echo $activeTab === 'branding' ? 'active' : ''; ?>" data-panel="branding">
                        <div class="card-header-section">
                            <div>
                                <div class="card-title"><i class="fas fa-palette me-2" style="color: var(--brand);"></i>Branding</div>
                                <div class="card-subtitle">Logo, favicon, and visual identity</div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Current Logo</label>
                                <div class="preview-box">
                                    <?php if (!empty($settings['logo_uploaded'])): ?>
                                        <img src="<?php echo htmlspecialchars($settings['logo_uploaded']); ?>" alt="Logo">
                                    <?php else: ?>
                                        <span class="empty-state">No logo uploaded</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Upload New Logo</label>
                                <div class="media-upload-zone" onclick="document.getElementById('logoInput').click()">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <p>Click to upload</p>
                                    <small>JPG, PNG, SVG (Max 1MB)</small>
                                    <input type="file" name="logo_file" id="logoInput" accept="image/*" class="d-none">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Current Favicon</label>
                                <div class="preview-box">
                                    <?php if (!empty($settings['favicon'])): ?>
                                        <img src="<?php echo htmlspecialchars($settings['favicon']); ?>" alt="Favicon" style="max-height:48px;">
                                    <?php else: ?>
                                        <span class="empty-state">No favicon</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Upload New Favicon</label>
                                <div class="media-upload-zone" onclick="document.getElementById('faviconInput').click()">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <p>Click to upload</p>
                                    <small>ICO, PNG, SVG</small>
                                    <input type="file" name="favicon_file" id="faviconInput" accept="image/*,.ico" class="d-none">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ============================================ -->
                    <!-- SEO PANEL -->
                    <!-- ============================================ -->
                    <div class="settings-card <?php echo $activeTab === 'seo' ? 'active' : ''; ?>" data-panel="seo">
                        <div class="card-header-section">
                            <div>
                                <div class="card-title"><i class="fas fa-magnifying-glass me-2" style="color: var(--brand);"></i>SEO &amp; Analytics</div>
                                <div class="card-subtitle">How your site appears in search engines and social shares</div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Meta Title</label>
                                <input type="text" name="settings[meta_title]" class="form-control" value="<?php echo $val('meta_title'); ?>" placeholder="Kilele Tech - Your Trusted ICT Partner">
                                <div class="form-hint">The title that appears in search results (50-60 characters recommended)</div>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Meta Description</label>
                                <textarea name="settings[meta_description]" class="form-control" rows="2" placeholder="Description for search engines..."><?php echo $val('meta_description'); ?></textarea>
                                <div class="form-hint">Aim for 150-160 characters</div>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Meta Keywords</label>
                                <input type="text" name="settings[meta_keywords]" class="form-control" value="<?php echo $val('meta_keywords'); ?>" placeholder="ICT, software, security, Kenya">
                                <div class="form-hint">Comma separated keywords</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Google Analytics ID</label>
                                <input type="text" name="settings[google_analytics_id]" class="form-control" value="<?php echo $val('google_analytics_id'); ?>" placeholder="G-XXXXXXXXXX">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Google Site Verification</label>
                                <input type="text" name="settings[google_site_verification]" class="form-control" value="<?php echo $val('google_site_verification'); ?>" placeholder="Google verification code">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Current Social Share Image (OG)</label>
                                <div class="preview-box">
                                    <?php if (!empty($settings['og_image'])): ?>
                                        <img src="<?php echo htmlspecialchars($settings['og_image']); ?>" alt="OG Image">
                                    <?php else: ?>
                                        <span class="empty-state">No OG image set</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Upload Social Share Image</label>
                                <div class="media-upload-zone" onclick="document.getElementById('ogImageInput').click()">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <p>Click to upload</p>
                                    <small>JPG, PNG, WEBP (Recommended: 1200×630px)</small>
                                    <input type="file" name="og_image_file" id="ogImageInput" accept="image/*" class="d-none">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ============================================ -->
                    <!-- SOCIAL PANEL -->
                    <!-- ============================================ -->
                    <div class="settings-card <?php echo $activeTab === 'social' ? 'active' : ''; ?>" data-panel="social">
                        <div class="card-header-section">
                            <div>
                                <div class="card-title"><i class="fas fa-share-nodes me-2" style="color: var(--brand);"></i>Social &amp; Links</div>
                                <div class="card-subtitle">Connect with your audience on social media</div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label"><i class="fab fa-facebook me-1"></i> Facebook</label>
                                <input type="url" name="settings[facebook_url]" class="form-control" value="<?php echo $val('facebook_url'); ?>" placeholder="https://facebook.com/yourpage">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><i class="fab fa-x-twitter me-1"></i> X / Twitter</label>
                                <input type="url" name="settings[twitter_url]" class="form-control" value="<?php echo $val('twitter_url'); ?>" placeholder="https://twitter.com/yourhandle">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><i class="fab fa-instagram me-1"></i> Instagram</label>
                                <input type="url" name="settings[instagram_url]" class="form-control" value="<?php echo $val('instagram_url'); ?>" placeholder="https://instagram.com/yourpage">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><i class="fab fa-linkedin me-1"></i> LinkedIn</label>
                                <input type="url" name="settings[linkedin_url]" class="form-control" value="<?php echo $val('linkedin_url'); ?>" placeholder="https://linkedin.com/company/yourcompany">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><i class="fab fa-youtube me-1"></i> YouTube</label>
                                <input type="url" name="settings[youtube_url]" class="form-control" value="<?php echo $val('youtube_url'); ?>" placeholder="https://youtube.com/@yourchannel">
                            </div>
                        </div>
                    </div>

                    <!-- ============================================ -->
                    <!-- THEME PANEL -->
                    <!-- ============================================ -->
                    <div class="settings-card <?php echo $activeTab === 'theme' ? 'active' : ''; ?>" data-panel="theme">
                        <div class="card-header-section">
                            <div>
                                <div class="card-title"><i class="fas fa-brush me-2" style="color: var(--brand);"></i>Theme</div>
                                <div class="card-subtitle">Customize the look and feel of your site</div>
                            </div>
                        </div>

                        <div class="row g-4">
                            <div class="col-md-4">
                                <label class="form-label">Primary Color</label>
                                <div class="d-flex gap-2 align-items-center">
                                    <input type="color" id="primaryColorPicker" class="form-control form-control-color" style="width: 50px; height: 44px; padding: 2px;" value="<?php echo $val('theme_primary_color', '#29A08E'); ?>">
                                    <input type="text" name="settings[theme_primary_color]" id="primaryColorText" class="form-control" value="<?php echo $val('theme_primary_color', '#29A08E'); ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Secondary Color</label>
                                <div class="d-flex gap-2 align-items-center">
                                    <input type="color" id="secondaryColorPicker" class="form-control form-control-color" style="width: 50px; height: 44px; padding: 2px;" value="<?php echo $val('theme_secondary_color', '#0f1e33'); ?>">
                                    <input type="text" name="settings[theme_secondary_color]" id="secondaryColorText" class="form-control" value="<?php echo $val('theme_secondary_color', '#0f1e33'); ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Body Font</label>
                                <select name="settings[theme_font]" class="form-select" id="themeFontSelect">
                                    <?php
                                    $fonts = ['Poppins', 'Inter', 'Roboto', 'Open Sans', 'Lato', 'Montserrat', 'Nunito', 'Raleway'];
                                    $currentFont = $settings['theme_font'] ?? 'Poppins';
                                    foreach ($fonts as $font) {
                                        $sel = $currentFont === $font ? 'selected' : '';
                                        echo "<option value=\"$font\" $sel>$font</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="mt-4 p-4" style="background: var(--gray-50); border-radius: 12px; border: 1px solid var(--gray-200);">
                            <div class="form-hint mb-3">Live Preview</div>
                            <div id="themePreview" style="display: flex; gap: 16px; align-items: center; flex-wrap: wrap;">
                                <div style="display: flex; gap: 8px; align-items: center;">
                                    <span style="font-size: 0.8rem; color: var(--gray-500);">Primary:</span>
                                    <div style="width: 32px; height: 32px; border-radius: 8px; border: 1px solid var(--gray-300);" id="swatchPrimary"></div>
                                </div>
                                <div style="display: flex; gap: 8px; align-items: center;">
                                    <span style="font-size: 0.8rem; color: var(--gray-500);">Secondary:</span>
                                    <div style="width: 32px; height: 32px; border-radius: 8px; border: 1px solid var(--gray-300);" id="swatchSecondary"></div>
                                </div>
                                <div style="display: flex; gap: 8px; align-items: center;">
                                    <span style="font-size: 0.8rem; color: var(--gray-500);">Font:</span>
                                    <span id="swatchFontSample" style="font-size: 1rem; font-weight: 500;">The quick brown fox</span>
                                </div>
                            </div>
                            <div class="mt-3 p-3" style="background: var(--brand); border-radius: 8px; color: #fff; transition: 0.3s;" id="previewButton">
                                <span style="font-weight: 600;">Preview Button</span>
                            </div>
                        </div>
                    </div>

                    <!-- ============================================ -->
                    <!-- INTEGRATIONS PANEL -->
                    <!-- ============================================ -->
                    <div class="settings-card <?php echo $activeTab === 'integrations' ? 'active' : ''; ?>" data-panel="integrations">
                        <div class="card-header-section">
                            <div>
                                <div class="card-title"><i class="fas fa-plug me-2" style="color: var(--brand);"></i>Integrations</div>
                                <div class="card-subtitle">Email delivery and third-party API keys</div>
                            </div>
                        </div>

                        <h6 class="fw-bold mb-3" style="color: var(--ink);"><i class="fas fa-envelope me-2" style="color: var(--brand);"></i>SMTP Settings</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label">SMTP Host</label>
                                <input type="text" name="settings[smtp_host]" class="form-control" value="<?php echo $val('smtp_host'); ?>" placeholder="smtp.gmail.com">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">SMTP Port</label>
                                <input type="text" name="settings[smtp_port]" class="form-control" value="<?php echo $val('smtp_port', '587'); ?>" placeholder="587">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">SMTP Username</label>
                                <input type="text" name="settings[smtp_username]" class="form-control" value="<?php echo $val('smtp_username'); ?>" placeholder="user@gmail.com">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">SMTP Password</label>
                                <div class="input-group">
                                    <input type="password" name="settings[smtp_password]" id="smtpPassword" class="form-control" value="<?php echo $val('smtp_password'); ?>" placeholder="••••••••">
                                    <button type="button" class="btn btn-outline-secondary" id="toggleSmtpPw"><i class="fas fa-eye"></i></button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">"From" Email</label>
                                <input type="email" name="settings[smtp_from_email]" class="form-control" value="<?php echo $val('smtp_from_email'); ?>" placeholder="noreply@kileletech.com">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">"From" Name</label>
                                <input type="text" name="settings[smtp_from_name]" class="form-control" value="<?php echo $val('smtp_from_name'); ?>" placeholder="Kilele Tech">
                            </div>
                        </div>

                        <h6 class="fw-bold mb-3" style="color: var(--ink);"><i class="fas fa-key me-2" style="color: var(--brand);"></i>API Keys</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">reCAPTCHA Site Key</label>
                                <input type="text" name="settings[recaptcha_site_key]" class="form-control" value="<?php echo $val('recaptcha_site_key'); ?>" placeholder="Site key">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">reCAPTCHA Secret Key</label>
                                <input type="text" name="settings[recaptcha_secret_key]" class="form-control" value="<?php echo $val('recaptcha_secret_key'); ?>" placeholder="Secret key">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Google Maps API Key</label>
                                <input type="text" name="settings[google_maps_api_key]" class="form-control" value="<?php echo $val('google_maps_api_key'); ?>" placeholder="AIza...">
                            </div>
                        </div>
                    </div>

                </div><!-- /panel-area -->
            </div><!-- /settings-shell -->

            <!-- SAVE BAR -->
            <div class="save-bar">
                <div class="saving-indicator" id="savingIndicator">
                    <div class="spinner"></div>
                    <span>Saving...</span>
                </div>
                <button type="button" class="btn btn-outline-secondary" onclick="location.reload()">
                    <i class="fas fa-rotate me-1"></i> Reset
                </button>
                <button type="submit" name="save_settings" class="btn btn-primary" id="saveBtn">
                    <i class="fas fa-floppy-disk me-2"></i>Save Changes
                </button>
            </div>
        </form>

    </div><!-- /content-wrapper -->
</div><!-- /main-content -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {

    // ============================================================
    // TAB SWITCHING
    // ============================================================
    const tabButtons = document.querySelectorAll('.tab-btn');
    const panels = document.querySelectorAll('[data-panel]');
    const activeTabInput = document.getElementById('active_tab_input');

    function activateTab(tabKey) {
        tabButtons.forEach(b => b.classList.toggle('active', b.dataset.tab === tabKey));
        panels.forEach(p => p.classList.toggle('active', p.dataset.panel === tabKey));
        if (activeTabInput) activeTabInput.value = tabKey;
        history.replaceState(null, '', '?tab=' + tabKey);
    }

    tabButtons.forEach(btn => {
        btn.addEventListener('click', () => activateTab(btn.dataset.tab));
    });

    // ============================================================
    // HERO VIDEO LIVE PREVIEW
    // ============================================================
    const heroVideoInput = document.getElementById('heroVideoInput');
    const heroVideoPreview = document.getElementById('heroVideoPreview');
    if (heroVideoInput && heroVideoPreview) {
        heroVideoInput.addEventListener('input', function() {
            const url = this.value.trim();
            if (url) {
                heroVideoPreview.innerHTML = `<iframe src="${url}" width="100%" height="200" style="border-radius:10px;border:0;" allow="autoplay; encrypted-media" allowfullscreen></iframe>`;
            } else {
                heroVideoPreview.innerHTML = `<span class="empty-state">No video URL provided</span>`;
            }
        });
    }

    // ============================================================
    // FILE UPLOAD - SHOW FILENAME
    // ============================================================
    document.querySelectorAll('input[type="file"]').forEach(input => {
        input.addEventListener('change', function() {
            const zone = this.closest('.media-upload-zone');
            if (zone && this.files && this.files[0]) {
                const p = zone.querySelector('p');
                if (p) p.textContent = this.files[0].name;
                const small = zone.querySelector('small');
                if (small) small.textContent = (this.files[0].size / 1024 / 1024).toFixed(2) + ' MB';
            }
        });
    });

    // ============================================================
    // DRAG & DROP UPLOAD ZONES
    // ============================================================
    document.querySelectorAll('.media-upload-zone').forEach(zone => {
        zone.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.classList.add('dragover');
        });
        zone.addEventListener('dragleave', function(e) {
            e.preventDefault();
            this.classList.remove('dragover');
        });
        zone.addEventListener('drop', function(e) {
            e.preventDefault();
            this.classList.remove('dragover');
            const input = this.querySelector('input[type="file"]');
            if (input && e.dataTransfer.files.length) {
                input.files = e.dataTransfer.files;
                input.dispatchEvent(new Event('change'));
            }
        });
    });

    // ============================================================
    // THEME COLOR SYNC
    // ============================================================
    function syncColor(pickerId, textId, swatchId) {
        const picker = document.getElementById(pickerId);
        const text = document.getElementById(textId);
        const swatch = document.getElementById(swatchId);
        if (!picker || !text || !swatch) return;

        function apply(val) {
            swatch.style.background = val;
            // Also update the preview button
            const previewBtn = document.getElementById('previewButton');
            if (previewBtn && pickerId === 'primaryColorPicker') {
                previewBtn.style.background = val;
            }
        }

        apply(picker.value);
        picker.addEventListener('input', function() {
            text.value = this.value;
            apply(this.value);
        });
        text.addEventListener('input', function() {
            if (/^#[0-9A-Fa-f]{6}$/.test(this.value)) {
                picker.value = this.value;
                apply(this.value);
            }
        });
    }
    syncColor('primaryColorPicker', 'primaryColorText', 'swatchPrimary');
    syncColor('secondaryColorPicker', 'secondaryColorText', 'swatchSecondary');

    // Font preview
    const fontSelect = document.getElementById('themeFontSelect');
    const fontSample = document.getElementById('swatchFontSample');
    if (fontSelect && fontSample) {
        fontSelect.addEventListener('change', function() {
            fontSample.style.fontFamily = this.value;
        });
        fontSample.style.fontFamily = fontSelect.value;
    }

    // ============================================================
    // SMTP PASSWORD TOGGLE
    // ============================================================
    const toggleSmtpPw = document.getElementById('toggleSmtpPw');
    if (toggleSmtpPw) {
        toggleSmtpPw.addEventListener('click', function() {
            const field = document.getElementById('smtpPassword');
            if (field) {
                field.type = field.type === 'password' ? 'text' : 'password';
                this.querySelector('i').classList.toggle('fa-eye');
                this.querySelector('i').classList.toggle('fa-eye-slash');
            }
        });
    }

    // ============================================================
    // AJAX SAVE WITH TOAST NOTIFICATIONS
    // ============================================================
    const form = document.getElementById('settingsForm');
    const saveBtn = document.getElementById('saveBtn');
    const savingIndicator = document.getElementById('savingIndicator');

    function showToast(message, type = 'success') {
        const container = document.getElementById('toastContainer');
        const toast = document.createElement('div');
        toast.className = `toast-custom ${type}`;
        const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
        const color = type === 'success' ? 'var(--brand)' : '#dc3545';
        toast.innerHTML = `
            <div style="display:flex;align-items:center;justify-content:space-between;">
                <div style="display:flex;align-items:center;">
                    <span class="toast-icon" style="color:${color}"><i class="fas ${icon}"></i></span>
                    <span>${message}</span>
                </div>
                <button class="toast-close" onclick="this.closest('.toast-custom').remove()">×</button>
            </div>
        `;
        container.appendChild(toast);
        setTimeout(() => {
            if (toast.parentNode) toast.remove();
        }, 5000);
    }

    // Intercept form submit for AJAX
    if (form) {
        form.addEventListener('submit', function(e) {
            // Use AJAX if not a file upload (or if we want to handle files with FormData)
            const hasFile = this.querySelector('input[type="file"]') && 
                           Array.from(this.querySelectorAll('input[type="file"]')).some(f => f.files && f.files.length > 0);
            
            // Always use AJAX for better UX
            e.preventDefault();
            
            const formData = new FormData(this);
            formData.append('ajax_save', '1');
            
            // Show saving state
            saveBtn.disabled = true;
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Saving...';
            savingIndicator.classList.add('show');

            // Also update the active tab
            formData.set('active_tab', activeTabInput.value);

            fetch(window.location.href, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    showToast(data.message || 'All settings saved successfully!', 'success');
                    // Reload settings from server after a moment
                    setTimeout(() => {
                        // Optionally reload the page to show new values
                        window.location.href = window.location.pathname + '?tab=' + activeTabInput.value + '&status=success&msg=' + encodeURIComponent(data.message || 'Saved!');
                    }, 800);
                } else {
                    showToast(data.message || 'Something went wrong. Please try again.', 'error');
                    saveBtn.disabled = false;
                    saveBtn.innerHTML = '<i class="fas fa-floppy-disk me-2"></i>Save Changes';
                    savingIndicator.classList.remove('show');
                }
            })
            .catch(error => {
                showToast('Network error. Please check your connection.', 'error');
                saveBtn.disabled = false;
                saveBtn.innerHTML = '<i class="fas fa-floppy-disk me-2"></i>Save Changes';
                savingIndicator.classList.remove('show');
            });
        });
    }

    // Auto-dismiss flash messages from URL
    if (window.location.search.includes('status=success')) {
        const msg = new URLSearchParams(window.location.search).get('msg');
        if (msg) showToast(decodeURIComponent(msg), 'success');
    } else if (window.location.search.includes('status=error')) {
        const msg = new URLSearchParams(window.location.search).get('msg');
        if (msg) showToast(decodeURIComponent(msg), 'error');
    }

});
</script>
</body>
</html>