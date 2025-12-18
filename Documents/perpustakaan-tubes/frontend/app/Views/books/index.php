<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="container py-5">
    <!-- API Status Alert -->
    <?php if ($apiStatus === 'disconnected'): ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <h5><i class="bi bi-exclamation-triangle-fill me-2"></i> API Connection Issue</h5>
            <p class="mb-1"><?= esc($apiMessage) ?></p>
            <small>API URL: <code><?= esc($apiUrl) ?></code></small>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            <div class="mt-2">
                <a href="<?= base_url('books/debug') ?>" class="btn btn-sm btn-outline-warning me-2">
                    <i class="bi bi-bug"></i> Debug Info
                </a>
                <a href="<?= base_url('books/test') ?>" target="_blank" class="btn btn-sm btn-outline-info">
                    <i class="bi bi-plug"></i> Test API
                </a>
            </div>
        </div>
    <?php endif; ?>

    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="fw-bold">📚 Katalog Buku</h2>
            <p class="text-muted">Jelajahi koleksi buku perpustakaan kami</p>
        </div>
        <div class="col-md-4">
            <form action="/books" method="get" class="d-flex">
                <input type="text" class="form-control me-2" name="search" 
                       placeholder="Cari judul, penulis, atau ISBN..." 
                       value="<?= esc($currentSearch ?? '') ?>">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Category Filters -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex flex-wrap gap-2">
                <a href="/books" 
                   class="btn btn-outline-primary <?= empty($currentCategory ?? '') ? 'active' : '' ?>">
                    Semua Kategori
                </a>
                <?php if (!empty($categories)): ?>
                    <?php foreach ($categories as $cat): ?>
                    <a href="/books?category=<?= urlencode($cat) ?>" 
                       class="btn btn-outline-primary <?= ($currentCategory ?? '') === $cat ? 'active' : '' ?>">
                        <?= esc($cat) ?>
                    </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <span class="text-muted">Tidak ada kategori tersedia</span>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Books Grid -->
    <?php if (!empty($books)): ?>
    <div class="row g-4">
        <?php foreach ($books as $book): ?>
        <div class="col-md-4 col-lg-3">
            <div class="card h-100 shadow-sm border-0">
                <!-- Book Cover -->
                <?php if (!empty($book['cover_image'])): ?>
                    <img src="<?= base_url('storage/' . $book['cover_image']) ?>" 
                         class="card-img-top book-cover" 
                         alt="<?= esc($book['title']) ?>"
                         style="height: 200px; object-fit: cover;">
                <?php else: ?>
                    <div class="card-img-top book-cover d-flex align-items-center justify-content-center bg-light" 
                         style="height: 200px;">
                        <i class="bi bi-book" style="font-size: 3rem; color: #6c757d;"></i>
                    </div>
                <?php endif; ?>
                
                <!-- Card Body -->
                <div class="card-body d-flex flex-column">
                    <h6 class="card-title fw-bold"><?= esc($book['title']) ?></h6>
                    <p class="card-text text-muted small mb-2">
                        <i class="bi bi-person me-1"></i><?= esc($book['author']) ?>
                    </p>
                    
                    <!-- Stock Status -->
                    <?php if (($book['available_stock'] ?? 0) > 0): ?>
                        <span class="badge bg-success mb-2">
                            <i class="bi bi-check-circle me-1"></i>Tersedia: <?= $book['available_stock'] ?>
                        </span>
                    <?php else: ?>
                        <span class="badge bg-danger mb-2">
                            <i class="bi bi-x-circle me-1"></i>Tidak Tersedia
                        </span>
                    <?php endif; ?>
                    
                    <!-- Category -->
                    <?php if (!empty($book['category'])): ?>
                        <span class="badge bg-info mb-2">
                            <i class="bi bi-tag me-1"></i><?= esc($book['category']) ?>
                        </span>
                    <?php endif; ?>
                    
                    <!-- Action Buttons -->
                    <div class="mt-auto pt-3">
                        <a href="/books/<?= $book['id'] ?>" 
                           class="btn btn-outline-primary btn-sm w-100 mb-2">
                            <i class="bi bi-eye me-1"></i>Detail Buku
                        </a>
                        
                        <?php if (($book['available_stock'] ?? 0) > 0): ?>
                            <?php if (session()->has('isLoggedIn')): ?>
                            <form action="/books/<?= $book['id'] ?>/borrow" method="post" class="mb-0">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-primary btn-sm w-100">
                                    <i class="bi bi-book me-1"></i>Pinjam Buku
                                </button>
                            </form>
                            <?php else: ?>
                            <a href="/login" class="btn btn-primary btn-sm w-100">
                                <i class="bi bi-box-arrow-in-right me-1"></i>Login untuk Pinjam
                            </a>
                            <?php endif; ?>
                        <?php else: ?>
                            <button class="btn btn-secondary btn-sm w-100" disabled>
                                <i class="bi bi-clock me-1"></i>Menunggu Stok
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <?php if (isset($pagination['last_page']) && $pagination['last_page'] > 1): ?>
    <nav aria-label="Page navigation" class="mt-5">
        <ul class="pagination justify-content-center">
            <!-- Previous -->
            <?php if (($pagination['current_page'] ?? 1) > 1): ?>
            <li class="page-item">
                <a class="page-link" 
                   href="?page=<?= ($pagination['current_page'] ?? 1) - 1 ?><?= ($currentSearch ?? '') ? '&search=' . urlencode($currentSearch) : '' ?><?= ($currentCategory ?? '') ? '&category=' . urlencode($currentCategory) : '' ?>">
                    <i class="bi bi-chevron-left"></i> Sebelumnya
                </a>
            </li>
            <?php endif; ?>

            <!-- Page Numbers -->
            <?php 
            $currentPage = $pagination['current_page'] ?? 1;
            $lastPage = $pagination['last_page'] ?? 1;
            $startPage = max(1, $currentPage - 2);
            $endPage = min($lastPage, $currentPage + 2);
            
            for ($i = $startPage; $i <= $endPage; $i++): 
            ?>
            <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
                <a class="page-link" 
                   href="?page=<?= $i ?><?= ($currentSearch ?? '') ? '&search=' . urlencode($currentSearch) : '' ?><?= ($currentCategory ?? '') ? '&category=' . urlencode($currentCategory) : '' ?>">
                    <?= $i ?>
                </a>
            </li>
            <?php endfor; ?>

            <!-- Next -->
            <?php if ($currentPage < $lastPage): ?>
            <li class="page-item">
                <a class="page-link" 
                   href="?page=<?= $currentPage + 1 ?><?= ($currentSearch ?? '') ? '&search=' . urlencode($currentSearch) : '' ?><?= ($currentCategory ?? '') ? '&category=' . urlencode($currentCategory) : '' ?>">
                    Selanjutnya <i class="bi bi-chevron-right"></i>
                </a>
            </li>
            <?php endif; ?>
        </ul>
    </nav>
    
    <!-- Pagination Info -->
    <div class="text-center text-muted mt-3">
        <small>
            Menampilkan <?= count($books) ?> dari <?= $pagination['total'] ?? count($books) ?> buku | 
            Halaman <?= $currentPage ?> dari <?= $lastPage ?>
        </small>
    </div>
    <?php endif; ?>

    <?php else: ?>
    <!-- No Books Found -->
    <div class="text-center py-5">
        <div class="mb-4">
            <i class="bi bi-inbox" style="font-size: 4rem; color: #dee2e6;"></i>
        </div>
        <h4 class="mb-3">Tidak ada buku ditemukan</h4>
        <p class="text-muted mb-4">
            <?php if (!empty($currentSearch)): ?>
                Tidak ditemukan hasil untuk "<strong><?= esc($currentSearch) ?></strong>"
            <?php elseif (!empty($currentCategory)): ?>
                Tidak ada buku dalam kategori "<strong><?= esc($currentCategory) ?></strong>"
            <?php else: ?>
                Katalog buku sedang kosong
            <?php endif; ?>
        </p>
        <a href="/books" class="btn btn-primary">
            <i class="bi bi-house me-1"></i>Kembali ke Katalog
        </a>
    </div>
    <?php endif; ?>

    <!-- Debug Info (only for development) -->
    <?php if (ENVIRONMENT !== 'production'): ?>
    <div class="mt-5 pt-4 border-top">
        <details class="small">
            <summary class="text-muted">🔍 Debug Info</summary>
            <div class="mt-2 p-3 bg-light rounded">
                <pre class="mb-0" style="font-size: 11px;"><?php 
                echo "API Status: " . ($apiStatus ?? 'unknown') . "\n";
                echo "Books Count: " . count($books ?? []) . "\n";
                echo "Categories Count: " . count($categories ?? []) . "\n";
                echo "Current Search: " . ($currentSearch ?? 'empty') . "\n";
                echo "Current Category: " . ($currentCategory ?? 'empty') . "\n";
                ?></pre>
            </div>
        </details>
    </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Auto-hide alerts after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    });
</script>
<?= $this->endSection() ?>