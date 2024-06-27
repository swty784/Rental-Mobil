<?php
include('config.php');

try {
    $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
