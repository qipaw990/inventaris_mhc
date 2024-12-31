<!-- navbar.php -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">


        <!-- Navbar Title / Branding -->
        <a class="navbar-brand ms-3" href="admin_dashboard.php">Inventori Barang</a>

        <!-- User Greeting and Logout Button -->
        <span class="navbar-text ms-auto">
            Selamat datang, <?php echo $_SESSION['username']; ?>
        </span>

        <a href="logout.php" class="btn btn-danger ms-3">Logout</a>
    </div>
</nav>
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
