<?php
require_once 'includes/auth.php';

if (isLoggedIn()) {
    header('location:pages/dashboard.php');
    exit();
} else {
    header('location:pages/login.php');
    exit();
}
