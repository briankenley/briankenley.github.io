<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>MobilKupedia - Platform Jual Beli Mobil Terpercaya</title>
    <link rel="stylesheet" href="style.css">
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
              <a class="nav-link active" href="#">Beranda</a>
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

    <section class="hero-section text-center" style="background-image: url(background.jpg); opacity: 70%; width: 100%;">

      <div class="container" >
        
        <div class="row">
          <div class="col-lg-8 mx-auto">
            <h1 class="display-4 fw-bold mb-4">
              Jual Beli Mobil Kini Lebih Mudah
            </h1>
            <p class="lead mb-5">
              MobilKupedia menghubungkan pemilik mobil dengan pembeli potensial
              melalui platform terpercaya. Semua mobil telah terverifikasi
              kualitasnya.
            </p>
            <div class="d-flex justify-content-center gap-3">
              <a href="jual.php" class="btn btn-primary btn-lg px-4 py-3"
                >Jual Mobil Anda</a
              >
              <a href="beli.php" class="btn btn-outline-light btn-lg px-4 py-3"
                >Beli Mobil</a
              >
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="py-5">
      <div class="container">
        <h2 class="section-title">Bagaimana Cara Kerjanya</h2>
        <div class="row g-4">
          <div class="col-md-4">
            <div class="card feature-card h-100 border-0 shadow-sm">
              <div class="card-body text-center p-4">
                <div class="feature-icon">
                  <i class="fas fa-car-side"></i>
                </div>
                <h4>1. Ajukan Penawaran</h4>
                <p class="text-muted">
                  Isi formulir dan unggah foto kondisi mobil Anda beserta
                  dokumen lengkap. Tim kami akan meninjau penawaran Anda.
                </p>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card feature-card h-100 border-0 shadow-sm">
              <div class="card-body text-center p-4">
                <div class="feature-icon">
                  <i class="fas fa-clipboard-check"></i>
                </div>
                <h4>2. Verifikasi &amp; Penawaran</h4>
                <p class="text-muted">
                  Tim kami akan memverifikasi kondisi mobil dan memberikan
                  penawaran terbaik sesuai dengan harga pasar.
                </p>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card feature-card h-100 border-0 shadow-sm">
              <div class="card-body text-center p-4">
                <div class="feature-icon">
                  <i class="fas fa-money-bill-wave"></i>
                </div>
                <h4>3. Transaksi Aman</h4>
                <p class="text-muted">
                  Setelah harga disepakati, kami akan melakukan pembayaran
                  langsung ke rekening Anda dengan proses yang cepat dan aman.
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>


    <section class="py-5">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-6 order-lg-2">
            <h2 class="section-title text-start">
              Pantau Proses Pembelian di Dashboard Pribadi
            </h2>
            <p>
              Setiap pengguna yang mengajukan penawaran akan memiliki dashboard
              pribadi untuk memantau status penawaran secara real-time,
              termasuk:
            </p>
            <ul class="list-unstyled mb-4">
              <li class="mb-2">
                <i class="fas fa-check text-primary me-2"></i> Status proses
                verifikasi
              </li>
              <li class="mb-2">
                <i class="fas fa-check text-primary me-2"></i> Detail penawaran
                harga
              </li>
              <li class="mb-2">
                <i class="fas fa-check text-primary me-2"></i> Histori pengajuan
              </li>
              <li class="mb-2">
                <i class="fas fa-check text-primary me-2"></i> Notifikasi
                perubahan status
              </li>
              <li class="mb-2">
                <i class="fas fa-check text-primary me-2"></i> Komunikasi dengan
                tim support
              </li>
            </ul>
              <a href="dashboard.php" class="btn btn-primary px-4 py-2">Lihat Dashboard</a>
          </div>
          <div class="col-lg-6 order-lg-1 mt-4 mt-lg-0">
            <img
              src="dashboard.png"
              alt="Dashboard Preview"
              class="img-fluid rounded shadow"
            />
          </div>
        </div>
      </div>
    </section>

    <section class="py-5 text-white" style="background-color: #f39c12">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-8 mx-auto text-center">
            <h2 class="mb-4">Siap Membeli atau Menjual Mobil?</h2>
            <p class="lead mb-4">
              Bergabunglah dengan ribuan pengguna yang telah merasakan kemudahan
              transaksi di MobilKupedia
            </p>
            <div class="d-flex justify-content-center gap-3">
              <a href="beli.php" class="btn btn-light px-4 py-2">Beli Mobil</a>
              <a href="jual.php" class="btn btn-outline-light px-4 py-2">Jual Mobil</a>
            </div>
          </div>
        </div>
      </div>
    </section>

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
                <i class="fas fa-map-marker-alt me-2"></i>  Medan
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
