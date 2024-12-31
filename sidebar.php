<?php
include('config.php');

// Pastikan pengguna sudah login dan memiliki role yang valid
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$userRole = $_SESSION['role'];  // Mengambil role pengguna dari sesi
?>

<!-- sidebar.php -->
<div class="sidebar" id="sidebar">
    <h6 class="text-center text-white py-3"> <?php echo $userRole == 'admin' ? 'Admin Dashboard' : 'Operator Dashboard'; ?></h6>
    <hr class="text-white">
    <ul class="list-unstyled">
    <?php if ($userRole == 'admin'): ?>
        <li>
            <a href="admin_dashboard.php" class="text-white">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
        </li>
        <?php else:?>
            <li>
            <a href="operator_dashboard.php" class="text-white">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
        </li>  
        <?php endif;?>

        <!-- Menampilkan menu Kelola Barang hanya untuk admin -->
            <li>
                <a href="kelola_barang.php" class="text-white">
                    <i class="fas fa-cogs"></i> Kelola Barang
                </a>
            </li>


        <!-- Menampilkan menu Kelola Peminjaman untuk operator dan admin -->
        <li>
            <a href="kelola_peminjaman.php" class="text-white">
                <i class="fas fa-clipboard-list"></i> Kelola Peminjaman
            </a>
        </li>

        <!-- Menampilkan menu Kelola Ruangan hanya untuk admin -->
        <?php if ($userRole == 'admin'): ?>
            <li>
                <a href="kelola_ruangan.php" class="text-white">
                    <i class="fas fa-building"></i> Kelola Ruangan
                </a>
            </li>
        <?php endif; ?>
        <?php if ($userRole == 'admin'): ?>
            <li>
                <a href="kelola_pengguna.php" class="text-white">
                    <i class="fas fa-building"></i> Kelola Pengguna
                </a>
            </li>
        <?php endif; ?>
    </ul>
</div>

<!-- Sidebar Styling (Custom CSS) -->
<style>
    .sidebar {
        height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        width: 250px;
        background-color: #343a40;
        color: white;
        padding-top: 20px;
        transition: all 0.3s ease;
    }

    .sidebar a {
        color: white;
        text-decoration: none;
        padding: 10px 20px;
        display: block;
        transition: background-color 0.3s ease;
    }

    .sidebar a:hover {
        background-color: #495057;
    }

    .sidebar hr {
        border-top: 1px solid #ccc;
    }
</style>
