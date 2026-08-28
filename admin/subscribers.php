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

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header('Location: subscribers.php?error=' . urlencode('Please enter a valid email address.'));
        exit;
    }

    try {
        $check = $pdo->prepare("SELECT id FROM newsletter_subscribers WHERE email = ?");
        $check->execute([$email]);

        if ($check->fetch()) {
            header('Location: subscribers.php?error=' . urlencode("\"$email\" is already on the list."));
            exit;
        }

        $insert = $pdo->prepare("INSERT INTO newsletter_subscribers (email) VALUES (?)");
        $insert->execute([$email]);

        header('Location: subscribers.php?added=1');
        exit;
    } catch (PDOException $e) {
        error_log("Subscriber add error: " . $e->getMessage());
        header('Location: subscribers.php?error=' . urlencode('Something went wrong. Please try again.'));
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
// FETCH the current list (first paint — live updates take over after)
// ------------------------------------------------------------
$subscribers = [];
try {
    $stmt = $pdo->query("SELECT * FROM newsletter_subscribers ORDER BY subscribed_at DESC");
    $subscribers = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log("Subscribers query error: " . $e->getMessage());
}
$totalSubscribers = count($subscribers);
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
        .live-dot {
            display: inline-block; width: 8px; height: 8px; border-radius: 50%;
            background: #28a745; margin-right: 6px;
            animation: pulse 1.5s infinite;
        }
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(40,167,69,0.5); }
            70% { box-shadow: 0 0 0 8px rgba(40,167,69,0); }
            100% { box-shadow: 0 0 0 0 rgba(40,167,69,0); }
        }
        tr.row-new {
            animation: flashNew 2.5s ease;
        }
        @keyframes flashNew {
            0% { background-color: #d4edda; }
            100% { background-color: transparent; }
        }
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

    <div class="card p-3 shadow-sm mb-4">
        <h6 class="mb-3"><i class="fas fa-user-plus me-2 text-success"></i>Add Subscriber</h6>
        <form method="POST" action="subscribers.php" class="row g-2">
            <input type="hidden" name="action" value="add">
            <div class="col-sm-8">
                <input type="email" name="email" class="form-control" placeholder="email@example.com" required>
            </div>
            <div class="col-sm-4">
                <button type="submit" class="btn btn-success w-100">Add</button>
            </div>
        </form>
    </div>

    <div class="card p-3 shadow-sm">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h5 class="mb-0"><i class="fas fa-list me-2 text-success"></i>All Subscribers (<span id="subCount"><?php echo $totalSubscribers; ?></span>)</h5>
            <span style="font-size: 0.8rem; color: #888;"><span class="live-dot"></span>Live</span>
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
            <tbody id="subscribersBody">
                <?php if ($totalSubscribers > 0): ?>
                    <?php foreach ($subscribers as $sub): ?>
                        <tr data-id="<?php echo (int) $sub['id']; ?>">
                            <td><?php echo htmlspecialchars($sub['id']); ?></td>
                            <td><?php echo htmlspecialchars($sub['email']); ?></td>
                            <td><?php echo htmlspecialchars($sub['status'] ?? 'active'); ?></td>
                            <td><?php echo htmlspecialchars($sub['subscribed_at']); ?></td>
                            <td>
                                <a href="subscribers.php?delete=<?php echo (int) $sub['id']; ?>"
                                   class="btn btn-sm btn-outline-danger"
                                   onclick="return confirm('Delete <?php echo htmlspecialchars($sub['email'], ENT_QUOTES); ?>?');">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr id="emptyRow">
                        <td colspan="5" class="text-center py-4">No subscribers yet.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
// ============================================================
// LIVE UPDATES — polls ajax-subscribers.php every 5 seconds and
// patches the table in place, so new signups appear without a
// manual page refresh. Uses polling (not WebSockets) since it
// needs no special server setup and works on any PHP host.
// ============================================================
let knownIds = new Set(
    Array.from(document.querySelectorAll('#subscribersBody tr[data-id]'))
        .map(tr => tr.getAttribute('data-id'))
);

function escapeHtml(str) {
    const div = document.createElement('div');
    div.textContent = str;
    return div.innerHTML;
}

async function pollSubscribers() {
    try {
        const res = await fetch('ajax-subscribers.php', { cache: 'no-store' });
        const data = await res.json();
        if (!data.success) return;

        document.getElementById('subCount').textContent = data.total;

        const tbody = document.getElementById('subscribersBody');
        const currentIds = new Set(data.subscribers.map(s => String(s.id)));

        // Remove rows that no longer exist (deleted elsewhere)
        tbody.querySelectorAll('tr[data-id]').forEach(tr => {
            if (!currentIds.has(tr.getAttribute('data-id'))) {
                tr.remove();
            }
        });

        // Remove "no subscribers" placeholder if we now have data
        const emptyRow = document.getElementById('emptyRow');
        if (data.subscribers.length > 0 && emptyRow) {
            emptyRow.remove();
        }

        // Add any brand-new subscribers at the top, highlighted
        data.subscribers.forEach(sub => {
            const idStr = String(sub.id);
            if (!knownIds.has(idStr)) {
                const tr = document.createElement('tr');
                tr.setAttribute('data-id', idStr);
                tr.className = 'row-new';
                tr.innerHTML = `
                    <td>${escapeHtml(String(sub.id))}</td>
                    <td>${escapeHtml(sub.email)}</td>
                    <td>${escapeHtml(sub.status || 'active')}</td>
                    <td>${escapeHtml(sub.subscribed_at)}</td>
                    <td>
                        <a href="subscribers.php?delete=${sub.id}" class="btn btn-sm btn-outline-danger"
                           onclick="return confirm('Delete ${escapeHtml(sub.email)}?');">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                `;
                tbody.prepend(tr);
                knownIds.add(idStr);
            }
        });
    } catch (err) {
        console.error('Live update failed:', err);
    }
}

// Poll every 5 seconds. Pause polling when the tab isn't visible,
// to avoid wasting requests when nobody's actually looking.
let pollTimer = setInterval(pollSubscribers, 5000);

document.addEventListener('visibilitychange', () => {
    if (document.hidden) {
        clearInterval(pollTimer);
    } else {
        pollSubscribers(); // catch up immediately on return
        pollTimer = setInterval(pollSubscribers, 5000);
    }
});
</script>
</body>
</html>