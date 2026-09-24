<?php
require_once 'auth.php';
require_once '../config.php';

// Personelleri getir
$stmt = $pdo->query("SELECT * FROM employees ORDER BY id DESC");
$employees = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yönetim Paneli - Pienti NFC</title>
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

    <div class="container">
        <!-- Header Section -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
            <div>
                <h2 class="mb-1 fw-bold" style="letter-spacing: 0.5px;">Kayıtlı Personeller</h2>
                <p class="text-secondary mb-0">Toplam <?= count($employees) ?> aktif/pasif dijital kartvizit kaydı listeleniyor.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="export.php" class="btn btn-glass"><i class="fa-solid fa-cloud-arrow-down me-2"></i> Netlify (ZIP) İndir</a>
                <a href="add.php" class="btn btn-gold"><i class="fa-solid fa-plus me-2"></i> Yeni Ekle</a>
            </div>
        </div>

        <!-- Employees List Table Card -->
        <div class="glass-admin-card">
            <div class="table-responsive">
                <table class="table-custom mb-0 align-middle">
                    <thead>
                        <tr>
                            <th style="width: 5%">#</th>
                            <th style="width: 10%">Fotoğraf</th>
                            <th style="width: 25%">Ad Soyad</th>
                            <th style="width: 25%">Ünvan / Departman</th>
                            <th style="width: 20%">Bağlantı</th>
                            <th style="width: 10%">Durum</th>
                            <th style="width: 5%" class="text-end">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($employees as $emp): ?>
                        <tr>
                            <td><span class="text-secondary">#<?= $emp['id'] ?></span></td>
                            <td>
                                <img src="../assets/img/<?= htmlspecialchars($emp['profile_image']) ?>" class="rounded-circle admin-thumb" width="45" height="45" style="object-fit: cover;" onerror="this.src='../assets/img/default_profile.jpg'">
                            </td>
                            <td>
                                <span class="fw-bold"><?= htmlspecialchars($emp['first_name'] . ' ' . $emp['last_name']) ?></span>
                            </td>
                            <td>
                                <div class="fw-semibold text-gold"><?= htmlspecialchars($emp['title']) ?></div>
                                <small class="text-secondary"><?= htmlspecialchars($emp['department']) ?></small>
                            </td>
                            <td>
                                <a href="/card/<?= htmlspecialchars($emp['slug']) ?>" target="_blank" class="text-gold text-decoration-none fw-semibold">
                                    /card/<?= htmlspecialchars($emp['slug']) ?> <i class="fa-solid fa-arrow-up-right-from-square ms-1" style="font-size: 0.8rem;"></i>
                                </a>
                            </td>
                            <td>
                                <?php if($emp['status'] == 1): ?>
                                    <span class="badge-custom badge-active">Aktif</span>
                                <?php else: ?>
                                    <span class="badge-custom badge-passive">Pasif</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <div class="action-btn-group d-flex justify-content-end">
                                    <a href="edit.php?id=<?= $emp['id'] ?>" class="btn btn-edit-custom" title="Düzenle">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <a href="delete.php?id=<?= $emp['id'] ?>" class="btn btn-delete-custom" title="Sil" onclick="return confirm('Bu personeli silmek/pasife almak istediğinize emin misiniz?');">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        
                        <?php if(empty($employees)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-secondary">
                                <i class="fa-solid fa-users-slash fa-2x mb-3 text-muted" style="display: block;"></i>
                                Henüz kayıtlı personel bulunmuyor. Yeni bir kayıt ekleyerek başlayın.
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
