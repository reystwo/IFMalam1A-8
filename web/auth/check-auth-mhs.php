<?php
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'mahasiswa') {
    header("Location: ../login.php");
    exit();
}

$current_user = $_SESSION['nama'];
?>