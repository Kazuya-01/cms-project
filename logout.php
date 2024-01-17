<?php
session_start();

if (isset($_POST['confirm_logout'])) {
    // Menghapus semua data sesi
    session_destroy();

    // Redirect ke halaman login setelah logout
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Employee Attendance System - Logout</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
        <div class="card">
            <div class="card-body">
                <h2 class="card-title">Logout</h2>
                <p class="card-text">Apakah Anda yakin ingin logout?</p>
                <form method="post">
                    <input type="hidden" name="confirm_logout" value="1">
                    <button type="submit" class="btn btn-danger">Logout</button>
                    <?php
                    // Menampilkan tombol "Batal" sesuai peran pengguna yang sedang login
                    if ($_SESSION['role'] === 'admin') {
                        echo '<a href="/karyawan/admin/dashboard_admin.php" class="btn btn-secondary">Batal</a>';
                    } else {
                        echo '<a href="/karyawan/user/dashboard_user.php" class="btn btn-secondary">Batal</a>';
                    }
                    ?>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
