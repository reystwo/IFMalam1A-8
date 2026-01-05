<?php
session_start();
require_once '../../config/koneksi.php';

// Cek session admin
if (!isset($_SESSION['username'])) {
    header("Location: ../../web/auth/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize input
    $judul = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $isi = mysqli_real_escape_string($koneksi, $_POST['isi']);
    $kategori = mysqli_real_escape_string($koneksi, $_POST['kategori']);
    $status = mysqli_real_escape_string($koneksi, $_POST['status']);
    $penulis = $_SESSION['username']; // Ambil dari session admin
    
    // Handle gambar upload
    $gambar = '';
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $gambar_name = basename($_FILES['gambar']['name']);
        $gambar_ext = strtolower(pathinfo($gambar_name, PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (in_array($gambar_ext, $allowed_ext)) {
            // Generate unique filename
            $new_gambar_name = 'img_' . time() . '_' . uniqid() . '.' . $gambar_ext;
            $gambar_tmp = $_FILES['gambar']['tmp_name'];
            $gambar_path = '../../uploads/images/' . $new_gambar_name;
            
            // Pindahkan file
            if (move_uploaded_file($gambar_tmp, $gambar_path)) {
                $gambar = $new_gambar_name;
            }
        }
    }
    
    // Handle PDF upload
    $pdf = '';
    if (isset($_FILES['pdf']) && $_FILES['pdf']['error'] == 0) {
        $pdf_name = basename($_FILES['pdf']['name']);
        $pdf_ext = strtolower(pathinfo($pdf_name, PATHINFO_EXTENSION));
        
        if ($pdf_ext == 'pdf') {
            // Generate unique filename
            $new_pdf_name = 'pdf_' . time() . '_' . uniqid() . '.pdf';
            $pdf_tmp = $_FILES['pdf']['tmp_name'];
            $pdf_path = '../../uploads/pdf/' . $new_pdf_name;
            
            // Pindahkan file
            if (move_uploaded_file($pdf_tmp, $pdf_path)) {
                $pdf = $new_pdf_name;
            }
        }
    }
    
    // Insert ke database
    $query = "INSERT INTO pengumuman (judul, isi, kategori, status, penulis, gambar, pdf, date) 
              VALUES ('$judul', '$isi', '$kategori', '$status', '$penulis', '$gambar', '$pdf', NOW())";
    
    if (mysqli_query($koneksi, $query)) {
        header("Location: ../../web/dosen/pengumuman.php?notif=success&msg=Pengumuman berhasil ditambahkan");
    } else {
        header("Location: ../../web/dosen/pengumuman.php?notif=error&msg=Gagal menambahkan pengumuman: " . mysqli_error($koneksi));
    }
} else {
    header("Location: ../../web/dosen/pengumuman.php");
}
exit();
?>