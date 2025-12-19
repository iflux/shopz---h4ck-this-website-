<?php
require_once 'includes/config.php';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        
        $timestamp = time();
        $token = md5($email . $timestamp);
        
        $update = "UPDATE users SET reset_token = '$token' WHERE id = " . $user['id'];
        mysqli_query($conn, $update);
        
        $host = $_SERVER['HTTP_HOST'];
        $reset_link = "http://$host/reset_password.php?token=$token&email=$email";
        
        $message = "Password reset link sent to $email!<br><small>Debug: <a href='$reset_link'>$reset_link</a></small>";
        $message .= "<br><small>Token: $token (generated at $timestamp)</small>";
        $message .= "<br><small>FLAG{reset_token_predictable}</small>";
    } else {
        $error = "Email not found";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Shopz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header">
                        <h4 class="mb-0">Reset Password</h4>
                    </div>
                    <div class="card-body">
                        <?php if($message): ?>
                            <div class="alert alert-success"><?php echo $message; ?></div>
                        <?php endif; ?>
                        <?php if($error): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Send Reset Link</button>
                        </form>
                        
                        <hr>
                        <p class="text-center"><a href="login.php">Back to Login</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
