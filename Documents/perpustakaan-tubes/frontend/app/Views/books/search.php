<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="container py-5">
    <!-- Back Button -->
    <a href="/books" class="btn btn-outline-secondary mb-4">
        <i class="bi bi-arrow-left me-1"></i>Kembali ke Katalog
    </a>

    <!-- Search Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="fw-bold">
                <i class="bi bi-search me-2"></i>Hasil Pencarian
            </h2>
            <p class="text-muted">
                Menampilkan hasil untuk: "<strong><?= esc($query) ?></strong>"
            </p>
        </div>
        <div class="col-md-4">
            <form action="/books/search" method="get" class="d-flex">
                <input type="text" class="form-control me-2" name="q" 
                       placeholder="Cari buku lain..." value="<?= esc($query ?? '') ?>">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Results -->
    <?php if (!empty($books)): ?>
        <div class="row g-4">
            <?php foreach ($books as $book): ?>
            <div class="col-md-4 col-lg-3">
                <div class="card h-100 shadow-sm border-0">
                    <?php if (!empty($book['cover_image'])): ?>
                        <img src="<?= base_url('storage/' . $book['cover_image']) ?>" 
                             class="card-img-top book-cover" alt="<?= esc($book['title']) ?>">
                    <?php else: ?>
                        <div class="card-img-top book-cover d-flex align-items-center justify-content-center">
                            <i class="bi bi-book" style="font-size: 3rem; color: #6c757d;"></i>
                        </div>
                    <?php endif; ?>
                    
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title"><?= esc($book['title']) ?></h6>
                        <p class="card-text text-muted small mb-2"><?= esc($book['author']) ?></p>
                        
                        <?php if (($book['available_stock'] ?? 0) > 0): ?>
                            <span class="badge bg-success mb-2">Tersedia</span>
                        <?php else: ?>
                            <span class="badge bg-danger mb-2">Tidak Tersedia</span>
                        <?php endif; ?>
                        
                        <div class="mt-auto">
                            <a href="/books/<?= $book['id'] ?>" class="btn btn-sm btn-outline-primary w-100">
                                <i class="bi bi-eye me-1"></i>Detail
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="bi bi-search" style="font-size: 4rem; color: #dee2e6;"></i>
            </div>
            <h4 class="mb-3">Tidak ditemukan hasil pencarian</h4>
            <p class="text-muted mb-4">
                Tidak ada buku yang cocok dengan "<strong><?= esc($query) ?></strong>"
            </p>
            <a href="/books" class="btn btn-primary">
                <i class="bi bi-house me-1"></i>Kembali ke Katalog
            </a>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>