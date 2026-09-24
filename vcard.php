<?php
require_once 'config.php';

$slug = isset($_GET['slug']) ? htmlspecialchars(trim($_GET['slug'])) : '';

if (empty($slug)) {
    die("Geçersiz URL.");
}

$employee = getEmployeeBySlug($pdo, $slug);

if (!$employee) {
    die("Kartvizit bulunamadı.");
}

// vCard İçeriği Oluşturma
$vcard = "BEGIN:VCARD\r\n";
$vcard .= "VERSION:3.0\r\n";
$vcard .= "N:" . $employee['last_name'] . ";" . $employee['first_name'] . ";;;\r\n";
$vcard .= "FN:" . $employee['first_name'] . " " . $employee['last_name'] . "\r\n";
$vcard .= "ORG:" . $employee['hotel_name'] . "\r\n";
$vcard .= "TITLE:" . $employee['title'] . "\r\n";
$vcard .= "ROLE:" . $employee['department'] . "\r\n";
/* ==== FOTOĞRAF BLOĞU ==== */
if (!empty($employee['profile_image'])) {
// Gerçek mutlak yolu al (assets/img klasörü)
    $imgPath = realpath(__DIR__ . '/assets/img/' . $employee['profile_image']);
    if ($imgPath && file_exists($imgPath)) {
        $imgData = base64_encode(file_get_contents($imgPath));
        // 75 karakterlik parçalar, devam satırları bir boşlukla başlar (RFC 6350)
        $chunked = chunk_split($imgData, 75, "\r\n ");
        $chunked = rtrim($chunked, "\r\n ");
        // MIME type based on file extension
        $ext = strtolower(pathinfo($employee['profile_image'], PATHINFO_EXTENSION));
        $mimeType = $ext === 'png' ? 'PNG' : 'JPEG';
        // Standard PHOTO field with ENCODING=b (iOS compatibility)
        $vcard .= "PHOTO;ENCODING=b;TYPE={$mimeType}:" . $chunked . "\r\n";
        // Additional PHOTO field with ENCODING=BASE64 (some clients)
        $vcard .= "PHOTO;ENCODING=BASE64;TYPE={$mimeType}:" . $chunked . "\r\n";
        // Apple‑specific X‑ABPHOTO field (kept for iOS)
        $vcard .= "X-ABPHOTO;ENCODING=b;TYPE={$mimeType}:" . $chunked . "\r\n";
    } else {
        // Fallback to URL if file not found or unreadable
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        $photoUrl = $protocol . '://' . $host . '/assets/img/' . $employee['profile_image'];
        $vcard .= "PHOTO;VALUE=URI;TYPE=JPEG:" . $photoUrl . "\r\n";
    }
}
/* ==== FOTOĞRAF BLOĞU SONU ==== */
if (!empty($employee['phone'])) {
    $vcard .= "TEL;TYPE=WORK,VOICE:" . $employee['phone'] . "\r\n";
}
if (!empty($employee['mobile'])) {
    $vcard .= "TEL;TYPE=CELL,VOICE:" . $employee['mobile'] . "\r\n";
}
if (!empty($employee['email'])) {
    $vcard .= "EMAIL;TYPE=PREF,INTERNET:" . $employee['email'] . "\r\n";
}
if (!empty($employee['website'])) {
    $vcard .= "URL:" . $employee['website'] . "\r\n";
}
if (!empty($employee['hotel_address'])) {
    // Adres formatı: Postane kutusu; Genişletilmiş adres; Sokak; Şehir; Bölge; Posta kodu; Ülke
    // Basit bir yaklaşımla doğrudan adres alanına yazıyoruz.
    $vcard .= "ADR;TYPE=WORK:;;".str_replace("\r\n", " ", $employee['hotel_address']).";;;;\r\n";
}

$vcard .= "END:VCARD\r\n";

header('Content-Type: text/vcard; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $employee['first_name'] . '_' . $employee['last_name'] . '.vcf"');

echo $vcard;
