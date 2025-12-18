<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow border-0">
                <div class="card-body p-5">
                    <!-- Logo/Header -->
                    <div class="text-center mb-4">
                        <h2 class="fw-bold text-primary">
                            <i class="bi bi-person-circle me-2"></i>Login
                        </h2>
                        <p class="text-muted">Masuk ke akun perpustakaan Anda</p>
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

                    <!-- Login Form -->
                    <form action="/login/process" method="post">
                        <?= csrf_field() ?>
                        
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
                                       value="<?= old('email', 'user@example.com') ?>" 
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
                        <div class="mb-4">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-lock"></i>
                                </span>
                                <input type="password" 
                                       class="form-control <?= session('errors.password') ? 'is-invalid' : '' ?>" 
                                       id="password" 
                                       name="password" 
                                       value="<?= old('password', 'password123') ?>" 
                                       placeholder="Masukkan password"
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

                        <!-- Remember Me -->
                        <div class="mb-4 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">Ingat saya</label>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
                            </button>
                        </div>

                        <!-- Links -->
                        <div class="text-center">
                            <p class="mb-2">
                                Belum punya akun? 
                                <a href="/register" class="text-decoration-none">Daftar disini</a>
                            </p>
                            <p class="mb-0">
                                <a href="/" class="text-decoration-none">
                                    <i class="bi bi-house me-1"></i>Kembali ke Beranda
                                </a>
                            </p>
                        </div>
                    </form>

                    <!-- Demo Credentials -->
                    <div class="mt-4 p-3 bg-light rounded">
                        <h6 class="mb-2"><i class="bi bi-info-circle me-1"></i>Demo Credentials:</h6>
                        <p class="mb-1 small">Email: <code>user@example.com</code></p>
                        <p class="mb-0 small">Password: <code>password123</code></p>
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

    // Auto-focus email field
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('email').focus();
    });
</script>
<?= $this->endSection() ?>