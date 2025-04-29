<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tentang Kami</title>
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

    <section class="py-5" id="about">
        <div class="container">
          <div class="row align-items-center">
            <div class="col-lg-6">
              <h2 class="section-title text-start">
                Jual Mobil Anda dengan Mudah dan Cepat
              </h2>
              <p class="mb-4">
                Dengan MobilKupedia, Anda dapat menjual mobil Anda tanpa ribet.
                Kami memberikan penawaran terbaik dan proses yang transparan:
              </p>
              <ul class="list-unstyled mb-4">
                <li class="mb-2">
                  <i class="fas fa-check text-primary me-2"></i> Proses pengajuan
                  online yang sederhana
                </li>
                <li class="mb-2">
                  <i class="fas fa-check text-primary me-2"></i> Penawaran harga
                  yang kompetitif
                </li>
                <li class="mb-2">
                  <i class="fas fa-check text-primary me-2"></i> Pembayaran cepat
                  dan aman
                </li>
                <li class="mb-2">
                  <i class="fas fa-check text-primary me-2"></i> Tim profesional
                  yang siap membantu
                </li>
                <li class="mb-2">
                  <i class="fas fa-check text-primary me-2"></i> Bebas dari
                  kewajiban negosiasi dengan banyak pembeli
                </li>
              </ul>
              <a href="#" class="btn btn-primary px-4 py-2"
                >Ajukan Penawaran Sekarang</a
              >
            </div>
            <div class="col-lg-6 mt-4 mt-lg-0">
              <img
                src="xtrail.jpg"
                alt="Jual Mobil"
                class="img-fluid rounded shadow"
              />
            </div>
          </div>
        </div>
      </section>
  
      <section class="py-5 bg-light">
        <div class="container">
          <h2 class="section-title">Apa Kata Mereka</h2>
          <div class="row g-4">
            <div class="col-md-4">
              <div class="testimonial-card shadow-sm">
                <div class="mb-3 text-warning">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                </div>
                <p class="mb-3">
                  "Proses jual mobil yang sangat mudah dan cepat. Saya mendapatkan
                  harga yang bagus untuk Honda CR-V saya. Transaksi selesai dalam
                  3 hari!"
                </p>
                <div class="d-flex align-items-center">
                  <div
                    class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                    style="width: 50px; height: 50px"
                  >
                    BS
                  </div>
                  <div class="ms-3">
                    <h6 class="mb-0">Budi Santoso</h6>
                    <small class="text-muted">Jakarta</small>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="testimonial-card shadow-sm">
                <div class="mb-3 text-warning">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                </div>
                <p class="mb-3">
                  "Sangat puas dengan layanan MobilKupedia. Saya bisa membeli
                  mobil dengan harga yang adil tanpa harus susah untuk mencari
                  penjual."
                </p>
                <div class="d-flex align-items-center">
                  <div
                    class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                    style="width: 50px; height: 50px"
                  >
                    DS
                  </div>
                  <div class="ms-3">
                    <h6 class="mb-0">Dewi Suryani</h6>
                    <small class="text-muted">Bandung</small>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="testimonial-card shadow-sm">
                <div class="mb-3 text-warning">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star-half-alt"></i>
                </div>
                <p class="mb-3">
                  "Platform yang sangat terpercaya untuk membeli mobil bekas. Saya
                  membeli Avanza melalui MobilKupedia dan kondisinya sesuai dengan
                  yang dijelaskan."
                </p>
                <div class="d-flex align-items-center">
                  <div
                    class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                    style="width: 50px; height: 50px"
                  >
                    RP
                  </div>
                  <div class="ms-3">
                    <h6 class="mb-0">Rudi Pratama</h6>
                    <small class="text-muted">Surabaya</small>
                  </div>
                </div>
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
  
  </body>
</html>
