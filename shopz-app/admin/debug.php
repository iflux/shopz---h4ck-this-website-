<?php
require_once '../includes/config.php';

echo "<h1>Debug Information</h1>";
echo "<p>FLAG{forced_browsing_debug}</p>";

echo "<h2>Server Info</h2>";
echo "<pre>";
echo "PHP Version: " . phpversion() . "\n";
echo "Server Software: " . $_SERVER['SERVER_SOFTWARE'] . "\n";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "\n";
echo "Script Filename: " . $_SERVER['SCRIPT_FILENAME'] . "\n";
echo "</pre>";

echo "<h2>Database Config</h2>";
echo "<pre>";
echo "Host: $db_host\n";
echo "Database: $db_name\n";
echo "User: $db_user\n";
echo "Password: $db_pass\n";
echo "</pre>";

echo "<h2>Environment Variables</h2>";
echo "<pre>";
print_r($_ENV);
echo "</pre>";

echo "<h2>Session Data</h2>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

phpinfo();
?>
