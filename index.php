<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SAPA - Landing Page</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background:#dedede
    }
    .container .navbar-brand {
      font-weight:750
    }
    .logo { 
      display: block;
      margin-left: auto;
      margin-right: 10px;
      width: 60px;
      height: auto;
      border-radius: 10px;
      object-fit: cover;
      display: block;
    }
    .hero {
      background: linear-gradient(90deg,#1479ff 0%, #7b2cff 100%);
      background-image: url(assets/img/polibatam.jpeg);
      color:#fff;
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      position: relative;
      padding:150px 0
    }
    .custom-background {
      background-color: rgba(0, 0, 0, 0.5);
      padding: 20px;
      border-radius: 8px;
      width: 80%;
      margin: 50px auto;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
      text-align: center;
    }
    .hero h1 {
      font-size:48px;
      font-weight:800
    }
    .hero p {
      opacity:.9
    }
    .btn-ghost {
      background:#fff;
      color:#333;
      border-radius:8px;
      padding:10px 18px;
      font-weight:600
    }
    .feature-card {
      border-radius:8px;
      border:1px solid #e9ecef;
      box-shadow:0 6px 18px rgba(50,50,93,.06);
      min-height:220px
    }
    .isi {
      text-align: center;
    }
    .feature-card .card-body {
      padding:28px
    }
    .feature-card .icon {
      height:60px;
      width:60px;
      background:#f1f5f9;
      border-radius:8px;
      display:flex;
      align-items:center;
      justify-content:center;
      margin-bottom:18px
    }
    .section-title {
      font-weight:700;
      margin-bottom:34px
    }
    .contact-card {
      max-width:760px;
      margin:40px auto;
      padding:26px;
      background:#fff;
      border-radius:10px;
      box-shadow:0 10px 30px rgba(50,50,93,0.6)
    }
    footer {
      padding:18px 0;
      background:#282929;
      text-align:center;
      margin-top:32px
    }
    .containers {
      color: white;
      font-weight: 500;
    }
    @media (max-width:576px) {
      .hero h1{
        font-size:32px
      }
    }
  </style>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <img src="assets/img/logosapa1.png" alt="Logo" class="logo">
      <a class="navbar-brand" href="#">Sistem Pengumuman Akademik</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navMenu">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link active" href="#">Beranda</a></li>
          <li class="nav-item"><a class="nav-link" href="#fitur">Fitur</a></li>
          <li><a href="web/login.php" class="btn btn-primary btn-login">Login</a></li>
        </ul>
      </div>
    </div>
  </nav>
  <header class="hero text-center text-white">
    <div class="container">
      <div class="custom-background">
        <h1 class="mb-3">Selamat Datang di SAPA</h1>
        <p class="lead mb-4">Sistem Aplikasi Pengumuman Akademik Berbasis Online</p>
      </div>
    </div>
  </header>
  <section id="fitur" class="py-5">
    <div class="container">
      <h3 class="text-center section-title"><b>Fitur Unggulan SAPA</b></h3>
      <div class="row g-4">
        <div class="col-lg-4 col-md-6">
          <div class="card feature-card h-100">
            <div class="card-body text-center">
              <h4 class="card-title"><b>Pencarian Pengumuman</b></h4>
              <p class="card-text text-muted">Cari pengumuman dengan cepat berdasarkan judul atau kategori</p>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6">
          <div class="card feature-card h-100">
            <div class="card-body text-center">
              <h4 class="card-title"><b>Informasi Terbaru</b></h4>
              <p class="card-text text-muted">Lihat daftar pengumuman terbaru yang diperbarui secara berkala</p>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6">
          <div class="card feature-card h-100">
            <div class="card-body text-center">
              <h4 class="card-title"><b>Akses Dosen & Mahasiswa</b></h4>
              <p class="card-text text-muted">Sistem login yang menyesuaikan tampilan berdasarkan peran pengguna</p>
            </div>
          </div>
        </div>
      </div>
      <h3 class="text-center section-title mt-5"><b>Tentang SAPA</b></h3>
      <div class="row g-4">
        <div class="col-lg-12 col-md-6">
          <div class="card feature-card h-100">
            <div class="card-body text-center">
              <p class="isi">SAPA adalah karya kreatif dan inovatif hasil gagasan mahasiswa yang berangkat dari permasalahan nyata di lingkungan kampus, yaitu belum tersedianya platform resmi untuk membagikan pengumuman secara terpusat antara dosen dan mahasiswa. Melalui SAPA, proses penyampaian informasi akademik kini menjadi lebih cepat, efisien, dan transparan. Kami percaya bahwa komunikasi yang efektif adalah kunci keberhasilan ekosistem pendidikan digital, dan SAPA hadir sebagai solusi nyata untuk mewujudkan hal tersebut.</p>
              <p class="isi">Didesain dengan semangat kolaborasi dan pemikiran modern, SAPA memadukan kemudahan akses, teknologi web yang responsif, serta tampilan interface yang intuitif. Sistem ini tidak hanya menjadi wadah informasi, tetapi juga simbol transformasi digital kampus menuju era yang lebih cerdas dan terhubung. Dengan SAPA, setiap pengumuman penting dapat tersampaikan tepat waktu, tanpa batasan ruang dan waktu demi mendukung kegiatan akademik yang lebih produktif, modern, dan berkelanjutan.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <footer>
    <div class="containers">© 2025 SAPA | Sistem Aplikasi Pengumuman Akademik </div>
  </footer>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>