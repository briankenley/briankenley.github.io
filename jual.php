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
    header("Location: login.php?message=Silakan login untuk menjual mobil.");
    exit();
}

$user_id = $_SESSION['userid'];
$success_message = '';
$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize inputs (basic validation)
    $make = trim($_POST['name'] ?? '');
    $model = trim($_POST['model'] ?? '');
    $year = filter_input(INPUT_POST, 'year', FILTER_VALIDATE_INT);
    $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);
    $mileage = filter_input(INPUT_POST, 'mileage', FILTER_VALIDATE_INT);
    $description = trim($_POST['description'] ?? '');
    $image_path = ''; // Placeholder for image path

    // --- Basic Input Validation ---
    if (empty($make) || empty($model) || $year === false || $price === false || $mileage === false || empty($description)) {
        $error_message = "Semua field wajib diisi dengan benar.";
    }
    // --- Image Upload Handling (Placeholder) ---
    // A real implementation would involve:
    // 1. Checking $_FILES['image']['error']
    // 2. Validating file type and size
    // 3. Generating a unique filename
    // 4. Moving the uploaded file using move_uploaded_file()
    // 5. Storing the final path/filename in $image_path
    elseif (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
         // Basic example: use original name (NOT recommended for production)
         // $target_dir = "uploads/"; // Make sure this directory exists and is writable
         // $image_path = $target_dir . basename($_FILES["image"]["name"]);
         // if (move_uploaded_file($_FILES["image"]["tmp_name"], $image_path)) {
              // File uploaded successfully
              $image_path = basename($_FILES["image"]["name"]); // Store just the name for now
         // } else {
         //     $error_message = "Maaf, terjadi error saat mengupload gambar.";
         //     $image_path = ''; // Reset path on error
         // }
         // For now, just store the filename as a placeholder if uploaded
         $image_path = $conn->real_escape_string(basename($_FILES["image"]["name"]));
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
            // Bind parameters (i: integer, s: string, d: double)
            $stmt->bind_param("issidiss", $user_id, $make, $model, $year, $price, $mileage, $description, $image_path);

            if ($stmt->execute()) {
                $success_message = "Mobil berhasil ditambahkan.";
                // Optionally clear form or redirect
                // header("Location: dashboard.php"); exit();
            } else {
                $error_message = "Error: Gagal menambahkan mobil. " . $stmt->error;
            }
            $stmt->close();
        } else {
            $error_message = "Error: Gagal mempersiapkan statement. " . $conn->error;
        }
    }
}
$conn->close(); // Close connection after processing
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

        <form method="POST" action="jual.php" enctype="multipart/form-data" class="needs-validation" novalidate>
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
                    <input type="number" name="year" class="form-control" id="year" placeholder="Contoh: 2022" required min="1900" max="<?php echo date('Y') + 1; // Allow next year ?>">
                    <div class="invalid-feedback">Tahun wajib diisi (antara 1900 - <?php echo date('Y') + 1; ?>).</div>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="price" class="form-label">Harga (Rp)</label>
                    <input type="number" name="price" class="form-control" id="price" placeholder="Contoh: 250000000" required min="0">
                     <div class="invalid-feedback">Harga wajib diisi (angka positif).</div>
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
                <div class="form-text">Upload gambar mobil (format: JPG, PNG, GIF). Ukuran maks: 2MB (contoh).</div>
                <!-- Add validation feedback if needed -->
            </div>
            <button type="submit" class="btn btn-primary btn-lg w-100">Tambahkan Mobil</button>
        </form>
    </div>

    <footer class="py-5 mt-5 bg-light" id="kontak"> <!-- Added bg-light -->
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
