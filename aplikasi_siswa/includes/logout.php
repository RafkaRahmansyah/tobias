<?php
require_once 'auth.php';

// Hancurkan semua sesi
session_start();
session_unset();
session_destroy();

// Redirect ke halaman login
header('location:../pages/login.php');
exit();
