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

$stmt = mysqli_prepare($koneksi, "UPDATE mahasiswa SET nama=?, jurusan=?, angkatan=? WHERE nim=?");
mysqli_stmt_bind_param($stmt, "ssss", $nama, $jurusan, $angkatan, $nim);

if (mysqli_stmt_execute($stmt)) {
    header("Location: ../../web/dosen/mahasiswa.php?notif=success&msg=Data Berhasil Diupdate");
} else {
    header("Location: ../../web/dosen/mahasiswa.php?notif=error&msg=Gagal Mengupdate Data");
}

mysqli_stmt_close($stmt);
mysqli_close($koneksi);
?>