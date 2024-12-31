<?php
$host = 'localhost';  // Atur sesuai dengan host Anda
$dbname = 'inventori';  // Nama database
$username = 'root';  // Username database
$password = '';  // Password database

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
