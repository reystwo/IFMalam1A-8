<?php
session_start();
require_once '../../config/koneksi.php';

// Cek apakah user sudah login dan role mahasiswa
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'mahasiswa') {
    header("Location: ../login.php");
    exit();
}

$nim = $_SESSION['username'];
$nama = $_SESSION['nama'];
$jurusan = isset($_SESSION['jurusan']) ? $_SESSION['jurusan'] : 'Tidak diketahui';
$angkatan = isset($_SESSION['angkatan']) ? $_SESSION['angkatan'] : 'Tidak diketahui';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profile - Mahasiswa</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f4f6f9;
        }
        .navbar-custom {
            background-color: #143c8c;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .navbar-custom .navbar-brand,
        .navbar-custom .nav-link {
            color: white;
        }
        .profile-container {
            background: white;
            border-radius: 10px;
            padding: 40px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            max-width: 800px;
            margin: 50px auto;
        }
        .profile-picture {
            width: 180px;
            height: 180px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid #143c8c;
            margin: 0 auto 20px;
            display: block;
            background-color: #f5f5f5;
        }
        .user-name {
            font-size: 28px;
            font-weight: 700;
            text-align: center;
            color: #143c8c;
            margin-bottom: 10px;
        }
        .user-nim {
            font-size: 20px;
            text-align: center;
            color: #666;
            margin-bottom: 20px;
        }
        .user-role {
            background: #143c8c;
            color: white;
            padding: 10px 25px;
            border-radius: 20px;
            font-size: 18px;
            display: inline-block;
            margin: 10px auto;
            font-weight: 600;
            text-align: center;
            width: fit-content;
        }
        .info-box {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 25px;
            border-left: 5px solid #143c8c;
        }
        .info-label {
            font-weight: 600;
            color: #666;
            margin-bottom: 8px;
            font-size: 16px;
        }
        .info-value {
            font-size: 20px;
            color: #333;
            font-weight: 500;
        }
        .center-content {
            text-align: center;
        }
        .btn-custom {
            background-color: #143c8c;
            color: white;
            padding: 12px 30px;
            font-weight: 600;
            font-size: 16px;
            border-radius: 8px;
            transition: all 0.3s ease;
            border: none;
        }
        .btn-custom:hover {
            background-color: #0d2a5c;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .section-title {
            font-size: 24px;
            font-weight: 700;
            color: #143c8c;
            margin-bottom: 25px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e0e0e0;
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-custom">
  <div class="container">
    <a class="navbar-brand fw-bold" href="home-mhs.php">SAPA Mahasiswa</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon text-white">☰</span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link" href="home-mhs.php"><i class="fas fa-home me-1"></i> Beranda</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="profile-mhs.php"><i class="fas fa-user me-1"></i> Profile</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../auth/logout.php"><i class="fas fa-sign-out-alt me-1"></i> Logout</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="profile-container">
    <div class="center-content">
        <img src="../../uploads/profile/default.png" alt="Profile Picture" class="profile-picture">
        
        <div class="user-name"><?php echo htmlspecialchars($nama); ?></div>
        <div class="user-nim">NIM: <?php echo htmlspecialchars($nim); ?></div>
        <div class="user-role">Mahasiswa</div>
    </div>
    
    <div class="section-title text-center mt-5">Informasi Mahasiswa</div>
    
    <div class="info-box">
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="info-label">Nama Lengkap</div>
                <div class="info-value"><?php echo htmlspecialchars($nama); ?></div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="info-label">NIM</div>
                <div class="info-value"><?php echo htmlspecialchars($nim); ?></div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="info-label">Jurusan</div>
                <div class="info-value"><?php echo htmlspecialchars($jurusan); ?></div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="info-label">Angkatan</div>
                <div class="info-value"><?php echo htmlspecialchars($angkatan); ?></div>
            </div>
        </div>
    </div>
    
    <div class="info-box">
        <div class="info-label">Status Akademik</div>
        <div class="info-value">Aktif</div>
        
        <div class="info-label mt-3">Catatan</div>
        <div class="info-value" style="font-size: 16px;">
            <i class="fas fa-info-circle text-primary me-2"></i>
            Untuk perubahan data pribadi atau masalah akademik, silakan hubungi Administrasi Akademik.
        </div>
    </div>
    
    <div class="text-center mt-4">
        <a href="home-mhs.php" class="btn btn-custom me-3">
            <i class="fas fa-arrow-left me-2"></i>Kembali ke Beranda
        </a>
        <a href="../auth/logout.php" class="btn btn-outline-primary">
            <i class="fas fa-sign-out-alt me-2"></i>Logout
        </a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Auto-hide alert setelah 5 detik
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    });
</script>
</body>
</html>