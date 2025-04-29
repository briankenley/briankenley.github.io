<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard Pengguna - MobilKupedia</title>
    <link rel="stylesheet" href="style.css" />
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

// Check if user is logged in
if (!isset($_SESSION['userid']) || !isset($_SESSION['username'])) {
    // Redirect to login page if not logged in
    header("Location: login.php?message=Silakan login untuk mengakses dashboard.");
    exit();
}

$user_id = $_SESSION['userid'];
$username = $_SESSION['username'];

// Fetch cars listed by the user
$cars_listed = [];
$stmt_listed = $conn->prepare("SELECT id, make, model, year, price, mileage, image FROM cars WHERE user_id = ? ORDER BY created_at DESC");
if ($stmt_listed) {
    $stmt_listed->bind_param("i", $user_id);
    $stmt_listed->execute();
    $result_listed = $stmt_listed->get_result();
    while ($row = $result_listed->fetch_assoc()) {
        $cars_listed[] = $row;
    }
    $stmt_listed->close();
} else {
    // Handle error - e.g., log it or display a generic message
    error_log("Error preparing statement for listed cars: " . $conn->error);
}


// Fetch cars purchased by the user
$cars_purchased = [];
// Note: The 'purchases' table stores car name as a string.
// We might need more details if we want to display images etc. like the listed cars.
// For now, we'll display the information available in the 'purchases' table.
$stmt_purchased = $conn->prepare("SELECT id, car, name, address, payment_method, rating, comment FROM purchases WHERE username = ? ORDER BY id DESC"); // Assuming purchase timestamp isn't stored
if ($stmt_purchased) {
    $stmt_purchased->bind_param("s", $username);
    $stmt_purchased->execute();
    $result_purchased = $stmt_purchased->get_result();
    while ($row = $result_purchased->fetch_assoc()) {
        $cars_purchased[] = $row;
    }
    $stmt_purchased->close();
} else {
     // Handle error
     error_log("Error preparing statement for purchased cars: " . $conn->error);
}

$conn->close();
?>
<body>
    <nav
        class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top"
    >
        <div class="container">
            <a class="navbar-brand" href="mobilkupedia.php"
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
                        <a class="nav-link active" href="dashboard.php">Dashboard</a>
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
                    <span class="navbar-text me-3">Selamat datang, <?php echo htmlspecialchars($username); ?>!</span>
                    <a href="logout.php" class="btn btn-outline-danger">Keluar</a> <!-- Assuming you have a logout script -->
                </div>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <h1 class="mb-4">Dashboard</h1>

        <!-- Section for Cars Listed for Sale -->
        <section id="mobil-dijual" class="mb-5">
            <h2 class="mb-3">Mobil yang Dijual</h2>
            <div class="row g-4">
                <?php if (!empty($cars_listed)): ?>
                    <?php foreach ($cars_listed as $car): ?>
                        <div class="col-md-4">
                            <div class="card car-card border-0 shadow-sm h-100">
                                <img
                                    src="<?php echo !empty($car['image']) ? htmlspecialchars($car['image']) : 'placeholder.png'; ?>"
                                    style="height: 200px; object-fit: cover;"
                                    class="card-img-top"
                                    alt="<?php echo htmlspecialchars($car['make']) . ' ' . htmlspecialchars($car['model']); ?>"
                                />
                                <div class="card-body d-flex flex-column">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h5 class="card-title mb-0"><?php echo htmlspecialchars($car['make']) . ' ' . htmlspecialchars($car['model']); ?></h5>
                                        <span class="badge badge-custom"><?php echo htmlspecialchars($car['year']); ?></span>
                                    </div>
                                    <p class="text-primary fw-bold">Rp <?php echo number_format($car['price'], 0, ',', '.'); ?></p>
                                    <div class="d-flex mb-3">
                                        <div class="me-3">
                                            <i class="fas fa-tachometer-alt text-muted me-1"></i>
                                            <?php echo $car['mileage'] ? number_format($car['mileage'], 0, ',', '.') . ' km' : 'N/A'; ?>
                                        </div>
                                    </div>
                                    <!-- Add Edit/Delete buttons if needed -->
                                    <a href="detail.php?id=<?php echo $car['id']; ?>" class="btn btn-secondary w-100 mt-auto btn-sm">Lihat Detail</a>
                                    <!-- Example: <a href="edit_car.php?id=<?php echo $car['id']; ?>" class="btn btn-warning btn-sm mt-2">Edit</a> -->
                                    <!-- Example: <a href="delete_car.php?id=<?php echo $car['id']; ?>" class="btn btn-danger btn-sm mt-2" onclick="return confirm('Yakin ingin menghapus mobil ini?');">Hapus</a> -->
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="alert alert-info" role="alert">
                            Anda belum mendaftarkan mobil untuk dijual. <a href="jual.php" class="alert-link">Jual mobil sekarang!</a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <hr class="my-5">

        <!-- Section for Purchased Cars -->
        <section id="mobil-dibeli">
            <h2 class="mb-3">Riwayat Pembelian Mobil Anda</h2>
            <div class="list-group">
                <?php if (!empty($cars_purchased)): ?>
                    <?php foreach ($cars_purchased as $purchase): ?>
                        <div class="list-group-item list-group-item-action flex-column align-items-start mb-3 shadow-sm">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1"><?php echo htmlspecialchars($purchase['car']); ?></h5>
                                <!-- <small>[Purchase Date if available]</small> -->
                            </div>
                            <p class="mb-1"><strong>Penerima:</strong> <?php echo htmlspecialchars($purchase['name']); ?></p>
                            <p class="mb-1"><strong>Alamat:</strong> <?php echo htmlspecialchars($purchase['address']); ?></p>
                            <p class="mb-1"><strong>Metode Pembayaran:</strong> <?php echo htmlspecialchars(ucwords(str_replace('_', ' ', $purchase['payment_method']))); ?></p>
                            <?php if (!empty($purchase['comment'])): ?>
                                <p class="mb-1 mt-2"><strong>Komentar Anda:</strong> <?php echo nl2br(htmlspecialchars($purchase['comment'])); ?></p>
                            <?php endif; ?>
                             <?php if (!empty($purchase['rating'])): ?>
                                <p class="mb-1 mt-2"><strong>Rating Anda:</strong>
                                    <?php for($i = 0; $i < $purchase['rating']; $i++): ?>
                                        <i class="fas fa-star text-warning"></i>
                                    <?php endfor; ?>
                                    <?php for($i = $purchase['rating']; $i < 5; $i++): ?>
                                        <i class="far fa-star text-warning"></i>
                                    <?php endfor; ?>
                                    (<?php echo $purchase['rating']; ?>/5)
                                </p>
                            <?php else: ?>
                                <!--Add a link/button to add rating/comment -->
                                <a href="process_purchase.php?id=<?php echo $purchase['id']; ?>" class="btn btn-outline-warning btn-sm mt-2">Beri Rating</a>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="alert alert-info" role="alert">
                        Anda belum melakukan pembelian mobil. <a href="beli.php" class="alert-link">Cari mobil impian Anda!</a>
                    </div>
                <?php endif; ?>
            </div>
        </section>

    </div>

    <?php include 'footer.php'; // Include footer -- assuming you have a footer.php ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
