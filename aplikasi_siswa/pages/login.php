<?php
require_once '../includes/auth.php';

// Jika user sudah login, redirect ke dashboard
if (isLoggedIn()) {
    header('location:dashboard.php');
    exit();
}

include '../includes/header.php';
?>

<div class="auth-container">
    <div class="card auth-card">
        <div class="auth-header">
            <h2><i class="fas fa-sign-in-alt me-2"></i>Login</h2>
            <p>Masuk ke akun Anda</p>
        </div>
        <div class="auth-body">
            <?php
            if (isset($_GET['register_success'])) {
                echo "<div class='alert alert-success'>Registrasi berhasil! Silakan login.</div>";
            }
            if (isset($_GET['login_failed'])) {
                echo "<div class='alert alert-danger'>Username atau password salah!</div>";
            }
            ?>
            <form action="login_process.php" method="post" class="needs-validation" novalidate>
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                        <div class="invalid-feedback">
                            Masukkan username Anda.
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                        <div class="invalid-feedback">
                            Masukkan password Anda.
                        </div>
                    </div>
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Login</button>
                </div>
                <div class="text-center mt-3">
                    <p>Belum punya akun? <a href="register.php">Register di sini</a></p>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>