<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// ------------------------------------------------------------
// ADD NEW QUOTE
// ------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $company = trim($_POST['company'] ?? '');
    $service = trim($_POST['service'] ?? '');
    $package = trim($_POST['package'] ?? '');
    $status = trim($_POST['status'] ?? 'Pending');

    if ($name === '' || $email === '' || $service === '') {
        header('Location: view_quotes.php?error=' . urlencode('Please fill in all required fields.'));
        exit;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO quote_requests (name, company, phone, email, service, package, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $company, $phone, $email, $service, $package, $status]);
        header('Location: view_quotes.php?added=1');
        exit;
    } catch (PDOException $e) {
        error_log("Quote add error: " . $e->getMessage());
        header('Location: view_quotes.php?error=' . urlencode('Could not add quote.'));
        exit;
    }
}

// ------------------------------------------------------------
// UPDATE / EDIT A QUOTE
// ------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $id = (int) ($_POST['id'] ?? 0);
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $company = trim($_POST['company'] ?? '');
    $service = trim($_POST['service'] ?? '');
    $package = trim($_POST['package'] ?? '');
    $status = trim($_POST['status'] ?? 'Pending');

    if ($id <= 0 || $name === '' || $email === '' || $service === '') {
        header('Location: view_quotes.php?error=' . urlencode('Please fill in all required fields.'));
        exit;
    }

    try {
        $stmt = $pdo->prepare("UPDATE quote_requests SET name = ?, company = ?, phone = ?, email = ?, service = ?, package = ?, status = ? WHERE id = ?");
        $stmt->execute([$name, $company, $phone, $email, $service, $package, $status, $id]);
        header('Location: view_quotes.php?updated=1');
        exit;
    } catch (PDOException $e) {
        error_log("Quote update error: " . $e->getMessage());
        header('Location: view_quotes.php?error=' . urlencode('Could not update quote.'));
        exit;
    }
}

// ------------------------------------------------------------
// DELETE A QUOTE
// ------------------------------------------------------------
if (isset($_GET['delete'])) {
    $delId = (int) $_GET['delete'];

    try {
        $pdo->prepare("DELETE FROM quote_requests WHERE id = ?")->execute([$delId]);
        header('Location: view_quotes.php?deleted=1');
        exit;
    } catch (PDOException $e) {
        error_log("Quote delete error: " . $e->getMessage());
        header('Location: view_quotes.php?error=' . urlencode('Could not delete quote.'));
        exit;
    }
}

// ------------------------------------------------------------
// FETCH ALL QUOTES
// ------------------------------------------------------------
$quotes = [];
try {
    $stmt = $pdo->query("SELECT * FROM quote_requests ORDER BY created_at DESC");
    $quotes = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log("Quotes query error: " . $e->getMessage());
}

