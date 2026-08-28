<?php
// NO HEADER INCLUDED – THIS WILL NEVER CRASH
$host = 'localhost';
$dbname = 'kilelete_kilele_tech';
$username = 'kilelete_admin'; 
$password = 'Kilele2023!!'; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get product ID (default to 1)
    $product_id = isset($_GET['id']) ? (int)$_GET['id'] : 1;
    
    // Fetch the product
    $stmt = $pdo->prepare("SELECT * FROM market_products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();

    // If not found, get first product
    if (!$product) {
        $product = $pdo->query("SELECT * FROM market_products ORDER BY id ASC LIMIT 1")->fetch();
    }
} catch (Exception $e) {
    die("Database Connection Error: " . $e->getMessage());
}

// Handle Order
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    $customer_name = trim($_POST['customer_name'] ?? '');
    $customer_email = trim($_POST['customer_email'] ?? '');
    $customer_phone = trim($_POST['customer_phone'] ?? '');
    $quantity = (int)($_POST['quantity'] ?? 1);
    $product_id = (int)$_POST['product_id'];

    if (empty($customer_name) || empty($customer_email) || empty($customer_phone)) {
        $error = "Please fill in all required fields.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO market_orders (product_id, customer_name, customer_email, customer_phone, quantity) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$product_id, $customer_name, $customer_email, $customer_phone, $quantity]);
            $success = "Order placed successfully! We will contact you shortly.";
        } catch (Exception $e) {
            $error = "Could not place order. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details - KileleTech</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f4f6f8; font-family: Arial, sans-serif; }
        .navbar { background: #0f1e33; }
        .navbar-brand { color: #fff !important; font-weight: bold; }
        .nav-link { color: rgba(255,255,255,0.8) !important; }
        .nav-link:hover { color: #29A08E !important; }
        
        .detail-hero { background: #0f1e33; padding: 40px 0; text-align: center; }
        .detail-hero h1 { color: #fff; font-size: 2rem; font-weight: 800; }
        
        .product-image { font-size: 4rem; color: #29A08E; text-align: center; margin-bottom: 20px; }
        .price-tag { font-size: 1.8rem; font-weight: 800; color: #0f1e33; }
        .price-tag small { font-size: 1rem; color: #29A08E; }
        
        .order-form { background: #fff; border-radius: 16px; padding: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .btn-order { background: #29A08E; color: #fff; padding: 12px 30px; border-radius: 50px; font-weight: 700; border: none; width: 100%; }
        .btn-order:hover { background: #1e7a6b; }
    </style>
</head>
<body>
    <!-- SIMPLE NAVBAR -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="index.php"><i class="fas fa-microchip me-2"></i>KileleTech</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navMenu">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="market.php">Market</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- HERO -->
    <section class="detail-hero">
        <div class="container">
            <a href="market.php" style="color: #29A08E; text-decoration: none;"><i class="fas fa-arrow-left me-2"></i>Back to Market</a>
            <h1 class="mt-3"><?php echo htmlspecialchars($product['product_name']); ?></h1>
            <p style="color: rgba(255,255,255,0.8);"><?php echo htmlspecialchars($product['category']); ?></p>
        </div>
    </section>

    <section style="padding: 40px 0;">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-6">
                    <div class="product-image"><i class="fas fa-microchip"></i></div>
                    <h3><?php echo htmlspecialchars($product['product_name']); ?></h3>
                    <p style="color: #777;">High quality component for your ICT needs.</p>
                    
                    <div class="mt-4">
                        <div class="price-tag"><small>KES</small> <?php echo number_format($product['price']); ?></div>
                        <p style="color: <?php echo $product['stock'] > 0 ? '#28a745' : '#dc3545'; ?>; font-weight: 600; margin-top: 10px;">
                            <i class="fas fa-circle me-2" style="font-size: 0.7rem;"></i>
                            <?php echo $product['stock'] > 0 ? 'In Stock (' . $product['stock'] . ' available)' : 'Out of Stock'; ?>
                        </p>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="order-form">
                        <h4 style="font-weight: 700; margin-bottom: 20px;">Place Your Order</h4>
                        
                        <?php if (isset($success)): ?>
                            <div class="alert alert-success"><?php echo $success; ?></div>
                        <?php endif; ?>
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>

                        <form method="POST">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            <input type="hidden" name="place_order" value="1">
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Full Name *</label>
                                <input type="text" name="customer_name" class="form-control" placeholder="Your name" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Email Address *</label>
                                <input type="email" name="customer_email" class="form-control" placeholder="you@example.com" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Phone Number *</label>
                                <input type="tel" name="customer_phone" class="form-control" placeholder="+254 7..." required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Quantity</label>
                                <select name="quantity" class="form-control">
                                    <?php for ($i = 1; $i <= 10; $i++): ?>
                                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            
                            <button type="submit" class="btn-order">
                                <i class="fas fa-check-circle me-2"></i> Place Order
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>