<?php
session_start();

// Cek apakah pengguna sudah login atau belum
if (isset($_SESSION['username'])) {
    // Jika sudah login, arahkan ke halaman dashboard_admin.php jika rolenya admin, atau ke halaman dashboard_user.php jika rolenya user
    if ($_SESSION['role'] === 'admin') {
        header('Location: dashboard_admin.php');
    } else {
        header('Location: dashboard_user.php');
    }
    exit;
}

// Jika ada permintaan POST (pengguna mengklik tombol login)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once 'db_config.php';

    // Ambil data dari form
    $username = $_POST['username']; // Ambil username dari input "username"
    $password = $_POST['password']; // Ambil password dari input "password"

    // Query SQL untuk mencari pengguna dengan username dan password yang sesuai di tabel "users"
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $sql);

    // Jika hasil query menghasilkan satu baris data, berarti login berhasil
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);

        // Set session untuk menyimpan informasi pengguna yang sudah login
        $_SESSION['username'] = $row['username'];
        $_SESSION['role'] = $row['role'];

        // Redirect ke halaman dashboard_admin.php jika rolenya admin, atau ke halaman dashboard_user.php jika rolenya user
        if ($row['role'] === 'admin') {
            header('Location: admin/dashboard_admin.php');
        } else {
            header('Location: user/dashboard_user.php');
        }
        exit;
    } else {
        // Jika hasil query tidak menghasilkan data, tampilkan pesan error
        $error_message = "Username atau password salah";
    }

    mysqli_close($conn);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Employee Attendance System - Login</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <style>
        /* Custom CSS to change the Login button background color to green */
        .btn-login {
            background-color: #4CAF50;
            border-color: #4CAF50;
        }

        /* Custom CSS to change the Login button text color */
        .btn-login {
            color: #fff;
        }

        /* Custom CSS to add a background color to the container */
        body {
            background-color: #f2f2f2;
        }

        /* Custom CSS to center the login card on the screen */
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card w-50">
            <div class="card-body">
                <h2 class="card-title text-center">Employee Attendance System</h2>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-login btn-block">Login</button>
                </form>
                <?php
                // Tampilkan pesan error jika login gagal
                if (isset($error_message)) {
                    echo '<div class="mt-3 alert alert-danger">' . $error_message . '</div>';
                }
                ?>
            </div>
        </div>
    </div>

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>
