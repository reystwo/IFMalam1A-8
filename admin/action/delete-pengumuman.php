<?php
session_start();
require_once '../../config/koneksi.php';

// Cek session admin
if (!isset($_SESSION['username'])) {
    header("Location: ../../web/auth/login.php");
    exit();
}

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);
    
    // Ambil data untuk hapus file
    $query = "SELECT gambar, pdf FROM pengumuman WHERE id = '$id'";
    $result = mysqli_query($koneksi, $query);
    
    if (mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);
        
        // Hapus gambar jika ada
        if (!empty($data['gambar']) && file_exists('../../uploads/images/' . $data['gambar'])) {
            unlink('../../uploads/images/' . $data['gambar']);
        }
        
        // Hapus PDF jika ada
        if (!empty($data['pdf']) && file_exists('../../uploads/pdf/' . $data['pdf'])) {
            unlink('../../uploads/pdf/' . $data['pdf']);
        }
        
        // Hapus dari database
        $delete_query = "DELETE FROM pengumuman WHERE id = '$id'";
        
        if (mysqli_query($koneksi, $delete_query)) {
            header("Location: ../../web/dosen/pengumuman.php?notif=success&msg=Pengumuman berhasil dihapus");
        } else {
            header("Location: ../../web/dosen/pengumuman.php?notif=error&msg=Gagal menghapus pengumuman: " . mysqli_error($koneksi));
        }
    } else {
        header("Location: ../../web/dosen/pengumuman.php?notif=error&msg=Data tidak ditemukan");
    }
} else {
    header("Location: ../../web/dosen/pengumuman.php?notif=error&msg=ID tidak valid");
}
exit();
?>