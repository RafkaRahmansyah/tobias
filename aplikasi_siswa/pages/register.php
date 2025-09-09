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
            <h2><i class="fas fa-user-plus me-2"></i>Register</h2>
            <p>Buat akun baru</p>
        </div>
        <div class="auth-body">
            <form action="register_process.php" method="post" class="needs-validation" novalidate>
                <div class="mb-3">
                    <label for="nama" class="form-label">Nama Lengkap</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama Lengkap" required>
                        <div class="invalid-feedback">
                            Masukkan nama lengkap Anda.
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-at"></i></span>
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
                <div class="mb-3">
                    <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-venus-mars"></i></span>
                        <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                            <option value="" selected disabled>Pilih Jenis Kelamin</option>
                            <option value="Pria">Pria</option>
                            <option value="Wanita">Wanita</option>
                        </select>
                        <div class="invalid-feedback">
                            Pilih jenis kelamin Anda.
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="role" class="form-label">Role</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
                        <select class="form-select" id="role" name="role" required>
                            <option value="" selected disabled>Pilih Role</option>
                            <option value="siswa">Siswa</option>
                            <option value="guru">Guru</option>
                        </select>
                        <div class="invalid-feedback">
                            Pilih role Anda.
                        </div>
                    </div>
                </div>

                <!-- Field untuk Siswa -->
                <div id="siswaFields" style="display: none;">
                    <div class="mb-3">
                        <label for="kelas" class="form-label">Kelas</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-school"></i></span>
                            <input type="text" class="form-control" id="kelas" name="kelas" placeholder="Contoh: 10 IPA 1">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="id_guru" class="form-label">Wali Kelas</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-chalkboard-teacher"></i></span>
                            <select class="form-select" id="id_guru" name="id_guru">
                                <option value="">-- Pilih Guru --</option>
                                <?php
                                include '../config/database.php';
                                $guru_query = mysqli_query($connect, "SELECT * FROM guru");
                                while ($guru = mysqli_fetch_assoc($guru_query)) {
                                    echo "<option value='" . $guru['id_guru'] . "'>" . $guru['nama_guru'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Field untuk Guru -->
                <div id="guruFields" style="display: none;">
                    <div class="mb-3">
                        <label for="mata_pelajaran" class="form-label">Mata Pelajaran</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-book"></i></span>
                            <input type="text" class="form-control" id="mata_pelajaran" name="mata_pelajaran" placeholder="Contoh: Matematika" required>
                            <div class="invalid-feedback">
                                Masukkan mata pelajaran yang diajar.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Register</button>
                </div>
                <div class="text-center mt-3">
                    <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Tampilkan/sembunyikan field berdasarkan role yang dipilih
    document.getElementById('role').addEventListener('change', function() {
        const siswaFields = document.getElementById('siswaFields');
        const guruFields = document.getElementById('guruFields');

        // Sembunyikan semua field terlebih dahulu
        siswaFields.style.display = 'none';
        guruFields.style.display = 'none';

        // Hapus required dari semua field
        document.getElementById('kelas').removeAttribute('required');
        document.getElementById('mata_pelajaran').removeAttribute('required');

        // Tampilkan field yang sesuai dengan role
        if (this.value === 'siswa') {
            siswaFields.style.display = 'block';
            document.getElementById('kelas').setAttribute('required', 'required');
        } else if (this.value === 'guru') {
            guruFields.style.display = 'block';
            document.getElementById('mata_pelajaran').setAttribute('required', 'required');
        }
    });

    // Validasi form sebelum submit
    document.querySelector('form').addEventListener('submit', function(event) {
        const role = document.getElementById('role').value;

        if (role === 'guru') {
            const mataPelajaran = document.getElementById('mata_pelajaran').value;
            if (mataPelajaran.trim() === '') {
                event.preventDefault();
                event.stopPropagation();
                alert('Mohon isi mata pelajaran untuk guru');
            }
        } else if (role === 'siswa') {
            const kelas = document.getElementById('kelas').value;
            if (kelas.trim() === '') {
                event.preventDefault();
                event.stopPropagation();
                alert('Mohon isi kelas untuk siswa');
            }
        }

        // Tambahkan class was-validated untuk menampilkan feedback
        this.classList.add('was-validated');
    });
</script>

<?php include '../includes/footer.php'; ?>