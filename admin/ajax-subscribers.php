<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ../login.php');
    exit;
}
require_once __DIR__ . '/../includes/db.php';

// ------------------------------------------------------------
// ADD a subscriber
// ------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $email = trim($_POST['email'] ?? '');
    $status = trim($_POST['status'] ?? 'active');

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header('Location: subscribers.php?error=' . urlencode('Please enter a valid email address.'));
        exit;
    }

    try {
        // Check for an existing subscriber first
        $check = $pdo->prepare("SELECT id FROM newsletter_subscribers WHERE email = ?");
        $check->execute([$email]);

        if ($check->fetch()) {
            header('Location: subscribers.php?error=' . urlencode("\"$email\" is already on the list."));
            exit;
        }

        $insert = $pdo->prepare("INSERT INTO newsletter_subscribers (email, status) VALUES (?, ?)");
        $insert->execute([$email, $status]);

        header('Location: subscribers.php?added=1');
        exit;
    } catch (PDOException $e) {
        error_log("Subscriber add error: " . $e->getMessage());
        header('Location: subscribers.php?error=' . urlencode('Something went wrong. Please try again.'));
        exit;
    }
}

// ------------------------------------------------------------
// UPDATE an existing subscriber (EDIT)
// ------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $id = (int) ($_POST['id'] ?? 0);
    $email = trim($_POST['email'] ?? '');
    $status = trim($_POST['status'] ?? 'active');

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header('Location: subscribers.php?error=' . urlencode('Please enter a valid email address.'));
        exit;
    }

    try {
        $stmt = $pdo->prepare("UPDATE newsletter_subscribers SET email = ?, status = ? WHERE id = ?");
        $stmt->execute([$email, $status, $id]);
        header('Location: subscribers.php?updated=1');
        exit;
    } catch (PDOException $e) {
        error_log("Subscriber update error: " . $e->getMessage());
        header('Location: subscribers.php?error=' . urlencode('Could not update that subscriber.'));
        exit;
    }
}

// ------------------------------------------------------------
// DELETE a subscriber
// ------------------------------------------------------------
if (isset($_GET['delete'])) {
    $delId = (int) $_GET['delete'];

    try {
        $pdo->prepare("DELETE FROM newsletter_subscribers WHERE id = ?")->execute([$delId]);
        header('Location: subscribers.php?deleted=1');
        exit;
    } catch (PDOException $e) {
        error_log("Subscriber delete error: " . $e->getMessage());
        header('Location: subscribers.php?error=' . urlencode('Could not delete that subscriber.'));
        exit;
    }
}

// ------------------------------------------------------------
// FETCH the current list (always runs, for display)
// ------------------------------------------------------------
$subscribers = [];
try {
    $stmt = $pdo->query("SELECT * FROM newsletter_subscribers ORDER BY subscribed_at DESC");
    $subscribers = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log("Subscribers query error: " . $e->getMessage());
}
$totalSubscribers = count($subscribers);

// ------------------------------------------------------------
// EDIT: Pre-fill the form when Edit button is clicked
// ------------------------------------------------------------
$editingSub = null;
if (isset($_GET['edit'])) {
    $editId = (int) $_GET['edit'];
    foreach ($subscribers as $sub) {
        if ($sub['id'] == $editId) {
            $editingSub = $sub;
            break;
        }
    }
}

// ------------------------------------------------------------
// EXPORT CSV (Download directly)
// ------------------------------------------------------------
if (isset($_GET['export'])) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="subscribers_' . date('Y-m-d') . '.csv"');
    
    $output = fopen('php://output', 'w');
    fputcsv($output, ['ID', 'Email', 'Status', 'Subscribed At']);
    
    foreach ($subscribers as $sub) {
        fputcsv($output, [
            $sub['id'],
            $sub['email'],
            $sub['status'] ?? 'active',
            $sub['subscribed_at']
        ]);
    }
    fclose($output);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscribers – KileleTech Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f8fafb; font-family: sans-serif; padding: 20px; }
        .card { border-radius: 16px; }
    </style>