// ------------------------------------------------------------
// EDIT: Pre-fill the form if an 'edit' is requested
// ------------------------------------------------------------
$editingQuote = null;
if (isset($_GET['edit'])) {
    $editId = (int) $_GET['edit'];
    foreach ($quotes as $q) {
        if ($q['id'] == $editId) {
            $editingQuote = $q;
            break;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Standard Quotes – KileleTech Admin</title>
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
        .top-bar small { color: #777; }
        .form-card { background: #ffffff; border-radius: 16px; padding: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.04); margin-bottom: 24px; }
        .table-card { background: #ffffff; border-radius: 16px; padding: 24px; box-shadow: 0 4px 12px rgba(0,0,0,0.04); }
        .table thead th { border-bottom: 2px solid #f0f0f0; color: #777; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; }
        .table tbody td { color: #555; vertical-align: middle; font-size: 0.9rem; }
        .table tbody tr:hover { background: #f0f9f7; }
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
                    <a href="messages.php" class="nav-link"><i class="fas fa-envelope"></i> Messages</a>
                    <a href="subscribers.php" class="nav-link"><i class="fas fa-users"></i> Subscribers</a>
                    <a href="view_quotes.php" class="nav-link active"><i class="fas fa-file-invoice"></i> Standard Quotes</a>
                    <a href="bundle_quotes.php" class="nav-link"><i class="fas fa-layer-group"></i> Bundle Quotes</a>
                    <a href="logout.php" class="nav-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 col-lg-10 main-content">
                <div class="top-bar">
                    <div>
                        <h5 class="mb-0">Standard Quote Requests</h5>
                        <small class="text-muted">Total: <?php echo count($quotes); ?> requests</small>
                    </div>
                </div>

                <!-- ALERTS -->
                <?php if (isset($_GET['added'])): ?>
                    <div class="alert alert-success">Quote added successfully.</div>
                <?php endif; ?>
                <?php if (isset($_GET['updated'])): ?>
                    <div class="alert alert-success">Quote updated successfully.</div>
                <?php endif; ?>
                <?php if (isset($_GET['deleted'])): ?>
                    <div class="alert alert-success">Quote deleted successfully.</div>
                <?php endif; ?>
                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
                <?php endif; ?>

                <!-- SUPER TINY FORM (No labels, just 1 row) -->
                <div class="form-card">
                    <form method="POST" action="view_quotes.php" class="row g-1 align-items-center">
                        <input type="hidden" name="action" value="<?php echo $editingQuote ? 'update' : 'add'; ?>">
                        <?php if ($editingQuote): ?>
                            <input type="hidden" name="id" value="<?php echo $editingQuote['id']; ?>">
                        <?php endif; ?>

                        <div class="col-md-2">
                            <input type="text" name="name" class="form-control form-control-sm" placeholder="Name" required value="<?php echo $editingQuote['name'] ?? ''; ?>">
                        </div>
                        <div class="col-md-2">
                            <input type="email" name="email" class="form-control form-control-sm" placeholder="Email" required value="<?php echo $editingQuote['email'] ?? ''; ?>">
                        </div>
                        <div class="col-md-2">
                            <input type="text" name="phone" class="form-control form-control-sm" placeholder="Phone" value="<?php echo $editingQuote['phone'] ?? ''; ?>">
                        </div>
                        <div class="col-md-2">
                            <input type="text" name="company" class="form-control form-control-sm" placeholder="Company" value="<?php echo $editingQuote['company'] ?? ''; ?>">
                        </div>
                        <div class="col-md-2">
                            <select name="service" class="form-select form-select-sm" required>
                                <option value="">Service</option>
                                <option value="Software Development" <?php echo ($editingQuote['service'] ?? '') == 'Software Development' ? 'selected' : ''; ?>>Software Dev</option>
                                <option value="Web Development & Hosting" <?php echo ($editingQuote['service'] ?? '') == 'Web Development & Hosting' ? 'selected' : ''; ?>>Web Dev</option>
                                <option value="Hardware Supply & Installation" <?php echo ($editingQuote['service'] ?? '') == 'Hardware Supply & Installation' ? 'selected' : ''; ?>>Hardware</option>
                                <option value="CCTV & Security Systems" <?php echo ($editingQuote['service'] ?? '') == 'CCTV & Security Systems' ? 'selected' : ''; ?>>CCTV</option>
                                <option value="ICT Consulting" <?php echo ($editingQuote['service'] ?? '') == 'ICT Consulting' ? 'selected' : ''; ?>>Consulting</option>
                                <option value="Database Management" <?php echo ($editingQuote['service'] ?? '') == 'Database Management' ? 'selected' : ''; ?>>Database</option>
                                <option value="Security Monitoring" <?php echo ($editingQuote['service'] ?? '') == 'Security Monitoring' ? 'selected' : ''; ?>>Monitoring</option>
                                <option value="Firewall Solutions" <?php echo ($editingQuote['service'] ?? '') == 'Firewall Solutions' ? 'selected' : ''; ?>>Firewall</option>
                            </select>
                        </div>
                        <div class="col-md-1">
                            <select name="package" class="form-select form-select-sm">
                                <option value="Starter" <?php echo ($editingQuote['package'] ?? '') == 'Starter' ? 'selected' : ''; ?>>Starter</option>
                                <option value="Business" <?php echo ($editingQuote['package'] ?? '') == 'Business' ? 'selected' : ''; ?>>Business</option>
                                <option value="Enterprise" <?php echo ($editingQuote['package'] ?? '') == 'Enterprise' ? 'selected' : ''; ?>>Enterprise</option>
                                <option value="Not sure yet" <?php echo ($editingQuote['package'] ?? '') == 'Not sure yet' ? 'selected' : ''; ?>>Not sure</option>
                            </select>
                        </div>
                        <div class="col-md-1">
                            <select name="status" class="form-select form-select-sm">
                                <option value="Pending" <?php echo ($editingQuote['status'] ?? '') == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="Approved" <?php echo ($editingQuote['status'] ?? '') == 'Approved' ? 'selected' : ''; ?>>Approved</option>
                                <option value="Rejected" <?php echo ($editingQuote['status'] ?? '') == 'Rejected' ? 'selected' : ''; ?>>Rejected</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-sm btn-success w-100">
                                <i class="fas fa-save"></i> <?php echo $editingQuote ? 'Update' : 'Add'; ?>
                            </button>
                        </div>
                    </form>
                    <?php if ($editingQuote): ?>
                        <div class="mt-2">
                            <a href="view_quotes.php" class="btn btn-sm btn-outline-secondary">Cancel Edit</a>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- TABLE -->
                <div class="table-card">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Service</th>
                                <th>Message</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($quotes) > 0): ?>
                                <?php foreach ($quotes as $q): ?>
                                    <tr>
                                        <td><?php echo $q['id']; ?></td>
                                        <td><strong><?php echo htmlspecialchars($q['name'] ?? 'N/A'); ?></strong></td>
                                        <td><?php echo htmlspecialchars($q['email']); ?></td>
                                        <td><?php echo htmlspecialchars($q['service'] ?? 'General'); ?></td>
                                        <td><?php echo htmlspecialchars(substr($q['message'] ?? '', 0, 50)) . '...'; ?></td>
                                        <td>
                                            <?php if ($q['status'] == 'Approved'): ?>
                                                <span class="badge bg-success text-white">Approved</span>
                                            <?php elseif ($q['status'] == 'Rejected'): ?>
                                                <span class="badge bg-danger text-white">Rejected</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo date('M j, g:i A', strtotime($q['created_at'])); ?></td>
                                        <td style="white-space: nowrap;">
                                            <a href="?edit=<?php echo $q['id']; ?>" class="btn btn-sm btn-primary" title="Edit"><i class="fas fa-edit"></i></a>
                                            <a href="view_quotes.php?delete=<?php echo (int) $q['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this quote?');"><i class="fas fa-trash"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="8" class="text-center text-muted py-3">No standard quote requests yet.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>