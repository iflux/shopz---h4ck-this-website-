<?php
require_once 'includes/config.php';

$page = isset($_GET['page']) ? $_GET['page'] : 'home';

$allowed_pages = array('home', 'about', 'contact', 'faq', 'terms');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo ucfirst($page); ?> - Shopz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="/">Shopz</a>
            <div class="navbar-nav">
                <a class="nav-link" href="page.php?page=about">About</a>
                <a class="nav-link" href="page.php?page=contact">Contact</a>
                <a class="nav-link" href="page.php?page=faq">FAQ</a>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <?php
        $file = "pages/" . $page . ".php";
        if (file_exists($file)) {
            include($file);
        } else {
            include($page);
        }
        ?>
    </div>
</body>
</html>
