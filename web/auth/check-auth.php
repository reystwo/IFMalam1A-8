<?php
session_start();

if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_username'])) {
    header("Location: ../../web/login.php");
    exit();
}

$current_user = $_SESSION['admin_username'];
?>