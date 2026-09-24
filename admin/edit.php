<?php
require_once 'auth.php';
require_once '../config.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: index.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM employees WHERE id = ?");
$stmt->execute([$id]);
$emp = $stmt->fetch();

if (!$emp) {
    die("Personel bulunamadı.");
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $slug = trim($_POST['slug']);
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $title = trim($_POST['title']);
    $department = trim($_POST['department']);
    $phone = trim($_POST['phone']);
    $mobile = trim($_POST['mobile']);
    $email = trim($_POST['email']);
    $website = trim($_POST['website']);
    $whatsapp = trim($_POST['whatsapp']);
    $hotel_address = trim($_POST['hotel_address'] ?? '');
    $social_linkedin = trim($_POST['social_linkedin'] ?? '');
    $social_instagram = trim($_POST['social_instagram'] ?? '');
    $status = $_POST['status'] ?? 1;
    $show_balloons = isset($_POST['show_balloons']) ? 1 : 0;

    $profile_image = $emp['profile_image']; // Varsayılan mevcut resim

    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../assets/img/';
        $tmp_name = $_FILES['profile_image']['tmp_name'];
        $name = basename($_FILES['profile_image']['name']);
        
        $new_name = time() . '_' . preg_replace("/[^a-zA-Z0-9.]/", "", $name);
        
        if (move_uploaded_file($tmp_name, $upload_dir . $new_name)) {
            // Eski resmi silmeyi deneyebiliriz (isteğe bağlı)
            if ($profile_image != 'default_profile.jpg' && file_exists($upload_dir . $profile_image)) {
                @unlink($upload_dir . $profile_image);
            }
            $profile_image = $new_name;
        }
    }

    try {
        $update_stmt = $pdo->prepare("UPDATE employees SET slug=?, first_name=?, last_name=?, title=?, department=?, phone=?, mobile=?, email=?, website=?, whatsapp=?, hotel_address=?, social_linkedin=?, social_instagram=?, profile_image=?, status=?, show_balloons=? WHERE id=?");
        $update_stmt->execute([$slug, $first_name, $last_name, $title, $department, $phone, $mobile, $email, $website, $whatsapp, $hotel_address, $social_linkedin, $social_instagram, $profile_image, $status, $show_balloons, $id]);
        
        header("Location: index.php");
        exit;
    } catch (PDOException $e) {
        $message = "Hata: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personel Düzenle - Pienti NFC</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Custom Admin CSS -->
    <link rel="stylesheet" href="../assets/css/admin.css?v=2">
</head>
<body>

    <!-- Background glowing light orbs -->
    <div class="admin-bg-shape admin-shape-1"></div>
    <div class="admin-bg-shape admin-shape-2"></div>

    <!-- Redesigned Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom mb-5">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fa-solid fa-hotel text-gold me-2"></i> NFC ADMIN
            </a>
            <div class="ms-auto">
                <a href="logout.php" class="btn btn-outline-gold btn-sm">
                    <i class="fa-solid fa-right-from-bracket me-1"></i> Çıkış Yap
                </a>
            </div>
        </div>
    </nav>

    <div class="container pb-5">
        <div class="glass-admin-card mx-auto" style="max-width: 800px;">
            <div class="glass-admin-card-header">
                <h5 class="mb-0 fw-bold"><i class="fa-solid fa-user-pen text-gold me-2"></i> Personel Düzenle</h5>
                <a href="index.php" class="btn btn-glass btn-sm"><i class="fa-solid fa-arrow-left me-1"></i> Listeye Dön</a>
            </div>
            
            <div class="glass-admin-card-body">
                <?php if($message): ?>
                    <div class="alert-custom mb-4"><i class="fa-solid fa-circle-exclamation me-2"></i> <?= $message ?></div>
                <?php endif; ?>

                <form method="POST" enctype="multipart/form-data">
                    
                    <div class="row mb-3">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label for="slug">Özel URL (Slug) *</label>
                            <input type="text" id="slug" name="slug" class="form-control" required value="<?= htmlspecialchars($emp['slug']) ?>">
                            <small class="text-muted-custom">Sadece küçük harfler, sayılar ve tire (-) kullanılabilir.</small>
                        </div>
                        <div class="col-md-6">
                            <label for="profile_image">Profil Fotoğrafı (Değiştirmek istemiyorsanız boş bırakın)</label>
                            <input type="file" id="profile_image" name="profile_image" class="form-control" accept="image/*">
                            <div class="mt-3 d-flex align-items-center gap-2">
                                <span class="text-secondary small">Mevcut Görsel:</span>
                                <img src="../assets/img/<?= htmlspecialchars($emp['profile_image']) ?>" height="55" width="55" style="object-fit: cover;" class="rounded-circle admin-thumb">
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label for="first_name">Ad *</label>
                            <input type="text" id="first_name" name="first_name" class="form-control" required value="<?= htmlspecialchars($emp['first_name']) ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="last_name">Soyad *</label>
                            <input type="text" id="last_name" name="last_name" class="form-control" required value="<?= htmlspecialchars($emp['last_name']) ?>">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label for="title">Ünvan *</label>
                            <input type="text" id="title" name="title" class="form-control" required value="<?= htmlspecialchars($emp['title']) ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="department">Departman</label>
                            <input type="text" id="department" name="department" class="form-control" value="<?= htmlspecialchars($emp['department']) ?>">
                        </div>
                    </div>

                    <hr>
                    
                    <div class="row mb-3">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label for="mobile">Cep Telefonu</label>
                            <input type="text" id="mobile" name="mobile" class="form-control" value="<?= htmlspecialchars($emp['mobile']) ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="whatsapp">WhatsApp Numarası</label>
                            <input type="text" id="whatsapp" name="whatsapp" class="form-control" value="<?= htmlspecialchars($emp['whatsapp']) ?>">
                            <small class="text-muted-custom">Ülke kodu ile başlayıp boşluksuz yazın.</small>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label for="phone">Sabit Hat (İş)</label>
                            <input type="text" id="phone" name="phone" class="form-control" value="<?= htmlspecialchars($emp['phone']) ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="email">E-posta</label>
                            <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($emp['email']) ?>">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="hotel_address">Adres</label>
                            <textarea id="hotel_address" name="hotel_address" class="form-control" rows="2"><?= htmlspecialchars($emp['hotel_address'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label for="social_linkedin">LinkedIn URL</label>
                            <input type="url" id="social_linkedin" name="social_linkedin" class="form-control" value="<?= htmlspecialchars($emp['social_linkedin'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="social_instagram">Instagram URL</label>
                            <input type="url" id="social_instagram" name="social_instagram" class="form-control" value="<?= htmlspecialchars($emp['social_instagram'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="row mb-4 align-items-center">
                        <div class="col-md-4 mb-3 mb-md-0">
                            <label for="website">Web Sitesi</label>
                            <input type="text" id="website" name="website" class="form-control" value="<?= htmlspecialchars($emp['website']) ?>">
                        </div>
                        <div class="col-md-4 mb-3 mb-md-0">
                            <label for="status">Durum</label>
                            <select id="status" name="status" class="form-select">
                                <option value="1" <?= $emp['status'] == 1 ? 'selected' : '' ?>>Aktif</option>
                                <option value="0" <?= $emp['status'] == 0 ? 'selected' : '' ?>>Pasif</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="d-block mb-2">Grup Balonlar Bölümü</label>
                            <div class="form-check form-switch pt-1">
                                <input class="form-check-input" type="checkbox" id="show_balloons" name="show_balloons" value="1" <?= $emp['show_balloons'] == 1 ? 'checked' : '' ?>>
                                <label class="form-check-label text-white-50" for="show_balloons">Profilde Göster</label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="index.php" class="btn btn-glass">İptal</a>
                        <button type="submit" class="btn btn-gold"><i class="fa-solid fa-floppy-disk me-1"></i> Değişiklikleri Kaydet</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // URL Slug alanını otomatik düzelt (boşlukları tire yap, küçük harfe çevir)
        const slugInput = document.getElementById('slug');
        slugInput.addEventListener('input', function() {
            let val = this.value;
            val = val.toLowerCase();
            // Türkçe karakterleri çevir
            const trMap = {'ç':'c','ğ':'g','ş':'s','ü':'u','ı':'i','ö':'o'};
            for(let key in trMap) {
                val = val.replace(new RegExp(key, 'g'), trMap[key]);
            }
            // Boşlukları ve geçersiz karakterleri tire yap
            val = val.replace(/[^a-z0-9]/g, '-').replace(/-+/g, '-');
            this.value = val;
        });
    </script>
</body>
</html>
