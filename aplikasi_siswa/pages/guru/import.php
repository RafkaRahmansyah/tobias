<?php
require_once '../../includes/auth.php';
requireLogin();

// Hanya guru yang bisa mengakses halaman ini
if ($_SESSION['role'] != 'guru') {
    header('location:../dashboard.php');
    exit();
}

include '../../config/database.php';

// Proses import data
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['file'])) {
    $file = $_FILES['file']['tmp_name'];
    $handle = fopen($file, "r");

    $success = 0;
    $failed = 0;

    // Lewati baris pertama (header)
    fgetcsv($handle, 1000, ",");

    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $nama_guru = mysqli_real_escape_string($connect, $data[0]);
        $mata_pelajaran = mysqli_real_escape_string($connect, $data[1]);

        // Insert ke tabel guru
        $insert = mysqli_query($connect, "INSERT INTO guru (nama_guru, mata_pelajaran) VALUES ('$nama_guru', '$mata_pelajaran')");

        if ($insert) {
            // Tambahkan juga ke tabel users
            $username = strtolower(str_replace(' ', '', $nama_guru));
            $password = 'guru123'; // Password default

            // Cek apakah username sudah ada
            $check_username = mysqli_query($connect, "SELECT username FROM users WHERE username='$username'");
            if (mysqli_num_rows($check_username) > 0) {
                // Jika username sudah ada, tambahkan angka di belakangnya
                $i = 1;
                $temp_username = $username . $i;
                while (mysqli_num_rows(mysqli_query($connect, "SELECT username FROM users WHERE username='$temp_username'")) > 0) {
                    $i++;
                    $temp_username = $username . $i;
                }
                $username = $temp_username;
            }

            mysqli_query($connect, "INSERT INTO users (nama, username, password, jenis_kelamin, role) VALUES ('$nama_guru', '$username', '$password', 'Pria', 'guru')");

            $success++;
        } else {
            $failed++;
        }
    }

    fclose($handle);

    header('location:manage.php?import_success=1&success=' . $success . '&failed=' . $failed);
}

include '../../includes/header.php';
?>

<div class="row mb-4">
    <div class="col-12">
        <h1>Import Data Guru</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="manage.php">Kelola Data Guru</a></li>
                <li class="breadcrumb-item active">Import Data Guru</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Import Data Guru dari CSV</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <p>Format file CSV harus memiliki struktur sebagai berikut:</p>
                    <pre>nama_guru,mata_pelajaran
"Budi Santoso","Matematika"
"Citra Dewi","Bahasa Indonesia"</pre>
                    <p>Username akan dibuat otomatis berdasarkan nama guru dengan password default: <strong>guru123</strong></p>
                </div>

                <form action="import.php" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="file" class="form-label">Pilih File CSV</label>
                        <input type="file" class="form-control" id="file" name="file" accept=".csv" required>
                    </div>
                    <div class="d-flex justify-content-between">
                        <a href="manage.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-file-import me-2"></i>Import Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>