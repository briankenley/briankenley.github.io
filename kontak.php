<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kontak</title>
  </head>
  <link rel="stylesheet" href="style.css" />
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css"
    rel="stylesheet"
  />
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
  />
<?php
session_start();
// Removed include 'db.php'; as DB interaction is removed
// Removed the entire POST processing block for simplification
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

    <div class="container mt-3">
      <h2 class="text-center mb-5">Contact Us</h2>
      <div class="row">
        <div class="col-md-6 me-3">
          <h4>Get in Touch</h4>
          <p><i class="fa fa-map-marker-alt me-2"></i> Medan</p>
          <p><i class="fa fa-envelope me-2"></i> mobilkupedia@example.com</p>
          <p><i class="fa fa-phone me-2"></i> +62-813-6789-7890</p>
          WhatsApp Chat Link
          <p><a href='https://wa.me/6281269791841' target='_blank'>Hubungi kami melalui WhatsApp</a></p>
          <iframe
            src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d3982.006031279587!2d98.6708661!3d3.5860894!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x303131cf9d318f0b%3A0x24276c6d401c3eee!2sUniversitas%20Pelita%20Harapan%20Medan!5e0!3m2!1sid!2sid!4v1741259438033!5m2!1sid!2sid"
            width="90%"
            height="250"
            frameborder="0"
            style="border: 0"
            allowfullscreen=""
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"
          ></iframe>
        </div>
        <div class="col-md-5">
          <h4>Send Us a Message</h4>
          <!-- Form action remains, but no server-side processing will happen -->
          <form method="POST" action="kontak.php">
            <div class="mb-3">
              <label for="name" class="form-label">Name</label>
              <input type="text" name="name" class="form-control" id="name" />
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="email" name="email" class="form-control" id="email" />
            </div>
            <div class="mb-3">
              <label for="message" class="form-label">Message</label>
              <textarea
                name="message"
                class="form-control"
                id="message"
                rows="4"
              ></textarea>
            </div>
            <!-- Button remains, but won't save data -->
            <button type="submit" class="btn btn-primary">Send Message</button>
          </form>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
