<?php
require_once '../../includes/auth.php';
requireLogin();

// Hanya guru yang bisa mengakses halaman ini
if ($_SESSION['role'] != 'guru') {
    header('location:../dashboard.php');
    exit();
}

include '../../config/database.php';

// Proses tambah guru
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'add') {
    $nama_guru = $_POST['nama_guru'];
    $mata_pelajaran = $_POST['mata_pelajaran'];

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

        header('location:manage.php?add_success=1');
    } else {
        echo 'gagal tambah data: ' . mysqli_error($connect);
    }
}

// Proses update guru
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'update') {
    $id_guru = $_POST['id_guru'];
    $nama_guru = $_POST['nama_guru'];
    $mata_pelajaran = $_POST['mata_pelajaran'];

    $update = mysqli_query($connect, "UPDATE guru SET nama_guru='$nama_guru', mata_pelajaran='$mata_pelajaran' WHERE id_guru='$id_guru'");

    if ($update) {
        // Update juga nama di tabel users
        mysqli_query($connect, "UPDATE users SET nama='$nama_guru' WHERE nama=(SELECT nama_guru FROM guru WHERE id_guru='$id_guru')");

        header('location:manage.php?update_success=1');
    } else {
        echo 'gagal update data: ' . mysqli_error($connect);
    }
}

// Proses hapus guru
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id_guru = $_GET['id'];

    // Ambil nama guru untuk dihapus dari tabel users
    $query = mysqli_query($connect, "SELECT nama_guru FROM guru WHERE id_guru='$id_guru'");
    $guru = mysqli_fetch_assoc($query);
    $nama_guru = $guru['nama_guru'];

    $delete = mysqli_query($connect, "DELETE FROM guru WHERE id_guru='$id_guru'");

    if ($delete) {
        // Hapus juga dari tabel users
        mysqli_query($connect, "DELETE FROM users WHERE nama='$nama_guru' AND role='guru'");

        // Update siswa yang memiliki id_guru ini menjadi NULL
        mysqli_query($connect, "UPDATE siswa SET id_guru=NULL WHERE id_guru='$id_guru'");

        header('location:manage.php?delete_success=1');
    } else {
        echo 'gagal hapus data: ' . mysqli_error($connect);
    }
}

// Ambil data guru
$query = mysqli_query($connect, "SELECT * FROM guru ORDER BY nama_guru");
$guru_list = mysqli_fetch_all($query, MYSQLI_ASSOC);

include '../../includes/header.php';
?>

<div class="row mb-4">
    <div class="col-12">
        <h1>Kelola Data Guru</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Kelola Data Guru</li>
            </ol>
        </nav>
    </div>
</div>

<?php
if (isset($_GET['add_success'])) {
    echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
            <i class='fas fa-check-circle me-2'></i>Data guru berhasil ditambahkan!
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
          </div>";
}
if (isset($_GET['update_success'])) {
    echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
            <i class='fas fa-check-circle me-2'></i>Data guru berhasil diperbarui!
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
          </div>";
}
if (isset($_GET['delete_success'])) {
    echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
            <i class='fas fa-check-circle me-2'></i>Data guru berhasil dihapus!
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
          </div>";
}
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Data Guru</h5>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addGuruModal">
                    <i class="fas fa-plus me-2"></i>Tambah Guru
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Guru</th>
                                <th>Mata Pelajaran</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($guru_list as $guru) : ?>
                                <tr>
                                    <td><?php echo $guru['id_guru'] ?></td>
                                    <td><?php echo $guru['nama_guru'] ?></td>
                                    <td><?php echo $guru['mata_pelajaran'] ?></td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editGuruModal<?php echo $guru['id_guru'] ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <a href="?action=delete&id=<?php echo $guru['id_guru'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>

                                <!-- Modal Edit Guru -->
                                <div class="modal fade" id="editGuruModal<?php echo $guru['id_guru'] ?>" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Data Guru</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="manage.php" method="post">
                                                <div class="modal-body">
                                                    <input type="hidden" name="action" value="update">
                                                    <input type="hidden" name="id_guru" value="<?php echo $guru['id_guru'] ?>">
                                                    <div class="mb-3">
                                                        <label for="nama_guru<?php echo $guru['id_guru'] ?>" class="form-label">Nama Guru</label>
                                                        <input type="text" class="form-control" id="nama_guru<?php echo $guru['id_guru'] ?>" name="nama_guru" value="<?php echo $guru['nama_guru'] ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="mata_pelajaran<?php echo $guru['id_guru'] ?>" class="form-label">Mata Pelajaran</label>
                                                        <input type="text" class="form-control" id="mata_pelajaran<?php echo $guru['id_guru'] ?>" name="mata_pelajaran" value="<?php echo $guru['mata_pelajaran'] ?>" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Guru -->
<div class="modal fade" id="addGuruModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data Guru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="manage.php" method="post">
                <div class="modal-body">
                    <input type="hidden" name="action" value="add">
                    <div class="mb-3">
                        <label for="nama_guru" class="form-label">Nama Guru</label>
                        <input type="text" class="form-control" id="nama_guru" name="nama_guru" placeholder="Nama Guru" required>
                    </div>
                    <div class="mb-3">
                        <label for="mata_pelajaran" class="form-label">Mata Pelajaran</label>
                        <input type="text" class="form-control" id="mata_pelajaran" name="mata_pelajaran" placeholder="Mata Pelajaran" required>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>Akun user akan dibuat otomatis dengan username berdasarkan nama guru dan password default: <strong>guru123</strong>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Tambah Guru</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>