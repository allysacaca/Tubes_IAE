<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow border-0">
                <div class="card-body p-5">
                    <!-- Header -->
                    <div class="text-center mb-4">
                        <h2 class="fw-bold text-primary">
                            <i class="bi bi-person-plus me-2"></i>Registrasi
                        </h2>
                        <p class="text-muted">Buat akun perpustakaan baru</p>
                    </div>

                    <!-- Flash Messages -->
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <?= session()->getFlashdata('error') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <?= session()->getFlashdata('success') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Register Form -->
                    <form action="/register/process" method="post">
                        <?= csrf_field() ?>
                        
                        <!-- Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Lengkap</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-person"></i>
                                </span>
                                <input type="text" 
                                       class="form-control <?= session('errors.name') ? 'is-invalid' : '' ?>" 
                                       id="name" 
                                       name="name" 
                                       value="<?= old('name') ?>" 
                                       placeholder="Nama lengkap"
                                       required>
                                <?php if (session('errors.name')): ?>
                                    <div class="invalid-feedback">
                                        <?= session('errors.name') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-envelope"></i>
                                </span>
                                <input type="email" 
                                       class="form-control <?= session('errors.email') ? 'is-invalid' : '' ?>" 
                                       id="email" 
                                       name="email" 
                                       value="<?= old('email') ?>" 
                                       placeholder="nama@email.com"
                                       required>
                                <?php if (session('errors.email')): ?>
                                    <div class="invalid-feedback">
                                        <?= session('errors.email') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-lock"></i>
                                </span>
                                <input type="password" 
                                       class="form-control <?= session('errors.password') ? 'is-invalid' : '' ?>" 
                                       id="password" 
                                       name="password" 
                                       placeholder="Minimal 6 karakter"
                                       required>
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <?php if (session('errors.password')): ?>
                                    <div class="invalid-feedback">
                                        <?= session('errors.password') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-lock-fill"></i>
                                </span>
                                <input type="password" 
                                       class="form-control <?= session('errors.password_confirmation') ? 'is-invalid' : '' ?>" 
                                       id="password_confirmation" 
                                       name="password_confirmation" 
                                       placeholder="Ulangi password"
                                       required>
                                <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <?php if (session('errors.password_confirmation')): ?>
                                    <div class="invalid-feedback">
                                        <?= session('errors.password_confirmation') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-person-plus me-2"></i>Daftar
                            </button>
                        </div>

                        <!-- Links -->
                        <div class="text-center">
                            <p class="mb-0">
                                Sudah punya akun? 
                                <a href="/login" class="text-decoration-none">Login disini</a>
                            </p>
                        </div>
                    </form>

                    <!-- Demo Info -->
                    <div class="mt-4 p-3 bg-light rounded">
                        <h6 class="mb-2"><i class="bi bi-info-circle me-1"></i>Informasi Demo:</h6>
                        <p class="mb-0 small">Gunakan email valid untuk simulasi registrasi. Data akan disimulasikan untuk testing.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Toggle password visibility
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordInput = document.getElementById('password');
        const icon = this.querySelector('i');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    });

    document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
        const passwordInput = document.getElementById('password_confirmation');
        const icon = this.querySelector('i');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    });

    // Auto-focus name field
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('name').focus();
    });
</script>
<?= $this->endSection() ?>