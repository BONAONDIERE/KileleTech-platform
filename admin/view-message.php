<?php
// ============================================================
// VIEW MESSAGE
// ============================================================

require_once 'includes/config.php';
require_once 'includes/auth.php';
require_once 'includes/functions.php';

requireLogin();

$type = $_GET['type'] ?? 'contact';
$id = (int)($_GET['id'] ?? 0);

$tableMap = [
    'contact' => 'contact_submissions',
    'quote' => 'quote_requests',
    'join' => 'join_requests'
];

$table = $tableMap[$type] ?? 'contact_submissions';
$pageTitle = ucfirst($type) . ' Message';

$message = getSubmissionById($db, $table, $id);

if (!$message) {
    header('Location: messages.php?type=' . $type);
    exit;
}

// Mark as read
if ($message['status'] === 'new') {
    updateSubmissionStatus($db, $table, $id, 'read');
    $message['status'] = 'read';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> – KileleTech Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f8fafb; }
        .sidebar { background: #0f1e33; min-height: 100vh; padding: 20px 0; }
        .sidebar .logo h4 { color: #29A08E; font-weight: 700; text-align: center; padding: 10px 0; }
        .sidebar .nav-link { color: rgba(255,255,255,0.7); padding: 12px 24px; border-radius: 8px; margin: 4px 12px; transition: 0.3s; }
        .sidebar .nav-link:hover { background: rgba(255,255,255,0.05); color: #fff; }
        .sidebar .nav-link i { width: 20px; margin-right: 10px; }
        .main-content { padding: 24px; }
        .top-bar { background: #fff; padding: 12px 24px; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.05); margin-bottom: 24px; }
        .message-card { background: #fff; border-radius: 12px; padding: 24px; box-shadow: 0 2px 12px rgba(0,0,0,0.05); }
        .message-card .label { font-weight: 600; color: #333; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 col-lg-2 sidebar">
                <div class="logo"><h4>KileleTech</h4></div>
                <nav class="nav flex-column mt-3">
                    <a href="index.php" class="nav-link"><i class="fas fa-th-large"></i> Dashboard</a>
                    <a href="messages.php?type=contact" class="nav-link active"><i class="fas fa-envelope"></i> Messages</a>
                    <a href="logout.php" class="nav-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 col-lg-10 main-content">
                <div class="top-bar d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0"><?php echo $pageTitle; ?></h5>
                        <small class="text-muted"><?php echo htmlspecialchars($message['name']); ?></small>
                    </div>
                    <a href="messages.php?type=<?php echo $type; ?>" class="btn btn-sm btn-outline-secondary">← Back</a>
                </div>

                <div class="message-card">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <p><span class="label">Name:</span> <?php echo htmlspecialchars($message['name']); ?></p>
                            <p><span class="label">Email:</span> <a href="mailto:<?php echo htmlspecialchars($message['email']); ?>"><?php echo htmlspecialchars($message['email']); ?></a></p>
                            <p><span class="label">Phone:</span> <?php echo htmlspecialchars($message['phone'] ?? 'N/A'); ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><span class="label">Status:</span> <?php echo getStatusBadge($message['status']); ?></p>
                            <p><span class="label">Received:</span> <?php echo formatDate($message['created_at']); ?></p>
                            <p><span class="label">Subject / Service:</span> <?php echo htmlspecialchars($message['subject'] ?? $message['service'] ?? 'N/A'); ?></p>
                            <?php if (isset($message['company']) && $message['company']): ?>
                                <p><span class="label">Company:</span> <?php echo htmlspecialchars($message['company']); ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="col-12">
                            <hr>
                            <p><span class="label">Message:</span></p>
                            <div style="background: #f8fafb; padding: 16px; border-radius: 8px; white-space: pre-wrap;">
                                <?php echo htmlspecialchars($message['message']); ?>
                            </div>
                        </div>
                        <div class="col-12 mt-3">
                            <div class="d-flex gap-2">
                                <a href="reply-message.php?type=<?php echo $type; ?>&id=<?php echo $message['id']; ?>" class="btn btn-primary">Reply</a>
                                <a href="messages.php?type=<?php echo $type; ?>" class="btn btn-outline-secondary">Back to List</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>