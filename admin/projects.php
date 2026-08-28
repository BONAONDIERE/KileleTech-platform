<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Add new project
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_project'])) {
    $title = trim($_POST['title'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if (!empty($title)) {
        $stmt = $pdo->prepare("INSERT INTO projects (title, category, description) VALUES (?, ?, ?)");
        $stmt->execute([$title, $category, $description]);
        header('Location: projects.php?added=1');
        exit;
    }
}

// Update project
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_project'])) {
    $id = (int) ($_POST['id'] ?? 0);
    $title = trim($_POST['title'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if ($id > 0 && !empty($title)) {
        $stmt = $pdo->prepare("UPDATE projects SET title = ?, category = ?, description = ? WHERE id = ?");
        $stmt->execute([$title, $category, $description, $id]);
        header('Location: projects.php?updated=1');
        exit;
    }
}

// Delete project
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM projects WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: projects.php?deleted=1');
    exit;
}

// Fetch all projects
$projects = $pdo->query("SELECT * FROM projects ORDER BY created_at DESC")->fetchAll();

// Edit Pre-fill
$editingProject = null;
if (isset($_GET['edit'])) {
    $editId = (int) $_GET['edit'];
    foreach ($projects as $p) {
        if ($p['id'] == $editId) {
            $editingProject = $p;
            break;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Projects Admin – KileleTech</title>
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
        .form-card { background: #fff; border-radius: 16px; padding: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.04); margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h5>KileleTech Admin</h5>
        <nav class="nav flex-column mt-3">
            <a href="dashboard.php" class="nav-link"><i class="fas fa-th-large me-2"></i> Dashboard</a>
            <a href="projects.php" class="nav-link active"><i class="fas fa-briefcase me-2"></i> Projects</a>
            <a href="logout.php" class="nav-link"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
        </nav>
    </div>

    <div class="main-content">
        <div class="top-bar">
            <h4>Projects</h4>
        </div>

        <?php if (isset($_GET['added'])): ?>
            <div class="alert alert-success">Project added successfully.</div>
        <?php endif; ?>
        <?php if (isset($_GET['updated'])): ?>
            <div class="alert alert-success">Project updated successfully.</div>
        <?php endif; ?>
        <?php if (isset($_GET['deleted'])): ?>
            <div class="alert alert-success">Project deleted successfully.</div>
        <?php endif; ?>

        <!-- SUPER SLIM ADD / EDIT FORM -->
        <div class="form-card">
            <form method="POST" action="projects.php" class="row g-1 align-items-center">
                <input type="hidden" name="id" value="<?php echo $editingProject['id'] ?? ''; ?>">
                
                <div class="col-md-3">
                    <input type="text" name="title" class="form-control form-control-sm" placeholder="Project Title" required value="<?php echo $editingProject['title'] ?? ''; ?>">
                </div>
                <div class="col-md-3">
                    <input type="text" name="category" class="form-control form-control-sm" placeholder="Category (Web, Security, etc.)" value="<?php echo $editingProject['category'] ?? ''; ?>">
                </div>
                <div class="col-md-4">
                    <input type="text" name="description" class="form-control form-control-sm" placeholder="Project Description" value="<?php echo $editingProject['description'] ?? ''; ?>">
                </div>
                <div class="col-md-2">
                    <button type="submit" name="<?php echo $editingProject ? 'update_project' : 'add_project'; ?>" class="btn btn-sm btn-success w-100">
                        <i class="fas fa-save"></i> <?php echo $editingProject ? 'Update' : 'Add'; ?>
                    </button>
                </div>
            </form>
            <?php if ($editingProject): ?>
                <div class="mt-2">
                    <a href="projects.php" class="btn btn-sm btn-outline-secondary">Cancel Edit</a>
                </div>
            <?php endif; ?>
        </div>

        <!-- PROJECT LIST -->
        <div class="card">
            <h6 class="fw-bold mb-3"><i class="fas fa-list me-2"></i> Project List</h6>
            <table class="table table-hover">
                <thead><tr><th>ID</th><th>Title</th><th>Category</th><th>Date</th><th>Action</th></tr></thead>
                <tbody>
                    <?php foreach ($projects as $p): ?>
                    <tr>
                        <td><?php echo $p['id']; ?></td>
                        <td><strong><?php echo htmlspecialchars($p['title']); ?></strong></td>
                        <td><?php echo htmlspecialchars($p['category']); ?></td>
                        <td><?php echo date('M d, Y', strtotime($p['created_at'])); ?></td>
                        <td style="white-space: nowrap;">
                            <a href="?edit=<?php echo $p['id']; ?>" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i> Edit</a>
                            <a href="projects.php?delete=<?php echo $p['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this project?')"><i class="fas fa-trash"></i> Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>