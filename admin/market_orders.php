<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// ------------------------------------------------------------
// ADD NEW ORDER
// ------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_order'])) {
    $product_id = (int) ($_POST['product_id'] ?? 0);
    $customer_name = trim($_POST['customer_name'] ?? '');
    $customer_email = trim($_POST['customer_email'] ?? '');
    $customer_phone = trim($_POST['customer_phone'] ?? '');
    $quantity = (int) ($_POST['quantity'] ?? 1);

    if ($product_id <= 0 || $customer_name === '' || $customer_email === '') {
        header('Location: market_orders.php?error=' . urlencode('Please fill in all required fields.'));
        exit;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO market_orders (product_id, customer_name, customer_email, customer_phone, quantity) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$product_id, $customer_name, $customer_email, $customer_phone, $quantity]);
        header('Location: market_orders.php?added=1');
        exit;
    } catch (PDOException $e) {
        error_log("Order add error: " . $e->getMessage());
        header('Location: market_orders.php?error=' . urlencode('Could not add order.'));
        exit;
    }
}

// ------------------------------------------------------------
// UPDATE / EDIT ORDER
// ------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_order'])) {
    $id = (int) ($_POST['id'] ?? 0);
    $product_id = (int) ($_POST['product_id'] ?? 0);
    $customer_name = trim($_POST['customer_name'] ?? '');
    $customer_email = trim($_POST['customer_email'] ?? '');
    $customer_phone = trim($_POST['customer_phone'] ?? '');
    $quantity = (int) ($_POST['quantity'] ?? 1);

    if ($id <= 0 || $product_id <= 0 || $customer_name === '' || $customer_email === '') {
        header('Location: market_orders.php?error=' . urlencode('Please fill in all required fields.'));
        exit;
    }

    try {
        $stmt = $pdo->prepare("UPDATE market_orders SET product_id = ?, customer_name = ?, customer_email = ?, customer_phone = ?, quantity = ? WHERE id = ?");
        $stmt->execute([$product_id, $customer_name, $customer_email, $customer_phone, $quantity, $id]);
        header('Location: market_orders.php?updated=1');
        exit;
    } catch (PDOException $e) {
        error_log("Order update error: " . $e->getMessage());
        header('Location: market_orders.php?error=' . urlencode('Could not update order.'));
        exit;
    }
}

// ------------------------------------------------------------
// DELETE ORDER
// ------------------------------------------------------------
if (isset($_GET['delete'])) {
    $delId = (int) $_GET['delete'];
    $pdo->prepare("DELETE FROM market_orders WHERE id = ?")->execute([$delId]);
    header('Location: market_orders.php?deleted=1');
    exit;
}

