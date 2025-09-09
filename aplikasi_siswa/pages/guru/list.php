<?php
require_once '../../includes/auth.php';
requireLogin();

// Hanya guru yang bisa mengakses halaman ini
if ($_SESSION['role'] != 'guru') {
    header('location:../dashboard.php');
    exit();
}

include '../../config/database.php';
$query = mysqli_query($connect, "SELECT * FROM guru");
$result = mysqli_fetch_all($query, MYSQLI_ASSOC);

include '../../includes/header.php';
?>

<div class="row mb-4">
    <div class="col-12">
        <h1>Daftar Guru</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Daftar Guru</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Data Guru</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Guru</th>
                                <th>Mata Pelajaran</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($result as $guru) : ?>
                                <tr>
                                    <td><?php echo $guru['id_guru'] ?></td>
                                    <td><?php echo $guru['nama_guru'] ?></td>
                                    <td><?php echo $guru['mata_pelajaran'] ?></td>
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