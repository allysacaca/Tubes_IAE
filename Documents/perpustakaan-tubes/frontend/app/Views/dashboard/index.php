<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - <?= esc($title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="/dashboard">Perpustakaan Online</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="/dashboard">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/books">Books</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/dashboard/profile">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/dashboard/transactions">Transactions</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/logout">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-4">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>
        
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Welcome, <?= esc($user['name']) ?>!</h4>
                    </div>
                    <div class="card-body">
                        <h5>Dashboard Overview</h5>
                        <div class="row mt-4">
                            <div class="col-md-3">
                                <div class="card text-white bg-primary mb-3">
                                    <div class="card-body">
                                        <h5 class="card-title">Borrowed Books</h5>
                                        <p class="card-text display-6">0</p>
                                        <a href="/my-books" class="text-white">View Details</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-white bg-success mb-3">
                                    <div class="card-body">
                                        <h5 class="card-title">Available Books</h5>
                                        <p class="card-text display-6">50</p>
                                        <a href="/books" class="text-white">Browse Books</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-white bg-warning mb-3">
                                    <div class="card-body">
                                        <h5 class="card-title">Pending Returns</h5>
                                        <p class="card-text display-6">0</p>
                                        <a href="/dashboard/transactions" class="text-white">View All</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-white bg-info mb-3">
                                    <div class="card-body">
                                        <h5 class="card-title">Fines</h5>
                                        <p class="card-text display-6">Rp 0</p>
                                        <a href="/dashboard/fines" class="text-white">Pay Now</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h6>Recent Activity</h6>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-group">
                                            <li class="list-group-item">Logged in successfully</li>
                                            <li class="list-group-item">No recent transactions</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h6>Quick Actions</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-grid gap-2">
                                            <a href="/books" class="btn btn-primary">Browse Books</a>
                                            <a href="/dashboard/profile" class="btn btn-outline-secondary">Edit Profile</a>
                                            <a href="/dashboard/transactions" class="btn btn-outline-info">View Transactions</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>