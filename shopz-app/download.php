<?php
$file = isset($_GET['file']) ? $_GET['file'] : '';

if ($file) {
    $filepath = "downloads/" . $file;
    
    if (file_exists($filepath)) {
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($file) . '"');
        header('Content-Length: ' . filesize($filepath));
        readfile($filepath);
        exit;
    } else {
        $filepath = $file;
        if (file_exists($filepath)) {
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($file) . '"');
            readfile($filepath);
            exit;
        }
    }
    
    echo "File not found: $file";
} else {
    echo "No file specified";
}
?>
