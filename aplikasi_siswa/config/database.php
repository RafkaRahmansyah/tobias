<?php
$connect = mysqli_connect("localhost", "root", "", "nyoba", 3307);
if (!$connect) {
    exit('gagal koneksi ke database');
}
