<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi Sewa Mobil</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/custom.css">
    <style>
        body, html {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
        .hero-section {
            background: url('assets/img/bg.jpg') no-repeat center center/cover;
            color: white;
            padding: 100px 0;
            text-align: center;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }
        .hero-section h1 {
            font-size: 3rem;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .hero-section p {
            font-size: 1.5rem;
            margin-bottom: 40px;
        }
        .hero-section .btn-lg {
            font-size: 1.2rem;
            padding: 10px 30px;
        }
        .services {
            padding: 50px 0;
        }
        .services .card {
            border: none;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        .services .card-body {
            text-align: center;
        }
        .services .card-title {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .services .card-text {
            font-size: 1.1rem;
            color: #555;
        }
        footer {
            background-color: #f8f9fa;
            padding: 30px 0;
            color: #666;
        }
        .card-img-top {
            height: 200px;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
            <div class="container">
                <a class="navbar-brand" href="index.php">Sewa Mobil</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">Home</a>
                        </li>
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <?php if ($_SESSION['role'] === 'admin'): ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="admin/index.php">Dasbor Admin</a>
                                </li>
                            <?php else: ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="user/index.php">Dasbor User</a>
                                </li>
                            <?php endif; ?>
                            <li class="nav-item">
                                <a class="nav-link" href="logout.php">Logout</a>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link" href="user/login.php">Login</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="user/register.php">Register</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <div class="hero-section">
        <div class="container">
            <h1>Selamat Datang di Aplikasi Sewa Mobil</h1>
            <p>Sewa mobil sekarang!</p>
            <a href="user/register.php" class="btn btn-primary btn-lg">Mulai Sekarang</a>
        </div>
    </div>

    <div class="container services mt-5">
        <h2 class="text-center mb-4">Layanan Kami</h2>
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <img src="assets/img/bg2.jpeg" class="card-img-top" alt="Layanan 1">
                    <div class="card-body">
                        <h5 class="card-title">Beragam Pilihan Mobil</h5>
                        <p class="card-text">Pilih dari berbagai macam mobil untuk setiap kebutuhan dan anggaran.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <img src="assets/img/bg3.jpeg" class="card-img-top" alt="Layanan 2">
                    <div class="card-body">
                        <h5 class="card-title">Harga Terjangkau</h5>
                        <p class="card-text">Kami menawarkan harga yang kompetitif untuk semua sewa mobil kami.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <img src="assets/img/bg1.jpeg" class="card-img-top" alt="Layanan 3">
                    <div class="card-body">
                        <h5 class="card-title">Dukungan Pelanggan 24/7</h5>
                        <p class="card-text">Tim dukungan pelanggan kami siap membantu Anda kapan saja.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
