<?php
session_start();

// Cek apakah sesi username dan role sudah terdaftar, jika tidak redirect ke halaman index.php
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location:../index.php');
    exit;
}

require_once '../db_config.php';

// Proses pengiriman data form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['role']) && isset($_POST['id_jabatan'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $role = $_POST['role'];
        $id_jabatan = $_POST['id_jabatan'];

        // Persiapkan dan ikat statement INSERT
        $stmt = $conn->prepare("INSERT INTO users (username, password, role, id_jabatan) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $username, $password, $role, $id_jabatan);

        // Validasi input sebelum mengeksekusi statement
        if (!empty($username) && !empty($password) && !empty($role) && !empty($id_jabatan)) {
            // Eksekusi statement
            if ($stmt->execute()) {
                header('Location: dashboard_admin.php');
                exit;
            } else {
                echo "Error: " . $conn->error;
            }
        } else {
            echo "All fields are required.";
        }
    }
}

// Fungsi untuk mendapatkan semua nama dan ID jabatan dari tabel "jabatan"
function getAllJabatans($conn) {
    $sql = "SELECT id, nama_jabatan FROM jabatan";
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
    <title>Employee Attendance System - Add New User</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
</head>
<body>
    <!-- ... -->
    <div class="container mt-4">
        <h2>Add New User</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="role">Role:</label>
                <select class="form-control" id="role" name="role" required>
                    <option value="admin">Admin</option>
                    <option value="user">User</option>
                </select>
            </div>
            <div class="form-group">
                <label for="id_jabatan">Jabatan:</label>
                <select class="form-control" id="id_jabatan" name="id_jabatan" required>
                    <?php
                    $jabatans = getAllJabatans($conn);
                    foreach ($jabatans as $jabatan) {
                        echo '<option value="' . $jabatan['id'] . '">' . $jabatan['nama_jabatan'] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Add User</button>
        </form>
    </div>
    <!-- ... -->
</body>
</html>
