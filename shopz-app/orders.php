<?php
require_once 'includes/config.php';

$order_id = isset($_GET['id']) ? $_GET['id'] : null;

if ($order_id) {
    $query = "SELECT o.*, u.username FROM orders o JOIN users u ON o.user_id = u.id WHERE o.id = $order_id";
    $result = mysqli_query($conn, $query);
    $order = mysqli_fetch_assoc($result);
    
    $items_query = "SELECT oi.*, p.name FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = $order_id";
    $items = mysqli_query($conn, $items_query);
} else {
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }
    $user_id = $_SESSION['user_id'];
    $query = "SELECT * FROM orders WHERE user_id = $user_id ORDER BY created_at DESC";
    $result = mysqli_query($conn, $query);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders - Shopz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="/">Shopz</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="profile.php">Profile</a>
                <a class="nav-link" href="logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <?php if($order_id && $order): ?>
            <h1>Order #<?php echo $order['id']; ?></h1>
            
            <div class="card mb-4">
                <div class="card-body">
                    <p><strong>Customer:</strong> <?php echo $order['username']; ?></p>
                    <p><strong>Status:</strong> <?php echo $order['status']; ?></p>
                    <p><strong>Shipping Address:</strong> <?php echo $order['shipping_address']; ?></p>
                    <p><strong>Notes:</strong> <?php echo $order['notes']; ?></p>
                    <p><strong>Date:</strong> <?php echo $order['created_at']; ?></p>
                    <p><strong>Total:</strong> $<?php echo $order['total']; ?></p>
                </div>
            </div>
            
            <h4>Order Items</h4>
            <table class="table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($item = mysqli_fetch_assoc($items)): ?>
                    <tr>
                        <td><?php echo $item['name']; ?></td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td>$<?php echo $item['price']; ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            
            <a href="orders.php" class="btn btn-secondary">Back to Orders</a>
            <a href="invoice.php?id=<?php echo $order['id']; ?>" class="btn btn-primary">Download Invoice</a>
            
        <?php else: ?>
            <h1>My Orders</h1>
            
            <?php if(mysqli_num_rows($result) == 0): ?>
                <div class="alert alert-info">No orders yet. <a href="/">Start shopping</a></div>
            <?php else: ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Date</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($order = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo $order['id']; ?></td>
                            <td><?php echo $order['created_at']; ?></td>
                            <td>$<?php echo $order['total']; ?></td>
                            <td><?php echo $order['status']; ?></td>
                            <td><a href="orders.php?id=<?php echo $order['id']; ?>" class="btn btn-sm btn-primary">View</a></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</body>
</html>
