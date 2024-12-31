<?php
session_start();
include('config.php');

// Cek apakah user sudah login dan memiliki role 'admin'
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Query untuk mengambil data pengguna
$query = "SELECT * FROM users";
$stmt = $pdo->query($query);
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pengguna - Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">

</head>
<body>
    <!-- Include Sidebar -->
    <?php include('sidebar.php'); ?>

    <!-- Main Content -->
    <div class="main-content" id="main-content" style="margin-left: 250px;">
        <!-- Include Navbar -->
        <?php include('navbar.php'); ?>

        <div class="container mt-4">
            <h2>Daftar Pengguna</h2>
            <p>Berikut adalah daftar pengguna yang terdaftar di sistem.</p>
            <button class="btn btn-primary mb-3" id="addUserBtn">Tambah Pengguna</button>
            
            <!-- Tabel Pengguna -->
            <table class="table table-bordered" id="userTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr data-id="<?php echo $user['id']; ?>" data-username="<?php echo $user['username']; ?>" data-email="<?php echo $user['email']; ?>" data-role="<?php echo $user['role']; ?>">
                            <td><?php echo $user['id']; ?></td>
                            <td><?php echo $user['username']; ?></td>
                            <td><?php echo $user['email']; ?></td>
                            <td><?php echo ucfirst($user['role']); ?></td>
                            <td>
                                <button class="btn btn-warning btn-sm editBtn">Edit</button>
                                <button class="btn btn-danger btn-sm deleteBtn">Hapus</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal untuk Tambah/Edit Pengguna -->
    <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userModalLabel">Tambah Pengguna</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="userForm">
                        <input type="hidden" id="userId" name="id">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email">
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-control" id="role" name="role" required>
                                <option value="admin">Admin</option>
                                <option value="operator">Operator</option>
                                <option value="user">User</option>
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

    <script>
        $(document).ready(function() {
            // Open modal untuk tambah pengguna
            $('#addUserBtn').click(function() {
                $('#userModalLabel').text('Tambah Pengguna');
                $('#userForm')[0].reset();
                $('#userId').val('');
                $('#userModal').modal('show');
            });

            // Open modal untuk edit pengguna
            $(document).on('click', '.editBtn', function() {
                var row = $(this).closest('tr');
                var userId = row.data('id');
                var username = row.data('username');
                var email = row.data('email');
                var role = row.data('role');

                $('#userId').val(userId);
                $('#username').val(username);
                $('#email').val(email);
                $('#role').val(role);
                $('#userModalLabel').text('Edit Pengguna');
                $('#userModal').modal('show');
            });

            // Submit form untuk tambah atau edit pengguna
            $('#userForm').submit(function(e) {
                e.preventDefault();

                var id = $('#userId').val();
                var username = $('#username').val();
                var password = $('#password').val();
                var email = $('#email').val();
                var role = $('#role').val();

                var url = id ? 'update_pengguna.php' : 'tambah_pengguna.php'; // Tentukan URL berdasarkan id (update atau tambah)
                
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: {
                        id: id,
                        username: username,
                        password: password,
                        email: email,
                        role: role
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

            // Hapus pengguna
            $(document).on('click', '.deleteBtn', function() {
                var row = $(this).closest('tr');
                var userId = row.data('id');
                
                Swal.fire({
                    title: 'Yakin ingin menghapus pengguna ini?',
                    text: "Data ini tidak bisa dikembalikan.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Hapus',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: 'POST',
                            url: 'hapus_pengguna.php',
                            data: { id: userId },
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
