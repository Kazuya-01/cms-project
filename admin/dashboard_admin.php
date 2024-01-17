<?php
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

require_once '../functions.php';
require_once '../db_config.php';

// Proses formulir untuk membuat notifikasi
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submitNotifikasiAdmin'])) {
    if (isset($_POST['judul']) && isset($_POST['deskripsi'])) {
        $judul = $_POST['judul'];
        $deskripsi = $_POST['deskripsi'];

        $sql = "INSERT INTO notifikasi (judul, deskripsi, tanggal_post) VALUES ('$judul', '$deskripsi', NOW())";
        if (mysqli_query($conn, $sql)) {
            echo '<div class="alert alert-success mt-3">Notifikasi berhasil dikirim.</div>';
        } else {
            echo '<div class="alert alert-danger mt-3">Error dalam mengirim notifikasi: ' . mysqli_error($conn) . '</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Attendance System - Dashboard Admin</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <style>
        .table-separator {
            border-bottom: 2px solid #ccc;
        }
        .mt-5 {
            margin-top: 3rem;
        }
        .sticky-top {
            position: sticky;
            top: 0;
            z-index: 1020;
            background-color: #343a40;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-md navbar-dark bg-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="#">Employee Attendance System</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="../logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Container -->
    <div class="container mt-4">
        <h2 class="text-center">Welcome, <?php echo $_SESSION['username']; ?>!</h2>

        <!-- Menampilkan data absensi -->
        <div class="mt-5">
            <h3>Attendance Data</h3>
            <div class="table-responsive">
                <table class="table table-striped table-bordered bg-light table-separator">
                    <tbody>
                        <?php displayAbsensiAdmin($conn); ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Menampilkan data pengguna (users) -->
        <div class="mt-4">
            <h3>Admin Dashboard</h3>
            <div class="table-responsive">
                <table class="table table-striped table-bordered bg-light table-separator">
                    <tbody>
                        <?php displayUsers($conn); ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tombol untuk menambah pengguna baru (user) -->
        <div class="text-center">
            <a href="create_user.php" class="btn btn-success mt-4">Add New User</a>
        </div>

        <!-- Menampilkan data jabatan -->
        <div class="mt-5">
            <h3>Job Title Data</h3>
            <div class="table-responsive">
                <table class="table table-striped table-bordered bg-light table-separator">
                    <tbody>
                        <?php displayJabatanAdmin($conn); ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tombol untuk menambah jabatan baru -->
        <div class="text-center">
            <a href="create_jabatan.php" class="btn btn-primary mt-4">Add New Job Title</a>
        </div>
    </div>

    <div class="card mt-5">
    <div class="card-header">
        <h3>Create Notification</h3>
    </div>
    <div class="card-body">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <div class="form-group">
                <label for="judul">Title:</label>
                <input type="text" class="form-control" id="judul" name="judul" required>
            </div>
            <div class="form-group">
                <label for="deskripsi">Description:</label>
                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required></textarea>
            </div>
            <button type="submit" name="submitNotifikasiAdmin" class="btn btn-primary">Send Notification</button>
        </form>
    </div>
</div>

    
    <!-- Footer -->
    <footer class="footer mt-auto py-3 bg-dark">
        <div class="container">
            <span class="text-muted">Created by Syarif &copy; <?php echo date('Y'); ?></span>
        </div>
    </footer>

    <script src="../js/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
</body>
</html>
