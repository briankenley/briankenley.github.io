<?php
session_start(); // Start the session

// Unset all session variables
$_SESSION = array();

// Destroy the session
if (session_destroy()) {
    // Optional: Delete the session cookie if needed
    // if (ini_get("session.use_cookies")) {
    //     $params = session_get_cookie_params();
    //     setcookie(session_name(), '', time() - 42000,
    //         $params["path"], $params["domain"],
    //         $params["secure"], $params["httponly"]
    //     );
    // }
    $logout_message = "Anda telah berhasil keluar.";
} else {
    $logout_message = "Terjadi kesalahan saat mencoba keluar."; // Should ideally not happen
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Logout - MobilKupedia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="style.css"> <!-- Include your custom styles if needed -->
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand" href="mobilkupedia.php">MobilKu<span style="color: #f39c12">pedia</span></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="mobilkupedia.php">Beranda</a></li>
                <!-- Hide Dashboard/Jual if logged out -->
                <!-- <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li> -->
                <!-- <li class="nav-item"><a class="nav-link" href="jual.php">Jual Mobil</a></li> -->
                <li class="nav-item"><a class="nav-link" href="beli.php">Beli Mobil</a></li>
                <li class="nav-item"><a class="nav-link" href="about.php">Tentang Kami</a></li>
                <li class="nav-item"><a class="nav-link" href="kontak.php">Kontak</a></li>
            </ul>
            <div class="d-flex">
                <a href="login.php" class="btn btn-outline-primary me-2">Masuk</a>
                <a href="signup.php" class="btn btn-primary">Daftar</a>
            </div>
        </div>
    </div>
</nav>

<div class="d-flex justify-content-center align-items-center vh-100">
    <div class="card p-4 shadow text-center" style="width: 350px;">
        <h2 class="mb-3">Logout</h2>
        <p class="alert alert-success"><?php echo htmlspecialchars($logout_message); ?></p>
        <a href="login.php" class="btn btn-primary w-100 mt-2">Login Kembali</a>
        <a href="mobilkupedia.php" class="btn btn-link mt-2">Kembali ke Beranda</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
