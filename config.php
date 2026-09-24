<?php
// Veritabanı yapılandırması
$host = 'db'; // Docker veritabanı servis adı
$dbname = 'pienti_nfc';
$username = 'root'; 
$password = 'root'; // Docker compose'daki şifre

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Üretim ortamında hatayı gizle ve logla, geliştirme ortamında göster
    die("Veritabanı bağlantı hatası. Lütfen sistem yöneticisi ile iletişime geçin.");
    // die("Veritabanı bağlantı hatası: " . $e->getMessage()); 
}

/**
 * Slug'a göre personel bilgilerini getirir.
 */
function getEmployeeBySlug($pdo, $slug) {
    $stmt = $pdo->prepare("SELECT * FROM employees WHERE slug = ? AND status = 1");
    $stmt->execute([$slug]);
    return $stmt->fetch();
}
?>
