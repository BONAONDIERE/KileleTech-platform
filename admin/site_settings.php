<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Fetch all settings
$settings = [];
$stmt = $pdo->query("SELECT * FROM site_settings ORDER BY id ASC");
foreach ($stmt->fetchAll() as $row) {
    $settings[$row['setting_key']] = $row['setting_value'];
}

// ==========================================================
// HANDLE UPLOADS & UPDATES
// ==========================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_settings'])) {
    
    // 1. Handle TEXT settings
    if (isset($_POST['settings']) && is_array($_POST['settings'])) {
        foreach ($_POST['settings'] as $key => $value) {
            $stmt = $pdo->prepare("UPDATE site_settings SET setting_value = ? WHERE setting_key = ?");
            $stmt->execute([$value, $key]);
        }
    }
    
    // 2. Handle VIDEO URL & MAP LINK
    if (isset($_POST['hero_video_url'])) {
        $stmt = $pdo->prepare("UPDATE site_settings SET setting_value = ? WHERE setting_key = 'hero_video'");
        $stmt->execute([trim($_POST['hero_video_url'])]);
    }
    if (isset($_POST['map_link'])) {
        $stmt = $pdo->prepare("UPDATE site_settings SET setting_value = ? WHERE setting_key = 'map_link'");
        $stmt->execute([trim($_POST['map_link'])]);
    }
    
    // 3. Handle IMAGE UPLOADS (Keep old images, just update the URL)
    $upload_dir = '../uploads/';
    if (!file_exists($upload_dir)) { mkdir($upload_dir, 0777, true); }
    
    $image_fields = ['logo_uploaded', 'hero_image', 'gallery_image_1', 'gallery_image_2', 'gallery_image_3'];
    
    foreach ($image_fields as $field) {
        if (isset($_FILES[$field]) && $_FILES[$field]['error'] === 0) {
            $file_name = time() . "_" . basename($_FILES[$field]["name"]);
            $target_file = $upload_dir . $file_name;
            
            if (move_uploaded_file($_FILES[$field]["tmp_name"], $target_file)) {
                $stmt = $pdo->prepare("UPDATE site_settings SET setting_value = ? WHERE setting_key = ?");
                $stmt->execute(["../uploads/" . $file_name, $field]);
            }
        }
    }
    
    // Refresh
    $stmt = $pdo->query("SELECT * FROM site_settings");
    $settings = [];
    foreach ($stmt->fetchAll() as $row) {
        $settings[$row['setting_key']] = $row['setting_value'];
    }
    
    header('Location: site_settings.php?updated=1');
    exit;
}

// ==========================================================
// EDIT EXISTING SETTING (In-line)
// ==========================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_setting'])) {
    $old_key = $_POST['old_key'];
    $new_key = trim($_POST['new_key'] ?? '');
    $new_value = trim($_POST['new_value'] ?? '');
    
    if (!empty($new_key) && !empty($new_value)) {
        $check = $pdo->prepare("SELECT COUNT(*) FROM site_settings WHERE setting_key = ? AND setting_key != ?");
        $check->execute([$new_key, $old_key]);
        if ($check->fetchColumn() > 0) {
            header('Location: site_settings.php?error=' . urlencode('That key already exists.'));
            exit;
        }
        
        $stmt = $pdo->prepare("UPDATE site_settings SET setting_key = ?, setting_value = ? WHERE setting_key = ?");
        $stmt->execute([$new_key, $new_value, $old_key]);
        header('Location: site_settings.php?updated=1');
        exit;
    }
}

// ==========================================================
// DYNAMIC ADD NEW SETTING
// ==========================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_setting'])) {
    $new_key = trim($_POST['new_key'] ?? '');
    $new_value = trim($_POST['new_value'] ?? '');
    
    if (!empty($new_key) && !empty($new_value)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO site_settings (setting_key, setting_value) VALUES (?, ?)");
            $stmt->execute([$new_key, $new_value]);
            header('Location: site_settings.php?added=1');
            exit;
        } catch (Exception $e) {
            header('Location: site_settings.php?error=' . urlencode('That key already exists.'));
            exit;
        }
    }
}

