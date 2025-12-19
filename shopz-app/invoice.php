<?php
require_once 'includes/config.php';

$id = isset($_GET['id']) ? $_GET['id'] : 0;

$query = "SELECT o.*, u.username, u.email, u.address as user_address FROM orders o JOIN users u ON o.user_id = u.id WHERE o.id = $id";
$result = mysqli_query($conn, $query);
$order = mysqli_fetch_assoc($result);

if (!$order) {
    die("Invoice not found");
}

$items_query = "SELECT oi.*, p.name FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = $id";
$items = mysqli_query($conn, $items_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice #<?php echo $id; ?> - Shopz</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; }
        .header { border-bottom: 2px solid #333; padding-bottom: 20px; }
        .invoice-info { margin: 20px 0; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background: #f5f5f5; }
        .total { font-size: 1.5em; text-align: right; margin-top: 20px; }
        .flag { color: #999; font-size: 0.8em; }
    </style>
</head>
<body>
    <div class="header">
        <h1>INVOICE</h1>
        <p>Shopz E-Commerce</p>
    </div>
    
    <div class="invoice-info">
        <p><strong>Invoice #:</strong> <?php echo $order['id']; ?></p>
        <p><strong>Date:</strong> <?php echo $order['created_at']; ?></p>
        <p><strong>Status:</strong> <?php echo $order['status']; ?></p>
    </div>
    
    <div class="invoice-info">
        <h3>Bill To:</h3>
        <p><?php echo $order['username']; ?></p>
        <p><?php echo $order['email']; ?></p>
        <p><?php echo $order['shipping_address']; ?></p>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php while($item = mysqli_fetch_assoc($items)): ?>
            <tr>
                <td><?php echo $item['name']; ?></td>
                <td><?php echo $item['quantity']; ?></td>
                <td>$<?php echo number_format($item['price'], 2); ?></td>
                <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    
    <div class="total">
        <strong>Total: $<?php echo number_format($order['total'], 2); ?></strong>
    </div>
    
    <p class="flag">FLAG{idor_invoice_stolen}</p>
    
    <p style="margin-top: 50px; color: #666;">Thank you for your business!</p>
</body>
</html>
