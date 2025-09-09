<?php
include '../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Periksa apakah semua field telah diisi
    if (isset($_POST['nama']) && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['jenis_kelamin']) && isset($_POST['role'])) {
        
        $nama = $_POST['nama'];
        $username = $_POST['username'];
        $password = $_POST['password']; // Tanpa enkripsi
        $jenis_kelamin = $_POST['jenis_kelamin'];
        $role = $_POST['role'];
        
        // Cek apakah username sudah ada
        $check_username = mysqli_query($connect, "SELECT username FROM users WHERE username='$username'");
        if (mysqli_num_rows($check_username) > 0) {
            header('location:register.php?error=username_exists');
            exit();
        }

        // Insert data ke tabel users
        $insert_user = mysqli_query($connect, "INSERT INTO users (nama, username, password, jenis_kelamin, role) VALUES ('$nama', '$username', '$password', '$jenis_kelamin', '$role')");

        if ($insert_user) {
            $id_user = mysqli_insert_id($connect);
            
            // Jika role adalah siswa, tambahkan ke tabel siswa dengan data lengkap
            if ($role == 'siswa') {
                // Ambil data tambahan untuk siswa
                $kelas = isset($_POST['kelas']) ? $_POST['kelas'] : '';
                $id_guru = isset($_POST['id_guru']) && !empty($_POST['id_guru']) ? (int)$_POST['id_guru'] : "NULL";
                
                // Validasi id_guru
                if ($id_guru != "NULL") {
                    $check_guru = mysqli_query($connect, "SELECT id_guru FROM guru WHERE id_guru = $id_guru");
                    if (mysqli_num_rows($check_guru) == 0) {
                        $id_guru = "NULL";
                    }
                }
                
                $insert_siswa = mysqli_query($connect, "INSERT INTO siswa (nama_siswa, kelas, id_guru) VALUES ('$nama', '$kelas', $id_guru)");
                
                if (!$insert_siswa) {
                    echo 'gagal insert data siswa: ' . mysqli_error($connect);
                }
            }
            // Jika role adalah guru, tambahkan ke tabel guru dengan data lengkap
            else if ($role == 'guru') {
                // Ambil data tambahan untuk guru
                $mata_pelajaran = isset($_POST['mata_pelajaran']) ? $_POST['mata_pelajaran'] : '';
                
                // Pastikan mata_pelajaran tidak kosong
                if (empty($mata_pelajaran)) {
                    $mata_pelajaran = 'Belum ditentukan'; // Nilai default jika kosong
                }
                
                $insert_guru = mysqli_query($connect, "INSERT INTO guru (nama_guru, mata_pelajaran) VALUES ('$nama', '$mata_pelajaran')");
                
                if (!$insert_guru) {
                    echo 'gagal insert data guru: ' . mysqli_error($connect);
                }
            }
            
            header('location:login.php?register_success=1');
        } else {
            echo 'gagal insert data user: ' . mysqli_error($connect);
        }
    } else {
        header('location:register.php?error=missing_fields');
    }
} else {
    header('location:register.php');
}
?>