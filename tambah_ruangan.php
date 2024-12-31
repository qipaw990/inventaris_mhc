<?php
include('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_ruangan = $_POST['nama_ruangan'];
    $deskripsi = $_POST['deskripsi'];
    $status = $_POST['status'];

    $stmt = $pdo->prepare("INSERT INTO ruangan (nama_ruangan, deskripsi, status) VALUES (?, ?, ?)");
    $stmt->execute([$nama_ruangan, $deskripsi, $status]);

    echo 'success';
}
?>
