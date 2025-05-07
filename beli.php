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

// Check if the user is logged in. If not, redirect to login page.
if (!isset($_SESSION['userid'])) {
    header('Location: login.php');
    exit; // Stop script execution after redirection
}

include 'db.php'; // Include database connection

$search_keyword = $_GET['keyword'] ?? ''; 

$sql = "SELECT id, make, model, year, price, mileage, image, description FROM cars";

if (!empty($search_keyword)) {
    $sql .= " WHERE make LIKE '%$search_keyword%' OR model LIKE '%$search_keyword%' OR description LIKE '%$search_keyword%'";
}

$sql .= " ORDER BY created_at DESC";

$result = $conn->query($sql);

// NOTE: Connection is closed later in the script
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
              <a class="nav-link active" href="beli.php">Beli Mobil</a> <?php // Add active class ?>
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
        <h2 class="section-title mb-4 text-center">Cari Mobil Impian Anda</h2>

        <!-- Search Form -->
        <form action="beli.php" method="GET" class="search-form mb-4">
          <div class="row g-2 justify-content-center">
            <div class="col-md-6">
              <label for="searchInput" class="form-label visually-hidden">Kata Kunci Pencarian</label>
              <input type="text" class="form-control form-control-lg" id="searchInput" name="keyword" placeholder="Cari mobil (cth: Avanza, X- Trail, Jazz)" value="<?php echo $search_keyword; ?>">
            </div>
            <div class="col-md-2">
              <button type="submit" class="btn btn-primary btn-lg w-100">Cari</button>
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
                    src="<?php echo !empty($car['image']) ? $car['image'] : 'placeholder.png'; // Use placeholder if no image ?>"
                    style="height: 250px; object-fit: cover;"
                    class="card-img-top"
                    alt="<?php echo $car['make'] . ' ' . $car['model']; ?>"
                  />
                  <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                      <h5 class="card-title mb-0"><?php echo $car['make'] . ' ' . $car['model']; ?></h5>
                      <span class="badge badge-custom"><?php echo $car['year']; ?></span>
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
                Tidak ada mobil yang ditemukan dengan kata kunci "<?php echo $search_keyword; ?>". Silakan coba kata kunci lain.
              </div>
            </div>
          <?php endif; ?>
          <?php if ($conn) $conn->close(); // Close connection after displaying results ?>
        </div>
        <!-- End Car Listing -->
      </div>
    </section>
    <?php include 'footer.php';?>
  </body>
</html>
