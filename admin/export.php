<?php
require_once 'auth.php';
require_once '../config.php';

// Temel dizinler
$base_dir = dirname(__DIR__);
$export_dir = $base_dir . '/export_tmp';
$zip_file = $base_dir . '/pienti-nfc-netlify.zip';

// Yardımcı Fonksiyon: Klasör silme
function deleteDir($dirPath) {
    if (!is_dir($dirPath)) return;
    $files = scandir($dirPath);
    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..') {
            $path = $dirPath . '/' . $file;
            is_dir($path) ? deleteDir($path) : unlink($path);
        }
    }
    rmdir($dirPath);
}

// Yardımcı Fonksiyon: Klasör kopyalama
function copyDir($src, $dst) {
    if (!is_dir($dst)) mkdir($dst, 0777, true);
    $files = scandir($src);
    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..') {
            $srcPath = $src . '/' . $file;
            $dstPath = $dst . '/' . $file;
            if (is_dir($srcPath)) {
                copyDir($srcPath, $dstPath);
            } else {
                copy($srcPath, $dstPath);
            }
        }
    }
}

// 1. Temizlik ve Hazırlık
if (file_exists($zip_file)) unlink($zip_file);
deleteDir($export_dir);
mkdir($export_dir, 0777, true);
mkdir($export_dir . '/card', 0777, true);
mkdir($export_dir . '/vcf', 0777, true);

// 2. Assets (CSS/İmajlar) kopyalama
copyDir($base_dir . '/assets', $export_dir . '/assets');

// Ana dizine boş bir index.html koyalım (Directory Listing engellemek için)
file_put_contents($export_dir . '/index.html', '<!DOCTYPE html><html><body>Pienti Group NFC System</body></html>');

// Manifest gibi kök dizin dosyalarını da kopyalayalım
if (file_exists($base_dir . '/manifest.json')) {
    copy($base_dir . '/manifest.json', $export_dir . '/manifest.json');
}

// 3. Veritabanındaki tüm aktif personelleri al
$stmt = $pdo->query("SELECT * FROM employees WHERE status = 1");
$employees = $stmt->fetchAll();

$IS_STATIC_EXPORT = true;

foreach ($employees as $emp) {
    $slug = $emp['slug'];
    
    // -- A) VCF Dosyasını Üret --
    // VCF kodunu vcard.php içinden değil, doğrudan burada üretiyoruz
    $vcard = "BEGIN:VCARD\r\n";
    $vcard .= "VERSION:3.0\r\n";
    $vcard .= "N:" . $emp['last_name'] . ";" . $emp['first_name'] . ";;;\r\n";
    $vcard .= "FN:" . $emp['first_name'] . " " . $emp['last_name'] . "\r\n";
    $vcard .= "ORG:" . $emp['hotel_name'] . "\r\n";
    $vcard .= "TITLE:" . $emp['title'] . "\r\n";
    if(!empty($emp['phone'])) $vcard .= "TEL;TYPE=WORK,VOICE:" . $emp['phone'] . "\r\n";
    if(!empty($emp['mobile'])) $vcard .= "TEL;TYPE=CELL,VOICE:" . $emp['mobile'] . "\r\n";
    if(!empty($emp['email'])) $vcard .= "EMAIL;TYPE=PREF,INTERNET:" . $emp['email'] . "\r\n";
    if(!empty($emp['website'])) $vcard .= "URL:" . $emp['website'] . "\r\n";
    if(!empty($emp['hotel_address'])) $vcard .= "ADR;TYPE=WORK:;;".str_replace("\r\n", " ", $emp['hotel_address']).";;;;\r\n";
    $vcard .= "END:VCARD\r\n";
    
    file_put_contents($export_dir . '/vcf/' . $slug . '.vcf', $vcard);
    
    // -- B) HTML Dosyasını Üret --
    ob_start();
    $employee = $emp; // card.php $employee ve $slug değişkenlerini kullanıyor
    include $base_dir . '/card.php';
    $htmlContent = ob_get_clean();
    
    // Klasör oluştur ve index.html içine kaydet (Örn: export_tmp/card/murat-kodan/index.html)
    $emp_dir = $export_dir . '/card/' . $slug;
    if (!is_dir($emp_dir)) mkdir($emp_dir, 0777, true);
    file_put_contents($emp_dir . '/index.html', $htmlContent);
}

// 4. ZİP Oluşturma
$zip = new ZipArchive();
if ($zip->open($zip_file, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
    // Klasörü gezip zip'e ekle
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($export_dir),
        RecursiveIteratorIterator::LEAVES_ONLY
    );

    foreach ($files as $name => $file) {
        if (!$file->isDir()) {
            $filePath = $file->getRealPath();
            $relativePath = substr($filePath, strlen($export_dir) + 1);
            $zip->addFile($filePath, $relativePath);
        }
    }
    $zip->close();
}

// 5. Temizlik (Geçici klasörü sil)
deleteDir($export_dir);

// 6. Dosyayı indir
if (file_exists($zip_file)) {
    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="pienti-nfc-netlify.zip"');
    header('Content-Length: ' . filesize($zip_file));
    readfile($zip_file);
    exit;
} else {
    echo "ZİP dosyası oluşturulamadı.";
}
?>
