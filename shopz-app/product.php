<?php
require_once 'includes/config.php';

$id = isset($_GET['id']) ? $_GET['id'] : 1;

$query = "SELECT * FROM products WHERE id = $id";
$result = mysqli_query($conn, $query);
$product = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
    $comment = $_POST['comment'];
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
    
    $insert = "INSERT INTO comments (product_id, user_id, content) VALUES ($id, $user_id, '$comment')";
    mysqli_query($conn, $insert);
}

$comments_query = "SELECT c.*, u.username FROM comments c LEFT JOIN users u ON c.user_id = u.id WHERE c.product_id = $id ORDER BY c.created_at DESC";
$comments = mysqli_query($conn, $comments_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $product['name']; ?> - Shopz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="/">Shopz</a>
        </div>
    </nav>

    <div class="container my-5">
        <div class="row">
            <div class="col-md-6">
                <div class="bg-light p-5 text-center">
                    <img src="assets/images/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" class="img-fluid" onerror="this.src='assets/images/placeholder.jpg'">
                </div>
            </div>
            <div class="col-md-6">
                <h1><?php echo $product['name']; ?></h1>
                <p class="lead"><?php echo $product['description']; ?></p>
                <h3 class="text-success">$<?php echo $product['price']; ?></h3>
                <p>Category: <?php echo $product['category']; ?></p>
                <p>In Stock: <?php echo $product['stock']; ?> units</p>
                
                <form action="cart.php" method="POST" class="mt-4">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <input type="hidden" name="price" value="<?php echo $product['price']; ?>">
                    <input type="hidden" name="action" value="add">
                    <div class="mb-3">
                        <label class="form-label">Quantity</label>
                        <input type="number" name="quantity" value="1" min="1" class="form-control w-25">
                    </div>
                    <button type="submit" class="btn btn-success btn-lg">Add to Cart</button>
                </form>
            </div>
        </div>

        <hr class="my-5">

        <h3>Customer Reviews</h3>
        
        <?php if(isset($_SESSION['user_id'])): ?>
        <form method="POST" class="mb-4">
            <div class="mb-3">
                <label class="form-label">Leave a review</label>
                <textarea name="comment" class="form-control" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit Review</button>
        </form>
        <?php else: ?>
        <p><a href="login.php">Login</a> to leave a review</p>
        <?php endif; ?>

        <div class="comments-section">
            <?php while($comment = mysqli_fetch_assoc($comments)): ?>
            <div class="card mb-2">
                <div class="card-body">
                    <strong><?php echo $comment['username'] ? $comment['username'] : 'Anonymous'; ?></strong>
                    <small class="text-muted"> - <?php echo $comment['created_at']; ?></small>
                    <p class="mb-0 mt-2"><?php echo $comment['content']; ?></p>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>

    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container text-center">
            <p>&copy; 2024 Shopz</p>
        </div>
    </footer>
</body>
</html>
