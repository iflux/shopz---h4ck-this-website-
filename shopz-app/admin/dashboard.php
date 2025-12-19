<?php
require_once '../includes/config.php';

$output = '';
$dns_output = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['ping_host'])) {
        $host = $_POST['ping_host'];
        $output = shell_exec("ping -c 3 " . $host);
    }
    
    if (isset($_POST['dns_lookup'])) {
        $domain = $_POST['dns_lookup'];
        $domain = str_replace(array(';', '|', '&'), '', $domain);
        $dns_output = shell_exec("nslookup " . $domain);
    }
}

$users = mysqli_query($conn, "SELECT id, username, email, role, created_at FROM users");
$orders = mysqli_query($conn, "SELECT COUNT(*) as total FROM orders");
$order_count = mysqli_fetch_assoc($orders)['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Shopz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-danger">
        <div class="container-fluid">
            <a class="navbar-brand" href="/admin/dashboard.php">Shopz Admin</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="/">View Site</a>
                <a class="nav-link" href="/logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container-fluid my-4">
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">Admin Menu</div>
                    <div class="list-group list-group-flush">
                        <a href="dashboard.php" class="list-group-item">Dashboard</a>
                        <a href="users.php" class="list-group-item">Users</a>
                        <a href="products.php" class="list-group-item">Products</a>
                        <a href="orders.php" class="list-group-item">Orders</a>
                        <a href="debug.php" class="list-group-item">Debug Info</a>
                        <a href="backup.php" class="list-group-item">Backup</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-9">
                <h2>Dashboard</h2>
                
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <h5>Total Orders</h5>
                                <h2><?php echo $order_count; ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <h5>Total Users</h5>
                                <h2><?php echo mysqli_num_rows($users); ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <h5>Server Status</h5>
                                <h2>Online</h2>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">Server Ping Tool</div>
                            <div class="card-body">
                                <form method="POST">
                                    <div class="input-group">
                                        <input type="text" name="ping_host" class="form-control" placeholder="Enter hostname or IP">
                                        <button type="submit" class="btn btn-primary">Ping</button>
                                    </div>
                                </form>
                                <?php if($output): ?>
                                <pre class="mt-3 bg-dark text-white p-3"><?php echo htmlspecialchars($output); ?></pre>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">DNS Lookup</div>
                            <div class="card-body">
                                <form method="POST">
                                    <div class="input-group">
                                        <input type="text" name="dns_lookup" class="form-control" placeholder="Enter domain">
                                        <button type="submit" class="btn btn-secondary">Lookup</button>
                                    </div>
                                </form>
                                <?php if($dns_output): ?>
                                <pre class="mt-3 bg-dark text-white p-3"><?php echo htmlspecialchars($dns_output); ?></pre>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">Recent Users</div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Created</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                mysqli_data_seek($users, 0);
                                while($user = mysqli_fetch_assoc($users)): 
                                ?>
                                <tr>
                                    <td><?php echo $user['id']; ?></td>
                                    <td><?php echo $user['username']; ?></td>
                                    <td><?php echo $user['email']; ?></td>
                                    <td><?php echo $user['role']; ?></td>
                                    <td><?php echo $user['created_at']; ?></td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
