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

// Simplified: Assume session started if needed, no login check

// Get Car ID from URL (UNSAFE)
$car_id = $_GET['id'] ?? 0;
$car_details = null;
$error_message = '';

if ($car_id > 0) {
    // Direct SQL query (UNSAFE)
    $sql = "SELECT make, model, year, price, mileage, description, image FROM cars WHERE id = '$car_id'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows === 1) {
        $car_details = $result->fetch_assoc();
    } else {
        $error_message = "Mobil tidak ditemukan.";
    }
} else {
    $error_message = "ID mobil tidak valid.";
}

// Construct car name (simplified)
$car_name_for_purchase = $car_details ? ($car_details['make'] . ' ' . $car_details['model']) : 'Unknown Car';

// Close connection later in the script
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
              <a class="nav-link" href="mobilkupedia.php">Beranda</a> <?php // Corrected link ?>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="dashboard.php">Dashboard</a>
            </li>
            <?php // Dynamically show 'Tambah Mobil' for admin or 'Jual Mobil' for others ?>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <li class="nav-item">
                    <a class="nav-link" href="admin_add_car.php">Tambah Mobil</a>
                </li>
            <?php else: // Non-admin logged-in users ?>
                <li class="nav-item">
                    <a class="nav-link" href="jual.php">Jual Mobil</a>
                </li>
            <?php endif; ?>
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
            <h1 class="mb-4">Detail Mobil: <?php echo $car_details['make'] . ' ' . $car_details['model']; ?></h1>

            <div class="row">
                <div class="col-md-7 mb-4">
                    <img src="<?php echo !empty($car_details['image']) ? $car_details['image'] : 'placeholder.png'; ?>" class="img-fluid rounded shadow-sm" alt="<?php echo $car_name_for_purchase; ?>" style="max-height: 450px; width: 100%; object-fit: cover;">
                </div>
                <div class="col-md-5">
                    <h3><?php echo $car_details['make'] . ' ' . $car_details['model']; ?> <span class="badge bg-secondary"><?php echo $car_details['year']; ?></span></h3>
                    <h4 class="text-primary fw-bold my-3">Rp <?php echo number_format($car_details['price'], 0, ',', '.'); ?></h4>

                    <p><strong>Kilometer:</strong> <?php echo $car_details['mileage'] ? number_format($car_details['mileage'], 0, ',', '.') . ' km' : 'N/A'; ?></p>

                    <p><strong>Deskripsi:</strong></p>
                    <p><?php echo !empty($car_details['description']) ? nl2br($car_details['description']) : '<em>Tidak ada deskripsi tambahan.</em>'; ?></p>

                    <hr>

                    <h4 class="mt-4">Formulir Pembelian</h4>
                    <form action="process_purchase.php?car=<?php echo $car_name_for_purchase; ?>" method="POST">
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

    <?php if ($conn) $conn->close(); // Close connection here ?>
    <?php include 'footer.php';?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
