<?php
require_once '../../includes/auth.php';
requireLogin();

include '../../config/database.php';
include '../../includes/functions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = cleanInput($_POST['id_siswa']);
    $nama_siswa = cleanInput($_POST['nama_siswa']);
    $kelas = cleanInput($_POST['kelas']);

    // Perbaikan: Handle id_guru dengan benar
    if (!empty($_POST['id_guru']) && is_numeric($_POST['id_guru'])) {
        $id_guru = (int)$_POST['id_guru'];
        // Periksa apakah guru ada di database
        $check_guru = mysqli_query($connect, "SELECT id_guru FROM guru WHERE id_guru = $id_guru");
        if (mysqli_num_rows($check_guru) == 0) {
            // Jika guru tidak ditemukan, set ke NULL
            $id_guru = "NULL";
        } else {
            // Jika guru ditemukan, gunakan nilainya
            $id_guru = "'$id_guru'";
        }
    } else {
        // Jika id_guru kosong atau bukan angka, set ke NULL
        $id_guru = "NULL";
    }

    $update = mysqli_query($connect, "
        UPDATE siswa 
        SET nama_siswa='$nama_siswa', kelas='$kelas', id_guru=$id_guru 
        WHERE id_siswa='$id'
    ");

    if ($update) {
        header('location:list.php?update_success=1');
    } else {
        echo 'gagal update data: ' . mysqli_error($connect);
    }
} else {
    header('location:list.php');
}
