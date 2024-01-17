<?php
session_start();

// Cek apakah sesi username dan role sudah terdaftar, jika tidak redirect ke halaman index.php
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location:../index.php');
    exit;
}

require_once '../db_config.php';

// Dapatkan ID jabatan dari parameter URL
if (isset($_GET['id'])) {
    $jabatan_id = $_GET['id'];
} else {
    // Jika ID jabatan tidak diberikan dalam URL, redirect kembali ke dashboard
    header('Location: dashboard_admin.php');
    exit;
}

// Proses penghapusan jabatan setelah konfirmasi
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['delete_confirmation'])) {
        // Periksa apakah jabatan terkait dengan pengguna manapun
        $user_check_sql = "SELECT COUNT(*) as user_count FROM users WHERE id_jabatan = ?";
        $user_check_stmt = $conn->prepare($user_check_sql);
        $user_check_stmt->bind_param("i", $jabatan_id);
        $user_check_stmt->execute();
        $user_check_result = $user_check_stmt->get_result();
        $user_count = $user_check_result->fetch_assoc()['user_count'];

        if ($user_count > 0) {
            // Jabatan terkait dengan pengguna, perbarui pengguna untuk mengatur jabatan menjadi "N/A"
            $update_users_sql = "UPDATE users SET id_jabatan = NULL WHERE id_jabatan = ?";
            $update_users_stmt = $conn->prepare($update_users_sql);
            $update_users_stmt->bind_param("i", $jabatan_id);
            $update_users_stmt->execute();
        }

        // Siapkan dan bind DELETE statement untuk jabatan
        $stmt = $conn->prepare("DELETE FROM jabatan WHERE id = ?");
        $stmt->bind_param("i", $jabatan_id);

        // Jalankan pernyataan DELETE
        if ($stmt->execute()) {
            header('Location: dashboard_admin.php');
            exit;
        } else {
            header('Location: error.php?message=' . urlencode('Error deleting jabatan: ' . $conn->error));
            exit;
        }
    } else {
        // Jika kotak centang delete_confirmation tidak dipilih, redirect kembali ke dashboard
        header('Location: dashboard_admin.php');
        exit;
    }
}

// Ambil nama jabatan yang ada dari database berdasarkan ID jabatan
$existing_jabatan_name = getJabatanName($conn, $jabatan_id);

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
    <title>Employee Attendance System - Delete Jabatan</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
</head>
<body>
    <!-- ... -->
    <div class="container mt-4">
        <h2>Delete Jabatan</h2>
        <p>Apakah Anda yakin ingin menghapus jabatan "<?php echo $existing_jabatan_name; ?>"?</p>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $jabatan_id; ?>">
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="delete_confirmation" name="delete_confirmation" required>
                <label class="form-check-label" for="delete_confirmation">Ya, saya mengkonfirmasi penghapusan ini.</label>
            </div>
            <button type="submit" class="btn btn-danger mt-3">Delete Jabatan</button>
            <a href="dashboard_admin.php" class="btn btn-secondary mt-3">Batal</a>
        </form>
    </div>
    <!-- ... -->
</body>
</html>
