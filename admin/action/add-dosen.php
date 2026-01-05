<?php
require_once '../../config/koneksi.php';

$nik = $_POST['nik'];
$nama = $_POST['nama'];
$prodi = $_POST['prodi'];
$jabatan = $_POST['jabatan'];

if (empty($nik) || empty($nama) || empty($prodi) || empty($jabatan)) {
    header("Location: ../../web/dosen/dosen.php?notif=error&msg=Semua field harus diisi!");
    exit;
}

$stmt = mysqli_prepare($koneksi, "INSERT INTO dosen (nik, nama, prodi, jabatan) VALUES (?, ?, ?, ?)");
mysqli_stmt_bind_param($stmt, "ssss", $nik, $nama, $prodi, $jabatan);

if (mysqli_stmt_execute($stmt)) {
    header("Location: ../../web/dosen/dosen.php?notif=success&msg=Data Berhasil Disimpan");
} else {
    header("Location: ../../web/dosen/dosen.php?notif=error&msg=Gagal Menyimpan Data");
}

mysqli_stmt_close($stmt);
mysqli_close($koneksi);
?>