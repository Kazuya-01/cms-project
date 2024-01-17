<?php
session_start();

// Cek apakah sesi username dan role sudah terdaftar, jika tidak redirect ke halaman index.php
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location:../index.php');
    exit;
}

require_once '../db_config.php';

// Proses penghapusan user setelah konfirmasi
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['user_id'])) {
        $user_id = $_POST['user_id'];

        // Siapkan dan bind DELETE statement untuk user
        $stmt = $conn->prepare(" DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);

        // Jalankan pernyataan DELETE
        if ($stmt->execute()) {
            header('Location: dashboard_admin.php');
            exit;
        } else {
            echo "Error: " . $conn->error;
        }
    }
}

// Dapatkan ID user dari parameter URL
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
} else {
    // Jika ID user tidak diberikan dalam URL, redirect kembali ke dashboard
    header('Location: dashboard_admin.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Employee Attendance System - Delete User</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <style>
        /* Custom CSS for centering the card */
        .card-center {
            margin: 0 auto;
            margin-top: 50px;
            max-width: 400px;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="card card-center">
            <div class="card-body">
                <h2 class="card-title">Delete User</h2>
                <p class="card-text">Apakah Anda yakin ingin menghapus pengguna ini?</p>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                    <button type="submit" class="btn btn-danger">Delete User</button>
                    <a href="dashboard_admin.php" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
