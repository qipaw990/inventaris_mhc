<?php
session_start();
include('config.php');

// Cek apakah user sudah login dan memiliki role 'admin'
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Query untuk menghitung jumlah total barang
$queryTotalBarang = "SELECT COUNT(*) as total_barang FROM barang";
$stmtTotalBarang = $pdo->query($queryTotalBarang);
$totalBarang = $stmtTotalBarang->fetch()['total_barang'];

// Query untuk menghitung jumlah barang yang tersedia (kondisi baik atau perlu perbaikan)
$queryBarangTersedia = "SELECT COUNT(*) as barang_tersedia FROM barang WHERE kondisi IN ('Baik', 'Perlu Perbaikan')";
$stmtBarangTersedia = $pdo->query($queryBarangTersedia);
$barangTersedia = $stmtBarangTersedia->fetch()['barang_tersedia'];

// Query untuk menghitung jumlah peminjaman yang sudah selesai
$queryPeminjamanSelesai = "SELECT COUNT(*) as peminjaman_selesai FROM peminjaman WHERE status = 'Kembali'";
$stmtPeminjamanSelesai = $pdo->query($queryPeminjamanSelesai);
$peminjamanSelesai = $stmtPeminjamanSelesai->fetch()['peminjaman_selesai'];

// Query untuk menghitung jumlah total peminjaman
$queryTotalPeminjaman = "SELECT COUNT(*) as total_peminjaman FROM peminjaman";
$stmtTotalPeminjaman = $pdo->query($queryTotalPeminjaman);
$totalPeminjaman = $stmtTotalPeminjaman->fetch()['total_peminjaman'];

// Query untuk menghitung jumlah total pengguna (misalnya, pengguna terdaftar)
$queryTotalPengguna = "SELECT COUNT(*) as total_pengguna FROM users";
$stmtTotalPengguna = $pdo->query($queryTotalPengguna);
$totalPengguna = $stmtTotalPengguna->fetch()['total_pengguna'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Inventori Barang</title>

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
            <h2>Dashboard Admin</h2>
            <p>Selamat datang di dashboard admin! Di bawah ini adalah statistik inventori barang dan peminjaman Anda.</p>

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

                <!-- Barang Tersedia -->
                <div class="col-md-3 mb-3">
                    <div class="card card-stat bg-success text-white">
                        <div class="card-body">
                            <div>
                                <i class="fas fa-boxes"></i>
                                <div class="stat-number"><?php echo $barangTersedia; ?></div>
                                <div class="stat-label">Barang Tersedia</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Peminjaman Selesai -->
                <div class="col-md-3 mb-3">
                    <div class="card card-stat bg-info text-white">
                        <div class="card-body">
                            <div>
                                <i class="fas fa-check-circle"></i>
                                <div class="stat-number"><?php echo $peminjamanSelesai; ?></div>
                                <div class="stat-label">Peminjaman Selesai</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Peminjaman -->
                <div class="col-md-3 mb-3">
                    <div class="card card-stat bg-warning text-white">
                        <div class="card-body">
                            <div>
                                <i class="fas fa-clipboard-list"></i>
                                <div class="stat-number"><?php echo $totalPeminjaman; ?></div>
                                <div class="stat-label">Total Peminjaman</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Pengguna -->
                <div class="col-md-3 mb-3">
                    <div class="card card-stat bg-secondary text-white">
                        <div class="card-body">
                            <div>
                                <i class="fas fa-users"></i>
                                <div class="stat-number"><?php echo $totalPengguna; ?></div>
                                <div class="stat-label">Total Pengguna</div>
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
    <script>
    $(document).ready(function () {
        // Toggle sidebar ketika tombol di navbar ditekan
        $('#toggleSidebarBtn').click(function () {
            // Toggle kelas CSS untuk sidebar dan main content
            $('#sidebar').toggleClass('collapsed');
            $('#main-content').toggleClass('collapsed');
        });
    });
</script>

</body>
</html>
