<?php
include('config.php');

// Pastikan hanya admin yang dapat mengakses halaman ini
session_start();
if (!isset($_SESSION['user_id']) ) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $nama_barang = $_POST['nama_barang'];
    $jumlah = $_POST['jumlah'];
    $ruangan_id = $_POST['ruangan_id'];
    $kondisi = $_POST['kondisi']; // Kondisi barang

    // Query untuk insert data barang
    try {
        $query = "INSERT INTO barang (nama_barang, jumlah, ruangan_id, kondisi) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$nama_barang, $jumlah, $ruangan_id, $kondisi]);

        echo 'Barang berhasil ditambahkan!';
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}
