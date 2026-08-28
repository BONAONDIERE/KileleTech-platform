<?php
// 1. SET TIMEZONE TO NAIROBI
date_default_timezone_set('Africa/Nairobi');

session_start();
require_once '../includes/db.php';

// MARK SINGLE MESSAGE AS READ ONLY WHEN CLICKED
if (isset($_GET['mark_read'])) {
    $readId = (int) $_GET['mark_read'];
    try {
        $pdo->prepare("UPDATE contact_messages SET is_read = 1 WHERE id = ?")->execute([$readId]);
    } catch (Exception $e) {
        // Ignore if column doesn't exist
    }
}


if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Set Database Timezone to Nairobi
try {
    $pdo->exec("SET time_zone = '+03:00'");
} catch (Exception $e) {
    // Ignore if unsupported
}

// ------------------------------------------------------------
// MARK ALL MESSAGES AS READ
// ------------------------------------------------------------
try {
    $pdo->exec("UPDATE contact_messages SET is_read = 1 WHERE is_read = 0");
} catch (Exception $e) {
    // Ignore if column doesn't exist
}

// ------------------------------------------------------------
// SEND A NEW MESSAGE (Adds to DB AND sends email)
// ------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'send') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($name === '' || $email === '' || $message === '') {
        header('Location: messages.php?error=' . urlencode('Please fill in all required fields.'));
        exit;
    }

    try {
        // Save to database
        $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, subject, message, is_read) VALUES (?, ?, ?, ?, 0)");
        $stmt->execute([$name, $email, $subject, $message]);

        // ACTUALLY SEND THE EMAIL
        $to = $email;
        $emailSubject = $subject;
        $emailBody = "Hello $name,\n\n$message\n\nBest regards,\nKileleTech Admin";
        $headers = "From: KileleTech <admin@kileletech.com>\r\n";
        $headers .= "Reply-To: admin@kileletech.com\r\n";
        
        @mail($to, $emailSubject, $emailBody, $headers);

        header('Location: messages.php?sent=1');
        exit;
    } catch (PDOException $e) {
        error_log("Message send error: " . $e->getMessage());
        header('Location: messages.php?error=' . urlencode('Could not send message.'));
        exit;
    }
}

// ------------------------------------------------------------
// SEND A REPLY (Adds to DB AND sends email)
// ------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'reply') {
    $messageId = (int) ($_POST['message_id'] ?? 0);
    $replyText = trim($_POST['reply_text'] ?? '');
    $customerEmail = trim($_POST['customer_email'] ?? '');
    $customerSubject = trim($_POST['customer_subject'] ?? '');

    if ($messageId <= 0 || $replyText === '') {
        header('Location: messages.php?error=' . urlencode('Please write a reply first.'));
        exit;
    }

    try {
        // Save reply to database
        $stmt = $pdo->prepare("INSERT INTO message_replies (message_id, reply_text) VALUES (?, ?)");
        $stmt->execute([$messageId, $replyText]);

        // ACTUALLY SEND THE REPLY EMAIL
        $to = $customerEmail;
        $emailSubject = "Re: " . $customerSubject;
        $emailBody = "Hello,\n\n$replyText\n\nBest regards,\nKileleTech Admin";
        $headers = "From: KileleTech <admin@kileletech.com>\r\n";
        $headers .= "Reply-To: admin@kileletech.com\r\n";
        
        @mail($to, $emailSubject, $emailBody, $headers);

        header('Location: messages.php?replied=1');
        exit;
    } catch (PDOException $e) {
        error_log("Reply save error: " . $e->getMessage());
        header('Location: messages.php?error=' . urlencode('Could not save reply.'));
        exit;
    }
}

// ------------------------------------------------------------
// UPDATE / EDIT A MESSAGE
// ------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $id = (int) ($_POST['id'] ?? 0);
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($id <= 0 || $name === '' || $email === '' || $message === '') {
        header('Location: messages.php?error=' . urlencode('Please fill in all required fields.'));
        exit;
    }

    try {
        $stmt = $pdo->prepare("UPDATE contact_messages SET name = ?, email = ?, subject = ?, message = ? WHERE id = ?");
        $stmt->execute([$name, $email, $subject, $message, $id]);
        header('Location: messages.php?updated=1');
        exit;
    } catch (PDOException $e) {
        error_log("Message update error: " . $e->getMessage());
        header('Location: messages.php?error=' . urlencode('Could not update message.'));
        exit;
    }
}

