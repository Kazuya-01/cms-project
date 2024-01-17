<?php
session_start();

// Cek apakah sesi username dan role sudah terdaftar, jika tidak redirect ke halaman index.php
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location:../index.php');
    exit;
}

require_once '../db_config.php';

$jabatan_id = null; // Inisialisasi variabel untuk menghindari kesalahan potensial.

// Dapatkan ID jabatan dari parameter URL
if (isset($_GET['id'])) {
    $jabatan_id = $_GET['id'];
} else {
    // Jika ID jabatan tidak diberikan dalam URL, redirect kembali ke dashboard
    header('Location: dashboard_admin.php');
    exit;
}

// Dapatkan nama jabatan yang sudah ada dari database berdasarkan ID jabatan
$existing_jabatan_name = getJabatanName($conn, $jabatan_id);

// Proses penyimpanan perubahan jabatan
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['nama_jabatan'])) {
        $nama_jabatan = $_POST['nama_jabatan'];

        // Siapkan dan bind pernyataan UPDATE untuk jabatan
        $stmt = $conn->prepare("UPDATE jabatan SET nama_jabatan = ? WHERE id = ?");
        $stmt->bind_param("si", $nama_jabatan, $jabatan_id);

        // Jalankan pernyataan UPDATE
        if ($stmt->execute()) {
            header('Location: dashboard_admin.php');
            exit;
        } else {
            header('Location: error.php?message=' . urlencode('Error updating jabatan: ' . $conn->error));
            exit;
        }
    }
}

// Fungsi untuk mendapatkan nama jabatan berdasarkan id dari tabel "jabatan"
function getJabatanName($conn, $jabatan_id) {
    $sql = "SELECT nama_jabatan FROM jabatan WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $jabatan_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['nama_jabatan'];
    }
    return 'N/A';
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Employee Attendance System - Edit Jabatan</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
</head>
<body>
    <!-- ... -->
    <div class="container mt-4">
        <h2>Edit Jabatan</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $jabatan_id; ?>">
            <div class="form-group">
                <label for="nama_jabatan">Nama Jabatan</label>
                <input type="text" class="form-control" id="nama_jabatan" name="nama_jabatan" value="<?php echo $existing_jabatan_name; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="dashboard_admin.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
    <!-- ... -->
</body>
</html>
