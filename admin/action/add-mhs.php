<?php
require_once '../../config/koneksi.php';

$nim = $_POST['nim'];
$nama = $_POST['nama'];
$jurusan = $_POST['jurusan'];
$angkatan = $_POST['angkatan'];

if (empty($nim) || empty($nama) || empty($jurusan) || empty($angkatan)) {
    header("Location: ../../web/dosen/mahasiswa.php?notif=error&msg=Semua field harus diisi!");
    exit;
}

$stmt = mysqli_prepare($koneksi, "INSERT INTO mahasiswa (nim, nama, jurusan, angkatan) VALUES (?, ?, ?, ?)");
mysqli_stmt_bind_param($stmt, "ssss", $nim, $nama, $jurusan, $angkatan);

if (mysqli_stmt_execute($stmt)) {
    header("Location: ../../web/dosen/mahasiswa.php?notif=success&msg=Data Berhasil Disimpan");
} else {
    header("Location: ../../web/dosen/mahasiswa.php?notif=error&msg=Gagal Menyimpan Data");
}

mysqli_stmt_close($stmt);
mysqli_close($koneksi);
?>