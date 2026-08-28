<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

require_once '../includes/db.php';

// Handle DELETE
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM reviews WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: manage_reviews.php?msg=deleted');
    exit;
}

// Handle UPDATE
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_id'])) {
    $id = (int)$_POST['update_id'];
    $name = htmlspecialchars($_POST['name']);
    $rating = (int)$_POST['rating'];
    $review = htmlspecialchars($_POST['review']);
    
    $stmt = $pdo->prepare("UPDATE reviews SET name = ?, rating = ?, review = ? WHERE id = ?");
    $stmt->execute([$name, $rating, $review, $id]);
    header('Location: manage_reviews.php?msg=updated');
    exit;
}

// Fetch all reviews
$reviews = $pdo->query("SELECT * FROM reviews ORDER BY created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Reviews – KileleTech</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { background: #f8fafb; font-family: 'Poppins', sans-serif; padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        .section-card { background: #ffffff; border-radius: 16px; padding: 25px; box-shadow: 0 4px 12px rgba(0,0,0,0.04); margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border-bottom: 1px solid #ddd; text-align: left; }
        th { background: #f8fafb; color: #0f1e33; }
        .btn-edit { background: #3498db; color: white; padding: 5px 10px; border-radius: 5px; text-decoration: none; }
        .btn-delete { background: #e74c3c; color: white; padding: 5px 10px; border-radius: 5px; text-decoration: none; }
        .success { background: #d4edda; color: #155724; padding: 15px; margin-bottom: 20px; border-radius: 8px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="section-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h4 style="color: #0f1e33; font-weight: 700;">Manage Customer Reviews</h4>
                <a href="dashboard.php" class="btn btn-outline-secondary btn-sm">← Back to Dashboard</a>
            </div>

            <?php if (isset($_GET['msg'])): ?>
                <div class="success"><i class="fas fa-check-circle"></i> Action successful!</div>
            <?php endif; ?>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Rating</th>
                        <th>Review</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reviews as $r): ?>
                    <tr>
                        <td><?php echo $r['id']; ?></td>
                        <td><?php echo htmlspecialchars($r['name']); ?></td>
                        <td><?php echo str_repeat("★", $r['rating']) . str_repeat("☆", 5 - $r['rating']); ?></td>
                        <td><?php echo htmlspecialchars($r['review']); ?></td>
                        <td><?php echo $r['created_at']; ?></td>
                        <td>
                            <a href="?edit=<?php echo $r['id']; ?>" class="btn-edit"><i class="fas fa-edit"></i> Edit</a>
                            <a href="?delete=<?php echo $r['id']; ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this?');"><i class="fas fa-trash"></i> Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Edit Form -->
            <?php 
            if (isset($_GET['edit'])) {
                $edit_id = (int)$_GET['edit'];
                foreach ($reviews as $r) {
                    if ($r['id'] == $edit_id) {
            ?>
                    <div style="margin-top: 40px; border-top: 2px solid #f0f0f0; padding-top: 20px;">
                        <h5 style="color: #0f1e33;">Edit Review #<?php echo $r['id']; ?></h5>
                        <form method="POST" style="margin-top: 20px;">
                            <input type="hidden" name="update_id" value="<?php echo $r['id']; ?>">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label>Name</label>
                                    <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($r['name']); ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label>Rating</label>
                                    <select name="rating" class="form-control" required>
                                        <option value="5" <?php if($r['rating']==5) echo 'selected'; ?>>★★★★★ (5 Stars)</option>
                                        <option value="4" <?php if($r['rating']==4) echo 'selected'; ?>>★★★★☆ (4 Stars)</option>
                                        <option value="3" <?php if($r['rating']==3) echo 'selected'; ?>>★★★☆☆ (3 Stars)</option>
                                        <option value="2" <?php if($r['rating']==2) echo 'selected'; ?>>★★☆☆☆ (2 Stars)</option>
                                        <option value="1" <?php if($r['rating']==1) echo 'selected'; ?>>★☆☆☆☆ (1 Star)</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label>Review Text</label>
                                    <textarea name="review" class="form-control" rows="5" required><?php echo htmlspecialchars($r['review']); ?></textarea>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Update Review</button>
                                </div>
                            </div>
                        </form>
                    </div>
            <?php 
                    }
                }
            }
            ?>
        </div>
    </div>
</body>
</html>