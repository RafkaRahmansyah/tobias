<?php
// Mulai sesi di awal file
session_start();

// Cek apakah user sudah login
function isLoggedIn()
{
    return isset($_SESSION['username']) && !empty($_SESSION['username']);
}

// Redirect jika belum login
function requireLogin()
{
    if (!isLoggedIn()) {
        // Simpan URL yang sedang diakses untuk redirect setelah login
        $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
        header('location:../pages/login.php');
        exit();
    }
}

// Logout
function logout()
{
    session_start();
    session_unset();
    session_destroy();
    header('location:../pages/login.php');
    exit();
}

// Fungsi untuk memastikan user memiliki role yang sesuai
function requireRole($role)
{
    requireLogin();
    if ($_SESSION['role'] != $role) {
        header('location:../pages/dashboard.php');
        exit();
    }
}
