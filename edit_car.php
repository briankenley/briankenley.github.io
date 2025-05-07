<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Mobil - MobilKupedia</title>
    <link rel="stylesheet" href="style.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php
session_start();
include 'db.php'; // Include database connection

// Check if user is logged in
if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['userid'];
$car_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$car = null;
$error_message = '';
$success_message = '';

// Fetch car details only if ID is valid and belongs to the user
if ($car_id > 0) {
    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT make, model, year, price, mileage, description, image FROM cars WHERE id = ? AND user_id = ?");
    if ($stmt) {
        $stmt->bind_param("ii", $car_id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 1) {
            $car = $result->fetch_assoc();
        } else {
            $error_message = "Mobil tidak ditemukan atau Anda tidak memiliki izin untuk mengeditnya.";
        }
        $stmt->close();
    } else {
        $error_message = "Gagal menyiapkan query: " . $conn->error;
    }
} else {
    $error_message = "ID mobil tidak valid.";
}

// Handle form submission for updating the car
if ($_SERVER["REQUEST_METHOD"] == "POST" && $car) {
    // Retrieve and sanitize form data
    $make = trim($_POST['make']);
    $model = trim($_POST['model']);
    $year = intval($_POST['year']);
    $price = floatval(str_replace('.', '', $_POST['price'])); // Remove dots for float conversion
    $mileage = intval($_POST['mileage']);
    $description = trim($_POST['description']);
    $current_image = $car['image']; // Keep track of the current image

    // Basic validation
    if (empty($make) || empty($model) || $year <= 1900 || $price <= 0 || $mileage < 0) {
        $error_message = "Mohon isi semua field yang wajib diisi dengan benar.";
    } else {
        $new_image_path = $current_image; // Start with the current path

        // Handle potential image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) { // Check for explicit OK status
            $target_dir = "uploads/";
            // Check if directory exists, create if not, check writability
            if (!file_exists($target_dir)) {
                if (!mkdir($target_dir, 0777, true)) {
                    $error_message = "Gagal membuat direktori uploads.";
                    goto process_update; // Skip image processing if directory creation fails
                }
            } elseif (!is_writable($target_dir)) {
                 $error_message = "Direktori uploads tidak dapat ditulis.";
                 goto process_update; // Skip image processing if not writable
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
                        // Delete old image ONLY if a new one is uploaded successfully and it's not the placeholder
                        if (!empty($current_image) && $current_image !== 'placeholder.png' && file_exists($current_image)) {
                            unlink($current_image);
                        }
                        $new_image_path = $target_file; // Set the path to the NEWLY uploaded file
                    } else {
                        $error_message = "Maaf, terjadi kesalahan saat mengunggah file Anda.";
                        // Keep $new_image_path as $current_image if upload fails
                    }
                }
            }
        } elseif (isset($_FILES['image']) && $_FILES['image']['error'] != UPLOAD_ERR_NO_FILE) {
             // Handle other upload errors (permissions, size limits, etc.)
             $error_message = "Terjadi kesalahan saat mengunggah gambar (Error code: " . $_FILES['image']['error'] . ").";
        }
        // else: No new file uploaded (UPLOAD_ERR_NO_FILE), $new_image_path remains $current_image

        process_update: // Label for goto jump

        // Proceed with update only if no critical validation/upload error occurred
        if (empty($error_message)) {
            $update_stmt = $conn->prepare("UPDATE cars SET make = ?, model = ?, year = ?, price = ?, mileage = ?, description = ?, image = ? WHERE id = ? AND user_id = ?");
            if ($update_stmt) {
                // Use the determined $new_image_path for the update
                $update_stmt->bind_param("ssidisiii", $make, $model, $year, $price, $mileage, $description, $new_image_path, $car_id, $user_id);
                if ($update_stmt->execute()) {
                    $success_message = "Data mobil berhasil diperbarui.";
                    // Refresh car data after update, including the potentially updated image path
                    $car['make'] = $make;
                    $car['model'] = $model;
                    $car['year'] = $year;
                    $car['price'] = $price;
                    $car['mileage'] = $mileage;
                    $car['description'] = $description;
                    $car['image'] = $new_image_path; // Update $car array with the final image path
                    header("Refresh: 2; URL=dashboard.php"); // Redirect back to dashboard
                } else {
                    $error_message = "Gagal memperbarui data mobil: " . $update_stmt->error;
                }
                $update_stmt->close();
            } else {
                 $error_message = "Gagal menyiapkan query update: " . $conn->error;
            }
        }
        // If there was an error (validation or upload), the form will re-display with the error message.
    }
}

$conn->close();
?>

    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand" href="mobilkupedia.php">MobilKu<span style="color: #f39c12">pedia</span></a>
            <!-- Add other nav items if needed, similar to dashboard.php -->
             <a href="dashboard.php" class="btn btn-outline-secondary ms-auto">Kembali ke Dashboard</a>
        </div>
    </nav>

    <div class="container py-5">
        <h1>Edit Detail Mobil</h1>

        <?php if ($error_message): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <?php if ($success_message): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <?php if ($car): ?>
        <form action="edit_car.php?id=<?php echo $car_id; ?>" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="make" class="form-label">Merk <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="make" name="make" value="<?php echo htmlspecialchars($car['make']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="model" class="form-label">Model <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="model" name="model" value="<?php echo htmlspecialchars($car['model']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="year" class="form-label">Tahun <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="year" name="year" value="<?php echo htmlspecialchars($car['year']); ?>" required min="1900" max="<?php echo date('Y') + 1; ?>">
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="price" name="price" value="<?php echo number_format($car['price'], 0, ',', '.'); ?>" required oninput="formatNumber(this)">
                 <small class="form-text text-muted">Masukkan angka saja, contoh: 150000000</small>
            </div>
            <div class="mb-3">
                <label for="mileage" class="form-label">Jarak Tempuh (km) <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="mileage" name="mileage" value="<?php echo htmlspecialchars($car['mileage']); ?>" required min="0">
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Deskripsi</label>
                <textarea class="form-control" id="description" name="description" rows="4"><?php echo htmlspecialchars($car['description']); ?></textarea>
            </div>
             <div class="mb-3">
                <label for="image" class="form-label">Gambar Mobil</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/png, image/jpeg, image/gif">
                <small class="form-text text-muted">Kosongkan jika tidak ingin mengganti gambar. Gambar saat ini:</small><br>
                <?php if (!empty($car['image']) && file_exists($car['image'])): ?>
                    <img src="<?php echo htmlspecialchars($car['image']); ?>" alt="Gambar Mobil Saat Ini" style="max-width: 200px; max-height: 150px; margin-top: 10px;">
                <?php else: ?>
                    <small>Tidak ada gambar.</small>
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="dashboard.php" class="btn btn-secondary">Batal</a>
        </form>
        <?php elseif (!$error_message): // Show only if no specific error was set, implies car not found for user ?>
             <div class="alert alert-warning">Mobil yang Anda coba edit tidak ditemukan atau bukan milik Anda.</div>
             <a href="dashboard.php" class="btn btn-primary">Kembali ke Dashboard</a>
        <?php endif; ?>
    </div>

    <?php include 'footer.php';?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Simple number formatting for price
        function formatNumber(input) {
            // Remove existing dots
            let value = input.value.replace(/\./g, '');
            // Format with dots
            input.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }
        // Initial formatting on page load
        document.addEventListener('DOMContentLoaded', function() {
            const priceInput = document.getElementById('price');
            if (priceInput) {
                formatNumber(priceInput);
            }
        });
    </script>
</body>
</html>
