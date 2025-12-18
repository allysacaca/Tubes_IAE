<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<!-- Hero Section -->
<div class="bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="display-4 fw-bold mb-4">Perpustakaan Online</h1>
                <p class="lead mb-4">Akses ribuan koleksi buku digital dan fisik dengan mudah. Pinjam, baca, dan kembalikan kapan saja, dimana saja.</p>
                <div class="d-flex gap-3">
                    <?php if (session()->has('isLoggedIn')): ?>
                        <a href="/books" class="btn btn-light btn-lg">
                            <i class="bi bi-search me-2"></i>Jelajahi Buku
                        </a>
                        <a href="/dashboard" class="btn btn-outline-light btn-lg">
                            Dashboard
                        </a>
                    <?php else: ?>
                        <a href="/register" class="btn btn-light btn-lg">
                            <i class="bi bi-person-plus me-2"></i>Daftar Sekarang
                        </a>
                        <a href="/books" class="btn btn-outline-light btn-lg">
                            Lihat Katalog
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-6 text-center">
                <i class="bi bi-book" style="font-size: 15rem; opacity: 0.3;"></i>
            </div>
        </div>
    </div>
</div>

<!-- Features Section -->
<div class="container py-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold">Kenapa Memilih Kami?</h2>
        <p class="text-muted">Perpustakaan modern dengan berbagai kemudahan</p>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card text-center h-100 border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 80px; height: 80px;">
                        <i class="bi bi-book text-primary" style="font-size: 2rem;"></i>
                    </div>
                    <h5 class="card-title">Koleksi Lengkap</h5>
                    <p class="card-text text-muted">Ribuan judul buku dari berbagai kategori dan penulis ternama</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-center h-100 border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 80px; height: 80px;">
                        <i class="bi bi-lightning text-success" style="font-size: 2rem;"></i>
                    </div>
                    <h5 class="card-title">Peminjaman Mudah</h5>
                    <p class="card-text text-muted">Proses peminjaman cepat dan praktis, hanya dengan beberapa klik</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-center h-100 border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 80px; height: 80px;">
                        <i class="bi bi-clock-history text-warning" style="font-size: 2rem;"></i>
                    </div>
                    <h5 class="card-title">Tracking Real-time</h5>
                    <p class="card-text text-muted">Pantau status peminjaman dan jatuh tempo secara real-time</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Latest Books Section -->
<?php if (!empty($latest_books)): ?>
<div class="bg-light py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-2">Buku Terbaru</h2>
                <p class="text-muted mb-0">Koleksi buku terbaru di perpustakaan</p>
            </div>
            <a href="/books" class="btn btn-outline-primary">
                Lihat Semua <i class="bi bi-arrow-right ms-2"></i>
            </a>
        </div>

        <div class="row g-4">
            <?php foreach ($latest_books as $book): ?>
            <div class="col-md-4 col-lg-2">
                <div class="card h-100">
                    <?php if (!empty($book['cover_image'])): ?>
                        <img src="<?= base_url('storage/' . $book['cover_image']) ?>" 
                             class="book-cover card-img-top" alt="<?= esc($book['title']) ?>">
                    <?php else: ?>
                        <div class="book-cover card-img-top"></div>
                    <?php endif; ?>
                    
                    <div class="card-body">
                        <h6 class="card-title text-truncate" title="<?= esc($book['title']) ?>">
                            <?= esc($book['title']) ?>
                        </h6>
                        <p class="card-text text-muted small text-truncate mb-2">
                            <?= esc($book['author']) ?>
                        </p>
                        <a href="/books/<?= $book['id'] ?>" class="btn btn-sm btn-outline-primary w-100">
                            Detail
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Recommendations Section -->
<?php if (!empty($recommendations)): ?>
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-2">Buku Populer</h2>
            <p class="text-muted mb-0">Rekomendasi buku yang sering dipinjam</p>
        </div>
        <a href="/books/recommendations" class="btn btn-outline-primary">
            Lihat Semua <i class="bi bi-arrow-right ms-2"></i>
        </a>
    </div>

    <div class="row g-4">
        <?php foreach ($recommendations as $book): ?>
        <div class="col-md-4 col-lg-2">
            <div class="card h-100">
                <div class="book-cover card-img-top"></div>
                <div class="card-body">
                    <h6 class="card-title text-truncate" title="<?= esc($book['title']) ?>">
                        <?= esc($book['title']) ?>
                    </h6>
                    <p class="card-text text-muted small text-truncate mb-2">
                        <?= esc($book['author']) ?>
                    </p>
                    <a href="/books/<?= $book['id'] ?>" class="btn btn-sm btn-outline-primary w-100">
                        Detail
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<!-- CTA Section -->
<?php if (!session()->has('isLoggedIn')): ?>
<div class="bg-primary text-white py-5">
    <div class="container text-center">
        <h2 class="fw-bold mb-3">Siap Memulai?</h2>
        <p class="lead mb-4">Daftar sekarang dan nikmati akses ke ribuan koleksi buku</p>
        <a href="/register" class="btn btn-light btn-lg">
            <i class="bi bi-person-plus me-2"></i>Daftar Gratis
        </a>
    </div>
</div>
<?php endif; ?>

<!-- Stats Section -->
<div class="container py-5">
    <div class="row text-center g-4">
        <div class="col-md-3">
            <div class="display-4 fw-bold text-primary">1000+</div>
            <p class="text-muted">Koleksi Buku</p>
        </div>
        <div class="col-md-3">
            <div class="display-4 fw-bold text-primary">500+</div>
            <p class="text-muted">Member Aktif</p>
        </div>
        <div class="col-md-3">
            <div class="display-4 fw-bold text-primary">50+</div>
            <p class="text-muted">Kategori</p>
        </div>
        <div class="col-md-3">
            <div class="display-4 fw-bold text-primary">24/7</div>
            <p class="text-muted">Akses Online</p>
        </div>
    </div>
</div>
<?= $this->endSection() ?>