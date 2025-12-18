<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title . ' - ' : '' ?>Perpustakaan Online</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #4F46E5;
            --secondary-color: #10B981;
        }
        
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .navbar-brand {
            font-weight: 700;
            color: var(--primary-color) !important;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: #4338CA;
            border-color: #4338CA;
        }
        
        .card {
            border: none;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .book-cover {
            height: 250px;
            object-fit: cover;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        footer {
            margin-top: auto;
            background-color: #f8f9fa;
        }
        
        .sidebar {
            min-height: calc(100vh - 56px);
            background-color: #f8f9fa;
        }
        
        .sidebar .nav-link {
            color: #495057;
            padding: 0.75rem 1rem;
            border-radius: 0.25rem;
            margin-bottom: 0.25rem;
        }
        
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: var(--primary-color);
            color: white;
        }
        
        .stat-card {
            background: linear-gradient(135deg, var(--primary-color) 0%, #6366F1 100%);
            color: white;
            border-radius: 10px;
            padding: 1.5rem;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="bi bi-book-fill me-2"></i>
                Perpustakaan Online
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/books">Katalog Buku</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/books/recommendations">Rekomendasi</a>
                    </li>
                    <?php if (session()->has('isLoggedIn')): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/dashboard">Dashboard</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i>
                                <?= esc(session()->get('user')['name']) ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="/dashboard/profile">
                                    <i class="bi bi-person"></i> Profil
                                </a></li>
                                <li><a class="dropdown-item" href="/dashboard/transactions">
                                    <i class="bi bi-clock-history"></i> Riwayat
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="/logout">
                                    <i class="bi bi-box-arrow-right"></i> Logout
                                </a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/login">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-primary btn-sm ms-2" href="/register">Daftar</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <?php if (session()->has('success')): ?>
        <div class="container mt-3">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>
                <?= session('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    <?php endif; ?>

    <?php if (session()->has('error')): ?>
        <div class="container mt-3">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <?= session('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    <?php endif; ?>

    <!-- Main Content -->
    <main class="flex-grow-1">
        <?= $this->renderSection('content') ?>
    </main>

    <!-- Footer -->
    <footer class="py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>Perpustakaan Online</h5>
                    <p class="text-muted">Sistem manajemen perpustakaan modern untuk kemudahan akses buku.</p>
                </div>
                <div class="col-md-3">
                    <h6>Quick Links</h6>
                    <ul class="list-unstyled">
                        <li><a href="/" class="text-decoration-none text-muted">Home</a></li>
                        <li><a href="/books" class="text-decoration-none text-muted">Katalog</a></li>
                        <li><a href="/about" class="text-decoration-none text-muted">Tentang</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h6>Kontak</h6>
                    <ul class="list-unstyled text-muted">
                        <li><i class="bi bi-envelope me-2"></i>info@perpustakaan.com</li>
                        <li><i class="bi bi-telephone me-2"></i>(021) 1234-5678</li>
                    </ul>
                </div>
            </div>
            <hr>
            <div class="text-center text-muted">
                <p class="mb-0">&copy; <?= date('Y') ?> Perpustakaan Online. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <?= $this->renderSection('scripts') ?>
</body>
</html>