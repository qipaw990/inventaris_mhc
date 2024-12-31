<?php
session_start();
include('config.php');
require('lib/fpdf.php'); // Pastikan FPDF sudah terpasang di folder lib

// Pastikan hanya admin yang dapat mengakses halaman ini
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Ambil data barang dari database
$queryBarang = "SELECT barang.*, ruangan.nama_ruangan FROM barang JOIN ruangan ON barang.ruangan_id = ruangan.id";
$stmtBarang = $pdo->query($queryBarang);
$barang = $stmtBarang->fetchAll();

// Membuat objek FPDF
$pdf = new FPDF();

// Set margin (Kiri, Atas, Kanan)
$pdf->SetMargins(10, 10, 10);  // Menambahkan margin kiri, atas, dan kanan sebesar 10mm
$pdf->AddPage(); // Menambahkan halaman baru

// Set font untuk judul
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Laporan Inventori Barang', 0, 1, 'C'); // Judul laporan

$pdf->Ln(10); // Menambah jarak setelah judul

// Set font untuk tabel header
$pdf->SetFont('Arial', 'B', 12);

// Kolom header dengan penyesuaian lebar kolom
$pdf->Cell(20, 10, 'ID', 1, 0, 'C');
$pdf->Cell(40, 10, 'Nama Barang', 1, 0, 'C');
$pdf->Cell(30, 10, 'Jumlah', 1, 0, 'C');
$pdf->Cell(60, 10, 'Ruangan', 1, 0, 'C');
$pdf->Cell(30, 10, 'Kondisi', 1, 1, 'C');

// Set font untuk data
$pdf->SetFont('Arial', '', 12);

// Isi data barang
foreach ($barang as $b) {
    $pdf->Cell(20, 10, $b['id'], 1, 0, 'C');
    $pdf->Cell(40, 10, $b['nama_barang'], 1, 0, 'L'); // Perbaiki agar teks kiri untuk nama barang
    $pdf->Cell(30, 10, $b['jumlah'], 1, 0, 'C');
    $pdf->Cell(60, 10, $b['nama_ruangan'], 1, 0, 'L'); // Perbaiki agar teks kiri untuk ruangan
    $pdf->Cell(30, 10, $b['kondisi'], 1, 1, 'C');
}

// Output PDF ke browser
$pdf->Output();
?>
