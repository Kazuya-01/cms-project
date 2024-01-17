<?php
session_start();

// Cek apakah sesi username dan role sudah terdaftar, jika tidak redirect ke halaman index.php
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location:../index.php');
    exit;
}

require_once '../db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['user_id']) && isset($_POST['new_username']) && isset($_POST['new_role']) && isset($_POST['new_jabatan'])) {
        // Ambil data dari form
        $user_id = $_POST['user_id'];
        $new_username = $_POST['new_username'];
        $new_role = $_POST['new_role'];
        $new_jabatan = $_POST['new_jabatan'];

        // SQL untuk melakukan update data user
        $sql = "UPDATE users SET username = '$new_username', role = '$new_role', id_jabatan = $new_jabatan WHERE id = $user_id";

        // Eksekusi query update
        if (mysqli_query($conn, $sql)) {
            header('Location: dashboard_admin.php');
            exit;
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }

        mysqli_close($conn);
    }
}

// Ambil ID user dari parameter URL
$user_id = $_GET['id'];

// SQL untuk mendapatkan data user berdasarkan ID
$sql = "SELECT * FROM users WHERE id = $user_id";
$result = mysqli_query($conn, $sql);
$user_data = mysqli_fetch_assoc($result);

// Fungsi untuk mendapatkan semua nama jabatan dari tabel "jabatan"
function getAllJabatanNames($conn) {
    $sql = "SELECT * FROM jabatan";
    $result = mysqli_query($conn, $sql);
    $jabatans = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $jabatans[] = $row;
    }
    return $jabatans;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Employee Attendance System - Edit User</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Employee Attendance System</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard_admin.php">Home</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>Edit User</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <div class="form-group">
                <label for="user_id">User ID:</label>
                <input type="text" class="form-control" id="user_id" name="user_id" value="<?php echo $user_data['id']; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="new_username">New Username:</label>
                <input type="text" class="form-control" id="new_username" name="new_username" value="<?php echo $user_data['username']; ?>" required>
            </div>
            <div class="form-group">
                <label for="new_role">New Role:</label>
                <select class="form-control" id="new_role" name="new_role" required>
                    <option value="admin" <?php if ($user_data['role'] === 'admin') echo 'selected'; ?>>Admin</option>
                    <option value="user" <?php if ($user_data['role'] === 'user') echo 'selected'; ?>>User</option>
                </select>
            </div>
            <div class="form-group">
                <label for="new_jabatan">New Jabatan:</label>
                <select class="form-control" id="new_jabatan" name="new_jabatan" required>
                    <?php
                    // Mendapatkan semua nama jabatan
                    $jabatans = getAllJabatanNames($conn);
                    foreach ($jabatans as $jabatan) {
                        echo '<option value="' . $jabatan['id'] . '"';
                        if ($user_data['id_jabatan'] == $jabatan['id']) echo 'selected';
                        echo '>' . $jabatan['nama_jabatan'] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update User</button>
        </form>
    </div>

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>
