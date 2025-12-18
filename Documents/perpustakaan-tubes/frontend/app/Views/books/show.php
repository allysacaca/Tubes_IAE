<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="container py-5">
    <!-- Back Button -->
    <a href="/books" class="btn btn-outline-secondary mb-4">
        <i class="bi bi-arrow-left me-1"></i>Kembali ke Katalog
    </a>

    <?php if (empty($book)): ?>
        <div class="alert alert-danger">
            <h4><i class="bi bi-exclamation-triangle"></i> Buku Tidak Ditemukan</h4>
            <p>Buku yang Anda cari tidak tersedia atau telah dihapus.</p>
        </div>
    <?php else: ?>
        <div class="row">
            <!-- Book Cover -->
            <div class="col-md-4 mb-4">
                <div class="card shadow border-0">
                    <?php if (!empty($book['cover_image'])): ?>
                        <img src="<?= base_url('storage/' . $book['cover_image']) ?>" 
                             class="card-img-top" 
                             alt="<?= esc($book['title']) ?>"
                             style="max-height: 400px; object-fit: contain;">
                    <?php else: ?>
                        <div class="card-img-top d-flex align-items-center justify-content-center bg-light" 
                             style="height: 400px;">
                            <i class="bi bi-book" style="font-size: 5rem; color: #6c757d;"></i>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Book Details -->
            <div class="col-md-8">
                <div class="card shadow border-0 h-100">
                    <div class="card-body">
                        <h1 class="card-title fw-bold mb-3"><?= esc($book['title']) ?></h1>
                        
                        <div class="mb-4">
                            <h5 class="text-muted">
                                <i class="bi bi-person me-2"></i><?= esc($book['author']) ?>
                            </h5>
                        </div>

                        <!-- Status Badge -->
                        <div class="mb-4">
                            <?php if (($book['available_stock'] ?? $book['stock'] ?? 0) > 0): ?>
                                <span class="badge bg-success fs-6 p-3">
                                    <i class="bi bi-check-circle-fill me-2"></i>
                                    Tersedia: <?= $book['available_stock'] ?? $book['stock'] ?>
                                </span>
                            <?php else: ?>
                                <span class="badge bg-danger fs-6 p-3">
                                    <i class="bi bi-x-circle-fill me-2"></i>
                                    Tidak Tersedia
                                </span>
                            <?php endif; ?>
                        </div>

                        <!-- Book Information -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h6 class="text-muted mb-2">
                                    <i class="bi bi-upc-scan me-2"></i>ISBN
                                </h6>
                                <p class="fs-5"><?= esc($book['isbn'] ?? '-') ?></p>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-muted mb-2">
                                    <i class="bi bi-tag me-2"></i>Kategori
                                </h6>
                                <p class="fs-5"><?= esc($book['category'] ?? '-') ?></p>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-muted mb-2">
                                    <i class="bi bi-building me-2"></i>Penerbit
                                </h6>
                                <p class="fs-5"><?= esc($book['publisher'] ?? '-') ?></p>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-muted mb-2">
                                    <i class="bi bi-calendar me-2"></i>Tahun Terbit
                                </h6>
                                <p class="fs-5"><?= esc($book['publication_year'] ?? '-') ?></p>
                            </div>
                        </div>

                        <!-- Description -->
                        <?php if (!empty($book['description'])): ?>
                        <div class="mb-5">
                            <h5 class="mb-3">
                                <i class="bi bi-card-text me-2"></i>Deskripsi
                            </h5>
                            <div class="p-3 bg-light rounded">
                                <p class="mb-0"><?= nl2br(esc($book['description'])) ?></p>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-3">
                            <?php if (($book['available_stock'] ?? $book['stock'] ?? 0) > 0): ?>
                                <?php if (session()->has('isLoggedIn')): ?>
                                <form action="/books/<?= $book['id'] ?>/borrow" method="post" class="flex-grow-1">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-primary btn-lg w-100">
                                        <i class="bi bi-book me-2"></i>Pinjam Buku Ini
                                    </button>
                                </form>
                                <?php else: ?>
                                <a href="/login" class="btn btn-primary btn-lg w-100">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>Login untuk Meminjam
                                </a>
                                <?php endif; ?>
                            <?php else: ?>
                                <button class="btn btn-secondary btn-lg w-100" disabled>
                                    <i class="bi bi-clock me-2"></i>Buku Sedang Tidak Tersedia
                                </button>
                            <?php endif; ?>
                            
                            <a href="/books" class="btn btn-outline-secondary btn-lg">
                                <i class="bi bi-list me-2"></i>Lihat Buku Lain
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>