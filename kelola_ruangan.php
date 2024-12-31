<?php
session_start();
include('config.php');

// Pastikan hanya admin yang dapat mengakses halaman ini
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Ambil data ruangan
$query = "SELECT * FROM ruangan";
$stmt = $pdo->query($query);
$ruangan = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Ruangan - Inventori Barang</title>
    
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
            <!-- Card untuk Kelola Ruangan -->
            <div class="card">
                <div class="card-header">
                    <h2>Kelola Ruangan</h2>
                    <p>Manage ruangan penyimpanan barang di sistem inventori Anda.</p>
                </div>
                <div class="card-body">
                    <!-- Tabel Ruangan -->
                    <table class="table table-bordered" id="ruanganTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Ruangan</th>
                                <th>Deskripsi</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($ruangan as $r): ?>
                                <tr>
                                    <td><?php echo $r['id']; ?></td>
                                    <td><?php echo $r['nama_ruangan']; ?></td>
                                    <td><?php echo $r['deskripsi']; ?></td>
                                    <td><?php echo $r['status']; ?></td>
                                    <td>
                                        <button class="btn btn-warning btn-sm editBtn" data-id="<?php echo $r['id']; ?>" data-nama="<?php echo $r['nama_ruangan']; ?>" data-deskripsi="<?php echo $r['deskripsi']; ?>" data-status="<?php echo $r['status']; ?>">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <button class="btn btn-danger btn-sm deleteBtn" data-id="<?php echo $r['id']; ?>">
                                            <i class="fas fa-trash-alt"></i> Hapus
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <!-- Button untuk Tambah Ruangan -->
                    <button class="btn btn-primary" id="addBtn">Tambah Ruangan</button>
                </div>
            </div> <!-- End Card -->

        </div>
    </div>

    <!-- Modal untuk Tambah/Edit Ruangan -->
    <div class="modal fade" id="ruanganModal" tabindex="-1" aria-labelledby="ruanganModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ruanganModalLabel">Tambah Ruangan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="ruanganForm">
                        <input type="hidden" id="ruanganId">
                        <div class="mb-3">
                            <label for="nama_ruangan" class="form-label">Nama Ruangan</label>
                            <input type="text" class="form-control" id="nama_ruangan" name="nama_ruangan" required>
                        </div>
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="aktif">Aktif</option>
                                <option value="non-aktif">Non-Aktif</option>
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
            $('#ruanganTable').DataTable();

            // Event Delegation untuk tombol Edit (karena tombol dibuat dinamis)
            $(document).on('click', '.editBtn', function() {
                var id = $(this).data('id');
                var nama = $(this).data('nama');
                var deskripsi = $(this).data('deskripsi');
                var status = $(this).data('status');

                $('#ruanganId').val(id);
                $('#nama_ruangan').val(nama);
                $('#deskripsi').val(deskripsi);
                $('#status').val(status);
                $('#ruanganModalLabel').text('Edit Ruangan');
                
                // Menampilkan modal secara manual
                $('#ruanganModal').modal('show');
            });

            // Tampilkan modal untuk tambah ruangan
            $('#addBtn').click(function() {
                $('#ruanganForm')[0].reset();
                $('#ruanganId').val('');
                $('#ruanganModalLabel').text('Tambah Ruangan');
                $('#ruanganModal').modal('show');
            });

            // Submit form untuk tambah atau edit ruangan
            $('#ruanganForm').submit(function(e) {
                e.preventDefault();

                var id = $('#ruanganId').val();
                var nama = $('#nama_ruangan').val();
                var deskripsi = $('#deskripsi').val();
                var status = $('#status').val();

                var url = (id) ? 'update_ruangan.php' : 'tambah_ruangan.php'; // Jika ID ada, update, jika tidak, tambah baru

                $.ajax({
                    type: 'POST',
                    url: url,
                    data: {
                        id: id,
                        nama_ruangan: nama,
                        deskripsi: deskripsi,
                        status: status
                    },
                    success: function(response) {
                        $('#ruanganModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Ruangan berhasil disimpan!',
                        }).then(() => {
                            location.reload(); // Reload halaman setelah sukses
                        });
                    },
                    error: function() {
                        Swal.fire('Oops!', 'Terjadi kesalahan.', 'error');
                    }
                });
            });

            // Hapus ruangan
            $('.deleteBtn').click(function() {
                var id = $(this).data('id');
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data ini akan dihapus!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Hapus',
                    cancelButtonText: 'Batal',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: 'POST',
                            url: 'hapus_ruangan.php',
                            data: { id: id },
                            success: function(response) {
                                Swal.fire('Dihapus!', 'Ruangan berhasil dihapus.', 'success')
                                    .then(() => location.reload());
                            },
                            error: function() {
                                Swal.fire('Oops!', 'Terjadi kesalahan.', 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>
