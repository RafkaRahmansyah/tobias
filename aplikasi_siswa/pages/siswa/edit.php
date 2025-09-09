<?php
require_once '../../includes/auth.php';
requireLogin();

include '../../config/database.php';
$id = $_GET['id'];
$query = mysqli_query($connect, "SELECT s.*, g.nama_guru FROM siswa s LEFT JOIN guru g ON s.id_guru = g.id_guru WHERE s.id_siswa='$id' LIMIT 1");
$result = mysqli_fetch_all($query, MYSQLI_ASSOC);

include '../../includes/header.php';
?>

<div class="row mb-4">
    <div class="col-12">
        <h1>Edit Data Siswa</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="list.php">Daftar Siswa</a></li>
                <li class="breadcrumb-item active">Edit Siswa</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Form Edit Siswa</h5>
            </div>
            <div class="card-body">
                <form action="update.php" method="post" class="needs-validation" novalidate>
                    <input type="hidden" name="id_siswa" value="<?php echo $result[0]['id_siswa'] ?>">
                    <div class="mb-3">
                        <label for="nama_siswa" class="form-label">Nama Siswa</label>
                        <input type="text" class="form-control" id="nama_siswa" name="nama_siswa" placeholder="Nama Siswa" value="<?php echo $result[0]['nama_siswa'] ?>" required>
                        <div class="invalid-feedback">
                            Masukkan nama siswa.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="kelas" class="form-label">Kelas</label>
                        <input type="text" class="form-control" id="kelas" name="kelas" placeholder="Kelas" value="<?php echo $result[0]['kelas'] ?>" required>
                        <div class="invalid-feedback">
                            Masukkan kelas siswa.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="id_guru" class="form-label">Wali Kelas</label>
                        <select class="form-select" id="id_guru" name="id_guru">
                            <option value="">-- Pilih Guru --</option>
                            <?php
                            $guru_query = mysqli_query($connect, "SELECT * FROM guru");
                            while ($guru = mysqli_fetch_assoc($guru_query)) {
                                $selected = ($guru['id_guru'] == $result[0]['id_guru']) ? 'selected' : '';
                                echo "<option value='" . $guru['id_guru'] . "' $selected>" . $guru['nama_guru'] . "</option>";
                            }
                            ?>
                        </select>
                        <div class="form-text">Kosongkan jika belum ada wali kelas</div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <a href="list.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>