</head>
<body>
<div class="container">
    <a href="dashboard.php" class="btn btn-outline-secondary btn-sm mb-3">← Back to Dashboard</a>

    <?php if (isset($_GET['added'])): ?>
        <div class="alert alert-success d-flex align-items-center" role="alert">
            <i class="fas fa-check-circle me-2"></i> Subscriber added successfully.
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['updated'])): ?>
        <div class="alert alert-success d-flex align-items-center" role="alert">
            <i class="fas fa-check-circle me-2"></i> Subscriber updated successfully.
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['deleted'])): ?>
        <div class="alert alert-success d-flex align-items-center" role="alert">
            <i class="fas fa-check-circle me-2"></i> Subscriber deleted successfully.
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger d-flex align-items-center" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i> <?php echo htmlspecialchars($_GET['error']); ?>
        </div>
    <?php endif; ?>

    <!-- ADD / EDIT FORM -->
    <div class="card p-3 shadow-sm mb-4">
        <h6 class="mb-3"><i class="fas fa-user-plus me-2 text-success"></i><?php echo $editingSub ? 'Edit Subscriber' : 'Add Subscriber'; ?></h6>
        <?php if ($editingSub): ?>
            <a href="subscribers.php" class="btn btn-sm btn-outline-secondary mb-2">Cancel Edit</a>
        <?php endif; ?>

        <form method="POST" action="subscribers.php" class="row g-2">
            <input type="hidden" name="action" value="<?php echo $editingSub ? 'update' : 'add'; ?>">
            <?php if ($editingSub): ?>
                <input type="hidden" name="id" value="<?php echo $editingSub['id']; ?>">
            <?php endif; ?>

            <div class="col-sm-5">
                <input type="email" name="email" class="form-control" placeholder="email@example.com" required value="<?php echo $editingSub['email'] ?? ''; ?>">
            </div>
            <div class="col-sm-3">
                <select name="status" class="form-select">
                    <option value="active" <?php echo ($editingSub['status'] ?? '') == 'active' ? 'selected' : ''; ?>>Active</option>
                    <option value="inactive" <?php echo ($editingSub['status'] ?? '') == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                    <option value="unsubscribed" <?php echo ($editingSub['status'] ?? '') == 'unsubscribed' ? 'selected' : ''; ?>>Unsubscribed</option>
                </select>
            </div>
            <div class="col-sm-4">
                <button type="submit" class="btn btn-success w-100">
                    <i class="fas fa-save"></i> <?php echo $editingSub ? 'Update' : 'Add'; ?>
                </button>
            </div>
        </form>
    </div>

    <!-- TABLE -->
    <div class="card p-3 shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0"><i class="fas fa-list me-2 text-success"></i>All Subscribers (<?php echo $totalSubscribers; ?>)</h5>
            <a href="subscribers.php?export=1" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-file-export me-1"></i> Export CSV
            </a>
        </div>

        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php if ($totalSubscribers > 0): ?>
                    <?php foreach ($subscribers as $sub): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($sub['id']); ?></td>
                            <td><?php echo htmlspecialchars($sub['email']); ?></td>
                            <td><?php echo htmlspecialchars($sub['status'] ?? 'active'); ?></td>
                            <td><?php echo htmlspecialchars($sub['subscribed_at']); ?></td>
                            <td>
                                <a href="?edit=<?php echo $sub['id']; ?>" class="btn btn-sm btn-primary" title="Edit"><i class="fas fa-edit"></i></a>
                                <a href="subscribers.php?delete=<?php echo (int) $sub['id']; ?>"
                                   class="btn btn-sm btn-outline-danger"
                                   onclick="return confirm('Delete <?php echo htmlspecialchars($sub['email'], ENT_QUOTES); ?>?');">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center py-4">No subscribers yet.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>