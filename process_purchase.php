<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MobilKupedia - Pembelian Berhasil</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
</head>
<?php
session_start();

// Simplified: Assume session started if needed, no login check
// Assume username is available if needed, but remove explicit check
$username = $_SESSION['username'] ?? 'guest'; // Provide a default if not set, though not strictly needed for simplified logic
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

    <div class="container">
        <h1>Pembelian Berhasil</h1>
        <?php
        include 'db.php';

        // Handle POST requests for both initial purchase and rating submission
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $car = isset($_GET['car']) ? $_GET['car'] : 'Unknown Car'; // Get car from URL parameter

            // Scenario 1: Initial Purchase Submission (check for name, address, payment_method)
            if (isset($_POST["name"]) && isset($_POST["address"]) && isset($_POST["payment_method"])) {
                $name = $_POST["name"];
                $address = $_POST["address"];
                $payment_method = $_POST["payment_method"];
                
                // Insert initial purchase details (without rating/comment yet)
                $sql = "INSERT INTO purchases (car, name, address, payment_method, username) VALUES ('$car', '$name', '$address', '$payment_method', '$username')";

                if ($conn->query($sql) === TRUE) {
                    $purchase_id = $conn->insert_id; // Get the ID of the purchase just inserted

                    echo "<p>Pembelian awal berhasil! Terima kasih.</p>";

                    // Payment Instructions
                    echo "<h2>Instruksi Pembayaran</h2>";
                    if ($payment_method == "credit_card") {
                        echo "<p>Untuk pembelian melalui kartu kredit, silahkan hubungi WhatsApp kami melalui link di bawah ini.</p>";
                    } elseif ($payment_method == "bank_transfer") {
                        echo "<p>Silakan transfer ke rekening BCA 1234567890 atas nama MobilKupedia dan kirim bukti transfer ke WhatsApp kami melalui link di bawah ini.</p>";
                    } 

                    // WhatsApp Chat Link
                    echo "<p><a href='https://wa.me/6281269791841' target='_blank'>Hubungi kami melalui WhatsApp</a></p>";

                    // Show the rating form, passing the purchase_id
                    echo "<div class='rating-form'>";
                    echo "<h4>Berikan rating untuk pengalaman pembelian Anda:</h4>";
                    // Simplified form action (removed urlencode)
                    echo "<form action='process_purchase.php?car=" . $car . "' method='POST'>";
                    echo "<input type='hidden' name='purchase_id' value='" . $purchase_id . "'>"; // Hidden field for purchase ID

                    // Simplified Star Rating UI (removed JS interaction logic later)
                    echo "<div class='star-rating'>";
                    echo "<input type='radio' id='star5' name='rating' value='5' required /><label for='star5' title='5 stars'></label>"; // Added required attribute
                    echo "<input type='radio' id='star4' name='rating' value='4' /><label for='star4' title='4 stars'></label>";
                    echo "<input type='radio' id='star3' name='rating' value='3' /><label for='star3' title='3 stars'></label>";
                    echo "<input type='radio' id='star2' name='rating' value='2' /><label for='star2' title='2 stars'></label>";
                    echo "<input type='radio' id='star1' name='rating' value='1' /><label for='star1' title='1 star'></label>";
                    echo "</div>";
                    
                    echo "<div class='star-rating-text' id='ratingText'>Klik bintang untuk memberikan rating (wajib)</div>";
                    
                    echo "<textarea name='comment' placeholder='Berikan komentar Anda (opsional)'></textarea>";
                    echo "<button type='submit' class='btn btn-primary'>Submit Rating</button>";
                    echo "</form>";
                    echo "</div>";

                } else {
                    echo "Error inserting purchase: " . $sql . "<br>" . $conn->error;
                }
            } 
            // Scenario 2: Rating Submission (check for rating and purchase_id)
            elseif (isset($_POST["rating"]) && isset($_POST["purchase_id"])) {
                $rating = $_POST["rating"];
                $comment = $_POST["comment"] ?? ""; // Comment is optional
                $purchase_id = $_POST["purchase_id"];
                $sql = "UPDATE purchases SET rating = '$rating', comment = '$comment' WHERE id = '$purchase_id'";

                if ($conn->query($sql) === TRUE) {
                    if ($conn->affected_rows > 0) {
                         echo "<h2>Terima Kasih!</h2>";
                         echo "<p>Rating dan komentar Anda telah berhasil disimpan.</p>";
                         echo "<p>Rating Anda: " . $rating . " bintang</p>";
                         if (!empty($comment)) {
                             echo "<p>Komentar Anda: " . $comment . "</p>";
                         }
                         echo "<p><a href='beli.php' class='btn btn-secondary'>Kembali ke Daftar Mobil</a></p>";
                    } else {
                         echo "<div class='alert alert-warning'>Tidak dapat menemukan atau memperbarui data pembelian Anda.</div>";
                    }
                } else {
                    echo "Error updating rating: " . $sql . "<br>" . $conn->error;
                }
            } 
            // Scenario 3: Invalid POST data
            else {
                 echo "<div class='alert alert-danger'>Data yang dikirim tidak lengkap atau tidak valid.</div>";
            }

            $conn->close();

        } else {


            echo "<div class='alert alert-info'>Halaman ini memproses data pembelian dan rating. Silakan lakukan pembelian melalui halaman 'Beli Mobil'.</div>";
        } 
        ?>
    </div>

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
                        <a href="#" class="text-white bg-primary p-2 rounded-circle">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="text-white bg-primary p-2 rounded-circle">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="text-white bg-primary p-2 rounded-circle">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="text-white bg-primary p-2 rounded-circle">
                            <i class="fab fa-youtube"></i>
                        </a>
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
            <hr class="my-4 bg-secondary"/>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha-1/dist/js/bootstrap.bundle.min.js"></script>
    <?php?>
</body>
</html>
