<?php
require_once 'auth.php';
require_once '../config.php';

$id = $_GET['id'] ?? null;
if ($id) {
    // Tamamen silmek yerine pasife alma (status = 0) mantığı da uygulanabilir.
    // Burada doğrudan SİLME işlemi uyguluyoruz.
    try {
        // İsterseniz önce resmi bulup klasörden silebilirsiniz:
        $stmt = $pdo->prepare("SELECT profile_image FROM employees WHERE id = ?");
        $stmt->execute([$id]);
        $emp = $stmt->fetch();
        
        if ($emp && $emp['profile_image'] != 'default_profile.jpg') {
            $imgPath = '../assets/img/' . $emp['profile_image'];
            if (file_exists($imgPath)) {
                @unlink($imgPath);
            }
        }

        $del_stmt = $pdo->prepare("DELETE FROM employees WHERE id = ?");
        $del_stmt->execute([$id]);
    } catch (PDOException $e) {
        // Hata
    }
}

header("Location: index.php");
exit;
?>
