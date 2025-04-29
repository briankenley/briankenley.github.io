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

// --- ADMIN ACCESS CHECK ---
// Redirect if not logged in or if logged in user is NOT an admin
if (!isset($_SESSION['userid']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    // Redirect non-admins to dashboard or login page
    header("Location: " . (isset($_SESSION['userid']) ? "dashboard.php?message=Akses ditolak." : "login.php?message=Silakan login sebagai admin."));
    exit();
}

$admin_user_id = $_SESSION['userid']; // Get admin's user ID
$success_message = '';
$error_message = '';

// Log PHP Integer Max Size (can be kept for debugging if needed)
error_log("PHP_INT_MAX: " . PHP_INT_MAX);

// Attempt to relax SQL mode (can be kept if needed)
if ($conn) {
    $conn->query("SET SESSION sql_mode = ''");
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize inputs (basic validation)
    $make = trim($_POST['name'] ?? '');
    $model = trim($_POST['model'] ?? '');
    $year = filter_input(INPUT_POST, 'year', FILTER_VALIDATE_INT);
    // Get price as string, validate as non-negative integer string for BIGINT
    $price_input = trim($_POST['price'] ?? '');
    $is_valid_price_format = is_numeric($price_input) && strpos($price_input, '.') === false && strpos($price_input, ',') === false && strpos(trim($price_input), '-') !== 0;
    $price_int_value = $is_valid_price_format ? (int)$price_input : false; // Cast only if valid format

    $mileage = filter_input(INPUT_POST, 'mileage', FILTER_VALIDATE_INT);
    $description = trim($_POST['description'] ?? '');
    $image_path = ''; // Placeholder for image path

    // --- Basic Input Validation ---
    if (empty($make) || empty($model) || $year === false || !$is_valid_price_format || $price_int_value === false || $mileage === false || empty($description)) {
         $error_message = "Semua field wajib diisi dengan benar. Pastikan harga (angka bulat tanpa titik/koma) dan jarak tempuh adalah angka numerik yang valid dan tidak negatif.";
    }
    // --- Image Upload Handling ---
    elseif (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
         // Basic example: use original name (NOT recommended for production)
         // A better approach involves unique names and potentially a dedicated uploads folder
         $image_path = $conn->real_escape_string(basename($_FILES["image"]["name"]));
         // Add actual file move logic here in a real application
         // move_uploaded_file($_FILES["image"]["tmp_name"], "uploads/" . $image_path);
    } else {
        // Handle cases where image is not uploaded or error occurred
        // $error_message = "Gambar wajib diupload."; // Uncomment if image is strictly required
        $image_path = ''; // No image uploaded or error
    }


    // Proceed only if no validation errors so far
    if (empty($error_message)) {
        // Use prepared statement to prevent SQL injection
        $sql = "INSERT INTO cars (user_id, make, model, year, price, mileage, description, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            // Log the price integer value just before binding
            error_log("Admin adding car - Attempting to bind price (int value): " . $price_int_value);

            // Bind parameters - Associate car with the logged-in admin's ID
            $stmt->bind_param("issiiiss", $admin_user_id, $make, $model, $year, $price_int_value, $mileage, $description, $image_path);

            if ($stmt->execute()) {
                $success_message = "Mobil berhasil ditambahkan oleh admin.";
                error_log("Admin (ID: $admin_user_id) added car successfully with price (int value): " . $price_int_value);
                // Optionally clear form or redirect to an admin listing page
            } else {
                $error_message = "Error: Gagal menambahkan mobil. " . $stmt->error;
                error_log("Admin (ID: $admin_user_id) error adding car: " . $stmt->error . " | Price int value attempted: " . $price_int_value);
            }
            $stmt->close();
        } else {
            $error_message = "Error: Gagal mempersiapkan statement. " . $conn->error;
            error_log("Admin add car - Error preparing statement: " . $conn->error);
        }
    }
}
// Close connection only if it was opened successfully
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

        <!-- Form remains largely the same as jual.php -->
        <form method="POST" action="admin_add_car.php" enctype="multipart/form-data" class="needs-validation" novalidate>
            <div class="mb-3">
                <label for="name" class="form-label">Merk Mobil</label>
                <input type="text" name="name" class="form-control" id="name" required>
                <div class="invalid-feedback">Merk mobil wajib diisi.</div>
            </div>
            <div class="mb-3">
                <label for="model" class="form-label">Model Mobil</label>
                <input type="text" name="model" class="form-control" id="model" required>
                 <div class="invalid-feedback">Model mobil wajib diisi.</div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="year" class="form-label">Tahun</label>
                    <input type="number" name="year" class="form-control" id="year" placeholder="Contoh: 2022" required min="1900" max="<?php echo date('Y') + 1; ?>">
                    <div class="invalid-feedback">Tahun wajib diisi (antara 1900 - <?php echo date('Y') + 1; ?>).</div>
                </div>
                 <div class="col-md-6 mb-3">
                     <label for="price" class="form-label">Harga (Rp - Angka Bulat)</label>
                     <input type="number" inputmode="numeric" pattern="[0-9]*" name="price" class="form-control" id="price" placeholder="Contoh: 250000000" required min="0">
                      <div class="invalid-feedback">Harga wajib diisi (angka bulat positif).</div>
                 </div>
             </div>
             <div class="mb-3">
                <label for="mileage" class="form-label">Jarak Tempuh (km)</label>
                <input type="number" name="mileage" class="form-control" id="mileage" placeholder="Contoh: 15000" required min="0">
                 <div class="invalid-feedback">Jarak tempuh wajib diisi (angka positif).</div>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Deskripsi</label>
                <textarea name="description" class="form-control" id="description" rows="4" placeholder="Jelaskan kondisi mobil, fitur unggulan, dll." required></textarea>
                 <div class="invalid-feedback">Deskripsi wajib diisi.</div>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Gambar Mobil</label>
                <input type="file" name="image" class="form-control" id="image" accept="image/jpeg, image/png, image/gif">
                <div class="form-text">Upload gambar mobil (format: JPG, PNG, GIF).</div>
                <!-- Add validation feedback if needed -->
            </div>
            <button type="submit" class="btn btn-danger btn-lg w-100">Tambahkan Mobil (Admin)</button> <!-- Changed button color/text -->
        </form>
    </div>

    <?php include 'footer.php';?>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Basic JS validation (optional, Bootstrap handles some) -->
    <script>
      // Example starter JavaScript for disabling form submissions if there are invalid fields
      (function () {
        'use strict'
        var forms = document.querySelectorAll('.needs-validation')
        Array.prototype.slice.call(forms)
          .forEach(function (form) {
            form.addEventListener('submit', function (event) {
              if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
              }
              form.classList.add('was-validated')
            }, false)
          })
      })()
    </script>
  </body>
</html>