// ==========================================================
// DELETE SETTING
// ==========================================================
if (isset($_GET['delete'])) {
    $key = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM site_settings WHERE setting_key = ?");
    $stmt->execute([$key]);
    header('Location: site_settings.php?deleted=1');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site Settings – KileleTech Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f8fafb; font-family: 'Poppins', sans-serif; }
        .sidebar { background: #ffffff; border-right: 1px solid #e9ecef; min-height: 100vh; width: 250px; position: fixed; left: 0; top: 0; padding-top: 20px; }
        .sidebar .logo { text-align: center; padding-bottom: 20px; border-bottom: 1px solid #f0f0f0; margin-bottom: 20px; }
        .sidebar .logo h4 { color: #0f1e33; font-weight: 700; }
        .sidebar .logo span { color: #29A08E; }
        .sidebar .nav-link { color: #555; padding: 12px 24px; font-weight: 500; transition: 0.3s; border-left: 3px solid transparent; }
        .sidebar .nav-link:hover { background: #f0f9f7; color: #29A08E; border-left-color: #29A08E; }
        .sidebar .nav-link.active { background: #f0f9f7; color: #29A08E; border-left-color: #29A08E; }
        .sidebar .nav-link i { width: 25px; color: #aaa; }
        .sidebar .nav-link:hover i, .sidebar .nav-link.active i { color: #29A08E; }
        .main-content { margin-left: 250px; padding: 24px; }
        .top-bar { background: #ffffff; padding: 15px 24px; border-radius: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.04); margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center; }
        .top-bar h5 { font-weight: 700; color: #0f1e33; margin-bottom: 0; }
        .settings-card { background: #ffffff; border-radius: 16px; padding: 25px; box-shadow: 0 4px 12px rgba(0,0,0,0.04); margin-bottom: 20px; }
        .form-label { font-weight: 600; color: #0f1e33; font-size: 0.9rem; }
        .form-control { border-radius: 8px; padding: 10px 14px; border: 1px solid #e1e5e9; }
        .form-control:focus { border-color: #29A08E; box-shadow: 0 0 0 0.25rem rgba(41, 160, 142, 0.15); }
        .btn-primary { background: #29A08E; border: none; border-radius: 8px; padding: 10px 24px; font-weight: 700; }
        .btn-primary:hover { background: #1e7a6b; }
        .section-title { font-weight: 700; color: #0f1e33; font-size: 1.2rem; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
        .current-image { width: 100px; height: auto; border-radius: 8px; border: 1px solid #e1e5e9; margin-top: 5px; }
        
        .icon-btn-edit { background: none; border: none; color: #29A08E; font-size: 0.9rem; cursor: pointer; padding: 2px; transition: 0.2s; }
        .icon-btn-edit:hover { color: #1e7a6b; transform: scale(1.1); }
        .icon-btn-delete { background: none; border: none; color: #dc3545; font-size: 0.9rem; cursor: pointer; padding: 2px; transition: 0.2s; }
        .icon-btn-delete:hover { color: #b02a37; transform: scale(1.1); }
        .edit-form-container { background: #f0f9f7; border: 1px solid #d1f0eb; border-radius: 12px; padding: 20px; margin-bottom: 24px; display: none; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 col-lg-2 sidebar">
                <div class="logo">
                    <h4><span>K</span>ileleTech</h4>
                    <small style="color: #999;">Admin Panel</small>
                </div>
                <nav class="nav flex-column mt-3">
                    <a href="dashboard.php" class="nav-link"><i class="fas fa-th-large"></i> Dashboard</a>
                    <a href="site_settings.php" class="nav-link active"><i class="fas fa-cog"></i> Site Settings</a>
                    <a href="messages.php" class="nav-link"><i class="fas fa-envelope"></i> Messages</a>
                    <a href="view_quotes.php" class="nav-link"><i class="fas fa-file-invoice"></i> Quotes</a>
                    <a href="bundle_quotes.php" class="nav-link"><i class="fas fa-layer-group"></i> Bundle Quotes</a>
                    <a href="logout.php" class="nav-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 col-lg-10 main-content">
                <div class="top-bar">
                    <h5>Site Settings</h5>
                </div>

                <!-- ALERTS -->
                <?php if (isset($_GET['updated'])): ?>
                    <div class="alert alert-success">Settings saved successfully!</div>
                <?php endif; ?>
                <?php if (isset($_GET['added'])): ?>
                    <div class="alert alert-success">New setting added!</div>
                <?php endif; ?>
                <?php if (isset($_GET['deleted'])): ?>
                    <div class="alert alert-success">Setting deleted!</div>
                <?php endif; ?>
                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
                <?php endif; ?>

                <!-- TOP EDIT FORM (Shows when Edit is clicked) -->
                <div class="edit-form-container" id="editFormContainer">
                    <h6 class="mb-3" style="color: #29A08E; font-weight: 700;"><i class="fas fa-edit me-2"></i> Edit Setting</h6>
                    <form method="POST" class="row g-2">
                        <input type="hidden" name="old_key" id="old_key">
                        <div class="col-md-5">
                            <input type="text" name="new_key" id="new_key" class="form-control" placeholder="Setting Key">
                        </div>
                        <div class="col-md-5">
                            <input type="text" name="new_value" id="new_value" class="form-control" placeholder="Setting Value">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" name="edit_setting" class="btn btn-success w-100">Update</button>
                        </div>
                    </form>
                </div>

                <!-- UPLOAD / UPDATE FORM -->
                <form method="POST" enctype="multipart/form-data">
                    
                    <!-- HERO SECTION (With Edit/Delete icons) -->
                    <div class="settings-card">
                        <div class="section-title"><i class="fas fa-image" style="color: #29A08E;"></i> Hero Section</div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Hero Title</label>
                                <div class="d-flex align-items-center gap-2">
                                    <input type="text" name="settings[hero_title]" class="form-control" value="<?php echo htmlspecialchars($settings['hero_title'] ?? ''); ?>">
                                    <button type="button" class="icon-btn-edit" onclick="editSetting('hero_title', '<?php echo htmlspecialchars($settings['hero_title'] ?? ''); ?>')"><i class="fas fa-edit"></i></button>
                                    <a href="site_settings.php?delete=hero_title" class="icon-btn-delete" onclick="return confirm('Delete this setting?')"><i class="fas fa-trash"></i></a>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Hero Video URL</label>
                                <div class="d-flex align-items-center gap-2">
                                    <input type="text" name="hero_video_url" class="form-control" value="<?php echo htmlspecialchars($settings['hero_video'] ?? ''); ?>">
                                    <button type="button" class="icon-btn-edit" onclick="editSetting('hero_video', '<?php echo htmlspecialchars($settings['hero_video'] ?? ''); ?>')"><i class="fas fa-edit"></i></button>
                                    <a href="site_settings.php?delete=hero_video" class="icon-btn-delete" onclick="return confirm('Delete this setting?')"><i class="fas fa-trash"></i></a>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Hero Description</label>
                                <div class="d-flex align-items-center gap-2">
                                    <textarea name="settings[hero_description]" class="form-control" rows="3"><?php echo htmlspecialchars($settings['hero_description'] ?? ''); ?></textarea>
                                    <button type="button" class="icon-btn-edit" onclick="editSetting('hero_description', '<?php echo htmlspecialchars($settings['hero_description'] ?? ''); ?>')"><i class="fas fa-edit"></i></button>
                                    <a href="site_settings.php?delete=hero_description" class="icon-btn-delete" onclick="return confirm('Delete this setting?')"><i class="fas fa-trash"></i></a>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Upload Hero Image</label>
                                <input type="file" name="hero_image" class="form-control" accept="image/*">
                                <?php if (!empty($settings['hero_image'])): ?>
                                    <img src="<?php echo htmlspecialchars($settings['hero_image']); ?>" class="current-image mt-2" alt="Hero Image">
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- ANNOUNCEMENT / MOVING TEXT -->
                    <div class="settings-card">
                        <div class="section-title"><i class="fas fa-bullhorn" style="color: #29A08E;"></i> Announcement / Moving Text</div>
                        <div class="mb-3">
                            <label class="form-label">Announcement Text</label>
                            <div class="d-flex align-items-center gap-2">
                                <input type="text" name="settings[announcement_text]" class="form-control" value="<?php echo htmlspecialchars($settings['announcement_text'] ?? ''); ?>">
                                <button type="button" class="icon-btn-edit" onclick="editSetting('announcement_text', '<?php echo htmlspecialchars($settings['announcement_text'] ?? ''); ?>')"><i class="fas fa-edit"></i></button>
                                <a href="site_settings.php?delete=announcement_text" class="icon-btn-delete" onclick="return confirm('Delete this setting?')"><i class="fas fa-trash"></i></a>
                            </div>
                        </div>
                    </div>

                    <!-- BRANDING & CONTACT -->
                    <div class="settings-card">
                        <div class="section-title"><i class="fas fa-palette" style="color: #29A08E;"></i> Branding & Contact</div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Contact Phone</label>
                                <div class="d-flex align-items-center gap-2">
                                    <input type="text" name="settings[contact_phone]" class="form-control" value="<?php echo htmlspecialchars($settings['contact_phone'] ?? ''); ?>">
                                    <button type="button" class="icon-btn-edit" onclick="editSetting('contact_phone', '<?php echo htmlspecialchars($settings['contact_phone'] ?? ''); ?>')"><i class="fas fa-edit"></i></button>
                                    <a href="site_settings.php?delete=contact_phone" class="icon-btn-delete" onclick="return confirm('Delete this setting?')"><i class="fas fa-trash"></i></a>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Contact Email</label>
                                <div class="d-flex align-items-center gap-2">
                                    <input type="text" name="settings[contact_email]" class="form-control" value="<?php echo htmlspecialchars($settings['contact_email'] ?? ''); ?>">
                                    <button type="button" class="icon-btn-edit" onclick="editSetting('contact_email', '<?php echo htmlspecialchars($settings['contact_email'] ?? ''); ?>')"><i class="fas fa-edit"></i></button>
                                    <a href="site_settings.php?delete=contact_email" class="icon-btn-delete" onclick="return confirm('Delete this setting?')"><i class="fas fa-trash"></i></a>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Upload Logo</label>
                                <input type="file" name="logo_uploaded" class="form-control" accept="image/*">
                                <?php if (!empty($settings['logo_uploaded'])): ?>
                                    <img src="<?php echo htmlspecialchars($settings['logo_uploaded']); ?>" class="current-image mt-2" alt="Logo">
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Footer Text</label>
                                <div class="d-flex align-items-center gap-2">
                                    <input type="text" name="settings[footer_text]" class="form-control" value="<?php echo htmlspecialchars($settings['footer_text'] ?? ''); ?>">
                                    <button type="button" class="icon-btn-edit" onclick="editSetting('footer_text', '<?php echo htmlspecialchars($settings['footer_text'] ?? ''); ?>')"><i class="fas fa-edit"></i></button>
                                    <a href="site_settings.php?delete=footer_text" class="icon-btn-delete" onclick="return confirm('Delete this setting?')"><i class="fas fa-trash"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- MAP & SOCIAL LINKS -->
                    <div class="settings-card">
                        <div class="section-title"><i class="fas fa-map-marked-alt" style="color: #29A08E;"></i> Map & Social Links</div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Map Link</label>
                                <div class="d-flex align-items-center gap-2">
                                    <input type="text" name="map_link" class="form-control" value="<?php echo htmlspecialchars($settings['map_link'] ?? ''); ?>">
                                    <button type="button" class="icon-btn-edit" onclick="editSetting('map_link', '<?php echo htmlspecialchars($settings['map_link'] ?? ''); ?>')"><i class="fas fa-edit"></i></button>
                                    <a href="site_settings.php?delete=map_link" class="icon-btn-delete" onclick="return confirm('Delete this setting?')"><i class="fas fa-trash"></i></a>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Facebook URL</label>
                                <div class="d-flex align-items-center gap-2">
                                    <input type="text" name="settings[facebook_url]" class="form-control" value="<?php echo htmlspecialchars($settings['facebook_url'] ?? ''); ?>">
                                    <button type="button" class="icon-btn-edit" onclick="editSetting('facebook_url', '<?php echo htmlspecialchars($settings['facebook_url'] ?? ''); ?>')"><i class="fas fa-edit"></i></button>
                                    <a href="site_settings.php?delete=facebook_url" class="icon-btn-delete" onclick="return confirm('Delete this setting?')"><i class="fas fa-trash"></i></a>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Twitter / X URL</label>
                                <div class="d-flex align-items-center gap-2">
                                    <input type="text" name="settings[twitter_url]" class="form-control" value="<?php echo htmlspecialchars($settings['twitter_url'] ?? ''); ?>">
                                    <button type="button" class="icon-btn-edit" onclick="editSetting('twitter_url', '<?php echo htmlspecialchars($settings['twitter_url'] ?? ''); ?>')"><i class="fas fa-edit"></i></button>
                                    <a href="site_settings.php?delete=twitter_url" class="icon-btn-delete" onclick="return confirm('Delete this setting?')"><i class="fas fa-trash"></i></a>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">LinkedIn URL</label>
                                <div class="d-flex align-items-center gap-2">
                                    <input type="text" name="settings[linkedin_url]" class="form-control" value="<?php echo htmlspecialchars($settings['linkedin_url'] ?? ''); ?>">
                                    <button type="button" class="icon-btn-edit" onclick="editSetting('linkedin_url', '<?php echo htmlspecialchars($settings['linkedin_url'] ?? ''); ?>')"><i class="fas fa-edit"></i></button>
                                    <a href="site_settings.php?delete=linkedin_url" class="icon-btn-delete" onclick="return confirm('Delete this setting?')"><i class="fas fa-trash"></i></a>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Instagram URL</label>
                                <div class="d-flex align-items-center gap-2">
                                    <input type="text" name="settings[instagram_url]" class="form-control" value="<?php echo htmlspecialchars($settings['instagram_url'] ?? ''); ?>">
                                    <button type="button" class="icon-btn-edit" onclick="editSetting('instagram_url', '<?php echo htmlspecialchars($settings['instagram_url'] ?? ''); ?>')"><i class="fas fa-edit"></i></button>
                                    <a href="site_settings.php?delete=instagram_url" class="icon-btn-delete" onclick="return confirm('Delete this setting?')"><i class="fas fa-trash"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- GALLERY -->
                    <div class="settings-card">
                        <div class="section-title"><i class="fas fa-images" style="color: #29A08E;"></i> Gallery Images</div>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Gallery Image 1</label>
                                <input type="file" name="gallery_image_1" class="form-control" accept="image/*">
                                <?php if (!empty($settings['gallery_image_1'])): ?>
                                    <img src="<?php echo htmlspecialchars($settings['gallery_image_1']); ?>" class="current-image mt-2" alt="Gallery 1">
                                <?php endif; ?>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Gallery Image 2</label>
                                <input type="file" name="gallery_image_2" class="form-control" accept="image/*">
                                <?php if (!empty($settings['gallery_image_2'])): ?>
                                    <img src="<?php echo htmlspecialchars($settings['gallery_image_2']); ?>" class="current-image mt-2" alt="Gallery 2">
                                <?php endif; ?>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Gallery Image 3</label>
                                <input type="file" name="gallery_image_3" class="form-control" accept="image/*">
                                <?php if (!empty($settings['gallery_image_3'])): ?>
                                    <img src="<?php echo htmlspecialchars($settings['gallery_image_3']); ?>" class="current-image mt-2" alt="Gallery 3">
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mb-4">
                        <button type="submit" name="save_settings" class="btn btn-primary w-100">
                            <i class="fas fa-save me-2"></i> Save All Settings
                        </button>
                    </div>
                </form>

                <!-- DYNAMIC ADD NEW SETTING -->
                <div class="settings-card">
                    <div class="section-title"><i class="fas fa-plus-circle" style="color: #29A08E;"></i> Add New Setting</div>
                    <form method="POST" class="row g-2">
                        <div class="col-md-5">
                            <input type="text" name="new_key" class="form-control" placeholder="Setting Key (e.g. facebook_url)" required>
                        </div>
                        <div class="col-md-5">
                            <input type="text" name="new_value" class="form-control" placeholder="Setting Value" required>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" name="add_setting" class="btn btn-success w-100">Add</button>
                        </div>
                    </form>
                </div>

                <!-- ALL SETTINGS TABLE -->
                <div class="settings-card">
                    <div class="section-title"><i class="fas fa-list" style="color: #29A08E;"></i> All Settings (Edit or Delete)</div>
                    
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Key</th>
                                    <th>Value</th>
                                    <th style="width: 80px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($settings as $key => $value): ?>
                                    <tr>
                                        <td><code><?php echo htmlspecialchars($key); ?></code></td>
                                        <td><?php echo htmlspecialchars($value); ?></td>
                                        <td>
                                            <button type="button" class="icon-btn-edit" onclick="editSetting('<?php echo htmlspecialchars($key); ?>', '<?php echo htmlspecialchars($value); ?>')" title="Edit"><i class="fas fa-edit"></i></button>
                                            
                                            <a href="site_settings.php?delete=<?php echo urlencode($key); ?>" class="icon-btn-delete" onclick="return confirm('Delete this setting?')" title="Delete"><i class="fas fa-trash"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function editSetting(key, value) {
            document.getElementById('editFormContainer').style.display = 'block';
            document.getElementById('old_key').value = key;
            document.getElementById('new_key').value = key;
            document.getElementById('new_value').value = value;
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    </script>
</body>
</html>