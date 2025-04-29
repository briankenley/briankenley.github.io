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
if (isset($_SESSION['userid'])) {
    $username = $_SESSION['username'];
} else {
    $username = "Guest";
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
                        echo "<p>Silakan lakukan pembayaran melalui kartu kredit dengan nomor xxxx-xxxx-xxxx-1234.</p>";
                    } elseif ($payment_method == "bank_transfer") {
                        echo "<p>Silakan transfer ke rekening BCA 1234567890 atas nama MobilKupedia.</p>";
                    } elseif ($payment_method == "paypal") {
                        echo "<p>Anda akan diarahkan ke PayPal untuk menyelesaikan pembayaran.</p>";
                    }

                    // WhatsApp Chat Link
                    echo "<p><a href='https://wa.me/6281269791841' target='_blank'>Hubungi kami melalui WhatsApp</a></p>";

                    // Show the rating form, passing the purchase_id
                    echo "<div class='rating-form'>";
                    echo "<h4>Berikan rating untuk pengalaman pembelian Anda:</h4>";
                    // The form now submits the rating and comment along with the purchase_id
                    echo "<form action='process_purchase.php?car=" . urlencode($car) . "' method='POST'>"; 
                    echo "<input type='hidden' name='purchase_id' value='" . $purchase_id . "'>"; // Hidden field for purchase ID

                    // Star Rating UI
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
                $comment = isset($_POST["comment"]) ? $_POST["comment"] : ""; // Comment is optional
                $purchase_id = $_POST["purchase_id"];

                // Update the existing purchase record with the rating and comment
                $sql = "UPDATE purchases SET rating = '$rating', comment = '$comment' WHERE id = '$purchase_id' AND username = '$username'"; // Added username check for security

                if ($conn->query($sql) === TRUE) {
                    if ($conn->affected_rows > 0) {
                         echo "<h2>Terima Kasih!</h2>";
                         echo "<p>Rating dan komentar Anda telah berhasil disimpan.</p>";
                         echo "<p>Rating Anda: " . htmlspecialchars($rating) . " bintang</p>";
                         if (!empty($comment)) {
                             echo "<p>Komentar Anda: " . htmlspecialchars($comment) . "</p>";
                         }
                         echo "<p><a href='beli.php' class='btn btn-secondary'>Kembali ke Daftar Mobil</a></p>";
                    } else {
                         echo "<div class='alert alert-warning'>Tidak dapat menemukan atau memperbarui data pembelian Anda. Mungkin sudah dinilai sebelumnya atau ID tidak cocok.</div>";
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
            // --- GET Request Handling (Demo Mode) ---
            // Keep the existing GET request logic for demo purposes if needed, 
            // but ensure the form it shows points correctly if it should be functional.
            // For simplicity, let's just show a message that this page processes submissions.

            echo "<div class='alert alert-info'>Halaman ini memproses data pembelian dan rating. Silakan lakukan pembelian melalui halaman 'Beli Mobil'.</div>";
            // Optional: You could still show a non-functional demo rating form if desired.
            // The multi-line comment below was causing a parse error. It has been removed.
        } 
        // The redundant 'else' block below has also been removed.
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
    
    <!-- Star Rating JavaScript -->
    <script>
        // Get all star rating inputs
        const ratingInputs = document.querySelectorAll('.star-rating input');
        const ratingText = document.getElementById('ratingText');
        
        // Rating text descriptions
        const ratingDescriptions = {
            1: 'Sangat Buruk',
            2: 'Buruk',
            3: 'Cukup',
            4: 'Baik',
            5: 'Sangat Baik'
        };
        
        // Add event listeners to each star
        ratingInputs.forEach(input => {
            input.addEventListener('change', function() {
                const rating = this.value;
                ratingText.textContent = `Rating Anda: ${rating} - ${ratingDescriptions[rating]}`;
            });
            
            // Also add mouseover/mouseout effects for better UX
            const label = document.querySelector(`label[for="${input.id}"]`);
            
            label.addEventListener('mouseover', function() {
                const rating = input.value;
                ratingText.textContent = `${rating} - ${ratingDescriptions[rating]}`;
            });
            
            label.addEventListener('mouseout', function() {
                // If a rating is already selected, show that, otherwise show default text
                const selectedRating = document.querySelector('.star-rating input:checked');
                if (selectedRating) {
                    const rating = selectedRating.value;
                    ratingText.textContent = `Rating Anda: ${rating} - ${ratingDescriptions[rating]}`;
                } else {
                    ratingText.textContent = 'Klik bintang untuk memberikan rating';
                }
            });
        });
    </script>
</body>
</html>
