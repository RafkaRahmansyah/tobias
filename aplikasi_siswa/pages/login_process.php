<?php
// Mulai sesi
session_start();

// Hapus semua variabel sesi
session_unset();

// Hancurkan sesi
session_destroy();

// Mulai sesi baru
session_start();

include '../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Cek user di database
    $query = mysqli_query($connect, "SELECT * FROM users WHERE username='$username' AND password='$password'");
    $user = mysqli_fetch_assoc($query);

    if ($user) {
        // Login berhasil, set session
        $_SESSION['id_user'] = $user['id_user'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['nama'] = $user['nama'];
        $_SESSION['role'] = $user['role'];

        // Cek apakah ada URL redirect yang tersimpan
        if (isset($_SESSION['redirect_url'])) {
            $redirect_url = $_SESSION['redirect_url'];
            unset($_SESSION['redirect_url']); // Hapus URL redirect setelah digunakan
            header('location:' . $redirect_url);
        } else {
            // Redirect ke halaman dashboard
            header('location:dashboard.php');
        }
    } else {
        // Login gagal
        header('location:login.php?login_failed=1');
    }
} else {
    header('location:login.php');
}
