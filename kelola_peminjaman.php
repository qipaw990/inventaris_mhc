<?php
session_start();
include('config.php');

// Pastikan hanya admin yang dapat mengakses halaman ini
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Ambil data peminjaman dan barang
$queryPeminjaman = "SELECT peminjaman.*, barang.nama_barang FROM peminjaman JOIN barang ON peminjaman.barang_id = barang.id";
$stmtPeminjaman = $pdo->query($queryPeminjaman);
$peminjaman = $stmtPeminjaman->fetchAll();

// Ambil data barang untuk dropdown
$queryBarang = "SELECT * FROM barang";
$stmtBarang = $pdo->query($queryBarang);
$barang = $stmtBarang->fetchAll();
?>
<?php
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
    <title>Kelola Peminjaman Barang</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">

    <style>
        .table th, .table td {
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Sidebar dan Navbar (sesuaikan dengan layout Anda) -->
    <?php include('sidebar.php'); ?>
    <div class="main-content" id="main-content" style="margin-left: 250px;">
    <?php include('navbar.php'); ?>

    <div class="container mt-4">
        <!-- Card untuk Kelola Peminjaman -->
        <div class="card">
            <div class="card-header">
                <h2>Kelola Peminjaman Barang</h2>
                <p>Kelola peminjaman barang di sistem inventori Anda.</p>
            </div>
            <div class="card-body">
                <div class="row">
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

                </div>
                <!-- Tabel Peminjaman -->
                <table class="table table-bordered" id="peminjamanTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Peminjam</th>
                            <th>Nama Barang</th>
                            <th>Jumlah</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tanggal Kembali</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($peminjaman as $p): ?>
                            <tr>
                                <td><?php echo $p['id']; ?></td>
                                <td><?php echo $p['nama_peminjam']; ?></td>
                                <td><?php echo $p['nama_barang']; ?></td>
                                <td><?php echo $p['jumlah']; ?></td>
                                <td><?php echo $p['tanggal_pinjam']; ?></td>
                                <td><?php echo $p['tanggal_kembali']; ?></td>
                                <td><?php echo $p['status']; ?></td>
                                <td>
                                    <button class="btn btn-warning btn-sm editBtn" data-id="<?php echo $p['id']; ?>" data-nama_peminjam="<?php echo $p['nama_peminjam']; ?>" data-barang_id="<?php echo $p['barang_id']; ?>" data-jumlah="<?php echo $p['jumlah']; ?>" data-tanggal_pinjam="<?php echo $p['tanggal_pinjam']; ?>" data-tanggal_kembali="<?php echo $p['tanggal_kembali']; ?>" data-status="<?php echo $p['status']; ?>">
                                        Edit
                                    </button>
                                    <button class="btn btn-danger btn-sm deleteBtn" data-id="<?php echo $p['id']; ?>">
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <!-- Button untuk Tambah Peminjaman -->
                <button class="btn btn-primary" id="addBtn">Tambah Peminjaman</button>
                <!-- Link untuk mengunduh laporan peminjaman -->
<a href="laporan_peminjaman.php" class="btn btn-success ">Unduh Laporan Peminjaman</a>

            </div>
        </div>
    </div>

    <!-- Modal untuk Tambah/Edit Peminjaman -->
    <div class="modal fade" id="peminjamanModal" tabindex="-1" aria-labelledby="peminjamanModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="peminjamanModalLabel">Tambah Peminjaman</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="peminjamanForm">
                        <input type="hidden" id="peminjamanId">
                        <div class="mb-3">
                            <label for="nama_peminjam" class="form-label">Nama Peminjam</label>
                            <input type="text" class="form-control" id="nama_peminjam" name="nama_peminjam" required>
                        </div>
                        <div class="mb-3">
                            <label for="barang_id" class="form-label">Barang</label>
                            <select class="form-control" id="barang_id" name="barang_id" required>
                                <?php foreach ($barang as $b): ?>
                                    <option value="<?php echo $b['id']; ?>"><?php echo $b['nama_barang']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="jumlah" class="form-label">Jumlah</label>
                            <input type="number" class="form-control" id="jumlah" name="jumlah" required>
                        </div>
                        <div class="mb-3">
                            <label for="tanggal_pinjam" class="form-label">Tanggal Pinjam</label>
                            <input type="date" class="form-control" id="tanggal_pinjam" name="tanggal_pinjam" required>
                        </div>
                        <div class="mb-3">
                            <label for="tanggal_kembali" class="form-label">Tanggal Kembali</label>
                            <input type="date" class="form-control" id="tanggal_kembali" name="tanggal_kembali">
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="Dipinjam">Dipinjam</option>
                                <option value="Kembali">Kembali</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            // Inisialisasi DataTable
            $('#peminjamanTable').DataTable();

            // Tampilkan modal untuk tambah peminjaman
            $('#addBtn').click(function() {
                $('#peminjamanForm')[0].reset();
                $('#peminjamanId').val('');
                $('#peminjamanModalLabel').text('Tambah Peminjaman');
                $('#peminjamanModal').modal('show');
            });

            // Edit peminjaman
            $(document).on('click', '.editBtn', function() {
                var id = $(this).data('id');
                var namaPeminjam = $(this).data('nama_peminjam');
                var barangId = $(this).data('barang_id');
                var jumlah = $(this).data('jumlah');
                var tanggalPinjam = $(this).data('tanggal_pinjam');
                var tanggalKembali = $(this).data('tanggal_kembali');
                var status = $(this).data('status');

                $('#peminjamanId').val(id);
                $('#nama_peminjam').val(namaPeminjam);
                $('#barang_id').val(barangId);
                $('#jumlah').val(jumlah);
                $('#tanggal_pinjam').val(tanggalPinjam);
                $('#tanggal_kembali').val(tanggalKembali);
                $('#status').val(status);

                $('#peminjamanModalLabel').text('Edit Peminjaman');
                $('#peminjamanModal').modal('show');
            });

            $('#peminjamanForm').submit(function(e) {
    e.preventDefault();
    var id = $('#peminjamanId').val();
    var namaPeminjam = $('#nama_peminjam').val();
    var barangId = $('#barang_id').val();
    var jumlah = $('#jumlah').val();
    var tanggalPinjam = $('#tanggal_pinjam').val();
    var tanggalKembali = $('#tanggal_kembali').val();
    var status = $('#status').val();

    var url = id ? 'update_peminjaman.php' : 'tambah_peminjaman.php';

    $.post(url, {
        id: id,
        nama_peminjam: namaPeminjam,
        barang_id: barangId,
        jumlah: jumlah,
        tanggal_pinjam: tanggalPinjam,
        tanggal_kembali: tanggalKembali,
        status: status
    }, function(response) {
        // Check if the response contains "tidak boleh mengandung angka" to indicate an error
        if (response.includes("tidak boleh mengandung angka")) {
            Swal.fire({
                title: 'Error!',
                text: response,
                icon: 'error',
                confirmButtonText: 'OK'
            });
        } else {
            Swal.fire({
                title: 'Sukses!',
                text: response,
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(function() {
                location.reload();
            });
        }
    });
});

            // Hapus peminjaman
            $(document).on('click', '.deleteBtn', function() {
                var id = $(this).data('id');
                Swal.fire({
                    title: 'Yakin ingin menghapus peminjaman ini?',
                    text: "Data ini tidak bisa dikembalikan.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Hapus',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.post('hapus_peminjaman.php', { id: id }, function(response) {
                            Swal.fire({
                                title: 'Terhapus!',
                                text: response,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                location.reload();
                            });
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>
