<?php
session_start();
include('config.php');

// Pastikan hanya admin yang dapat mengakses halaman ini
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Ambil data barang dan ruangan
$queryBarang = "SELECT barang.*, ruangan.nama_ruangan FROM barang JOIN ruangan ON barang.ruangan_id = ruangan.id";
$stmtBarang = $pdo->query($queryBarang);
$barang = $stmtBarang->fetchAll();

$queryRuangan = "SELECT * FROM ruangan";
$stmtRuangan = $pdo->query($queryRuangan);
$ruangan = $stmtRuangan->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Barang - Inventori Barang</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    
    <style>
        .table th, .table td {
            text-align: center;
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
            <!-- Card untuk Kelola Barang -->
            <div class="card">
                <div class="card-header">
                    <h2>Kelola Barang</h2>
                    <p>Manage barang yang ada di sistem inventori Anda.</p>
                </div>
                <div class="card-body">
                    <!-- Tabel Barang -->
                    <table class="table table-bordered" id="barangTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Barang</th>
                                <th>Jumlah</th>
                                <th>Ruangan</th>
                                <th>Kondisi</th>  <!-- Kolom Kondisi -->
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($barang as $b): ?>
                                <tr>
                                    <td><?php echo $b['id']; ?></td>
                                    <td><?php echo $b['nama_barang']; ?></td>
                                    <td><?php echo $b['jumlah']; ?></td>
                                    <td><?php echo $b['nama_ruangan']; ?></td>
                                    <td><?php echo $b['kondisi']; ?></td>  <!-- Menampilkan kondisi -->
                                    <td>
                                        <button class="btn btn-warning btn-sm editBtn" data-id="<?php echo $b['id']; ?>" data-nama="<?php echo $b['nama_barang']; ?>" data-jumlah="<?php echo $b['jumlah']; ?>" data-ruangan="<?php echo $b['ruangan_id']; ?>" data-kondisi="<?php echo $b['kondisi']; ?>">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <?php if ($userRole == 'admin'): ?>
                                        <button class="btn btn-danger btn-sm deleteBtn" data-id="<?php echo $b['id']; ?>">
                                            <i class="fas fa-trash-alt"></i> Hapus
                                        </button>
                                        <?php endif;?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <!-- Button untuk Tambah Barang -->
                    <?php if ($userRole == 'admin'): ?>
                    <button class="btn btn-primary" id="addBtn">Tambah Barang</button>
                    <?php endif;?>
                    <?php if ($userRole == 'admin'): ?>
    <a href="laporan_barang.php" class="btn btn-secondary">Unduh Laporan PDF</a>
<?php endif; ?>

                </div>
            </div> <!-- End Card -->

        </div>
    </div>

    <!-- Modal untuk Tambah/Edit Barang -->
    <div class="modal fade" id="barangModal" tabindex="-1" aria-labelledby="barangModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="barangModalLabel">Tambah Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="barangForm">
                        <input type="hidden" id="barangId">
                        <div class="mb-3">
                            <label for="nama_barang" class="form-label">Nama Barang</label>
                            <input type="text" class="form-control" id="nama_barang" name="nama_barang" required>
                        </div>
                        <div class="mb-3" >
                            <label for="deskripsi" style="visibility: hidden;display: none;" class="form-label">Deskripsi</label>
                            <textarea style="visibility: hidden;display: none;" class="form-control" id="deskripsi" name="deskripsi"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="jumlah" class="form-label">Jumlah</label>
                            <input type="number" class="form-control" id="jumlah" name="jumlah" required>
                        </div>
                        <div class="mb-3">
                            <label for="ruangan_id" class="form-label">Ruangan</label>
                            <select class="form-control" id="ruangan_id" name="ruangan_id" required>
                                <?php foreach ($ruangan as $r): ?>
                                    <option value="<?php echo $r['id']; ?>"><?php echo $r['nama_ruangan']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="kondisi" class="form-label">Kondisi Barang</label>
                            <select class="form-control" id="kondisi" name="kondisi" required>
                                <option value="Baik">Baik</option>
                                <option value="Rusak">Rusak</option>
                                <option value="Perlu Perbaikan">Perlu Perbaikan</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
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
            // Inisialisasi DataTables
            $('#barangTable').DataTable();

            // Event Delegation untuk tombol Edit (karena tombol dibuat dinamis)
            $(document).on('click', '.editBtn', function() {
                var id = $(this).data('id');
                var nama = $(this).data('nama');
                var jumlah = $(this).data('jumlah');
                var ruangan = $(this).data('ruangan');
                var kondisi = $(this).data('kondisi'); // Kondisi barang

                $('#barangId').val(id);
                $('#nama_barang').val(nama);
                $('#jumlah').val(jumlah);
                $('#ruangan_id').val(ruangan);
                $('#kondisi').val(kondisi);  // Set nilai kondisi
                $('#barangModalLabel').text('Edit Barang');
                
                // Menampilkan modal secara manual
                $('#barangModal').modal('show');
            });

            // Tampilkan modal untuk tambah barang
            $('#addBtn').click(function() {
                $('#barangForm')[0].reset();
                $('#barangId').val('');
                $('#barangModalLabel').text('Tambah Barang');
                $('#barangModal').modal('show');
            });

            // Submit form untuk tambah atau edit barang
            $('#barangForm').submit(function(e) {
                e.preventDefault();

                var id = $('#barangId').val();
                var nama = $('#nama_barang').val();
                var deskripsi = $('#deskripsi').val();
                var jumlah = $('#jumlah').val();
                var ruangan = $('#ruangan_id').val();
                var kondisi = $('#kondisi').val();

                var url = id ? 'update_barang.php' : 'tambah_barang.php'; // Cek apakah update atau tambah
                
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: {
                        id: id,
                        nama_barang: nama,
                        deskripsi: deskripsi,
                        jumlah: jumlah,
                        ruangan_id: ruangan,
                        kondisi: kondisi
                    },
                    success: function(response) {
                        Swal.fire({
                            title: 'Sukses!',
                            text: response,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            location.reload();
                        });
                    }
                });
            });

            // Hapus barang
            $(document).on('click', '.deleteBtn', function() {
                var id = $(this).data('id');
                Swal.fire({
                    title: 'Yakin ingin menghapus barang ini?',
                    text: "Data ini tidak bisa dikembalikan.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Hapus',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: 'POST',
                            url: 'hapus_barang.php',
                            data: { id: id },
                            success: function(response) {
                                Swal.fire({
                                    title: 'Terhapus!',
                                    text: response,
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    location.reload();
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>
