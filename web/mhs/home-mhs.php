<?php
session_start();
require_once '../../config/koneksi.php';

// Set nama session jika belum ada
if (!isset($_SESSION['nama'])) {
    $_SESSION['nama'] = 'Mahasiswa';
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengumuman Akademik - SAPA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            background: #f3f4f6;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .navbar-brand {
            color: #0d6efd;
            font-weight: bold;
        }
        .event-card {
            border-radius: 12px;
            overflow: hidden;
            background: white;
            transition: all 0.3s ease;
            height: 100%;
            text-decoration: none;
            color: inherit;
            display: block;
            border: 1px solid #e0e0e0;
        }
        .event-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            text-decoration: none;
            color: inherit;
        }
        .event-card img {
            height: 200px;
            width: 100%;
            object-fit: cover;
        }
        .card-body {
            padding: 20px;
        }
        footer {
            background: linear-gradient(135deg, #0d3b66 0%, #1c5b96 100%);
            color: white;
            padding: 40px 0;
            margin-top: 50px;
        }
        .pdf-badge {
            background-color: #dc3545;
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
            display: inline-block;
        }
        .no-image {
            height: 200px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 14px;
        }
        .category-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            background: rgba(0,0,0,0.7);
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
        }
        .sidebar-filter {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }
        .search-box {
            max-width: 400px;
        }
        .card-title {
            font-weight: 600;
            line-height: 1.4;
            margin-bottom: 10px;
            color: #333;
        }
        .card-text {
            color: #666;
            font-size: 14px;
            line-height: 1.6;
        }
        .card-footer-info {
            border-top: 1px solid #eee;
            padding-top: 10px;
            margin-top: 10px;
        }
        .no-results {
            padding: 50px 20px;
            text-align: center;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
    </style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="home-mhs.php">
            <i class="fas fa-bullhorn me-2"></i>SAPA
        </a>
        
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link active fw-medium" href="home-mhs.php">Home</a></li>
                <li class="nav-item"><a class="nav-link fw-medium" href="profile-mhs.php">Profile</a></li>
                <li class="nav-item"><a class="nav-link fw-medium" href="#">Kontak</a></li>
            </ul>

            <div class="d-flex align-items-center">
                <span class="text-muted me-3">Halo, <?php echo htmlspecialchars($_SESSION['nama']); ?></span>
                <a href="../auth/logout.php" class="btn btn-outline-danger btn-sm">
                    <i class="fas fa-sign-out-alt me-1"></i>Logout
                </a>
            </div>
        </div>
    </div>
</nav>

<!-- HEADER & SEARCH -->
<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold text-primary">Pengumuman Akademik</h2>
            <p class="text-muted">Temukan pengumuman terbaru dari kampus Anda</p>
            
            <form method="GET" action="" class="search-box">
                <div class="input-group">
                    <input type="text" class="form-control" name="search" placeholder="Cari pengumuman..." 
                           value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <!-- SIDEBAR FILTER -->
        <div class="col-lg-3 mb-4">
            <div class="sidebar-filter">
                <h5 class="fw-bold mb-3"><i class="fas fa-filter me-2"></i>Filter</h5>

                <form method="GET" action="">
                    <input type="hidden" name="search" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    
                    <h6 class="fw-bold mt-4 mb-2">Kategori</h6>
                    <?php
                    // Query untuk mendapatkan kategori unik
                    $kategori_query = "SELECT DISTINCT kategori FROM pengumuman WHERE status = 'published' ORDER BY kategori";
                    $kategori_result = mysqli_query($koneksi, $kategori_query);
                    
                    while ($row = mysqli_fetch_assoc($kategori_result)) {
                        $kategori = $row['kategori'];
                        $checked = (isset($_GET['kategori']) && in_array($kategori, $_GET['kategori'])) ? 'checked' : '';
                        echo "<div class='form-check mb-2'>
                                <input class='form-check-input' type='checkbox' name='kategori[]' value='$kategori' id='kat-$kategori' $checked>
                                <label class='form-check-label' for='kat-$kategori'>$kategori</label>
                              </div>";
                    }
                    ?>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary btn-sm w-100">
                            <i class="fas fa-check me-1"></i>Terapkan Filter
                        </button>
                        <a href="home-mhs.php" class="btn btn-outline-secondary btn-sm w-100 mt-2">
                            <i class="fas fa-times me-1"></i>Reset Filter
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- PENGUMUMAN LIST -->
        <div class="col-lg-9">
            <div class="row g-4">
                <?php
                // Query dengan filter
                $where = array();
                
                // Hanya tampilkan published
                $where[] = "status = 'published'";
                
                // Filter kategori
                if (isset($_GET['kategori']) && is_array($_GET['kategori'])) {
                    $kategori_filter = array_map(function($kat) use ($koneksi) {
                        return mysqli_real_escape_string($koneksi, $kat);
                    }, $_GET['kategori']);
                    $kategori_list = "'" . implode("','", $kategori_filter) . "'";
                    $where[] = "kategori IN ($kategori_list)";
                }
                
                // Filter pencarian
                if (isset($_GET['search']) && !empty($_GET['search'])) {
                    $search = mysqli_real_escape_string($koneksi, $_GET['search']);
                    $where[] = "(judul LIKE '%$search%' OR isi LIKE '%$search%' OR penulis LIKE '%$search%')";
                }
                
                // Build query
                $where_clause = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";
                $query = "SELECT * FROM pengumuman $where_clause ORDER BY date DESC LIMIT 12";
                
                $result = mysqli_query($koneksi, $query);
                
                if (mysqli_num_rows($result) > 0) {
                    while ($pengumuman = mysqli_fetch_assoc($result)) {
                        // Format tanggal
                        $tanggal = date('d/m/Y', strtotime($pengumuman['date']));
                        
                        // Potong isi untuk preview
                        $isi_preview = strip_tags($pengumuman['isi']);
                        if (strlen($isi_preview) > 120) {
                            $isi_preview = substr($isi_preview, 0, 120) . '...';
                        }
                        
                        // Tentukan gambar
                        $gambar_tampil = '';
                        if (!empty($pengumuman['gambar'])) {
                            $gambar_path = '../../uploads/images/' . $pengumuman['gambar'];
                            if (file_exists($gambar_path)) {
                                $gambar_tampil = '<img src="' . $gambar_path . '" class="card-img-top" alt="' . htmlspecialchars($pengumuman['judul']) . '">';
                            } else {
                                $gambar_tampil = '<div class="no-image">
                                                    <div class="text-center">
                                                        <i class="fas fa-image-slash fa-3x mb-2"></i><br>
                                                        <span>Gambar tidak ditemukan</span>
                                                    </div>
                                                  </div>';
                            }
                        } else {
                            $gambar_tampil = '<div class="no-image">
                                                <div class="text-center">
                                                    <i class="fas fa-newspaper fa-3x mb-2"></i><br>
                                                    <span>Tidak ada gambar</span>
                                                </div>
                                              </div>';
                        }
                        
                        // Badge kategori
                        $kategori_badge = '<span class="category-badge">' . $pengumuman['kategori'] . '</span>';
                ?>
                
                <div class="col-md-6 col-lg-4">
                    <a href="detail-pengumuman.php?id=<?php echo $pengumuman['id']; ?>" class="event-card">
                        <div class="position-relative">
                            <?php echo $gambar_tampil; ?>
                            <?php echo $kategori_badge; ?>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($pengumuman['judul']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($isi_preview); ?></p>
                            
                            <div class="card-footer-info">
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <i class="bi bi-calendar me-1"></i><?php echo $tanggal; ?>
                                    </small>
                                    <small class="text-muted">
                                        <i class="bi bi-person me-1"></i><?php echo htmlspecialchars($pengumuman['penulis']); ?>
                                    </small>
                                </div>
                                <?php if (!empty($pengumuman['pdf'])): ?>
                                    <small class="pdf-badge mt-2">
                                        <i class="fas fa-file-pdf me-1"></i>Lampiran PDF
                                    </small>
                                <?php endif; ?>
                            </div>
                        </div>
                    </a>
                </div>
                
                <?php
                    }
                } else {
                    echo '<div class="col-12">
                            <div class="no-results">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <h4 class="text-muted">Tidak ada pengumuman ditemukan</h4>
                                <p class="text-muted">Coba gunakan kata kunci lain atau hapus filter</p>
                                <a href="home-mhs.php" class="btn btn-primary mt-2">
                                    <i class="fas fa-home me-1"></i>Lihat Semua Pengumuman
                                </a>
                            </div>
                          </div>';
                }
                ?>
            </div>
        </div>
    </div>
</div>

<!-- FOOTER -->
<footer>
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4">
                <h5 class="fw-bold mb-3">Tentang SAPA</h5>
                <p class="small">Sistem Pengumuman Akademik Online yang memudahkan mahasiswa dan dosen dalam mengakses informasi akademik terbaru.</p>
            </div>
            <div class="col-md-4 mb-4">
                <h5 class="fw-bold mb-3">Tautan Cepat</h5>
                <a href="home-mhs.php" class="d-block text-light mb-2">Home</a>
                <a href="profile-mhs.php" class="d-block text-light mb-2">Profile</a>
                <a href="#" class="d-block text-light mb-2">Kontak</a>
            </div>
            <div class="col-md-4 mb-4">
                <h5 class="fw-bold mb-3">Hubungi Kami</h5>
                <p class="small mb-1"><i class="fas fa-envelope me-2"></i>sapa@kampus.edu</p>
                <p class="small mb-1"><i class="fas fa-phone me-2"></i>0812-3456-7890</p>
            </div>
        </div>
        <div class="row mt-4 pt-3 border-top border-light">
            <div class="col-12 text-center">
                <p class="mb-0">
                    <i class="fas fa-bullhorn me-1"></i>
                    &copy; <?php echo date('Y'); ?> Sistem Pengumuman Akademik Online (SAPA) - Hak Cipta Dilindungi
                </p>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>