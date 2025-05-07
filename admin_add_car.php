<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin: Tambah Mobil Baru</title> <!-- Changed Title -->
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

// --- Basic Admin Check (Simplified) ---
if (!isset($_SESSION['userid']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php?message=Admin login required.");
    exit();
}

$admin_user_id = $_SESSION['userid'];
$success_message = '';
$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Directly use POST data (UNSAFE - as requested)
    $make = $_POST['name'] ?? '';
    $model = $_POST['model'] ?? '';
    $year = $_POST['year'] ?? '';
    $price = $_POST['price'] ?? '';
    $mileage = $_POST['mileage'] ?? '';
    $description = $_POST['description'] ?? '';
    $image_path = '';

    // Simplified image handling (just get the name)
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $image_path = basename($_FILES["image"]["name"]);
        // NOTE: No file move logic here for simplicity
    }

    // Basic check if required fields are present (can be removed entirely if needed)
    if (empty($make) || empty($model) || empty($year) || empty($price) || empty($mileage) || empty($description)) {
        $error_message = "Mohon isi semua field.";
    } else {
        // Direct SQL query (UNSAFE - as requested)
        $sql = "INSERT INTO cars (user_id, make, model, year, price, mileage, description, image)
                VALUES ('$admin_user_id', '$make', '$model', '$year', '$price', '$mileage', '$description', '$image_path')";

        if ($conn->query($sql) === TRUE) {
            $success_message = "Mobil berhasil ditambahkan oleh admin.";
        } else {
            $error_message = "Error: Gagal menambahkan mobil. " . $conn->error;
        }
    }
}

// Close connection
if ($conn) {
    $conn->close();
}
?>
  <body>
    <!-- Navbar - Adjust links/visibility for admin context if needed -->
    <nav
      class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top"
    >
      <div class="container">
        <a class="navbar-brand" href="dashboard.php"
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
                        <a class="nav-link" href="mobilkupedia.php">Beranda</a>
                    </li>
            <li class="nav-item">
              <a class="nav-link" href="dashboard.php">Dashboard</a>
            </li>
             <li class="nav-item">
              <a class="nav-link active" href="admin_add_car.php">Tambah Mobil</a> <!-- Active link -->
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
          <div class="d-flex align-items-center">
             <span class="navbar-text me-3">Admin: <?php echo htmlspecialchars($_SESSION['username']); ?></span>
             <a href="logout.php" class="btn btn-outline-danger">Keluar</a>
          </div>
        </div>
      </div>
    </nav>

    <header class="text-white text-center py-5" style="background-color: #dc3545;">
      <h1>Admin: Tambah Mobil Baru</h1>
      <p>Masukkan detail mobil untuk ditambahkan ke katalog.</p>
    </header>


    <div class="container mt-5" id="admin-add-car">
        <h2 class="text-center mb-4">Formulir Tambah Mobil (Admin)</h2>

        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success" role="alert">
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <!-- Simplified Form -->
        <form method="POST" action="admin_add_car.php" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="name" class="form-label">Merk Mobil</label>
                <input type="text" name="name" class="form-control" id="name">
            </div>
            <div class="mb-3">
                <label for="model" class="form-label">Model Mobil</label>
                <input type="text" name="model" class="form-control" id="model">
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="year" class="form-label">Tahun</label>
                    <input type="number" name="year" class="form-control" id="year" placeholder="Contoh: 2022">
                </div>
                 <div class="col-md-6 mb-3">
                     <label for="price" class="form-label">Harga (Rp)</label>
                     <input type="number" name="price" class="form-control" id="price" placeholder="Contoh: 250000000">
                 </div>
             </div>
             <div class="mb-3">
                <label for="mileage" class="form-label">Jarak Tempuh (km)</label>
                <input type="number" name="mileage" class="form-control" id="mileage" placeholder="Contoh: 15000">
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Deskripsi</label>
                <textarea name="description" class="form-control" id="description" rows="4" placeholder="Jelaskan kondisi mobil..."></textarea>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Gambar Mobil</label>
                <input type="file" name="image" class="form-control" id="image" accept="image/jpeg, image/png, image/gif">
                <div class="form-text">Upload gambar mobil (opsional).</div>
            </div>
            <button type="submit" class="btn btn-danger btn-lg w-100">Tambahkan Mobil (Admin)</button>
        </form>
    </div>

    <?php include 'footer.php';?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Removed client-side validation script -->
  </body>
</html>
