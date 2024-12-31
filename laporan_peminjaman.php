<?php
session_start();
include('config.php');
require('lib/fpdf.php'); // Pastikan FPDF sudah terpasang di folder lib

// Pastikan hanya admin yang dapat mengakses halaman ini
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Ambil data peminjaman dari database
$queryPeminjaman = "SELECT peminjaman.*, barang.nama_barang FROM peminjaman JOIN barang ON peminjaman.barang_id = barang.id";
$stmtPeminjaman = $pdo->query($queryPeminjaman);
$peminjaman = $stmtPeminjaman->fetchAll();

// Membuat objek FPDF
$pdf = new FPDF();
$pdf->SetMargins(10, 10, 10);  // Menambahkan margin kiri, atas, dan kanan
$pdf->AddPage(); // Menambahkan halaman baru

// Set font untuk judul laporan
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Laporan Peminjaman Barang', 0, 1, 'C'); // Judul laporan
$pdf->Ln(10); // Menambah jarak setelah judul

// Set font untuk header tabel
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(15, 10, 'ID', 1, 0, 'C');
$pdf->Cell(30, 10, 'Nama Peminjam', 1, 0, 'C');
$pdf->Cell(50, 10, 'Nama Barang', 1, 0, 'C');
$pdf->Cell(20, 10, 'Jumlah', 1, 0, 'C');
$pdf->Cell(30, 10, 'Tanggal Pinjam', 1, 0, 'C');
$pdf->Cell(30, 10, 'Tanggal Kembali', 1, 0, 'C');
$pdf->Cell(20, 10, 'Status', 1, 1, 'C'); // Mengatur header tabel

// Set font untuk data tabel
$pdf->SetFont('Arial', '', 9);

// Isi data peminjaman
foreach ($peminjaman as $p) {
    $pdf->Cell(15, 10, $p['id'], 1, 0, 'C');
    $pdf->Cell(30, 10, $p['nama_peminjam'], 1, 0, 'L'); // Data nama peminjam
    $pdf->Cell(50, 10, $p['nama_barang'], 1, 0, 'L'); // Data nama barang
    $pdf->Cell(20, 10, $p['jumlah'], 1, 0, 'C'); // Data jumlah
    $pdf->Cell(30, 10, $p['tanggal_pinjam'], 1, 0, 'C'); // Data tanggal pinjam
    $pdf->Cell(30, 10, $p['tanggal_kembali'], 1, 0, 'C'); // Data tanggal kembali
    $pdf->Cell(20, 10, $p['status'], 1, 1, 'C'); // Data status
}

// Output PDF ke browser
$pdf->Output();
?>
