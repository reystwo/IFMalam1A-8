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
    $id = mysqli_real_escape_string($koneksi, $_POST['id']);
    $judul = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $isi = mysqli_real_escape_string($koneksi, $_POST['isi']);
    $kategori = mysqli_real_escape_string($koneksi, $_POST['kategori']);
    $status = mysqli_real_escape_string($koneksi, $_POST['status']);
    
    // Ambil data lama untuk gambar dan pdf
    $query_old = "SELECT gambar, pdf FROM pengumuman WHERE id = '$id'";
    $result_old = mysqli_query($koneksi, $query_old);
    $old_data = mysqli_fetch_assoc($result_old);
    
    $gambar = $old_data['gambar'];
    $pdf = $old_data['pdf'];
    
    // Handle gambar upload baru
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0 && !empty($_FILES['gambar']['name'])) {
        $gambar_name = basename($_FILES['gambar']['name']);
        $gambar_ext = strtolower(pathinfo($gambar_name, PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (in_array($gambar_ext, $allowed_ext)) {
            // Hapus gambar lama jika ada
            if (!empty($gambar) && file_exists('../../uploads/images/' . $gambar)) {
                unlink('../../uploads/images/' . $gambar);
            }
            
            // Generate unique filename baru
            $new_gambar_name = 'img_' . time() . '_' . uniqid() . '.' . $gambar_ext;
            $gambar_tmp = $_FILES['gambar']['tmp_name'];
            $gambar_path = '../../uploads/images/' . $new_gambar_name;
            
            // Pindahkan file baru
            if (move_uploaded_file($gambar_tmp, $gambar_path)) {
                $gambar = $new_gambar_name;
            }
        }
    }
    
    // Handle PDF upload baru
    if (isset($_FILES['pdf']) && $_FILES['pdf']['error'] == 0 && !empty($_FILES['pdf']['name'])) {
        $pdf_name = basename($_FILES['pdf']['name']);
        $pdf_ext = strtolower(pathinfo($pdf_name, PATHINFO_EXTENSION));
        
        if ($pdf_ext == 'pdf') {
            // Hapus PDF lama jika ada
            if (!empty($pdf) && file_exists('../uploads/pdf/' . $pdf)) {
                unlink('../../uploads/pdf/' . $pdf);
            }
            
            // Generate unique filename baru
            $new_pdf_name = 'pdf_' . time() . '_' . uniqid() . '.pdf';
            $pdf_tmp = $_FILES['pdf']['tmp_name'];
            $pdf_path = '../../uploads/pdf/' . $new_pdf_name;
            
            // Pindahkan file baru
            if (move_uploaded_file($pdf_tmp, $pdf_path)) {
                $pdf = $new_pdf_name;
            }
        }
    }
    
    // Jika ingin hapus gambar
    if (isset($_POST['hapus_gambar']) && $_POST['hapus_gambar'] == '1') {
        if (!empty($gambar) && file_exists('../../uploads/images/' . $gambar)) {
            unlink('../../uploads/images/' . $gambar);
        }
        $gambar = '';
    }
    
    // Jika ingin hapus PDF
    if (isset($_POST['hapus_pdf']) && $_POST['hapus_pdf'] == '1') {
        if (!empty($pdf) && file_exists('../../uploads/pdf/' . $pdf)) {
            unlink('../../uploads/pdf/' . $pdf);
        }
        $pdf = '';
    }
    
    // Update database
    $query = "UPDATE pengumuman 
              SET judul = '$judul', 
                  isi = '$isi', 
                  kategori = '$kategori', 
                  status = '$status', 
                  gambar = '$gambar', 
                  pdf = '$pdf'
              WHERE id = '$id'";
    
    if (mysqli_query($koneksi, $query)) {
        header("Location: ../../web/dosen/pengumuman.php?notif=success&msg=Pengumuman berhasil diupdate");
    } else {
        header("Location: ../../web/dosen/pengumuman.php?notif=error&msg=Gagal mengupdate pengumuman: " . mysqli_error($koneksi));
    }
} else {
    header("Location: ../../web/dosen/pengumuman.php");
}
exit();
?>