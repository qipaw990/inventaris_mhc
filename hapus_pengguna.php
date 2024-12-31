<?php
include('config.php');

// Pastikan hanya admin yang dapat mengakses
session_start();
if ($_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil ID pengguna dari request
    $id = $_POST['id'];

    // Query untuk menghapus pengguna
    $query = "DELETE FROM users WHERE id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$id]);

    echo "Pengguna berhasil dihapus!";
}
?>
