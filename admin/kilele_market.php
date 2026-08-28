<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// ------------------------------------------------------------
// ADD NEW PRODUCT
// ------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $name = trim($_POST['product_name'] ?? '');
    $price = trim($_POST['price'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $stock = (int)($_POST['stock'] ?? 0);

    if (empty($name) || empty($price)) {
        header('Location: kilele_market.php?error=' . urlencode('Product name and price are required.'));
        exit;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO market_products (product_name, price, category, stock) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $price, $category, $stock]);
        header('Location: kilele_market.php?added=1');
        exit;
    } catch (PDOException $e) {
        error_log("Product add error: " . $e->getMessage());
        header('Location: kilele_market.php?error=' . urlencode('Could not add product.'));
        exit;
    }
}

// ------------------------------------------------------------
// UPDATE / EDIT PRODUCT
// ------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_product'])) {
    $id = (int) ($_POST['id'] ?? 0);
    $name = trim($_POST['product_name'] ?? '');
    $price = trim($_POST['price'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $stock = (int)($_POST['stock'] ?? 0);

    if ($id <= 0 || $name === '' || $price === '') {
        header('Location: kilele_market.php?error=' . urlencode('Product name and price are required.'));
        exit;
    }

    try {
        $stmt = $pdo->prepare("UPDATE market_products SET product_name = ?, price = ?, category = ?, stock = ? WHERE id = ?");
        $stmt->execute([$name, $price, $category, $stock, $id]);
        header('Location: kilele_market.php?updated=1');
        exit;
    } catch (PDOException $e) {
        error_log("Product update error: " . $e->getMessage());
        header('Location: kilele_market.php?error=' . urlencode('Could not update product.'));
        exit;
    }
}

// ------------------------------------------------------------
// DELETE PRODUCT
// ------------------------------------------------------------
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];

    try {
        $pdo->prepare("DELETE FROM market_products WHERE id = ?")->execute([$id]);
        header('Location: kilele_market.php?deleted=1');
        exit;
    } catch (PDOException $e) {
        error_log("Product delete error: " . $e->getMessage());
        header('Location: kilele_market.php?error=' . urlencode('Could not delete product.'));
        exit;
    }
}

// ------------------------------------------------------------
// FETCH ALL PRODUCTS
// ------------------------------------------------------------
$products = [];
try {
    $stmt = $pdo->query("SELECT * FROM market_products ORDER BY created_at DESC");
    $products = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log("Products query error: " . $e->getMessage());
}

// ------------------------------------------------------------
// EDIT: PRE-FILL THE FORM
// ------------------------------------------------------------
$editingProduct = null;
if (isset($_GET['edit'])) {
    $editId = (int) $_GET['edit'];
    foreach ($products as $p) {
        if ($p['id'] == $editId) {
            $editingProduct = $p;
            break;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Kilele Market Admin – KileleTech</title>
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
        
        .price-text { color: #29A08E; font-weight: 700; }
        .stock-badge { padding: 4px 12px; border-radius: 50px; font-size: 0.75rem; font-weight: 700; }
        .stock-low { background: #fff3cd; color: #856404; }
        .stock-mid { background: #d4edda; color: #155724; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h5>KileleTech Admin</h5>
        <nav class="nav flex-column mt-3">
            <a href="dashboard.php" class="nav-link"><i class="fas fa-th-large me-2"></i> Dashboard</a>
            <a href="kilele_market.php" class="nav-link active"><i class="fas fa-shopping-cart me-2"></i> Kilele Market</a>
            <a href="logout.php" class="nav-link"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
        </nav>
    </div>

    <div class="main-content">
        <div class="top-bar">
            <h4>Kilele Market Inventory</h4>
        </div>

        <!-- ALERTS -->
        <?php if (isset($_GET['added'])): ?>
            <div class="alert alert-success compact-alert">Product added successfully.</div>
        <?php endif; ?>
        <?php if (isset($_GET['updated'])): ?>
            <div class="alert alert-success compact-alert">Product updated successfully.</div>
        <?php endif; ?>
        <?php if (isset($_GET['deleted'])): ?>
            <div class="alert alert-success compact-alert">Product deleted successfully.</div>
        <?php endif; ?>
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger compact-alert"><?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>

        <!-- SUPER SLIM ADD / EDIT FORM -->
        <div class="form-card">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="mb-0"><i class="fas fa-plus-circle text-primary me-2"></i> <?php echo $editingProduct ? 'Edit Product' : 'Add New Product'; ?></h6>
                <?php if ($editingProduct): ?>
                    <a href="kilele_market.php" class="btn btn-sm btn-outline-secondary">Cancel Edit</a>
                <?php endif; ?>
            </div>

            <form method="POST" class="row g-1 align-items-center">
                <input type="hidden" name="id" value="<?php echo $editingProduct['id'] ?? ''; ?>">
                
                <div class="col-md-3">
                    <input type="text" name="product_name" class="form-control form-control-sm" placeholder="Product Name" required value="<?php echo $editingProduct['product_name'] ?? ''; ?>">
                </div>
                <div class="col-md-2">
                    <input type="number" step="0.01" name="price" class="form-control form-control-sm" placeholder="Price (KES)" required value="<?php echo $editingProduct['price'] ?? ''; ?>">
                </div>
                <div class="col-md-2">
                    <input type="text" name="category" class="form-control form-control-sm" placeholder="Category" value="<?php echo $editingProduct['category'] ?? ''; ?>">
                </div>
                <div class="col-md-2">
                    <input type="number" name="stock" class="form-control form-control-sm" placeholder="Stock" value="<?php echo $editingProduct['stock'] ?? ''; ?>">
                </div>
                <div class="col-md-3">
                    <button type="submit" name="<?php echo $editingProduct ? 'update_product' : 'add_product'; ?>" class="btn btn-sm btn-success w-100">
                        <i class="fas fa-save"></i> <?php echo $editingProduct ? 'Update' : 'Add'; ?>
                    </button>
                </div>
            </form>
        </div>

        <!-- PRODUCT INVENTORY TABLE -->
        <div class="card">
            <h6 class="fw-bold mb-3"><i class="fas fa-box me-2"></i> Product Inventory</h6>
            <table class="table table-hover">
                <thead><tr><th>ID</th><th>Product</th><th>Price</th><th>Category</th><th>Stock</th><th>Date</th><th>Action</th></tr></thead>
                <tbody>
                    <?php foreach ($products as $p): ?>
                    <tr>
                        <td><?php echo $p['id']; ?></td>
                        <td><strong><?php echo htmlspecialchars($p['product_name']); ?></strong></td>
                        <td class="price-text">KES <?php echo number_format($p['price']); ?></td>
                        <td><?php echo htmlspecialchars($p['category']); ?></td>
                        <td>
                            <?php if ($p['stock'] <= 5): ?>
                                <span class="stock-badge stock-low">Low: <?php echo $p['stock']; ?></span>
                            <?php else: ?>
                                <span class="stock-badge stock-mid"><?php echo $p['stock']; ?></span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo date('M d, Y', strtotime($p['created_at'])); ?></td>
                        <td style="white-space: nowrap;">
                            <a href="?edit=<?php echo $p['id']; ?>" class="btn btn-primary btn-sm" title="Edit"><i class="fas fa-edit"></i></a>
                            <a href="kilele_market.php?delete=<?php echo $p['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this product?')"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>