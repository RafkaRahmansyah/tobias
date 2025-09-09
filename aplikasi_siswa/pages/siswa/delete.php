<?php
require_once '../../includes/auth.php';
requireLogin();

include '../../config/database.php';
include '../../includes/functions.php';

if (isset($_GET['id'])) {
    $id = cleanInput($_GET['id']);
    $delete = mysqli_query($connect, "DELETE FROM siswa WHERE id_siswa='$id'");

    if ($delete) {
        header('location:list.php?delete_success=1');
    } else {
        echo 'gagal delete data: ' . mysqli_error($connect);
    }
} else {
    header('location:list.php');
}
