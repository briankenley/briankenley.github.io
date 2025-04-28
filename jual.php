<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Jual Mobil</title>
    <link rel="stylesheet" href="style.css">
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    />
  </head>
<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize inputs
    $user_id = $_SESSION['userid'] ?? 0;
    $make = $conn->real_escape_string($_POST['name']);
    $model = $conn->real_escape_string($_POST['model']);
    $year = intval($_POST['year']);
    $price = floatval($_POST['price']);
    $mileage = intval($_POST['mileage']);
    $description = $conn->real_escape_string($_POST['description']);
    // For image, assuming upload handling will be added later
    $image = ''; // Placeholder for image path or name

    // Insert into database
    $sql = "INSERT INTO cars (user_id, make, model, year, price, mileage, description, image) VALUES ($user_id, '$make', '$model', $year, $price, $mileage, '$description', '$image')";
    if ($conn->query($sql) === TRUE) {
        $success_message = "Listing submitted successfully.";
    } else {
        $error_message = "Error: " . $conn->error;
    }
}
?>
  <body>
    <nav
      class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top"
    >
      <div class="container">
        <a class="navbar-brand" href="#"
          >MobilKu<span style="color: #f39c12">pedia</span></a
        >
        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarNav"
          aria-controls="navbarNav"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav me-auto">
            <li class="nav-item">
              <a class="nav-link active" href="mobilkupedia.php">Beranda</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="dashboard.php">Dashboard</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="jual.php">Jual Mobil</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="beli.php">Beli Mobil</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="about.php">Tentang Kami</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="kontak.php">Kontak</a>
            </li>
          </ul>
          <div class="d-flex">
<?php if (!isset($_SESSION['userid'])): ?>
            <a href="login.php" class="btn btn-outline-primary me-2">Masuk</a>
            <a href="signup.php" class="btn btn-primary">Daftar</a>
<?php endif; ?>
          </div>
        </div>
      </div>
    </nav>

    <header class="text-white text-center py-5" style="background-color: #f39c12;">
      <h1>Jual Mobil Anda</h1>
      <p>Penawaran terbaik untuk mobil baru dan bekas</p>
    </header>


    
    <div class="container mt-5" id="sell">
        <h2 class="text-center">Jual Mobil Anda</h2>
        <form method="POST" action="jual.php" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="make" class="form-label">Nama Mobil</label>
                <input type="text" name="name" class="form-control" id="name" required>
            </div>
            <div class="mb-3">
                <label for="model" class="form-label">Model Mobil</label>
                <input type="text" name="model" class="form-control" id="model" required>
            </div>
            <div class="mb-3">
                <label for="year" class="form-label">Tahun</label>
                <input type="number" name="year" class="form-control" id="year" required>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Harga</label>
                <input type="number" name="price" class="form-control" id="price" required>
            </div>
            <div class="mb-3">
                <label for="mileage" class="form-label">Jarak Tempuh (km)</label>
                <input type="number" name="mileage" class="form-control" id="mileage" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Deskripsi</label>
                <textarea name="description" class="form-control" id="description" rows="4" required></textarea>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Upload Car Image</label>
                <input type="file" name="image" class="form-control" id="image" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit Listing</button>
        </form>
    </div>

      </form>
    </div>
    <footer class="py-5 mt-5" id="kontak">
        <div class="container">
          <div class="row g-4">
            <div class="col-lg-4">
              <h5 class="mb-3">MobilKupedia</h5>
              <p class="">
                Platform terpercaya untuk jual beli mobil dengan proses yang
                mudah, aman, dan transparan.
              </p>
              <div class="d-flex gap-3 mt-3">
                <a href="#" class="text-white bg-primary p-2 rounded-circle"
                  ><i class="fab fa-facebook-f"></i
                ></a>
                <a href="#" class="text-white bg-primary p-2 rounded-circle"
                  ><i class="fab fa-instagram"></i
                ></a>
                <a href="#" class="text-white bg-primary p-2 rounded-circle"
                  ><i class="fab fa-twitter"></i
                ></a>
                <a href="#" class="text-white bg-primary p-2 rounded-circle"
                  ><i class="fab fa-youtube"></i
                ></a>
              </div>
            </div>
            <div class="col-lg-2 col-md-4 footer-links">
              <h5 class="mb-3">Layanan</h5>
              <ul class="list-unstyled">
                <li class="mb-2"><a href="#">Jual Mobil</a></li>
                <li class="mb-2"><a href="#">Beli Mobil</a></li>
                <li class="mb-2"><a href="#">Katalog Mobil</a></li>
                <li class="mb-2"><a href="#">Verifikasi Mobil</a></li>
              </ul>
            </div>
            <div class="col-lg-2 col-md-4 footer-links">
              <h5 class="mb-3">Perusahaan</h5>
              <ul class="list-unstyled">
                <li class="mb-2"><a href="#">Tentang Kami</a></li>
                <li class="mb-2"><a href="#">Karir</a></li>
                <li class="mb-2"><a href="#">Blog</a></li>
                <li class="mb-2"><a href="#">FAQ</a></li>
              </ul>
            </div>
            <div class="col-lg-4 col-md-4">
              <h5 class="mb-3">Kontak</h5>
              <ul class="list-unstyled">
                <li class="mb-2">
                  <i class="fas fa-map-marker-alt me-2"></i> Medan
                </li>
                <li class="mb-2">
                  <i class="fas fa-phone me-2"></i> +62-813-6789-7890
                </li>
                <li class="mb-2">
                  <i class="fas fa-envelope me-2"></i> mobilkupedia@gmail.com
                </li>
                <li class="mb-2">
                  <i class="fas fa-clock me-2"></i> Senin - Jumat: 08.00 - 17.00
                </li>
              </ul>
            </div>
          </div>
          <hr class="my-4 bg-secondary" />
          <div class="row">
            <div class="col-md-6">
              <p class="mb-0">&copy; 2025 MobilKupedia. Hak Cipta Dilindungi.</p>
            </div>
            <div class="col-md-6 text-md-end footer-links">
              <a href="#" class="me-3">Kebijakan Privasi</a>
              <a href="#" class="me-3">Syarat & Ketentuan</a>
              <a href="#">Peta Situs</a>
            </div>
          </div>
        </div>
      </footer>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
