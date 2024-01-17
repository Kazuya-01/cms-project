<?php
// Fungsi untuk menampilkan semua data absensi untuk admin
function displayAbsensiAdmin($conn) {
    $sql = "SELECT absensi.*, users.username FROM absensi JOIN users ON absensi.id_karyawan = users.id";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        // Tampilkan tabel untuk menampilkan data absensi
        echo '<table class="table">';
        echo '<thead class="thead-dark">';
        echo '<tr>';
        echo '<th>No</th>';
        echo '<th>Username</th>';
        echo '<th>Tanggal</th>';
        echo '<th>Jam Masuk</th>';
        echo '<th>Jam Keluar</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        $no = 1; // Inisialisasi variabel untuk nomor urut

        while ($row = mysqli_fetch_assoc($result)) {
            // Tampilkan setiap baris data absensi
            echo '<tr>';
            echo '<td>' . $no . '</td>';
            $no++ ;
            echo '<td>' . $row['username'] . '</td>';
            echo '<td>' . $row['tanggal'] . '</td>';
            echo '<td>' . $row['jam_masuk'] . '</td>';
            echo '<td>' . $row['jam_keluar'] . '</td>';
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
    } else {
        echo 'Tidak ada data yang ditemukan.';
    }
}

// Fungsi untuk menampilkan semua pengguna dari tabel "users"
function displayUsers($conn) {
    $sql = "SELECT * FROM users";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        // Tampilkan tabel untuk menampilkan data pengguna
        echo '<table class="table">';
        echo '<thead class="thead-dark">';
        echo '<tr>';
        echo '<th>No</th>';
        echo '<th>Username</th>';
        echo '<th>Role</th>';
        echo '<th>Jabatan</th>';
        echo '<th>Action</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        $no = 1; // Inisialisasi variabel untuk nomor urut

        while ($row = mysqli_fetch_assoc($result)) {
            // Tampilkan setiap baris data pengguna
            echo '<tr>';
            echo '<td>' . $no . '</td>'; 
            $no++; // Tambahkan nomor urut setiap iterasi
            echo '<td>' . $row['username'] . '</td>';
            echo '<td>' . $row['role'] . '</td>';
            echo '<td>' . getJabatanName($conn, $row['id_jabatan']) . '</td>';
            echo '<td>';
            echo '<a href="edit_user.php?id=' . $row['id'] . '" class="btn btn-info">Edit</a>';
            echo '<a href="delete_user.php?id=' . $row['id'] . '" class="btn btn-danger ml-2">Delete</a>';
            echo '</td>';
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
    } else {
        echo 'Tidak ada data yang ditemukan.';
    }
}

// Fungsi untuk menampilkan data absensi berdasarkan nama pengguna (username)
function displayAbsensiByName($conn, $username) {
    $user_id = getUserId($conn, $username);

    if ($user_id) {
        $sql = "SELECT * FROM absensi WHERE id_karyawan = $user_id";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            // Tampilkan tabel untuk menampilkan data absensi pengguna
            echo '<table class="table">';
            echo '<thead class="thead-dark">';
            echo '<tr>';
            echo '<th>No</th>'; 
            echo '<th>Tanggal</th>';
            echo '<th>Jam Masuk</th>';
            echo '<th>Jam Keluar</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';

            $no = 1; // Inisialisasi variabel untuk nomor urut

            while ($row = mysqli_fetch_assoc($result)) {
                // Tampilkan setiap baris data absensi pengguna
                echo '<tr>';
                echo '<td>' . $no . '</td>'; 
                $no++; // Tambahkan nomor urut setiap iterasi
                echo '<td>' . $row['tanggal'] . '</td>';
                echo '<td>' . $row['jam_masuk'] . '</td>';
                echo '<td>' . $row['jam_keluar'] . '</td>';
                echo '</tr>';
            }

            echo '</tbody>';
            echo '</table>';
        } else {
            echo 'Tidak ada data yang ditemukan.';
        }
    } else {
        echo 'Pengguna tidak ditemukan.';
    }
}

// Fungsi untuk menampilkan data jabatan dari tabel "jabatan"
function displayJabatanAdmin($conn) {
    $sql = "SELECT * FROM jabatan";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        // Tampilkan tabel untuk menampilkan data jabatan
        echo '<table class="table">';
        echo '<thead class="thead-dark">';
        echo '<tr>';
        echo '<th>No</th>';
        echo '<th>Nama Jabatan</th>';
        echo '<th>Action</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        $no = 1; // Inisialisasi variabel untuk nomor urut

        while ($row = mysqli_fetch_assoc($result)) {
            // Tampilkan setiap baris data jabatan
            echo '<tr>';
            echo '<td>' . $no . '</td>'; // Tampilkan nomor urut
            $no++; // Tambahkan nomor urut setiap iterasi
            echo '<td>' . $row['nama_jabatan'] . '</td>';
            echo '<td>';
            echo '<a href="edit_jabatan.php?id=' . $row['id'] . '" class="btn btn-info">Edit</a>';
            echo '<a href="delete_jabatan.php?id=' . $row['id'] . '" class="btn btn-danger ml-2">Delete</a>';
            echo '</td>';
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
    } else {
        echo 'Tidak ada data yang ditemukan.';
    }
}

// Fungsi untuk mendapatkan ID pengguna berdasarkan nama pengguna dari tabel "users"
function getUserId($conn, $username) {
    $sql = "SELECT id FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['id'];
    }
    return null;
}

// Fungsi untuk mendapatkan nama jabatan berdasarkan id_jabatan dari tabel "jabatan"
function getJabatanName($conn, $jabatan_id) {
    $sql = "SELECT nama_jabatan FROM jabatan WHERE id = $jabatan_id";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['nama_jabatan'];
    }
    return 'N/A';
}
// Fungsi untuk menampilkan notifikasi user berdasarkan jabatan
if (!function_exists('displayNotifikasiUser')) {
    function displayNotifikasiUser($conn, $jabatan_id) {
        $sql = "SELECT * FROM notifikasi WHERE tanggal_post <= NOW() ORDER BY tanggal_post DESC";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            echo '<ul class="list-group">';
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<li class="list-group-item">';
                echo '<h5 class="mb-1">' . $row['judul'] . '</h5>';
                echo '<p class="mb-1">' . $row['deskripsi'] . '</p>';
                echo '<small class="text-muted">' . $row['tanggal_post'] . '</small>';
                echo '</li>';
            }
            echo '</ul>';
        } else {
            echo '<p>Tidak ada notifikasi.</p>';
        }
    }
}

function getJabatanId($conn, $username) {
    $sql = "SELECT id_jabatan FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['id_jabatan'];
    }

    return null;
}
function setNotificationViewed($notificationId) {
    $_SESSION['viewed_notifications'][$notificationId] = true;
}

// Function to check if a notification has been viewed
function isNotificationViewed($notificationId) {
    return isset($_SESSION['viewed_notifications'][$notificationId]) && $_SESSION['viewed_notifications'][$notificationId] === true;
}
?>
