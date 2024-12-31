<?php
include('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $nama_ruangan = $_POST['nama_ruangan'];
    $deskripsi = $_POST['deskripsi'];
    $status = $_POST['status'];

    $stmt = $pdo->prepare("UPDATE ruangan SET nama_ruangan = ?, deskripsi = ?, status = ? WHERE id = ?");
    $stmt->execute([$nama_ruangan, $deskripsi, $status, $id]);

    echo 'success';
}
?>
