<?php
include('config.php');

// Pastikan hanya admin yang dapat mengakses
session_start();
if ($_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $id = $_POST['id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    
    // Jika password diubah, hash password baru
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

    try {
        // Update query jika password diubah
        if ($password) {
            // Query untuk update pengguna dengan password baru
            $query = "UPDATE users SET username = ?, email = ?, role = ?, password = ? WHERE id = ?";
            $params = [$username, $email, $role, $password, $id];
        } else {
            // Query untuk update tanpa mengubah password
            $query = "UPDATE users SET username = ?, email = ?, role = ? WHERE id = ?";
            $params = [$username, $email, $role, $id];
        }

        // Persiapkan dan eksekusi query
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);

        // Cek apakah ada baris yang terpengaruh (untuk memastikan data terupdate)
        if ($stmt->rowCount() > 0) {
            $message = "Pengguna berhasil diperbarui!";
            $message_type = 'success';
        } else {
            $message = "Tidak ada perubahan data pengguna.";
            $message_type = 'warning';
        }
    } catch (PDOException $e) {
        // Tangkap error SQL dan tampilkan pesan
        $message = "Error: " . $e->getMessage();
        $message_type = 'danger';
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Pengguna</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body>

<!-- Navbar -->
<?php include('navbar.php'); ?>

<!-- Main Content -->
<div class="container mt-4">

    <!-- Card untuk Update Pengguna -->
    <div class="card">
        <div class="card-header">
            <h5>Update Pengguna</h5>
        </div>
        <div class="card-body">

            <!-- Tampilkan pesan setelah update -->
            <?php if (isset($message)): ?>
                <div class="alert alert-<?= $message_type ?>"><?= $message ?></div>
            <?php endif; ?>

            <!-- Form Update Pengguna -->
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="id" class="form-label">ID Pengguna</label>
                    <input type="text" class="form-control" id="id" name="id" value="<?= isset($id) ? $id : '' ?>" readonly required>
                </div>
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" value="<?= isset($username) ? $username : '' ?>" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?= isset($email) ? $email : '' ?>" required>
                </div>
                <div class="mb-3">
                    <label for="role" class="form-label">Role</label>
                    <select class="form-control" id="role" name="role" required>
                        <option value="admin" <?= (isset($role) && $role == 'admin') ? 'selected' : '' ?>>Admin</option>
                        <option value="operator" <?= (isset($role) && $role == 'operator') ? 'selected' : '' ?>>Operator</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password (Opsional)</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password baru jika ingin mengganti">
                </div>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </form>

        </div>
    </div>

</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Font Awesome JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>

</body>
</html>
