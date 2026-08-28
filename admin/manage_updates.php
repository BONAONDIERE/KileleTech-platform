<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// ------------------------------------------------------------
// ADD NEW UPDATE
// ------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_update'])) {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');

    if (!empty($title) && !empty($content)) {
        $stmt = $pdo->prepare("INSERT INTO updates (title, content) VALUES (?, ?)");
        $stmt->execute([$title, $content]);
        header('Location: manage_updates.php?added=1');
        exit;
    } else {
        header('Location: manage_updates.php?error=' . urlencode('Please fill in both title and content.'));
        exit;
    }
}

// ------------------------------------------------------------
// UPDATE / EDIT UPDATE
// ------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_update'])) {
    $id = (int) ($_POST['id'] ?? 0);
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');

    if ($id <= 0 || $title === '' || $content === '') {
        header('Location: manage_updates.php?error=' . urlencode('Please fill in both title and content.'));
        exit;
    }

    try {
        $stmt = $pdo->prepare("UPDATE updates SET title = ?, content = ? WHERE id = ?");
        $stmt->execute([$title, $content, $id]);
        header('Location: manage_updates.php?updated=1');
        exit;
    } catch (PDOException $e) {
        error_log("Update edit error: " . $e->getMessage());
        header('Location: manage_updates.php?error=' . urlencode('Could not update update.'));
        exit;
    }
}

// ------------------------------------------------------------
// DELETE UPDATE
// ------------------------------------------------------------
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM updates WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: manage_updates.php?deleted=1');
    exit;
}

// ------------------------------------------------------------
// FETCH ALL UPDATES
// ------------------------------------------------------------
$updates = $pdo->query("SELECT * FROM updates ORDER BY created_at DESC")->fetchAll();

// ------------------------------------------------------------
// EDIT: PRE-FILL THE FORM
// ------------------------------------------------------------
$editingUpdate = null;
if (isset($_GET['edit'])) {
    $editId = (int) $_GET['edit'];
    foreach ($updates as $u) {
        if ($u['id'] == $editId) {
            $editingUpdate = $u;
            break;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Updates – KileleTech</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f8fafb; font-family: 'Poppins', sans-serif; }
        .sidebar { background: #ffffff; border-right: 1px solid #e9ecef; min-height: 100vh; width: 250px; position: fixed; left: 0; top: 0; padding: 20px; }
        .sidebar h5 { font-weight: 700; color: #0f1e33; }
        .sidebar .nav-link { color: #555; padding: 10px 15px; border-radius: 8px; }
        .sidebar .nav-link.active { background: #f0f9f7; color: #29A08E; }
        .main-content { margin-left: 250px; padding: 20px; }
        .top-bar { background: #fff; padding: 15px; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.05); margin-bottom: 20px; }
        .card { background: #fff; border-radius: 16px; padding: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.04); }
    </style>
</head>
<body>
    <div class="sidebar">
        <h5>KileleTech Admin</h5>
        <nav class="nav flex-column mt-3">
            <a href="dashboard.php" class="nav-link"><i class="fas fa-th-large me-2"></i> Dashboard</a>
            <a href="manage_updates.php" class="nav-link active"><i class="fas fa-bullhorn me-2"></i> Updates</a>
            <a href="logout.php" class="nav-link"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
        </nav>
    </div>

    <div class="main-content">
        <div class="top-bar">
            <h4>Manage Updates</h4>
        </div>

        <!-- ALERTS -->
        <?php if (isset($_GET['added'])): ?>
            <div class="alert alert-success">Update published successfully.</div>
        <?php endif; ?>
        <?php if (isset($_GET['updated'])): ?>
            <div class="alert alert-success">Update updated successfully.</div>
        <?php endif; ?>
        <?php if (isset($_GET['deleted'])): ?>
            <div class="alert alert-success">Update deleted successfully.</div>
        <?php endif; ?>
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>

        <!-- ADD / EDIT FORM -->
        <div class="card mb-4">
            <h6 class="fw-bold mb-3">
                <i class="fas fa-plus-circle text-primary me-2"></i> 
                <?php echo $editingUpdate ? 'Edit Update' : 'Add New Update'; ?>
            </h6>

            <?php if ($editingUpdate): ?>
                <a href="manage_updates.php" class="btn btn-sm btn-outline-secondary mb-2">Cancel Edit</a>
            <?php endif; ?>

            <form method="POST" class="row g-2">
                <input type="hidden" name="id" value="<?php echo $editingUpdate['id'] ?? ''; ?>">
                
                <div class="col-md-4">
                    <input type="text" name="title" class="form-control" placeholder="Update Title" required value="<?php echo $editingUpdate['title'] ?? ''; ?>">
                </div>
                <div class="col-md-6">
                    <input type="text" name="content" class="form-control" placeholder="Brief announcement text" required value="<?php echo $editingUpdate['content'] ?? ''; ?>">
                </div>
                <div class="col-md-2">
                    <button type="submit" name="<?php echo $editingUpdate ? 'update_update' : 'add_update'; ?>" class="btn btn-success w-100">
                        <i class="fas fa-save"></i> <?php echo $editingUpdate ? 'Update' : 'Publish'; ?>
                    </button>
                </div>
            </form>
        </div>

        <!-- UPDATES LIST -->
        <div class="card">
            <h6 class="fw-bold mb-3"><i class="fas fa-list me-2"></i> Published Updates</h6>
            <table class="table table-hover">
                <thead><tr><th>Title</th><th>Content</th><th>Date</th><th>Action</th></tr></thead>
                <tbody>
                    <?php foreach ($updates as $u): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($u['title']); ?></strong></td>
                        <td><?php echo htmlspecialchars($u['content']); ?></td>
                        <td><?php echo date('M d, Y', strtotime($u['created_at'])); ?></td>
                        <td style="white-space: nowrap;">
                            <a href="?edit=<?php echo $u['id']; ?>" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i> Edit</a>
                            <a href="manage_updates.php?delete=<?php echo $u['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this update?')"><i class="fas fa-trash"></i> Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>