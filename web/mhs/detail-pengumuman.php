<?php
session_start();
require_once '../../config/koneksi.php';

// Cek apakah ada parameter ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: home-mhs.php");
    exit();
}

$id = mysqli_real_escape_string($koneksi, $_GET['id']);

// Ambil data pengumuman
$query = "SELECT * FROM pengumuman WHERE id = '$id' AND status = 'published'";
$result = mysqli_query($koneksi, $query);

if (mysqli_num_rows($result) == 0) {
    header("Location: home-mhs.php");
    exit();
}

$pengumuman = mysqli_fetch_assoc($result);

// Format tanggal
$tanggal_full = date('d F Y H:i', strtotime($pengumuman['date']));
$tanggal_simple = date('d/m/Y', strtotime($pengumuman['date']));
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pengumuman['judul']); ?> - SAPA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .article-header {
            background: white;
            padding: 40px 0;
            border-bottom: 1px solid #e0e0e0;
            margin-bottom: 30px;
        }
        .article-content {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }
        .article-image {
    max-width: 100%;
    height: auto;  /* Biarkan tinggi menyesuaikan proporsi */
    border-radius: 8px;
    margin-bottom: 25px;
    display: block;
    margin-left: auto;
    margin-right: auto;
}
        .no-image-box {
            height: 200px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            border-radius: 8px;
            margin-bottom: 25px;
        }
        .pdf-download {
            background: #f8f9fa;
            border-left: 4px solid #dc3545;
            border-radius: 6px;
            padding: 20px;
            margin-top: 30px;
        }
        .category-badge {
            background: #0d6efd;
            color: white;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 500;
            display: inline-block;
        }
        .sidebar-box {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.05);
        }
        .related-article {
            border-bottom: 1px solid #eee;
            padding: 12px 0;
            transition: all 0.2s;
        }
        .related-article:hover {
            background: #f8f9fa;
            padding-left: 10px;
            border-radius: 4px;
        }
        .related-article:last-child {
            border-bottom: none;
        }
        .article-text {
            line-height: 1.8;
            font-size: 16px;
            color: #444;
        }
        .breadcrumb {
            background: transparent;
            padding: 0;
            margin-bottom: 15px;
        }
        .back-button {
            background: #6c757d;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 15px;
            transition: background 0.3s;
        }
        .back-button:hover {
            background: #5a6268;
            color: white;
            text-decoration: none;
        }
        .info-box {
            background: #e8f4ff;
            border-left: 4px solid #0d6efd;
            padding: 15px;
            border-radius: 4px;
            margin-top: 20px;
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
        
        <div class="d-flex align-items-center">
            <span class="text-muted me-3">Halo, <?php echo isset($_SESSION['nama']) ? htmlspecialchars($_SESSION['nama']) : 'Mahasiswa'; ?></span>
            <a href="../auth/logout.php" class="btn btn-outline-danger btn-sm">
                <i class="fas fa-sign-out-alt me-1"></i>Logout
            </a>
        </div>
    </div>
</nav>

<!-- HEADER -->
<div class="article-header">
    <div class="container">
        <a href="home-mhs.php" class="back-button">
            <i class="fas fa-arrow-left me-2"></i>Kembali ke Beranda
        </a>
        
        <div class="d-flex align-items-center mb-3">
            <span class="category-badge me-3"><?php echo $pengumuman['kategori']; ?></span>
            <small class="text-muted">
                <i class="bi bi-calendar me-1"></i><?php echo $tanggal_full; ?>
            </small>
        </div>
        
        <h1 class="fw-bold display-6"><?php echo htmlspecialchars($pengumuman['judul']); ?></h1>
        
        <div class="d-flex align-items-center mt-3">
            <small class="text-muted me-4">
                <i class="bi bi-person me-1"></i>Penulis: <?php echo htmlspecialchars($pengumuman['penulis']); ?>
            </small>
        </div>
    </div>
</div>

<!-- CONTENT -->
<div class="container my-4">
    <div class="row">
        <!-- ARTICLE CONTENT -->
        <div class="col-lg-8 mb-4">
            <div class="article-content">
                <?php if (!empty($pengumuman['gambar'])): 
                    $gambar_path = '../../uploads/images/' . $pengumuman['gambar'];
                    if (file_exists($gambar_path)):
                ?>
                    <img src="<?php echo $gambar_path; ?>" class="img-fluid article-image" 
                         alt="<?php echo htmlspecialchars($pengumuman['judul']); ?>">
                <?php else: ?>
                    <div class="no-image-box">
                        <div class="text-center">
                            <i class="fas fa-image-slash fa-3x mb-3"></i><br>
                            <h5>Gambar tidak ditemukan</h5>
                        </div>
                    </div>
                <?php endif; else: ?>
                    <div class="no-image-box">
                        <div class="text-center">
                            <i class="fas fa-newspaper fa-3x mb-3"></i><br>
                            <h5>Tidak ada gambar</h5>
                        </div>
                    </div>
                <?php endif; ?>
                
                <div class="article-text">
                    <?php echo nl2br(htmlspecialchars($pengumuman['isi'])); ?>
                </div>
                
                <?php if (!empty($pengumuman['pdf'])): 
                    $pdf_path = '../../uploads/pdf/' . $pengumuman['pdf'];
                    if (file_exists($pdf_path)):
                ?>
                    <div class="pdf-download">
                        <h5 class="mb-3"><i class="fas fa-file-pdf text-danger me-2"></i>Lampiran PDF</h5>
                        <p class="mb-3">Download dokumen lampiran untuk informasi lebih lengkap:</p>
                        <a href="<?php echo $pdf_path; ?>" class="btn btn-danger" target="_blank">
                            <i class="fas fa-download me-2"></i>Download PDF
                        </a>
                        <small class="text-muted d-block mt-2">
                            <i class="fas fa-info-circle me-1"></i>File: <?php echo htmlspecialchars($pengumuman['pdf']); ?>
                        </small>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>File PDF tidak ditemukan di server.
                    </div>
                <?php endif; endif; ?>
                
                <div class="info-box">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-1"><strong><i class="fas fa-tag me-2"></i>Kategori:</strong></p>
                            <p><?php echo $pengumuman['kategori']; ?></p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong><i class="fas fa-user me-2"></i>Penulis:</strong></p>
                            <p><?php echo htmlspecialchars($pengumuman['penulis']); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- SIDEBAR -->
        <div class="col-lg-4">
            <!-- PENGUMUMAN TERKAIT -->
            <div class="sidebar-box">
                <h5 class="fw-bold mb-3"><i class="fas fa-link me-2"></i>Pengumuman Terkait</h5>
                <?php
                $related_query = "SELECT * FROM pengumuman 
                                 WHERE kategori = '{$pengumuman['kategori']}' 
                                 AND id != '{$pengumuman['id']}' 
                                 AND status = 'published'
                                 ORDER BY date DESC 
                                 LIMIT 5";
                $related_result = mysqli_query($koneksi, $related_query);
                
                if (mysqli_num_rows($related_result) > 0) {
                    while ($related = mysqli_fetch_assoc($related_result)) {
                        $related_date = date('d/m/Y', strtotime($related['date']));
                        $related_title = htmlspecialchars($related['judul']);
                        if (strlen($related_title) > 50) {
                            $related_title = substr($related_title, 0, 50) . '...';
                        }
                ?>
                        <div class="related-article">
                            <a href="detail-pengumuman.php?id=<?php echo $related['id']; ?>" class="text-decoration-none">
                                <h6 class="fw-bold text-primary mb-1"><?php echo $related_title; ?></h6>
                                <small class="text-muted">
                                    <i class="bi bi-calendar me-1"></i><?php echo $related_date; ?>
                                </small>
                            </a>
                        </div>
                <?php
                    }
                } else {
                    echo '<p class="text-muted"><i>Belum ada pengumuman terkait.</i></p>';
                }
                ?>
            </div>
            
            <!-- KATEGORI -->
            <div class="sidebar-box">
                <h5 class="fw-bold mb-3"><i class="fas fa-tags me-2"></i>Kategori</h5>
                <?php
                $category_query = "SELECT kategori, COUNT(*) as total FROM pengumuman WHERE status = 'published' GROUP BY kategori ORDER BY kategori";
                $category_result = mysqli_query($koneksi, $category_query);
                
                if (mysqli_num_rows($category_result) > 0) {
                    while ($category = mysqli_fetch_assoc($category_result)) {
                        $active_class = ($category['kategori'] == $pengumuman['kategori']) ? 'fw-bold text-primary' : '';
                ?>
                    <a href="home-mhs.php?kategori[]=<?php echo urlencode($category['kategori']); ?>" 
                       class="d-flex justify-content-between align-items-center text-decoration-none mb-2 <?php echo $active_class; ?>">
                        <span><?php echo $category['kategori']; ?></span>
                        <span class="badge bg-secondary rounded-pill"><?php echo $category['total']; ?></span>
                    </a>
                <?php
                    }
                } else {
                    echo '<p class="text-muted"><i>Belum ada kategori.</i></p>';
                }
                ?>
            </div>
            
            <!-- INFO -->
            <div class="sidebar-box">
                <h5 class="fw-bold mb-3"><i class="fas fa-info-circle me-2"></i>Informasi</h5>
                <div class="mb-3">
                    <small class="text-muted d-block mb-1">Tanggal Publikasi:</small>
                    <strong><?php echo $tanggal_full; ?></strong>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block mb-1">Status:</small>
                    <span class="badge bg-success">Published</span>
                </div>
                <div>
                    <small class="text-muted d-block mb-1">Terakhir dilihat:</small>
                    <strong>Baru saja</strong>
                </div>
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
                <p class="small">Sistem Pengumuman Akademik Online - Solusi informasi akademik terpadu.</p>
            </div>
            <div class="col-md-4 mb-4">
                <h5 class="fw-bold mb-3">Navigasi</h5>
                <a href="home-mhs.php" class="d-block text-light mb-2">Beranda</a>
                <a href="#" class="d-block text-light mb-2">Semua Pengumuman</a>
                <a href="#" class="d-block text-light mb-2">Bantuan</a>
            </div>
            <div class="col-md-4 mb-4">
                <h5 class="fw-bold mb-3">Kontak</h5>
                <p class="small mb-1"><i class="fas fa-envelope me-2"></i>info@sapa.ac.id</p>
                <p class="small mb-1"><i class="fas fa-phone me-2"></i>+62 21 1234 5678</p>
            </div>
        </div>
        <div class="row mt-4 pt-3 border-top border-light">
            <div class="col-12 text-center">
                <p class="mb-0">
                    <i class="fas fa-bullhorn me-1"></i>
                    &copy; <?php echo date('Y'); ?> SAPA - Sistem Pengumuman Akademik
                </p>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>