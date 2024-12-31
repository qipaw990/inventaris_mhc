<?php
include('config.php');

// Pastikan hanya admin yang dapat mengakses halaman ini
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $id = $_POST['id'];
    $nama_barang = $_POST['nama_barang'];
    $jumlah = $_POST['jumlah'];
    $ruangan_id = $_POST['ruangan_id'];
    $kondisi = $_POST['kondisi'];  // Kondisi barang

    try {
        // Query untuk update data barang
        $query = "UPDATE barang SET nama_barang = ?, jumlah = ?, ruangan_id = ?, kondisi = ? WHERE id = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$nama_barang, $jumlah, $ruangan_id, $kondisi, $id]);

        echo 'Barang berhasil diperbarui!';
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}
