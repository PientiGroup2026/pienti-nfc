<?php
session_start();

// Kullanıcı giriş yapmamışsa login.php'ye yönlendir.
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}
?>
