<?php
// Memulai sesi atau mengambil sesi yang ada jika sudah ada
session_start();

// Jika pengguna belum login atau peran (role) pengguna bukan user, alihkan ke halaman login
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
    header('Location: index.php');
    exit;
}

// Memasukkan file fungsi yang diperlukan
require_once '../functions.php';
require_once '../db_config.php';

// Memeriksa apakah formulir disubmit untuk menambahkan data absensi
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['tanggal']) && isset($_POST['jam_masuk']) && isset($_POST['jam_keluar'])) {
        $tanggal = $_POST['tanggal'];
        $jam_masuk = $_POST['jam_masuk'];
        $jam_keluar = $_POST['jam_keluar'];
        $user_id = getUserId($conn, $_SESSION['username']);

        if ($user_id) {
            $sql = "INSERT INTO absensi (id_karyawan, tanggal, jam_masuk, jam_keluar) VALUES ($user_id, '$tanggal', '$jam_masuk', '$jam_keluar')";
            if (mysqli_query($conn, $sql)) {
                echo '<div class="alert alert-success">Absensi berhasil ditambahkan.</div>';
            } else {
                echo '<div class="alert alert-danger">Error dalam menambahkan absensi: ' . mysqli_error($conn) . '</div>';
            }
        } else {
            echo '<div class="alert alert-danger">Error: Pengguna tidak ditemukan.</div>';
        }
    }
}
$jabatan_id_user = getJabatanId($conn, $_SESSION['username']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Employee Attendance System - Dashboard User</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <style>
        /* Custom CSS for table separators */
        .table-separator {
            border-bottom: 2px solid #ccc;
        }
        /* Custom CSS for adding spacing */
        .mt-5 {
            margin-top: 3rem;
        }
        .navbar {
            margin-bottom: 20px;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
        }
        .absensi-table {
            width: 100%;
        }
        .absensi-table th,
        .absensi-table td {
            padding: 10px;
            text-align: center;
        }
        .absensi-table th {
            background-color: #f2f2f2;
        }
        .add-absensi-form {
            border: 1px solid #ccc;
            padding: 20px;
            margin-top: 30px;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-md navbar-dark bg-dark sticky-top">
    <div class="container">
        <a class="navbar-brand" href="#">Employee Attendance System</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <!-- Dropdown Notifikasi -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Notifications
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <?php displayNotifikasiUser($conn, $jabatan_id_user); ?>
                    </div>
                </li>

                <!-- Logout -->
                <li class="nav-item">
                    <a class="nav-link" href="../logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<?php
// Display notifications in the dropdown
function displayNotifikasiUser($conn, $jabatan_id) {
    $sql = "SELECT * FROM notifikasi WHERE tanggal_post <= NOW() ORDER BY tanggal_post DESC";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            // Check if the 'id' key exists in the $row array
            $notificationId = isset($row['id']) ? $row['id'] : '';
            $viewedClass = isNotificationViewed($notificationId) ? 'text-muted' : '';

            echo '<a class="dropdown-item ' . $viewedClass . '" href="#">';
            echo '<h5 class="mb-1">' . $row['judul'] . '</h5>';
            echo '<p class="mb-1">' . $row['deskripsi'] . '</p>';
            echo '<small class="text-muted">' . $row['tanggal_post'] . '</small>';
            echo '</a>';

            // Mark the notification as viewed
            setNotificationViewed($notificationId);
        }
    } else {
        echo '<p class="dropdown-item">Tidak ada notifikasi.</p>';
    }
}
?>



<div class="container">
    <h2 class="text-center">Selamat datang, <?php echo $_SESSION['username']; ?>!</h2>
    <h3 class="text-center">Dashboard Pengguna</h3>
    

    <!-- Menampilkan data absensi pengguna sendiri -->
    <div class="mt-5">
        <h4>Data Absensi Anda</h4>
        <?php displayAbsensiByName($conn, $_SESSION['username']); ?>
    </div>

    <!-- Form Tambah Absensi -->
    <div class="add-absensi-form mt-5">
        <h4>Tambah Absensi</h4>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <div class="form-group">
                <label for="tanggal">Tanggal:</label>
                <input type="date" class="form-control" id="tanggal" name="tanggal" required>
            </div>
            <div class="form-group">
                <label for="jam_masuk">Jam Masuk:</label>
                <input type="time" class="form-control" id="jam_masuk" name="jam_masuk" required>
            </div>
            <div class="form-group">
                <label for="jam_keluar">Jam Keluar:</label>
                <input type="time" class="form-control" id="jam_keluar" name="jam_keluar" required>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary">Tambah Absensi</button>
            </div>
        </form>
    </div>
</div>
<footer class="footer mt-auto py-4 bg-dark">
    <div class="container">
        <span class="text-muted">Dibuat oleh Syarif &copy; <?php echo date('Y'); ?></span>
    </div>
</footer>

<script src="../js/jquery.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
</body>
</html>
