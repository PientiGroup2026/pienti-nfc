<?php
// Hata ayıklama modunu açmak için (sadece development'ta)
// ini_set('display_errors', 1);
// error_reporting(E_ALL);

require_once 'config.php';

// Slug parametresini al
$slug = isset($_GET['slug']) ? htmlspecialchars(trim($_GET['slug'])) : '';

if (empty($slug)) {
    // Slug yoksa ana sayfaya veya 404 sayfasına yönlendir
    die("Geçersiz URL. Personel bulunamadı.");
}

// Personeli veritabanından bul
$employee = getEmployeeBySlug($pdo, $slug);

if (!$employee) {
    // Personel bulunamadı
    die("Kartvizit bulunamadı veya pasif durumda.");
}

// Personel verilerini card.php'ye aktararak tasarımı yükle
require_once 'card.php';
?>
