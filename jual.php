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

// Redirect if not logged in
if (!isset($_SESSION['userid'])) {
    header("Location: login.php"); // Simple redirect
    exit();
}

// Redirect Admin Users
if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    header("Location: admin_add_car.php"); // Redirect admin
    exit();
}

// Proceed for non-admin logged-in users
$user_id = $_SESSION['userid'];
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
    $image_path = 'placeholder.png'; // Default image if none uploaded or error

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "uploads/";
        // Check/create directory
        if (!file_exists($target_dir)) {
            if (!mkdir($target_dir, 0777, true)) {
                $error_message = "Gagal membuat direktori uploads.";
                goto skip_db_insert; // Skip insert if directory fails
            }
        } elseif (!is_writable($target_dir)) {
             $error_message = "Direktori uploads tidak dapat ditulis.";
             goto skip_db_insert; // Skip insert if not writable
        }

        $image_name = time() . '_' . preg_replace("/[^a-zA-Z0-9.]/", "_", basename($_FILES["image"]["name"])); // Sanitize filename
        $target_file = $target_dir . $image_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is an actual image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check === false) {
            $error_message = "File bukan gambar.";
        } else {
            // Allow certain file formats
            $allowed_types = ['jpg', 'png', 'jpeg', 'gif'];
            if (!in_array($imageFileType, $allowed_types)) {
                $error_message = "Maaf, hanya file JPG, JPEG, PNG & GIF yang diperbolehkan.";
            } else {
                // Attempt to move the uploaded file
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    $image_path = $target_file; // Set the full path for DB
                } else {
                    $error_message = "Maaf, terjadi kesalahan saat mengunggah file Anda.";
                    // Keep default image_path if upload fails
                }
            }
        }
    } elseif (isset($_FILES['image']) && $_FILES['image']['error'] != UPLOAD_ERR_NO_FILE) {
         // Handle other upload errors
         $error_message = "Terjadi kesalahan saat mengunggah gambar (Error code: " . $_FILES['image']['error'] . ").";
    }
    // else: No file uploaded (UPLOAD_ERR_NO_FILE), use default image_path

    // Basic validation
    if (empty($make) || empty($model) || empty($year) || empty($price) || empty($mileage)) { // Description is optional now
        $error_message = "Mohon isi semua field wajib (Merk, Model, Tahun, Harga, Jarak Tempuh).";
    }

    skip_db_insert: // Label for goto

    // Proceed with insert only if no critical error occurred
    if (empty($error_message)) {
        // Use prepared statement for security
        $stmt = $conn->prepare("INSERT INTO cars (user_id, make, model, year, price, mileage, description, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("issidiss", $user_id, $make, $model, $year, $price, $mileage, $description, $image_path);
            if ($stmt->execute()) {
                $success_message = "Mobil berhasil ditambahkan.";
                // Clear form fields or redirect here if desired
            } else {
                $error_message = "Error: Gagal menambahkan mobil. " . $stmt->error;
            }
            $stmt->close();
        } else {
             $error_message = "Error: Gagal menyiapkan statement. " . $conn->error;
        }
    }
}

// Close connection
if ($conn) {
    $conn->close();
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
            <?php // Dynamically show 'Tambah Mobil' for admin or 'Jual Mobil' for others ?>
            <?php // Note: Admins are redirected away from this page earlier, but this keeps the nav consistent. ?>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <li class="nav-item">
                    <a class="nav-link" href="admin_add_car.php">Tambah Mobil</a>
                </li>
            <?php else: // Non-admin logged-in users ?>
                <li class="nav-item">
                    <a class="nav-link active" href="jual.php">Jual Mobil</a> <?php // Add active class ?>
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

    <header class="text-white text-center py-5" style="background-color: #f39c12;">
      <h1>Jual Mobil Anda</h1>
      <p>Masukkan detail mobil yang ingin Anda jual.</p>
    </header>


    <div class="container mt-5" id="sell">
        <h2 class="text-center mb-4">Formulir Jual Mobil</h2>

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
        <form method="POST" action="jual.php" enctype="multipart/form-data">
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
            <button type="submit" class="btn btn-primary btn-lg w-100">Tambahkan Mobil</button>
        </form>
    </div>

    <?php include 'footer.php';?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Removed client-side validation script -->
  </body>
</html>
