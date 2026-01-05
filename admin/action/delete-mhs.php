<?php
require_once '../../config/koneksi.php';

$nim = $_GET['nim'];

if (empty($nim)) {
    header("Location: ../../web/dosen/mahasiswa.php?notif=error&msg=NIM tidak valid!");
    exit;
}

$check = mysqli_query($koneksi, "SELECT * FROM mahasiswa WHERE nim = '$nim'");
if (mysqli_num_rows($check) == 0) {
    header("Location: ../../web/dosen/mahasiswa.php?notif=error&msg=Data mahasiswa tidak ditemukan!");
    exit;
}

$stmt = mysqli_prepare($koneksi, "DELETE FROM mahasiswa WHERE nim = ?");
mysqli_stmt_bind_param($stmt, "s", $nim);

if (mysqli_stmt_execute($stmt)) {
    header("Location: ../../web/dosen/mahasiswa.php?notif=success&msg=Data Berhasil Dihapus");
} else {
    header("Location: ../../web/dosen/mahasiswa.php?notif=error&msg=Gagal Menghapus Data");
}

mysqli_stmt_close($stmt);
mysqli_close($koneksi);
?>