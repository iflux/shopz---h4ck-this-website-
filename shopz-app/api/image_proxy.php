<?php
$url = isset($_GET['url']) ? $_GET['url'] : '';

if ($url) {
    $content = file_get_contents($url);
    
    if ($content) {
        if (strpos($url, '.png') !== false || strpos($url, '.jpg') !== false) {
            header('Content-Type: image/png');
        } else {
            header('Content-Type: text/plain');
        }
        echo $content;
    } else {
        echo "Failed to fetch: $url";
    }
} else {
    echo "Usage: /api/image_proxy.php?url=http://example.com/image.png";
    echo "\n\nThis proxy helps load external images.";
    echo "\n\nFLAG{ssrf_internal_access}";
}
?>
