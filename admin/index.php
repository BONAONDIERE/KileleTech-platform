<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';
require_once 'includes/functions.php';

requireLogin();

$user = getCurrentUser($db);

$contactCount = getSubmissionCount($db, 'contact_submissions');
$quoteCount = getSubmissionCount($db, 'quote_requests');
$joinCount = getSubmissionCount($db, 'join_requests');
$subscriberCount = getSubmissionCount($db, 'newsletter_subscribers');

$newContactCount = getSubmissionCount($db, 'contact_submissions', 'new');
$newQuoteCount = getSubmissionCount($db, 'quote_requests', 'new');
$newJoinCount = getSubmissionCount($db, 'join_requests', 'new');

$recentContacts = getSubmissions($db, 'contact_submissions', 5, 0);
$recentQuotes = getSubmissions($db, 'quote_requests', 5, 0);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard – KileleTech</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f8fafb; }
        .sidebar { background: #0f1e33; min-height: 100vh; padding: 20px 0; }
        .sidebar .logo { color: #fff; text-align: center; padding: 10px 0 20px; border-bottom: 1px solid rgba(255,255,255,0.05); }
        .sidebar .logo h4 { color: #29A08E; font-weight: 700; }
        .sidebar .nav-link { color: rgba(255,255,255,0.7); padding: 12px 24px; border-radius: 8px; margin: 4px 12px; transition: 0.3s; }
        .sidebar .nav-link:hover { background: rgba(255,255,255,0.05); color: #fff; }
        .sidebar .nav-link.active { background: #29A08E; color: #fff; }
        .sidebar .nav-link i { width: 20px; margin-right: 10px; }
        .main-content { padding: 24px; }
        .stat-card { background: #fff; border-radius: 12px; padding: 20px 24px; box-shadow: 0 2px 12px rgba(0,0,0,0.05); border-left: 4px solid #29A08E; }
        .stat-card .stat-number { font-size: 28px; font-weight: 700; color: #0f1e33; }
        .stat-card .stat-label { color: #888; font-size: 13px; }
        .stat-card .stat-icon { font-size: 32px; color: #29A08E; opacity: 0.2; }
        .table-card { background: #fff; border-radius: 12px; padding: 20px; box-shadow: 0 2px 12px rgba(0,0,0,0.05); }
        .top-bar { background: #fff; padding: 12px 24px; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.05); margin-bottom: 24px; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 col-lg-2 sidebar">
                <div class="logo">
                    <h4>KileleTech</h4>
                    <small style="color: rgba(255,255,255,0.4);">Admin Portal</small>
                </div>
                <nav class="nav flex-column mt-3">
                    <a href="index.php" class="nav-link active"><i class="fas fa-th-large"></i> Dashboard</a>
                    <a href="messages.php?type=contact" class="nav-link"><i class="fas fa-envelope"></i> Contacts <span class="badge bg-danger float-end"><?php echo $newContactCount; ?></span></a>
                    <a href="messages.php?type=quote" class="nav-link"><i class="fas fa-file-invoice"></i> Quotes <span class="badge bg-danger float-end"><?php echo $newQuoteCount; ?></span></a>
                    <a href="messages.php?type=join" class="nav-link"><i class="fas fa-user-plus"></i> Join Requests <span class="badge bg-danger float-end"><?php echo $newJoinCount; ?></span></a>
                    <a href="subscribers.php" class="nav-link"><i class="fas fa-users"></i> Subscribers</a>
                    <a href="export.php" class="nav-link"><i class="fas fa-download"></i> Export Data</a>
                    <a href="users.php" class="nav-link"><i class="fas fa-user-cog"></i> Users</a>
                    <hr style="border-color: rgba(255,255,255,0.05);">
                    <a href="logout.php" class="nav-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 col-lg-10 main-content">
                <div class="top-bar d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">Dashboard</h5>
                        <small class="text-muted">Welcome back, <?php echo htmlspecialchars($user['full_name'] ?? 'Admin'); ?></small>
                    </div>
                    <div class="user-info">
                        <i class="fas fa-user-circle me-1"></i> <?php echo htmlspecialchars($user['username'] ?? 'admin'); ?>
                        <span class="badge bg-secondary ms-2"><?php echo htmlspecialchars($user['role'] ?? 'viewer'); ?></span>
                    </div>
                </div>

                <!-- Stats -->
                <div class="row g-4 mb-4">
                    <div class="col-md-3">
                        <div class="stat-card">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="stat-number"><?php echo $contactCount; ?></div>
                                    <div class="stat-label">Contact Messages</div>
                                </div>
                                <div class="stat-icon"><i class="fas fa-envelope"></i></div>
                            </div>
                            <small class="text-danger"><?php echo $newContactCount; ?> new</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card" style="border-left-color: #F2B91E;">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="stat-number"><?php echo $quoteCount; ?></div>
                                    <div class="stat-label">Quote Requests</div>
                                </div>
                                <div class="stat-icon" style="color: #F2B91E;"><i class="fas fa-file-invoice"></i></div>
                            </div>
                            <small class="text-danger"><?php echo $newQuoteCount; ?> new</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card" style="border-left-color: #C95873;">
                            <div>
                                <div class="stat-number"><?php echo $joinCount; ?></div>
                                <div class="stat-label">Join Requests</div>
                            </div>
                            <div class="stat-icon" style="color: #C95873;"><i class="fas fa-user-plus"></i></div>
                            <small class="text-danger"><?php echo $newJoinCount; ?> new</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card" style="border-left-color: #2D4A73;">
                            <div>
                                <div class="stat-number"><?php echo $subscriberCount; ?></div>
                                <div class="stat-label">Newsletter Subscribers</div>
                            </div>
                            <div class="stat-icon" style="color: #2D4A73;"><i class="fas fa-users"></i></div>
                        </div>
                    </div>
                </div>

                <!-- Recent Submissions -->
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="table-card">
                            <h6 class="fw-bold mb-3"><i class="fas fa-envelope text-primary me-2"></i>Recent Contacts</h6>
                            <?php if (count($recentContacts) > 0): ?>
                                <div class="list-group list-group-flush">
                                    <?php foreach ($recentContacts as $msg): ?>
                                        <a href="view-message.php?type=contact&id=<?php echo $msg['id']; ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong><?php echo htmlspecialchars($msg['name']); ?></strong>
                                                <br>
                                                <small class="text-muted"><?php echo htmlspecialchars($msg['subject'] ?? 'No subject'); ?></small>
                                            </div>
                                            <div class="text-end">
                                                <?php echo getStatusBadge($msg['status']); ?>
                                                <br>
                                                <small class="text-muted"><?php echo formatDate($msg['created_at']); ?></small>
                                            </div>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p class="text-muted text-center py-3">No contact messages yet.</p>
                            <?php endif; ?>
                            <a href="messages.php?type=contact" class="btn btn-sm btn-outline-primary mt-3">View All Contacts →</a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="table-card">
                            <h6 class="fw-bold mb-3"><i class="fas fa-file-invoice text-warning me-2"></i>Recent Quotes</h6>
                            <?php if (count($recentQuotes) > 0): ?>
                                <div class="list-group list-group-flush">
                                    <?php foreach ($recentQuotes as $msg): ?>
                                        <a href="view-message.php?type=quote&id=<?php echo $msg['id']; ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong><?php echo htmlspecialchars($msg['name']); ?></strong>
                                                <br>
                                                <small class="text-muted"><?php echo htmlspecialchars($msg['service'] ?? 'Not specified'); ?></small>
                                            </div>
                                            <div class="text-end">
                                                <?php echo getStatusBadge($msg['status']); ?>
                                                <br>
                                                <small class="text-muted"><?php echo formatDate($msg['created_at']); ?></small>
                                            </div>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p class="text-muted text-center py-3">No quote requests yet.</p>
                            <?php endif; ?>
                            <a href="messages.php?type=quote" class="btn btn-sm btn-outline-primary mt-3">View All Quotes →</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>