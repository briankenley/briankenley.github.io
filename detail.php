<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>MobilKupedia - Platform Jual Beli Mobil Terpercaya</title>
    <link rel="stylesheet" href="style.css">
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    />
  </head>
<?php
session_start();
include 'db.php'; // Include database connection

// Get Car ID from URL and validate
$car_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$car_details = null;
$error_message = '';

if ($car_id > 0) {
    // Prepare SQL statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT make, model, year, price, mileage, description, image FROM cars WHERE id = ?");
    $stmt->bind_param("i", $car_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $car_details = $result->fetch_assoc();
    } else {
        $error_message = "Mobil tidak ditemukan.";
    }
    $stmt->close();
} else {
    $error_message = "ID mobil tidak valid.";
}
$conn->close(); // Close connection after fetching data

// Construct car name for process_purchase.php compatibility
$car_name_for_purchase = $car_details ? htmlspecialchars($car_details['make'] . ' ' . $car_details['model']) : 'Unknown Car';

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
              <a class="nav-link active" href="#">Beranda</a>
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

    <div class="container py-5">
        <?php if ($car_details): ?>
            <h1 class="mb-4">Detail Mobil: <?php echo htmlspecialchars($car_details['make']) . ' ' . htmlspecialchars($car_details['model']); ?></h1>

            <div class="row">
                <div class="col-md-7 mb-4">
                    <img src="<?php echo !empty($car_details['image']) ? htmlspecialchars($car_details['image']) : 'placeholder.png'; ?>" class="img-fluid rounded shadow-sm" alt="<?php echo $car_name_for_purchase; ?>" style="max-height: 450px; width: 100%; object-fit: cover;">
                </div>
                <div class="col-md-5">
                    <h3><?php echo htmlspecialchars($car_details['make']) . ' ' . htmlspecialchars($car_details['model']); ?> <span class="badge bg-secondary"><?php echo htmlspecialchars($car_details['year']); ?></span></h3>
                    <h4 class="text-primary fw-bold my-3">Rp <?php echo number_format($car_details['price'], 0, ',', '.'); ?></h4>

                    <p><strong>Kilometer:</strong> <?php echo $car_details['mileage'] ? number_format($car_details['mileage'], 0, ',', '.') . ' km' : 'N/A'; ?></p>

                    <p><strong>Deskripsi:</strong></p>
                    <p><?php echo !empty($car_details['description']) ? nl2br(htmlspecialchars($car_details['description'])) : '<em>Tidak ada deskripsi tambahan.</em>'; ?></p>

                    <hr>

                    <h4 class="mt-4">Formulir Pembelian</h4>
                    <form action="process_purchase.php?car=<?php echo urlencode($car_name_for_purchase); ?>" method="POST">
                        <!-- Optional: Hidden field if process_purchase needs the ID later -->
                        <!-- <input type="hidden" name="car_id" value="<?php echo $car_id; ?>"> -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Lengkap:</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Alamat Pengiriman:</label>
                            <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="payment_method" class="form-label">Metode Pembayaran:</label>
                            <select class="form-select" id="payment_method" name="payment_method" required>
                                <option value="" disabled selected>Pilih Metode</option>
                                <option value="credit_card">Kartu Kredit</option>
                                <option value="bank_transfer">Transfer Bank</option>
                                <option value="paypal">PayPal</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success w-100 btn-lg">Beli Sekarang</button>
                    </form>
                </div>
            </div>

        <?php else: ?>
            <div class="alert alert-danger text-center" role="alert">
                <?php echo $error_message ?: "Terjadi kesalahan saat memuat detail mobil."; ?>
            </div>
            <div class="text-center">
                 <a href="beli.php" class="btn btn-secondary">Kembali ke Daftar Mobil</a>
            </div>
        <?php endif; ?>
    </div>

    <footer class="py-5 bg-light mt-auto" id="kontak"> <!-- Added bg-light and mt-auto -->
      <div class="container">
        <div class="row g-4">
          <div class="col-lg-4">
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
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
                <i class="fas fa-map-marker-alt me-2"></i>  Medan
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
