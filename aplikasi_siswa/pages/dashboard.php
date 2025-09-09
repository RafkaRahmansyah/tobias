<?php
require_once '../includes/auth.php';
requireLogin();

include '../config/database.php';

// Hitung jumlah siswa
$query_siswa = mysqli_query($connect, "SELECT COUNT(*) as total FROM siswa");
$siswa_count = mysqli_fetch_assoc($query_siswa)['total'];

// Hitung jumlah guru
$query_guru = mysqli_query($connect, "SELECT COUNT(*) as total FROM guru");
$guru_count = mysqli_fetch_assoc($query_guru)['total'];

// Hitung jumlah user
$query_user = mysqli_query($connect, "SELECT COUNT(*) as total FROM users");
$user_count = mysqli_fetch_assoc($query_user)['total'];

include '../includes/header.php';
?>

<div class="row mb-4">
    <div class="col-12">
        <h1>Dashboard</h1>
        <p class="text-muted">Selamat datang, <strong><?php echo $_SESSION['nama']; ?></strong> di Sistem Informasi Siswa</p>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card stats-card primary">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title"><?php echo $siswa_count; ?></h4>
                        <p class="card-text">Total Siswa</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-user-graduate fa-2x text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card success">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title"><?php echo $guru_count; ?></h4>
                        <p class="card-text">Total Guru</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-chalkboard-teacher fa-2x text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card warning">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title"><?php echo $user_count; ?></h4>
                        <p class="card-text">Total User</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-users fa-2x text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card danger">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title"><?php echo $_SESSION['role']; ?></h4>
                        <p class="card-text">Role Anda</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-user-tag fa-2x text-danger"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Menu Cepat</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="siswa/list.php" class="btn btn-outline-primary w-100">
                            <i class="fas fa-list me-2"></i>Daftar Siswa
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="siswa/add.php" class="btn btn-outline-success w-100">
                            <i class="fas fa-plus me-2"></i>Tambah Siswa
                        </a>
                    </div>
                    <?php if ($_SESSION['role'] == 'guru'): ?>
                        <div class="col-md-3 mb-3">
                            <a href="guru/list.php" class="btn btn-outline-info w-100">
                                <i class="fas fa-chalkboard-teacher me-2"></i>Daftar Guru
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="guru/manage.php" class="btn btn-outline-warning w-100">
                                <i class="fas fa-cogs me-2"></i>Kelola Guru
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>