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
              <a class="nav-link active" href="mobilkupedia.php">Beranda</a> <?php // Corrected link and added active class ?>
            </li>
            <?php // Links visible whether logged in or not, but some depend on session state ?>
            <?php if (isset($_SESSION['userid'])): ?>
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
            <?php else: // If not logged in, still show Beli Mobil link ?>
              <li class="nav-item">
                 <a class="nav-link" href="dashboard.php">Dashboard</a> <?php // Beli link always visible ?>
              </li>
              <li class="nav-item">
                 <a class="nav-link" href="jual.php">Jual Mobil</a> <?php // Beli link always visible ?>
              </li>
              <li class="nav-item">
                 <a class="nav-link" href="beli.php">Beli Mobil</a> <?php // Beli link always visible ?>
              </li>
            <?php endif; ?>
            <?php // Always show About and Contact ?>
            <li class="nav-item">
              <a class="nav-link" href="about.php">Tentang Kami</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="kontak.php">Kontak</a>
            </li>
          </ul>
          <div class="d-flex align-items-center"> <?php // Added align-items-center for better vertical alignment ?>
            <?php if (isset($_SESSION['userid'])): ?>
                <?php // Display welcome message and logout button ?>
                <span class="navbar-text me-3">Selamat datang, <?php echo $_SESSION['username']; ?>!</span>
                <a href="logout.php" class="btn btn-outline-danger">Keluar</a>
            <?php else: ?>
                <?php // Display login and signup buttons ?>
                <a href="login.php" class="btn btn-outline-primary me-2">Masuk</a>
                <a href="signup.php" class="btn btn-primary">Daftar</a>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </nav>

    <section class="hero-section text-center" style="background-image: url(background.jpg); opacity: 0.7; width: 100%; background-size: cover; background-position: center;">

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

    <?php include 'footer.php';?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
