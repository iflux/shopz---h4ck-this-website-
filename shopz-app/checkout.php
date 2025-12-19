<?php
require_once 'includes/config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $shipping = isset($_POST['shipping_address']) ? $_POST['shipping_address'] : '';
    $total = isset($_POST['total']) ? floatval($_POST['total']) : 0;
    $notes = isset($_POST['notes']) ? $_POST['notes'] : '';
    
    $insert = "INSERT INTO orders (user_id, total, shipping_address, notes) VALUES ($user_id, $total, '$shipping', '$notes')";
    
    if (mysqli_query($conn, $insert)) {
        $order_id = mysqli_insert_id($conn);
        
        foreach ($_SESSION['cart'] as $product_id => $item) {
            $qty = $item['quantity'];
            $price = $item['price'];
            $item_insert = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES ($order_id, $product_id, $qty, $price)";
            mysqli_query($conn, $item_insert);
        }
        
        $_SESSION['cart'] = array();
        unset($_SESSION['discount']);
        unset($_SESSION['coupon_applied']);
        
        $success = "Order #$order_id placed successfully!";
        
        if ($total < 0) {
            $success .= "<br>FLAG{logic_negative_price}";
        }
    } else {
        $error = "Order failed: " . mysqli_error($conn);
    }
}

$total = 0;
foreach ($_SESSION['cart'] as $product_id => $item) {
    $total += $item['price'] * $item['quantity'];
}

if (isset($_SESSION['discount'])) {
    $total -= $total * ($_SESSION['discount'] / 100);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Shopz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="/">Shopz</a>
        </div>
    </nav>

    <div class="container my-5">
        <h1>Checkout</h1>
        
        <?php if($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
            <a href="/" class="btn btn-primary">Continue Shopping</a>
        <?php elseif($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php else: ?>
        
        <form method="POST">
            <input type="hidden" name="total" value="<?php echo $total; ?>">
            
            <div class="row">
                <div class="col-md-8">
                    <div class="card mb-4">
                        <div class="card-header">Shipping Information</div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Shipping Address</label>
                                <textarea name="shipping_address" class="form-control" rows="3" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Order Notes (optional)</label>
                                <textarea name="notes" class="form-control" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">Order Summary</div>
                        <div class="card-body">
                            <p>Items: <?php echo count($_SESSION['cart']); ?></p>
                            <?php if(isset($_SESSION['discount'])): ?>
                                <p>Discount: <?php echo $_SESSION['discount']; ?>%</p>
                            <?php endif; ?>
                            <h4>Total: $<?php echo number_format($total, 2); ?></h4>
                            <button type="submit" class="btn btn-success w-100 mt-3">Place Order</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        
        <?php endif; ?>
    </div>
</body>
</html>