// ------------------------------------------------------------
// DELETE A MESSAGE
// ------------------------------------------------------------
if (isset($_GET['delete'])) {
    $delId = (int) $_GET['delete'];

    try {
        $pdo->prepare("DELETE FROM contact_messages WHERE id = ?")->execute([$delId]);
        header('Location: messages.php?deleted=1');
        exit;
    } catch (PDOException $e) {
        error_log("Message delete error: " . $e->getMessage());
        header('Location: messages.php?error=' . urlencode('Could not delete message.'));
        exit;
    }
}

// ------------------------------------------------------------
// FETCH ALL MESSAGES
// ------------------------------------------------------------
$messages = [];
try {
    $stmt = $pdo->query("SELECT * FROM contact_messages ORDER BY created_at DESC");
    $messages = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log("Messages query error: " . $e->getMessage());
}

// ------------------------------------------------------------
// EDIT: Pre-fill the form if an 'edit' is requested
// ------------------------------------------------------------
$editingMsg = null;
if (isset($_GET['edit'])) {
    $editId = (int) $_GET['edit'];
    foreach ($messages as $msg) {
        if ($msg['id'] == $editId) {
            $editingMsg = $msg;
            break;
        }
    }
}

// ------------------------------------------------------------
// FETCH REPLIES for a specific message (if clicked)
// ------------------------------------------------------------
$repliesForMsg = [];
if (isset($_GET['view_replies'])) {
    $replyMsgId = (int) $_GET['view_replies'];
    try {
        $stmt = $pdo->prepare("SELECT * FROM message_replies WHERE message_id = ? ORDER BY created_at DESC");
        $stmt->execute([$replyMsgId]);
        $repliesForMsg = $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Replies query error: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Messages - KileleTech Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { background: #f8fafb; font-family: 'Poppins', sans-serif; }
        
        /* SIDEBAR */
        .sidebar { background: #ffffff; border-right: 1px solid #e9ecef; min-height: 100vh; width: 250px; position: fixed; left: 0; top: 0; padding-top: 20px; }
        .sidebar .logo { text-align: center; padding-bottom: 20px; border-bottom: 1px solid #f0f0f0; margin-bottom: 20px; }
        .sidebar .logo h4 { color: #0f1e33; font-weight: 700; }
        .sidebar .logo span { color: #29A08E; }
        .sidebar .nav-link { color: #555; padding: 12px 24px; font-weight: 500; transition: 0.3s; border-left: 3px solid transparent; }
        .sidebar .nav-link:hover { background: #f0f9f7; color: #29A08E; border-left-color: #29A08E; }
        .sidebar .nav-link.active { background: #f0f9f7; color: #29A08E; border-left-color: #29A08E; }
        .sidebar .nav-link i { width: 25px; color: #aaa; }
        .sidebar .nav-link:hover i, .sidebar .nav-link.active i { color: #29A08E; }
        
        /* MAIN CONTENT */
        .main-content { margin-left: 250px; padding: 24px; }
        
        /* TOP BAR */
        .top-bar { background: #ffffff; padding: 12px 24px; border-radius: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.04); margin-bottom: 15px; display: flex; justify-content: space-between; align-items: center; }
        .top-bar h5 { font-weight: 700; color: #0f1e33; margin-bottom: 0; }
        .top-bar small { color: #777; }
        
        /* FORM CARD (Compact) */
        .form-card { background: #ffffff; border-radius: 16px; padding: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.04); margin-bottom: 15px; }
        .form-label { font-size: 0.75rem; font-weight: 600; color: #555; margin-bottom: 3px; }
        
        /* TABLE CARD */
        .table-card { background: #ffffff; border-radius: 16px; padding: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.04); }
        .table thead th { border-bottom: 2px solid #f0f0f0; color: #777; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px; }
        .table tbody td { color: #555; vertical-align: middle; font-size: 0.85rem; }
        .table tbody tr:hover { background: #f0f9f7; }
        
        /* STAT BADGE */
        .badge-new { background: #dcfce7; color: #16a34a; padding: 4px 10px; border-radius: 50px; font-size: 0.7rem; font-weight: 600; }
        .badge-read { background: #f0f9f7; color: #777; padding: 4px 10px; border-radius: 50px; font-size: 0.7rem; font-weight: 600; }
        
        /* COMPACT ALERTS */
        .compact-alert { padding: 8px 15px; margin-bottom: 10px; font-size: 0.85rem; border-radius: 8px; }
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
                    <a href="messages.php" class="nav-link active"><i class="fas fa-envelope"></i> Messages</a>
                    <a href="subscribers.php" class="nav-link"><i class="fas fa-users"></i> Subscribers</a>
                    <a href="logout.php" class="nav-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 col-lg-10 main-content">
                <div class="top-bar">
                    <div>
                        <h5 class="mb-0">Contact Messages</h5>
                        <small class="text-muted">Total: <?php echo count($messages); ?> messages</small>
                    </div>
                </div>

                <!-- COMPACT ALERTS -->
                <?php if (isset($_GET['sent'])): ?>
                    <div class="alert alert-success compact-alert">Message sent successfully!</div>
                <?php endif; ?>

                <?php if (isset($_GET['replied'])): ?>
                    <div class="alert alert-success compact-alert">Reply sent successfully!</div>
                <?php endif; ?>

                <?php if (isset($_GET['updated'])): ?>
                    <div class="alert alert-success compact-alert">Message updated successfully.</div>
                <?php endif; ?>

                <?php if (isset($_GET['deleted'])): ?>
                    <div class="alert alert-success compact-alert">Message deleted successfully.</div>
                <?php endif; ?>

                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger compact-alert"><?php echo htmlspecialchars($_GET['error']); ?></div>
                <?php endif; ?>

                <!-- COMPACT SEND / EDIT FORM (Hidden by default) -->
                <div class="form-card" id="sendFormContainer" style="display: <?php echo $editingMsg ? 'block' : 'none'; ?>;">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0"><i class="fas fa-paper-plane me-2" style="color: #29A08E;"></i><?php echo $editingMsg ? 'Edit Message' : 'Send Message'; ?></h6>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="document.getElementById('sendFormContainer').style.display='none';">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    
                    <?php if ($editingMsg): ?>
                        <a href="messages.php" class="btn btn-sm btn-outline-secondary mb-2">Cancel Edit</a>
                    <?php endif; ?>

                    <form method="POST" action="messages.php" class="row g-1">
                        <input type="hidden" name="action" value="<?php echo $editingMsg ? 'update' : 'send'; ?>">
                        <?php if ($editingMsg): ?>
                            <input type="hidden" name="id" value="<?php echo $editingMsg['id']; ?>">
                        <?php endif; ?>

                        <div class="col-md-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control form-control-sm" required value="<?php echo $editingMsg['name'] ?? ''; ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control form-control-sm" required value="<?php echo $editingMsg['email'] ?? ''; ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Subject</label>
                            <input type="text" name="subject" class="form-control form-control-sm" value="<?php echo $editingMsg['subject'] ?? ''; ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Message (brief)</label>
                            <input type="text" name="message" class="form-control form-control-sm" required value="<?php echo $editingMsg['message'] ?? ''; ?>">
                        </div>
                        <div class="col-12 mt-2">
                            <button type="submit" class="btn btn-sm btn-success">
                                <i class="fas fa-paper-plane"></i> <?php echo $editingMsg ? 'Update' : 'Send'; ?>
                            </button>
                            <a href="messages.php" class="btn btn-sm btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>

                <!-- Button to open Send Form -->
                <div class="mb-2">
                    <button class="btn btn-sm btn-primary" onclick="document.getElementById('sendFormContainer').style.display='block';">
                        <i class="fas fa-plus"></i> Send New Message
                    </button>
                </div>

                <!-- VIEW REPLIES SECTION (Compact, shown only if clicked) -->
                <?php if (isset($_GET['view_replies']) && count($repliesForMsg) > 0): ?>
                    <div class="form-card">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0"><i class="fas fa-history me-2 text-info"></i>Replies for Message #<?php echo (int)$_GET['view_replies']; ?></h6>
                            <a href="messages.php" class="btn btn-sm btn-outline-secondary">Close</a>
                        </div>
                        <?php foreach ($repliesForMsg as $reply): ?>
                            <div class="border-bottom py-1" style="font-size: 0.85rem;">
                                <strong class="text-muted"><?php echo date('M j, g:i A', strtotime($reply['created_at'])); ?>:</strong>
                                <?php echo nl2br(htmlspecialchars($reply['reply_text'])); ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- TABLE (Now sits higher!) -->
                <div class="table-card">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Subject</th>
                                <th>Message</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($messages) > 0): ?>
                                <?php foreach ($messages as $msg): ?>
                                    <tr>
                                        <td><?php echo $msg['id']; ?></td>
                                        <td><strong><?php echo htmlspecialchars($msg['name'] ?? 'N/A'); ?></strong></td>
                                        <td><?php echo htmlspecialchars($msg['email']); ?></td>
                                        <td><?php echo htmlspecialchars($msg['subject']); ?></td>
                                        <td><?php echo htmlspecialchars(substr($msg['message'], 0, 40)) . '...'; ?></td>
                                        <td><?php echo date('M j, g:i A', strtotime($msg['created_at'])); ?></td>
                                        <td>
                                            <?php if (isset($msg['is_read']) && $msg['is_read'] == 1): ?>
                                                <span class="badge-read">Read</span>
                                            <?php else: ?>
                                                <span class="badge-new">New</span>
                                            <?php endif; ?>
                                        </td>
                                        <td style="white-space: nowrap;">
                                            <a href="?edit=<?php echo $msg['id']; ?>" class="btn btn-sm btn-primary" title="Edit"><i class="fas fa-edit"></i></a>
                                            
                                            <button type="button" class="btn btn-sm btn-success" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#replyModal"
                                                    data-id="<?php echo $msg['id']; ?>"
                                                    data-email="<?php echo htmlspecialchars($msg['email']); ?>"
                                                    data-name="<?php echo htmlspecialchars($msg['name'] ?? ''); ?>"
                                                    data-subject="<?php echo htmlspecialchars($msg['subject']); ?>">
                                                <i class="fas fa-reply"></i>
                                            </button>

                                            <a href="?view_replies=<?php echo $msg['id']; ?>" class="btn btn-sm btn-info" title="View Replies"><i class="fas fa-history"></i></a>

                                            <a href="messages.php?delete=<?php echo (int) $msg['id']; ?>"
                                               class="btn btn-sm btn-outline-danger"
                                               onclick="return confirm('Delete this message?');">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="8" class="text-center text-muted py-3">No messages yet.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- REPLY MODAL -->
    <div class="modal fade" id="replyModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-reply me-2"></i>Reply to Message</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="messages.php">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="reply">
                        <input type="hidden" name="message_id" id="replyMessageId">
                        <input type="hidden" name="customer_email" id="replyCustomerEmail">
                        <input type="hidden" name="customer_subject" id="replyCustomerSubject">
                        
                        <div class="mb-3">
                            <label class="form-label">To</label>
                            <input type="text" class="form-control" id="replyTo" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Your Reply</label>
                            <textarea class="form-control" name="reply_text" id="replyBody" rows="4" placeholder="Type your reply here..." required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success"><i class="fas fa-paper-plane"></i> Send Reply</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Handle Reply Modal Data
        document.addEventListener('DOMContentLoaded', function() {
            var replyModal = document.getElementById('replyModal');
            replyModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                var id = button.getAttribute('data-id');
                var email = button.getAttribute('data-email');
                var name = button.getAttribute('data-name');
                var subject = button.getAttribute('data-subject');
                
                document.getElementById('replyMessageId').value = id;
                document.getElementById('replyCustomerEmail').value = email;
                document.getElementById('replyCustomerSubject').value = subject;
                document.getElementById('replyTo').value = name + ' <' + email + '>';
                document.getElementById('replyBody').value = '';
            });
        });
    </script>
</body>
</html>