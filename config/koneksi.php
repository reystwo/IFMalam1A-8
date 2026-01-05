<?php
// File: config/koneksi.php
$host = "localhost";
$username = "root"; // Sesuaikan dengan username MySQL Anda
$password = ""; // Sesuaikan dengan password MySQL Anda
$database = "sapadb"; // Sesuaikan dengan nama database

// Buat koneksi
$koneksi = mysqli_connect($host, $username, $password, $database);

// Cek koneksi
if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Set charset
mysqli_set_charset($koneksi, "utf8");
?>