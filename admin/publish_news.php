<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// ------------------------------------------------------------
// ADD NEW NEWS
// ------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_news'])) {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');

    if (empty($title) || empty($content)) {
        header('Location: publish_news.php?error=' . urlencode('Please fill in both title and content.'));
        exit;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO updates (title, content) VALUES (?, ?)");
        $stmt->execute([$title, $content]);
        header('Location: publish_news.php?added=1');
        exit;
    } catch (PDOException $e) {
        error_log("News add error: " . $e->getMessage());
        header('Location: publish_news.php?error=' . urlencode('Could not publish news.'));
        exit;
    }
}

// ------------------------------------------------------------
// UPDATE / EDIT NEWS
// ------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_news'])) {
    $id = (int) ($_POST['id'] ?? 0);
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');

    if ($id <= 0 || $title === '' || $content === '') {
        header('Location: publish_news.php?error=' . urlencode('Please fill in both title and content.'));
        exit;
    }

    try {
        $stmt = $pdo->prepare("UPDATE updates SET title = ?, content = ? WHERE id = ?");
        $stmt->execute([$title, $content, $id]);
        header('Location: publish_news.php?updated=1');
        exit;
    } catch (PDOException $e) {
        error_log("News update error: " . $e->getMessage());
        header('Location: publish_news.php?error=' . urlencode('Could not update news.'));
        exit;
    }
}

// ------------------------------------------------------------
// DELETE NEWS
// ------------------------------------------------------------
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM updates WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: publish_news.php?deleted=1');
    exit;
}

// ------------------------------------------------------------
// FETCH ALL NEWS
// ------------------------------------------------------------
$news = $pdo->query("SELECT * FROM updates ORDER BY created_at DESC")->fetchAll();

// ------------------------------------------------------------
// EDIT: PRE-FILL THE FORM
// ------------------------------------------------------------
$editingNews = null;
if (isset($_GET['edit'])) {
    $editId = (int) $_GET['edit'];
    foreach ($news as $n) {
        if ($n['id'] == $editId) {
            $editingNews = $n;
            break;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Publish News – KileleTech</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f8fafb; font-family: 'Poppins', sans-serif; }
        .sidebar { background: #ffffff; border-right: 1px solid #e9ecef; min-height: 100vh; width: 250px; position: fixed; left: 0; top: 0; padding: 20px; }
        .sidebar h5 { font-weight: 700; color: #0f1e33; }
        .sidebar .nav-link { color: #555; padding: 10px 15px; border-radius: 8px; }
        .sidebar .nav-link.active { background: #f0f9f7; color: #29A08E; border-left: 3px solid #29A08E; }
        .main-content { margin-left: 250px; padding: 20px; }
        .top-bar { background: #fff; padding: 15px; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.05); margin-bottom: 15px; }
        .card { background: #fff; border-radius: 16px; padding: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.04); }
        .form-card { background: #fff; border-radius: 16px; padding: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.04); margin-bottom: 15px; }
        .compact-alert { padding: 6px 12px; margin-bottom: 10px; font-size: 0.85rem; border-radius: 6px; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h5>KileleTech Admin</h5>
        <nav class="nav flex-column mt-3">
            <a href="dashboard.php" class="nav-link"><i class="fas fa-th-large me-2"></i> Dashboard</a>
            <a href="publish_news.php" class="nav-link active"><i class="fas fa-plus-circle me-2"></i> Publish News</a>
            <a href="logout.php" class="nav-link"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
        </nav>
    </div>

    <div class="main-content">
        <div class="top-bar">
            <h4>Publish News</h4>
        </div>

        <!-- COMPACT ALERTS -->
        <?php if (isset($_GET['added'])): ?>
            <div class="alert alert-success compact-alert">News published successfully.</div>
        <?php endif; ?>
        <?php if (isset($_GET['updated'])): ?>
            <div class="alert alert-success compact-alert">News updated successfully.</div>
        <?php endif; ?>
        <?php if (isset($_GET['deleted'])): ?>
            <div class="alert alert-success compact-alert">News deleted successfully.</div>
        <?php endif; ?>
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger compact-alert"><?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>

        <!-- SUPER TINY ADD / EDIT FORM -->
        <div class="form-card">
            <form method="POST" action="publish_news.php" class="row g-1 align-items-center">
                <input type="hidden" name="id" value="<?php echo $editingNews['id'] ?? ''; ?>">
                
                <div class="col-md-4">
                    <input type="text" name="title" class="form-control form-control-sm" placeholder="News Title" required value="<?php echo $editingNews['title'] ?? ''; ?>">
                </div>
                <div class="col-md-5">
                    <input type="text" name="content" class="form-control form-control-sm" placeholder="Brief news content" required value="<?php echo $editingNews['content'] ?? ''; ?>">
                </div>
                <div class="col-md-3">
                    <button type="submit" name="<?php echo $editingNews ? 'update_news' : 'add_news'; ?>" class="btn btn-sm btn-success w-100">
                        <i class="fas fa-save"></i> <?php echo $editingNews ? 'Update' : 'Publish'; ?>
                    </button>
                </div>
            </form>
            <?php if ($editingNews): ?>
                <div class="mt-2">
                    <a href="publish_news.php" class="btn btn-sm btn-outline-secondary">Cancel Edit</a>
                </div>
            <?php endif; ?>
        </div>

        <!-- NEWS LIST TABLE -->
        <div class="card">
            <h6 class="fw-bold mb-3"><i class="fas fa-list me-2"></i> Published News</h6>
            <table class="table table-hover">
                <thead><tr><th>Title</th><th>Content</th><th>Date</th><th>Action</th></tr></thead>
                <tbody>
                    <?php foreach ($news as $n): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($n['title']); ?></strong></td>
                        <td><?php echo htmlspecialchars($n['content']); ?></td>
                        <td><?php echo date('M d, Y', strtotime($n['created_at'])); ?></td>
                        <td style="white-space: nowrap;">
                            <a href="?edit=<?php echo $n['id']; ?>" class="btn btn-primary btn-sm" title="Edit"><i class="fas fa-edit"></i></a>
                            <a href="publish_news.php?delete=<?php echo $n['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this news?')"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>