<?php
session_start();

// Zaten giriş yapmışsa index'e yönlendir
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: index.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Basit doğrulama (Geliştirmeye açık, veritabanına taşınabilir)
    if ($username === 'admin' && $password === '123456') {
        $_SESSION['admin_logged_in'] = true;
        header("Location: index.php");
        exit;
    } else {
        $error = 'Kullanıcı adı veya şifre hatalı!';
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yönetim Paneli Girişi - Pienti NFC</title>
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

    <div class="login-wrapper">
        <div class="login-card-custom">
            <!-- Logo -->
            <img src="../assets/img/logo.svg" alt="Pienti Logo" class="login-logo" onerror="this.style.display='none';">
            <h3 class="login-title">NFC YÖNETİM</h3>
            <p class="login-subtitle">Pienti Group Dijital Kartvizit Yönetim Paneli</p>
            
            <?php if($error): ?>
                <div class="alert-custom mb-3"><i class="fa-solid fa-circle-exclamation me-2"></i> <?= $error ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3 text-start">
                    <label for="username"><i class="fa-solid fa-user me-1 text-gold"></i> Kullanıcı Adı</label>
                    <input type="text" id="username" name="username" class="form-control" required value="admin">
                </div>
                <div class="mb-4 text-start">
                    <label for="password"><i class="fa-solid fa-lock me-1 text-gold"></i> Şifre</label>
                    <input type="password" id="password" name="password" class="form-control" required value="123456">
                </div>
                <button type="submit" class="btn btn-gold w-100 py-3"><i class="fa-solid fa-right-to-bracket me-2"></i> Giriş Yap</button>
            </form>
        </div>
    </div>

</body>
</html>
