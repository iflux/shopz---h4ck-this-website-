<?php
require_once 'includes/config.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';

if ($search) {
    $query = "SELECT * FROM products WHERE name LIKE '%$search%' OR description LIKE '%$search%'";
} elseif ($category) {
    $query = "SELECT * FROM products WHERE category = '$category'";
} else {
    $query = "SELECT * FROM products";
}

$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopz - Your Tech Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .product-card { transition: transform 0.2s; }
        .product-card:hover { transform: translateY(-5px); }
        .hero { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 60px 0; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="/">Shopz</a>
            <div class="navbar-nav ms-auto">
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a class="nav-link" href="profile.php">Profile</a>
                    <a class="nav-link" href="orders.php">My Orders</a>
                    <a class="nav-link" href="cart.php">Cart</a>
                    <a class="nav-link" href="logout.php">Logout</a>
                <?php else: ?>
                    <a class="nav-link" href="login.php">Login</a>
                    <a class="nav-link" href="register.php">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="hero">
        <div class="container text-center">
            <h1>Welcome to Shopz</h1>
            <p>Your one-stop shop for all tech needs</p>
            <form class="d-flex justify-content-center mt-4" method="GET">
                <input type="text" name="search" class="form-control w-50" placeholder="Search products..." value="<?php echo $search; ?>">
                <button type="submit" class="btn btn-light ms-2">Search</button>
            </form>
        </div>
    </div>

    <?php if($search): ?>
    <div class="container mt-3">
        <div class="alert alert-info">
            Search results for: <strong><?php echo $search; ?></strong>
        </div>
    </div>
    <?php endif; ?>

    <div class="container my-5">
        <div class="row mb-4">
            <div class="col">
                <a href="?category=laptops" class="btn btn-outline-primary">Laptops</a>
                <a href="?category=peripherals" class="btn btn-outline-primary">Peripherals</a>
                <a href="?category=monitors" class="btn btn-outline-primary">Monitors</a>
                <a href="?category=components" class="btn btn-outline-primary">Components</a>
                <a href="?category=accessories" class="btn btn-outline-primary">Accessories</a>
                <a href="/" class="btn btn-outline-secondary">All</a>
            </div>
        </div>

        <div class="row">
            <?php while($product = mysqli_fetch_assoc($result)): ?>
            <div class="col-md-4 mb-4">
                <div class="card product-card h-100">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $product['name']; ?></h5>
                        <p class="card-text"><?php echo $product['description']; ?></p>
                        <p class="card-text"><strong>$<?php echo $product['price']; ?></strong></p>
                        <p class="card-text"><small>In stock: <?php echo $product['stock']; ?></small></p>
                        <a href="product.php?id=<?php echo $product['id']; ?>" class="btn btn-primary">View Details</a>
                        <form action="cart.php" method="POST" class="d-inline">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            <input type="hidden" name="action" value="add">
                            <button type="submit" class="btn btn-success">Add to Cart</button>
                        </form>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>

    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container text-center">
            <p>&copy; 2024 Shopz. All rights reserved.</p>
            <p><small>Powered by Apache/2.4.41 - PHP/7.4.3</small></p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        var searchParam = new URLSearchParams(window.location.search).get('search');
        if(searchParam) {
            document.getElementById('search-display').innerHTML = searchParam;
        }
    </script>
</body>
</html>
