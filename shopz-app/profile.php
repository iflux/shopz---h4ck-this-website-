<?php
require_once 'includes/config.php';

$user_id = isset($_GET['id']) ? $_GET['id'] : (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0);

if (!$user_id) {
    header('Location: login.php');
    exit;
}

$query = "SELECT * FROM users WHERE id = $user_id";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profile'])) {
        $email = $_POST['email'];
        $address = $_POST['address'];
        $update = "UPDATE users SET email = '$email', address = '$address' WHERE id = $user_id";
        if (mysqli_query($conn, $update)) {
            $message = "Profile updated successfully!";
            $result = mysqli_query($conn, $query);
            $user = mysqli_fetch_assoc($result);
        }
    }
    
    if (isset($_POST['change_password'])) {
        $new_password = md5($_POST['new_password']);
        $update = "UPDATE users SET password = '$new_password' WHERE id = $user_id";
        if (mysqli_query($conn, $update)) {
            $message = "Password changed successfully!";
        }
    }
    
    if (isset($_FILES['avatar'])) {
        $upload_dir = 'uploads/avatars/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $filename = $_FILES['avatar']['name'];
        $target = $upload_dir . $filename;
        
        if (move_uploaded_file($_FILES['avatar']['tmp_name'], $target)) {
            $message = "Avatar uploaded: <a href='$target'>$target</a>";
        } else {
            $error = "Upload failed";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Shopz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="/">Shopz</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="orders.php">My Orders</a>
                <a class="nav-link" href="logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <h1>User Profile</h1>
        
        <?php if($message): ?>
            <div class="alert alert-success"><?php echo $message; ?></div>
        <?php endif; ?>
        <?php if($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">Profile Information</div>
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="update_profile" value="1">
                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <input type="text" class="form-control" value="<?php echo $user['username']; ?>" disabled>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" value="<?php echo $user['email']; ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Role</label>
                                <input type="text" class="form-control" value="<?php echo $user['role']; ?>" disabled>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Address</label>
                                <textarea name="address" class="form-control"><?php echo $user['address']; ?></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Update Profile</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">Change Password</div>
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="change_password" value="1">
                            <div class="mb-3">
                                <label class="form-label">New Password</label>
                                <input type="password" name="new_password" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-warning">Change Password</button>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">Upload Avatar</div>
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <input type="file" name="avatar" class="form-control">
                            </div>
                            <button type="submit" class="btn btn-info">Upload</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <small class="text-muted">User ID: <?php echo $user['id']; ?> | Account created: <?php echo $user['created_at']; ?></small>
        </div>
    </div>
</body>
</html>
