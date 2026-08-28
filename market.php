<?php
// NO HEADER INCLUDED – THIS WILL NEVER CRASH
$host = 'localhost';
$dbname = 'kilelete_kilele_tech';
$username = 'kilelete_admin'; 
$password = 'Kilele2023!!'; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $products = $pdo->query("SELECT * FROM market_products ORDER BY created_at DESC")->fetchAll();
} catch (Exception $e) {
    die("Database Connection Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kilele Market - KileleTech</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f4f6f8; font-family: Arial, sans-serif; }
        .navbar { background: #0f1e33; }
        .navbar-brand { color: #fff !important; font-weight: bold; }
        .nav-link { color: rgba(255,255,255,0.8) !important; }
        .nav-link:hover { color: #29A08E !important; }
        
        .market-hero {
            background: linear-gradient(135deg, #0f1e33 0%, #1a2d47 100%);
            padding: 50px 0 40px;
            text-align: center;
        }
        .market-hero h1 { color: #ffffff; font-size: 2.5rem; font-weight: 800; }
        .market-hero p { color: rgba(255,255,255,0.7); }
        
        .market-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            transition: 0.3s;
            height: 100%;
        }
        .market-card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
        .market-icon { font-size: 2rem; color: #29A08E; margin-bottom: 15px; }
        
        .action-btn {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.75rem;
            text-decoration: none;
            transition: 0.3s;
            margin-top: 10px;
        }
        .action-btn.primary { background: #29A08E; color: #fff; }
        .action-btn.primary:hover { background: #1e7a6b; }
        .action-btn.outline { background: #fff; color: #29A08E; border: 1px solid #29A08E; }
        .action-btn.outline:hover { background: #29A08E; color: #fff; }
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
    <section class="market-hero">
        <div class="container">
            <h1>Kilele Market</h1>
            <p>Quality ICT components at unbeatable prices.</p>
        </div>
    </section>

    <!-- PRODUCTS -->
    <section style="padding: 40px 0;">
        <div class="container">
            <div class="row g-4">
                <?php if (count($products) > 0): ?>
                    <?php foreach ($products as $product): ?>
                        <div class="col-md-6 col-lg-3">
                            <div class="market-card">
                                <div class="market-icon"><i class="fas fa-microchip"></i></div>
                                <h5 style="font-weight: 700;"><?php echo htmlspecialchars($product['product_name']); ?></h5>
                                <p style="color: #777; font-size: 0.9rem;"><?php echo htmlspecialchars($product['category']); ?></p>
                                <h6 style="color: #29A08E; font-weight: 800;">KES <?php echo number_format($product['price']); ?></h6>
                                <div>
                                    <a href="market-details.php?id=<?php echo (int)$product['id']; ?>" class="action-btn primary"><i class="fas fa-shopping-cart me-1"></i> Order Now</a>
                                    <a href="contact.php" class="action-btn outline">Ask</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center"><p>No products yet.</p></div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>