<?php
session_start();
require_once "db.php";

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Directly use POST data (UNSAFE)
    $username = $_POST["username"] ?? '';
    $password = $_POST["password"] ?? '';

    if (empty($username) || empty($password)) {
        $error = "Username and password are required.";
    } else {
        // Check if username already exists (UNSAFE query)
        $sql_check = "SELECT id FROM users WHERE username = '$username'";
        $result_check = $conn->query($sql_check);

        if ($result_check && $result_check->num_rows > 0) {
            $error = "Username already taken.";
        } else {
            // Insert new user (UNSAFE query, storing plain password)
            $sql_insert = "INSERT INTO users (username, password) VALUES ('$username', '$password')";

            if ($conn->query($sql_insert) === TRUE) {
                $success = "Registration successful. You can now <a href='login.php'>login</a>.";
            } else {
                $error = "Error during registration. Please try again. " . $conn->error; // Added DB error for debugging
            }
        }
    }
    // Close connection if needed
    if ($conn) $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Daftar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand" href="#">MobilKu<span style="color: #f39c12">pedia</span></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link active" href="mobilkupedia.php">Beranda</a></li>
                <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="jual.php">Jual Mobil</a></li>
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
    <div class="card p-4 shadow" style="width: 350px;">
        <h2 class="text-center">Daftar</h2>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div> <?php // Removed htmlspecialchars ?>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div> <?php // Success message already contains HTML ?>
        <?php endif; ?>
        <form method="post" action="signup.php">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" id="username" name="username" class="form-control" /> <?php // Removed required ?>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password" class="form-control" /> <?php // Removed required ?>
            </div>
            <button type="submit" class="btn btn-primary w-100">Daftar</button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
