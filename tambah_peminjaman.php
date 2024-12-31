<?php
include('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_peminjam = $_POST['nama_peminjam'];
    $barang_id = $_POST['barang_id'];
    $jumlah = $_POST['jumlah'];
    $tanggal_pinjam = $_POST['tanggal_pinjam'];
    $tanggal_kembali = $_POST['tanggal_kembali'];
    $status = $_POST['status'];

    // Validasi nama peminjam: jangan mengandung angka
    if (preg_match('/\d/', $nama_peminjam)) {
        echo "Nama peminjam tidak boleh mengandung angka.";
    } else {
        // Lanjutkan ke proses penyimpanan data jika valid
        $query = "INSERT INTO peminjaman (nama_peminjam, barang_id, jumlah, tanggal_pinjam, tanggal_kembali, status) 
                  VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$nama_peminjam, $barang_id, $jumlah, $tanggal_pinjam, $tanggal_kembali, $status]);

        echo "Peminjaman berhasil ditambahkan.";
    }
}
?>
