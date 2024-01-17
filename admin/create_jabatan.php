<?php
// Memulai sesi atau mengambil sesi yang ada jika sudah ada
session_start();

// Jika pengguna belum login atau peran (role) pengguna bukan admin, alihkan ke halaman login
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location:../index.php');
    exit;
}

// Memerlukan file koneksi database
require_once '../db_config.php';

// Memproses pengiriman formulir
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['nama_jabatan'])) {
        $nama_jabatan = $_POST['nama_jabatan'];

        // Menyiapkan dan mengikat pernyataan INSERT untuk jabatan
        $stmt = $conn->prepare("INSERT INTO jabatan (nama_jabatan) VALUES (?)");
        $stmt->bind_param("s", $nama_jabatan);

        // Menjalankan pernyataan
        if ($stmt->execute()) {
            // Jika berhasil, alihkan kembali ke halaman dashboard admin
            header('Location: dashboard_admin.php');
            exit;
        } else {
            // Jika terjadi kesalahan, alihkan ke halaman error dengan menyertakan pesan kesalahan dalam URL
            header('Location: error.php?message=' . urlencode('Error creating jabatan: ' . $conn->error));
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Employee Attendance System - Create Jabatan</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <h2>Create New Jabatan</h2>
    <!-- Form untuk menambahkan jabatan baru -->
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="form-group">
            <label for="nama_jabatan">Nama Jabatan</label>
            <!-- Input untuk memasukkan nama jabatan, dengan atribut "required" agar tidak boleh kosong -->
            <input type="text" class="form-control" id="nama_jabatan" name="nama_jabatan" required>
        </div>
        <!-- Tombol untuk menambahkan jabatan baru -->
        <button type="submit" class="btn btn-primary">Tambah Jabatan</button>
        <!-- Tombol untuk membatalkan dan kembali ke halaman dashboard admin -->
        <a href="dashboard_admin.php" class="btn btn-secondary">Batal</a>
    </form>
</div>
<!-- ... -->
</body>
</html>
