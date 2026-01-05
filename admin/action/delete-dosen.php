<?php
require_once '../../config/koneksi.php';

$nik = $_GET['nik'];

if (empty($nik)) {
    header("Location: ../../web/dosen/dosen.php?notif=error&msg=NIK tidak valid!");
    exit;
}

$check = mysqli_query($koneksi, "SELECT * FROM dosen WHERE nik = '$nik'");
if (mysqli_num_rows($check) == 0) {
    header("Location: ../../web/dosen/dosen.php?notif=error&msg=Data dosen tidak ditemukan!");
    exit;
}

$stmt = mysqli_prepare($koneksi, "DELETE FROM dosen WHERE nik = ?");
mysqli_stmt_bind_param($stmt, "s", $nik);

if (mysqli_stmt_execute($stmt)) {
    header("Location: ../../web/dosen/dosen.php?notif=success&msg=Data Berhasil Dihapus");
} else {
    header("Location: ../../web/dosen/dosen.php?notif=error&msg=Gagal Menghapus Data");
}

mysqli_stmt_close($stmt);
mysqli_close($koneksi);
?>