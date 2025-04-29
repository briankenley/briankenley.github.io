<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Beli Mobil</title>
  </head>
  <link rel="stylesheet" href="style.css" />
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css"
    rel="stylesheet"
  />
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
  />
<?php
session_start();
include 'db.php'; // Include database connection

// --- Search Logic ---
$search_keyword = isset($_GET['keyword']) ? trim(mysqli_real_escape_string($conn, $_GET['keyword'])) : '';

$sql = "SELECT id, make, model, year, price, mileage, image, description FROM cars"; // Added description

// Build WHERE clause for search
if (!empty($search_keyword)) {
    $sql .= " WHERE make LIKE '%$search_keyword%' OR model LIKE '%$search_keyword%' OR description LIKE '%$search_keyword%'";
}

$sql .= " ORDER BY created_at DESC"; // Default sorting

$result = $conn->query($sql);

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

    <section class="py-5 bg-light" id="beli">
      <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="section-title mb-0">Cari Mobil Impian Anda</h2>
            <?php // Add Car button for Admin
            if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <a href="jual.php" class="btn btn-success">
                    <i class="fas fa-plus me-1"></i> Tambah Mobil
                </a>
            <?php endif; ?>
        </div>

        <!-- Search Form -->
        <form action="beli.php" method="GET" class="search-form mb-4">
          <div class="row g-2 justify-content-center">
            <div class="col-md-6">
              <label for="searchInput" class="form-label visually-hidden">Kata Kunci Pencarian</label>
              <input type="text" class="form-control form-control-lg" id="searchInput" name="keyword" placeholder="Cari mobil (cth: Avanza, X- Trail, Jazz)" value="<?php echo htmlspecialchars($search_keyword); ?>">
            </div>
            <div class="col-md-2">
              <button type="submit" class="btn btn-primary btn-lg w-100">Cari Mobil</button>
            </div>
          </div>
        </form>
        <!-- End Search Form -->

        <!-- Car Listing -->
        <div class="row g-4">
          <?php if ($result && $result->num_rows > 0): ?>
            <?php while($car = $result->fetch_assoc()): ?>
              <div class="col-md-4">
                <div class="card car-card border-0 shadow-sm h-100">
                  <img
                    src="<?php echo !empty($car['image']) ? htmlspecialchars($car['image']) : 'placeholder.png'; // Use placeholder if no image ?>"
                    style="height: 250px; object-fit: cover;"
                    class="card-img-top"
                    alt="<?php echo htmlspecialchars($car['make']) . ' ' . htmlspecialchars($car['model']); ?>"
                  />
                  <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                      <h5 class="card-title mb-0"><?php echo htmlspecialchars($car['make']) . ' ' . htmlspecialchars($car['model']); ?></h5>
                      <span class="badge badge-custom"><?php echo htmlspecialchars($car['year']); ?></span>
                    </div>
                    <p class="text-primary fw-bold">Rp <?php echo number_format($car['price'], 0, ',', '.'); ?></p>
                    <div class="d-flex mb-3">
                      <div class="me-3">
                        <i class="fas fa-tachometer-alt text-muted me-1"></i>
                        <?php echo $car['mileage'] ? number_format($car['mileage'], 0, ',', '.') . ' km' : 'N/A'; ?>
                      </div>

                    </div>
                    <a href="detail.php?id=<?php echo $car['id']; ?>" class="btn btn-primary w-100 mt-auto">Lihat Detail</a>
                  </div>
                </div>
              </div>
            <?php endwhile; ?>
          <?php else: ?>
            <div class="col-12">
              <div class="alert alert-warning text-center" role="alert">
                Tidak ada mobil yang ditemukan dengan kata kunci "<?php echo htmlspecialchars($search_keyword); ?>". Silakan coba kata kunci lain.
              </div>
            </div>
          <?php endif; ?>
          <?php $conn->close(); // Close connection after displaying results ?>
        </div>
        <!-- End Car Listing -->



<div class="text-center mt-4">
          <a href="#" class="btn btn-outline-primary px-4">Lihat Lebih Banyak</a>
        </div>
      </div>
    </section>
    <footer class="py-5" id="kontak">
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
  </body>
</html>
