<?php
require_once '../../includes/auth.php';
requireLogin();

include '../../config/database.php';
$query = mysqli_query($connect, "SELECT s.*, g.nama_guru FROM siswa s LEFT JOIN guru g ON s.id_guru = g.id_guru");
$result = mysqli_fetch_all($query, MYSQLI_ASSOC);

include '../../includes/header.php';
?>

<div class="row mb-4">
    <div class="col-12">
        <h1>Daftar Siswa</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Daftar Siswa</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-6">
        <a href="add.php" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Tambah Data Siswa
        </a>
    </div>
    <div class="col-md-6">
        <form action="search.php" method="get" class="d-flex">
            <input type="text" class="form-control me-2" name="keyword" placeholder="Cari siswa..." value="<?php echo isset($_GET['keyword']) ? $_GET['keyword'] : '' ?>" />
            <button type="submit" class="btn btn-success">
                <i class="fas fa-search"></i>
            </button>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Data Siswa</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Siswa</th>
                                <th>Kelas</th>
                                <th>Wali Kelas</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($result as $siswa) : ?>
                                <tr>
                                    <td><?php echo $siswa['id_siswa'] ?></td>
                                    <td><?php echo $siswa['nama_siswa'] ?></td>
                                    <td><?php echo $siswa['kelas'] ?></td>
                                    <td><?php echo $siswa['nama_guru'] ?? '-' ?></td>
                                    <td>
                                        <a href="edit.php?id=<?php echo $siswa['id_siswa'] ?>" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="delete.php?id=<?php echo $siswa['id_siswa'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>