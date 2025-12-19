<?php
require_once 'includes/config.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    
    if ($action === 'add') {
        $product_id = $_POST['product_id'];
        $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
        $price = isset($_POST['price']) ? floatval($_POST['price']) : 0;
        
        if ($price == 0) {
            $query = "SELECT price FROM products WHERE id = $product_id";
            $result = mysqli_query($conn, $query);
            $row = mysqli_fetch_assoc($result);
            $price = $row['price'];
        }
        
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$product_id] = array(
                'quantity' => $quantity,
                'price' => $price
            );
        }
    }
    
    if ($action === 'update') {
        $product_id = $_POST['product_id'];
        $quantity = intval($_POST['quantity']);
        $price = isset($_POST['price']) ? floatval($_POST['price']) : $_SESSION['cart'][$product_id]['price'];
        
        if ($quantity <= 0) {
            unset($_SESSION['cart'][$product_id]);
        } else {
            $_SESSION['cart'][$product_id]['quantity'] = $quantity;
            $_SESSION['cart'][$product_id]['price'] = $price;
        }
    }
    
    if ($action === 'remove') {
        $product_id = $_POST['product_id'];
        unset($_SESSION['cart'][$product_id]);
    }
    
    if ($action === 'apply_coupon') {
        $coupon_code = $_POST['coupon_code'];
        $query = "SELECT * FROM coupons WHERE code = '$coupon_code'";
        $result = mysqli_query($conn, $query);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $coupon = mysqli_fetch_assoc($result);
            $_SESSION['discount'] = $coupon['discount'];
            $_SESSION['coupon_applied'] = $coupon_code;
        }
    }
}

$total = 0;
$cart_items = array();

foreach ($_SESSION['cart'] as $product_id => $item) {
    $query = "SELECT * FROM products WHERE id = $product_id";
    $result = mysqli_query($conn, $query);
    $product = mysqli_fetch_assoc($result);
    
    $subtotal = $item['price'] * $item['quantity'];
    $total += $subtotal;
    
    $cart_items[] = array(
        'product' => $product,
        'quantity' => $item['quantity'],
        'price' => $item['price'],
        'subtotal' => $subtotal
    );
}

if (isset($_SESSION['discount'])) {
    $discount_amount = $total * ($_SESSION['discount'] / 100);
    $total -= $discount_amount;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - Shopz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="/">Shopz</a>
        </div>
    </nav>

    <div class="container my-5">
        <h1>Shopping Cart</h1>
        
        <?php if(empty($cart_items)): ?>
            <div class="alert alert-info">Your cart is empty. <a href="/">Continue shopping</a></div>
        <?php else: ?>
        
        <table class="table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($cart_items as $item): ?>
                <tr>
                    <td><?php echo $item['product']['name']; ?></td>
                    <td>$<?php echo number_format($item['price'], 2); ?></td>
                    <td>
                        <form method="POST" class="d-inline">
                            <input type="hidden" name="action" value="update">
                            <input type="hidden" name="product_id" value="<?php echo $item['product']['id']; ?>">
                            <input type="hidden" name="price" value="<?php echo $item['price']; ?>">
                            <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" class="form-control d-inline w-auto" style="width: 80px !important;">
                            <button type="submit" class="btn btn-sm btn-secondary">Update</button>
                        </form>
                    </td>
                    <td>$<?php echo number_format($item['subtotal'], 2); ?></td>
                    <td>
                        <form method="POST" class="d-inline">
                            <input type="hidden" name="action" value="remove">
                            <input type="hidden" name="product_id" value="<?php echo $item['product']['id']; ?>">
                            <button type="submit" class="btn btn-sm btn-danger">Remove</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5>Apply Coupon</h5>
                        <form method="POST">
                            <input type="hidden" name="action" value="apply_coupon">
                            <div class="input-group">
                                <input type="text" name="coupon_code" class="form-control" placeholder="Enter coupon code">
                                <button type="submit" class="btn btn-primary">Apply</button>
                            </div>
                        </form>
                        <?php if(isset($_SESSION['coupon_applied'])): ?>
                            <p class="text-success mt-2">Coupon "<?php echo $_SESSION['coupon_applied']; ?>" applied! <?php echo $_SESSION['discount']; ?>% off</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5>Order Summary</h5>
                        <?php if(isset($_SESSION['discount'])): ?>
                            <p>Discount: -$<?php echo number_format($discount_amount, 2); ?></p>
                        <?php endif; ?>
                        <h4>Total: $<?php echo number_format($total, 2); ?></h4>
                        <a href="checkout.php" class="btn btn-success btn-lg w-100">Proceed to Checkout</a>
                    </div>
                </div>
            </div>
        </div>
        
        <?php endif; ?>
    </div>
</body>
</html>
