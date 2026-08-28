<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$msg = '';

// Handle new media upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_media'])) {
    $media_type = $_POST['media_type'] ?? 'image';
    $title = trim($_POST['title'] ?? '');

    if (isset($_FILES['media_file']) && $_FILES['media_file']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['media_file']['name'], PATHINFO_EXTENSION);
        $allowed_img = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg'];
        $allowed_vid = ['mp4', 'webm', 'ogg'];

        if (($media_type == 'image' && in_array(strtolower($ext), $allowed_img)) || 
            ($media_type == 'video' && in_array(strtolower($ext), $allowed_vid))) {
            
            $new_name = 'media_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
            move_uploaded_file($_FILES['media_file']['tmp_name'], __DIR__ . '/../uploads/' . $new_name);
            
            $stmt = $pdo->prepare("INSERT INTO media_library (media_type, file_path, title) VALUES (?, ?, ?)");
            $stmt->execute([$media_type, '/uploads/' . $new_name, $title]);
            
            $msg = "<div class='alert alert-success'>✅ File uploaded successfully!</div>";
        } else {
            $msg = "<div class='alert alert-danger'>❌ Invalid file type. Only images or videos are allowed.</div>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Media Library – KileleTech Admin</title>
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
        .main-content { margin-left: 250px; padding: 24px; }
        .top-bar { background: #ffffff; padding: 15px 24px; border-radius: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.04); margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center; }
        .upload-card { background: #ffffff; border-radius: 16px; padding: 30px; box-shadow: 0 4px 12px rgba(0,0,0,0.04); margin-bottom: 20px; }
        .form-label { font-weight: 600; color: #0f1e33; }
        .form-control { border-radius: 10px; padding: 12px 15px; border: 1px solid #e1e5e9; }
        .form-control:focus { border-color: #29A08E; box-shadow: 0 0 0 0.25rem rgba(41, 160, 142, 0.15); }
        .btn-primary { background: #29A08E; border: none; border-radius: 50px; padding: 12px 30px; font-weight: 700; }
        .btn-primary:hover { background: #1e7a6b; }
        .media-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 15px; }
        .media-item { background: #fff; border-radius: 12px; padding: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); text-align: center; }
        .media-item img, .media-item video { width: 100%; height: 120px; object-fit: cover; border-radius: 8px; margin-bottom: 8px; }
        .media-item small { color: #888; }
        .drop-zone { border: 2px dashed #29A08E; border-radius: 12px; padding: 30px; text-align: center; cursor: pointer; transition: 0.3s; background: #f0f9f7; }
        .drop-zone:hover { border-style: solid; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 col-lg-2 sidebar">
                <div class="logo">
                    <h4><span>K</span>ileleTech</h4>
                    <small style="color: #999;">Admin Panel</small>
                </div>
                <nav class="nav flex-column mt-3">
                    <a href="dashboard.php" class="nav-link"><i class="fas fa-th-large"></i> Dashboard</a>
                    <a href="media_library.php" class="nav-link active"><i class="fas fa-photo-video"></i> Media Library</a>
                    <a href="site_settings.php" class="nav-link"><i class="fas fa-cog"></i> Site Settings</a>
                    <a href="messages.php" class="nav-link"><i class="fas fa-envelope"></i> Messages</a>
                    <a href="view_quotes.php" class="nav-link"><i class="fas fa-file-invoice"></i> Quotes</a>
                    <a href="bundle_quotes.php" class="nav-link"><i class="fas fa-layer-group"></i> Bundle Quotes</a>
                    <a href="logout.php" class="nav-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </nav>
            </div>

            <div class="col-md-10 col-lg-10 main-content">
                <div class="top-bar">
                    <h5>Media Library</h5>
                </div>

                <?php echo $msg; ?>

                <!-- Upload Section -->
                <div class="upload-card">
                    <h5 class="mb-4" style="font-weight: 700; color: #0f1e33;"><i class="fas fa-upload me-2"></i> Upload New Media</h5>
                    <form method="POST" enctype="multipart/form-data" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Media Type</label>
                            <select name="media_type" class="form-control">
                                <option value="image">Image</option>
                                <option value="video">Video</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Title (Optional)</label>
                            <input type="text" name="title" class="form-control" placeholder="e.g., Hero Banner">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Select File</label>
                            <div class="drop-zone" onclick="document.getElementById('fileInput').click()">
                                <i class="fas fa-cloud-upload-alt" style="font-size: 2rem; color: #29A08E;"></i>
                                <p class="mb-0 mt-2">Click to choose from Computer</p>
                                <input type="file" name="media_file" id="fileInput" class="d-none" required>
                            </div>
                        </div>
                        <div class="col-12 text-end">
                            <button type="submit" name="upload_media" class="btn btn-primary">Upload Media</button>
                        </div>
                    </form>
                </div>

                <!-- Media Grid -->
                <div class="upload-card">
                    <h5 class="mb-3" style="font-weight: 700; color: #0f1e33;"><i class="fas fa-th-large me-2"></i> All Uploaded Media</h5>
                    <div class="media-grid">
                        <?php
                        $media = $pdo->query("SELECT * FROM media_library ORDER BY created_at DESC")->fetchAll();
                        foreach ($media as $item):
                        ?>
                        <div class="media-item">
                            <?php if ($item['media_type'] == 'image'): ?>
                                <img src="<?php echo $item['file_path']; ?>" alt="<?php echo htmlspecialchars($item['title']); ?>">
                            <?php else: ?>
                                <video src="<?php echo $item['file_path']; ?>" controls></video>
                            <?php endif; ?>
                            <small><?php echo htmlspecialchars($item['title'] ?: 'Untitled'); ?></small>
                            <br>
                            <small class="text-muted"><?php echo $item['media_type']; ?> · <?php echo date('M d, Y', strtotime($item['created_at'])); ?></small>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>