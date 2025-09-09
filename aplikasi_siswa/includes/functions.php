<?php
// Fungsi untuk membersihkan input
function cleanInput($data)
{
    global $connect;
    return mysqli_real_escape_string($connect, htmlspecialchars($data));
}

// Fungsi untuk menampilkan pesan
function showAlert($message, $type = 'info')
{
    echo "<div class='alert alert-$type'>$message</div>";
}

// Fungsi untuk memeriksa apakah guru ada di database
function isGuruExists($id_guru)
{
    global $connect;
    $id_guru = (int)$id_guru;
    $query = mysqli_query($connect, "SELECT id_guru FROM guru WHERE id_guru = $id_guru");
    return mysqli_num_rows($query) > 0;
}