// ------------------------------------------------------------
// FETCH ALL ORDERS
// ------------------------------------------------------------
$orders = [];
try {
    $stmt = $pdo->query("
        SELECT mo.*, mp.product_name, mp.price 
        FROM market_orders mo
        LEFT JOIN market_products mp ON mo.product_id = mp.id
        ORDER BY mo.created_at DESC
    ");
    $orders = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log("Orders query error: " . $e->getMessage());
}

// ------------------------------------------------------------
// FETCH ALL PRODUCTS (For Dropdown)
// ------------------------------------------------------------
$products = $pdo->query("SELECT * FROM market_products ORDER BY product_name ASC")->fetchAll();

// ------------------------------------------------------------
// EDIT: PRE-FILL THE FORM
// ------------------------------------------------------------
$editingOrder = null;
if (isset($_GET['edit'])) {
    $editId = (int) $_GET['edit'];
    foreach ($orders as $order) {
        if ($order['id'] == $editId) {
            $editingOrder = $order;
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
    <title>Market Orders – KileleTech Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f8fafb; font-family: 'Poppins', sans-serif; }
        .sidebar { background: #ffffff; border-right: 1px solid #e9ecef; min-height: 100vh; width: 250px; position: fixed; left: 0; top: 0; padding: 20px; }
        .sidebar h5 { font-weight: 700; color: #0f1e33; }
        .sidebar .nav-link { color: #555; padding: 10px 15px; border-radius: 8px; }
        .sidebar .nav-link.active { background: #f0f9f7; color: #29A08E; border-left: 3px solid #29A08E; }
        .main-content { margin-left: 250px; padding: 20px; }
        .top-bar { background: #fff; padding: 15px; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.05); margin-bottom: 20px; }
        .card { background: #fff; border-radius: 16px; padding: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.04); }
        .form-card { background: #fff; border-radius: 16px; padding: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.04); margin-bottom: 20px; }
        .compact-alert { padding: 6px 12px; margin-bottom: 10px; font-size: 0.85rem; border-radius: 6px; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h5>KileleTech Admin</h5>
        <nav class="nav flex-column mt-3">
            <a href="dashboard.php" class="nav-link"><i class="fas fa-th-large me-2"></i> Dashboard</a>
            <a href="kilele_market.php" class="nav-link"><i class="fas fa-shopping-cart me-2"></i> Market Products</a>
            <a href="market_orders.php" class="nav-link active"><i class="fas fa-receipt me-2"></i> Market Orders</a>
            <a href="logout.php" class="nav-link"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
        </nav>
    </div>

    <div class="main-content">
        <div class="top-bar">
            <h4>Market Orders</h4>
        </div>

        <!-- ALERTS -->
        <?php if (isset($_GET['added'])): ?>
            <div class="alert alert-success compact-alert">Order added successfully.</div>
        <?php endif; ?>
        <?php if (isset($_GET['updated'])): ?>
            <div class="alert alert-success compact-alert">Order updated successfully.</div>
        <?php endif; ?>
        <?php if (isset($_GET['deleted'])): ?>
            <div class="alert alert-success compact-alert">Order deleted successfully.</div>
        <?php endif; ?>
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger compact-alert"><?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>

        <!-- SUPER SLIM ADD / EDIT FORM -->
        <div class="form-card">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="mb-0"><i class="fas fa-plus-circle text-primary me-2"></i> <?php echo $editingOrder ? 'Edit Order' : 'Add New Order'; ?></h6>
                <?php if ($editingOrder): ?>
                    <a href="market_orders.php" class="btn btn-sm btn-outline-secondary">Cancel Edit</a>
                <?php endif; ?>
            </div>

            <form method="POST" class="row g-1 align-items-center">
                <input type="hidden" name="id" value="<?php echo $editingOrder['id'] ?? ''; ?>">
                
                <div class="col-md-2">
                    <select name="product_id" class="form-select form-select-sm" required>
                        <option value="">Product</option>
                        <?php foreach ($products as $prod): ?>
                            <option value="<?php echo $prod['id']; ?>" <?php echo ($editingOrder['product_id'] ?? '') == $prod['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($prod['product_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="text" name="customer_name" class="form-control form-control-sm" placeholder="Customer Name" required value="<?php echo $editingOrder['customer_name'] ?? ''; ?>">
                </div>
                <div class="col-md-2">
                    <input type="email" name="customer_email" class="form-control form-control-sm" placeholder="Email" required value="<?php echo $editingOrder['customer_email'] ?? ''; ?>">
                </div>
                <div class="col-md-2">
                    <input type="text" name="customer_phone" class="form-control form-control-sm" placeholder="Phone" value="<?php echo $editingOrder['customer_phone'] ?? ''; ?>">
                </div>
                <div class="col-md-1">
                    <input type="number" name="quantity" class="form-control form-control-sm" placeholder="Qty" min="1" required value="<?php echo $editingOrder['quantity'] ?? 1; ?>">
                </div>
                <div class="col-md-3">
                    <button type="submit" name="<?php echo $editingOrder ? 'update_order' : 'add_order'; ?>" class="btn btn-sm btn-success w-100">
                        <i class="fas fa-save"></i> <?php echo $editingOrder ? 'Update' : 'Add'; ?>
                    </button>
                </div>
            </form>
        </div>

        <!-- ORDERS TABLE -->
        <div class="card">
            <h6 class="fw-bold mb-3"><i class="fas fa-receipt me-2"></i> Customer Orders</h6>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th>Customer</th>
                        <th>Contact</th>
                        <th>Qty</th>
                        <th>Total</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($orders) > 0): ?>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td><?php echo $order['id']; ?></td>
                                <td><strong><?php echo htmlspecialchars($order['product_name'] ?? 'Unknown'); ?></strong></td>
                                <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                                <td>
                                    <?php echo htmlspecialchars($order['customer_email']); ?><br>
                                    <small class="text-muted"><?php echo htmlspecialchars($order['customer_phone']); ?></small>
                                </td>
                                <td><?php echo $order['quantity']; ?></td>
                                <td><strong style="color: #29A08E;">KES <?php echo number_format($order['price'] * $order['quantity']); ?></strong></td>
                                <td><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                                <td style="white-space: nowrap;">
                                    <a href="?edit=<?php echo $order['id']; ?>" class="btn btn-primary btn-sm" title="Edit"><i class="fas fa-edit"></i></a>
                                    <a href="market_orders.php?delete=<?php echo (int) $order['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this order?')"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">No orders yet.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>