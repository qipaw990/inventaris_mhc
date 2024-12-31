<?php
include('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $nama_peminjam = $_POST['nama_peminjam'];
    $barang_id = $_POST['barang_id'];
    $jumlah = $_POST['jumlah'];
    $tanggal_pinjam = $_POST['tanggal_pinjam'];
    $tanggal_kembali = $_POST['tanggal_kembali'];
    $status = $_POST['status'];

    $query = "UPDATE peminjaman SET nama_peminjam = ?, barang_id = ?, jumlah = ?, tanggal_pinjam = ?, tanggal_kembali = ?, status = ? 
              WHERE id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$nama_peminjam, $barang_id, $jumlah, $tanggal_pinjam, $tanggal_kembali, $status, $id]);

    echo "Peminjaman berhasil diperbarui.";
}
?>
