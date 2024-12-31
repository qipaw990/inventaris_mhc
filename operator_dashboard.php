<?php
session_start();
include('config.php');

// Cek apakah user sudah login dan memiliki role 'operator'
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'operator') {
    header('Location: login.php');
    exit();
}

// Query untuk menghitung jumlah barang
$queryTotalBarang = "SELECT COUNT(*) as total_barang FROM barang";
$stmtTotalBarang = $pdo->query($queryTotalBarang);
$totalBarang = $stmtTotalBarang->fetch()['total_barang'];

// Query untuk menghitung jumlah barang yang sedang dipinjam
$queryBarangDipinjam = "SELECT COUNT(*) as barang_dipinjam FROM peminjaman WHERE status = 'Dipinjam'";
$stmtBarangDipinjam = $pdo->query($queryBarangDipinjam);
$barangDipinjam = $stmtBarangDipinjam->fetch()['barang_dipinjam'];

// Query untuk menghitung jumlah barang yang rusak
$queryBarangRusak = "SELECT COUNT(*) as barang_rusak FROM barang WHERE kondisi = 'Rusak'";
$stmtBarangRusak = $pdo->query($queryBarangRusak);
$barangRusak = $stmtBarangRusak->fetch()['barang_rusak'];

// Query untuk menghitung jumlah peminjaman yang sedang berlangsung
$queryPeminjamanAktif = "SELECT COUNT(*) as peminjaman_aktif FROM peminjaman WHERE status = 'Aktif'";
$stmtPeminjamanAktif = $pdo->query($queryPeminjamanAktif);
$peminjamanAktif = $stmtPeminjamanAktif->fetch()['peminjaman_aktif'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Operator Dashboard - Inventori Barang</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <style>
        /* Optional custom styling */
        .navbar-text {
            font-size: 16px;
            font-weight: bold;
        }

        .btn-outline-secondary {
            border: 1px solid #ccc;
        }

        .card-stat {
            height: 100%;
        }

        .card-body {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-body i {
            font-size: 40px;
        }

        .stat-number {
            font-size: 24px;
            font-weight: bold;
        }

        .stat-label {
            font-size: 16px;
        }
    </style>
</head>
<body>
    <!-- Include Sidebar -->
    <?php include('sidebar.php'); ?>

    <!-- Main Content -->
    <div class="main-content" id="main-content" style="margin-left: 250px;">
        <!-- Include Navbar -->
        <?php include('navbar.php'); ?>

        <div class="container mt-4">
            <h2>Dashboard Operator</h2>
            <p>Selamat datang di dashboard operator! Di bawah ini adalah statistik inventori barang Anda.</p>

            <!-- Statistik Cards -->
            <div class="row">
                <!-- Total Barang -->
                <div class="col-md-3 mb-3">
                    <div class="card card-stat bg-primary text-white">
                        <div class="card-body">
                            <div>
                                <i class="fas fa-cogs"></i>
                                <div class="stat-number"><?php echo $totalBarang; ?></div>
                                <div class="stat-label">Total Barang</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Barang Dipinjam -->
                <div class="col-md-3 mb-3">
                    <div class="card card-stat bg-warning text-white">
                        <div class="card-body">
                            <div>
                                <i class="fas fa-box-open"></i>
                                <div class="stat-number"><?php echo $barangDipinjam; ?></div>
                                <div class="stat-label">Barang Dipinjam</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Barang Rusak -->
                <div class="col-md-3 mb-3">
                    <div class="card card-stat bg-danger text-white">
                        <div class="card-body">
                            <div>
                                <i class="fas fa-exclamation-triangle"></i>
                                <div class="stat-number"><?php echo $barangRusak; ?></div>
                                <div class="stat-label">Barang Rusak</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Peminjaman Aktif -->
                <div class="col-md-3 mb-3">
                    <div class="card card-stat bg-success text-white">
                        <div class="card-body">
                            <div>
                                <i class="fas fa-check-circle"></i>
                                <div class="stat-number"><?php echo $peminjamanAktif; ?></div>
                                <div class="stat-label">Peminjaman Aktif</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- End of Statistics Row -->

        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Font Awesome JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>

</body>
</html>
