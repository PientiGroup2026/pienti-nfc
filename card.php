<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($employee['first_name'] . ' ' . $employee['last_name']) ?> - <?= htmlspecialchars($employee['title']) ?></title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="Digital business card of <?= htmlspecialchars($employee['first_name'] . ' ' . $employee['last_name']) ?>, <?= htmlspecialchars($employee['title']) ?> at <?= htmlspecialchars($employee['hotel_name']) ?>.">
    <meta name="author" content="<?= htmlspecialchars($employee['hotel_name']) ?>">
    <meta name="theme-color" content="#0a0a0a">
    
    <!-- PWA Manifest -->
    <link rel="manifest" href="/manifest.json">
    <link rel="apple-touch-icon" href="/assets/img/icon-192x192.png">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <!-- Google Fonts: Outfit for modern look -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/assets/css/style.css?v=6">
</head>
<body class="dark-theme">

    <!-- Arkaplan animasyonu için -->
    <div class="bg-shape shape-1"></div>
    <div class="bg-shape shape-2"></div>

    <div class="container mobile-container">
        <!-- Logo Alanı -->
        <div class="text-center mt-4 mb-3 fade-in">
            <img src="/assets/img/logo.svg" alt="<?= htmlspecialchars($employee['hotel_name']) ?> Logo" class="hotel-logo-img" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
            <h2 class="hotel-logo-text" style="display: none;"><i class="fa-solid fa-hotel text-gold"></i> <?= htmlspecialchars($employee['hotel_name']) ?></h2>
        </div>

        <!-- Profil Kartı (Glassmorphism) -->
        <div class="glass-card profile-card text-center mb-4 slide-up">
            <div class="profile-img-wrapper">
                <img src="/assets/img/<?= htmlspecialchars($employee['profile_image']) ?>" alt="Profile" class="profile-img" onerror="this.src='/assets/img/default_profile.jpg'">
            </div>
            <h1 class="name-title mt-3"><?= htmlspecialchars($employee['first_name'] . ' ' . $employee['last_name']) ?></h1>
            <p class="job-title text-gold mb-1"><?= htmlspecialchars($employee['title']) ?></p>
            <p class="department-text mb-0"><?= htmlspecialchars($employee['department']) ?></p>
            
            <div class="action-buttons mt-4">
                <?php 
                $vcf_link = (isset($IS_STATIC_EXPORT) && $IS_STATIC_EXPORT)
                            ? "/vcf/" . urlencode($slug) . ".vcf"
                            : "/vcard.php?slug=" . urlencode($slug); 
                ?>
                <a href="<?= $vcf_link ?>" class="btn btn-gold mb-2 rounded-pill shadow-sm">
                    <i class="fa-solid fa-address-book me-2"></i> Save Contact
                </a>
            </div>
        </div>

        <!-- Hızlı Aksiyonlar -->
        <div class="row g-3 mb-4 slide-up justify-content-center" style="animation-delay: 0.1s;">
            <?php if(!empty($employee['mobile'])): ?>
            <div class="col-3">
                <a href="tel:<?= str_replace(' ', '', $employee['mobile']) ?>" class="quick-action-btn glass-btn">
                    <i class="fa-solid fa-phone"></i>
                    <span>Call</span>
                </a>
            </div>
            <?php endif; ?>

            <?php if(!empty($employee['whatsapp'])): ?>
            <div class="col-3">
                <a href="https://wa.me/<?= str_replace(['+', ' '], '', $employee['whatsapp']) ?>" target="_blank" class="quick-action-btn glass-btn">
                    <i class="fa-brands fa-whatsapp"></i>
                    <span>Message</span>
                </a>
            </div>
            <?php endif; ?>

            <?php if(!empty($employee['email'])): ?>
            <div class="col-3">
                <a href="mailto:<?= htmlspecialchars($employee['email']) ?>" class="quick-action-btn glass-btn">
                    <i class="fa-solid fa-envelope"></i>
                    <span>Email</span>
                </a>
            </div>
            <?php endif; ?>

            <?php if(!empty($employee['location_url'])): ?>
            <div class="col-3">
                <a href="<?= htmlspecialchars($employee['location_url']) ?>" target="_blank" class="quick-action-btn glass-btn">
                    <i class="fa-solid fa-location-dot"></i>
                    <span>Location</span>
                </a>
            </div>
            <?php endif; ?>
        </div>

        <!-- İletişim Detayları -->
        <div class="glass-card contact-details mb-4 slide-up" style="animation-delay: 0.2s;">
            <h3 class="section-title">Contact Details</h3>
            
            <ul class="contact-list list-unstyled mb-0">
                <?php if(!empty($employee['mobile'])): ?>
                <li>
                    <div class="icon-box"><i class="fa-solid fa-mobile-screen"></i></div>
                    <div class="info-text">
                        <span class="label">Mobile</span>
                        <a href="tel:<?= str_replace(' ', '', $employee['mobile']) ?>"><?= htmlspecialchars($employee['mobile']) ?></a>
                    </div>
                </li>
                <?php endif; ?>

                <?php if(!empty($employee['phone'])): ?>
                <li>
                    <div class="icon-box"><i class="fa-solid fa-phone"></i></div>
                    <div class="info-text">
                        <span class="label">Work <?= !empty($employee['extension']) ? '(Ext: '.$employee['extension'].')' : '' ?></span>
                        <a href="tel:<?= str_replace(' ', '', $employee['phone']) ?>"><?= htmlspecialchars($employee['phone']) ?></a>
                    </div>
                </li>
                <?php endif; ?>

                <?php if(!empty($employee['website'])): ?>
                <li>
                    <div class="icon-box"><i class="fa-solid fa-globe"></i></div>
                    <div class="info-text">
                        <span class="label">Website</span>
                        <a href="http://<?= htmlspecialchars($employee['website']) ?>" target="_blank"><?= htmlspecialchars($employee['website']) ?></a>
                    </div>
                </li>
                <?php endif; ?>

                <?php if(!empty($employee['hotel_address'])): ?>
                <li>
                    <div class="icon-box"><i class="fa-solid fa-map-pin"></i></div>
                    <div class="info-text">
                        <span class="label">Address</span>
                        <span class="text-white-50"><?= htmlspecialchars($employee['hotel_address']) ?></span>
                    </div>
                </li>
                <?php endif; ?>

                <?php if(!empty($employee['social_linkedin'])): ?>
                <li>
                    <div class="icon-box"><i class="fa-brands fa-linkedin-in"></i></div>
                    <div class="info-text">
                        <span class="label">LinkedIn</span>
                        <a href="<?= htmlspecialchars($employee['social_linkedin']) ?>" target="_blank">View Profile</a>
                    </div>
                </li>
                <?php endif; ?>

                <?php if(!empty($employee['social_instagram'])): ?>
                <li>
                    <div class="icon-box"><i class="fa-brands fa-instagram"></i></div>
                    <div class="info-text">
                        <span class="label">Instagram</span>
                        <a href="<?= htmlspecialchars($employee['social_instagram']) ?>" target="_blank">Follow</a>
                    </div>
                </li>
                <?php endif; ?>
            </ul>
        </div>

        <!-- Grup Otellerimiz -->
        <div class="glass-card hotels-section mb-4 slide-up" style="animation-delay: 0.25s;">
            <h3 class="section-title text-center d-block mb-4" style="font-size: 1.1rem; letter-spacing: 2px;">Group Hotels</h3>
            <div class="hotel-logos-flex">
                <div class="hotel-logo-item">
                    <a href="https://pientigroup2026.github.io/pienti-nfc/asmalikonak/" target="_blank" rel="noopener noreferrer" class="hotel-logo-link">
                        <img src="/assets/img/asmali_konak-1.png" alt="Asmalı Konak" class="hotel-footer-logo" style="max-height: 45px;">
                    </a>
                </div>
                <div class="hotel-logo-item">
                    <a href="https://pientigroup2026.github.io/pienti-nfc/mithra/" target="_blank" rel="noopener noreferrer" class="hotel-logo-link">
                        <img src="/assets/img/mithra_cave-1.png" alt="Mithra Cave" class="hotel-footer-logo" style="max-height: 42px;">
                    </a>
                </div>
                <div class="hotel-logo-item">
                    <a href="https://pientigroup2026.github.io/pienti-nfc/yunak/" target="_blank" rel="noopener noreferrer" class="hotel-logo-link">
                        <img src="/assets/img/yunak_evleri-1.png" alt="Yunak Evleri" class="hotel-footer-logo" style="max-height: 34px;">
                    </a>
                </div>
            </div>
        </div>

        <!-- Grup Balonlar -->
        <?php if (isset($employee['show_balloons']) && $employee['show_balloons'] == 1): ?>
        <div class="glass-card hotels-section mb-4 slide-up" style="animation-delay: 0.28s;">
            <h3 class="section-title text-center d-block mb-4" style="font-size: 1.1rem; letter-spacing: 2px;">Group Balloons</h3>
            <div class="hotel-logos-flex">
                <div class="hotel-logo-item">
                    <a href="https://www.turkiyeballoons.com/" target="_blank" rel="noopener noreferrer" class="hotel-logo-link">
                        <img src="/assets/img/turkiye_balon-1.png" alt="Türkiye Balon" class="hotel-footer-logo" style="max-height: 75px;">
                    </a>
                </div>
                <div class="hotel-logo-item">
                    <a href="https://www.universalballoons.com/" target="_blank" rel="noopener noreferrer" class="hotel-logo-link">
                        <img src="/assets/img/universal_logo-1.png" alt="Universal Balon" class="hotel-footer-logo universal-logo" style="height: 70px; width: auto; max-width: 100%;">
                    </a>
                </div>
                <div class="hotel-logo-item">
                    <a href="https://www.istanbulballoons.com/" target="_blank" rel="noopener noreferrer" class="hotel-logo-link">
                        <img src="/assets/img/istanbul_logo-1.png" alt="İstanbul Balon" class="hotel-footer-logo" style="height: 70px; width: auto; max-width: 100%;">
                    </a>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div class="footer-text text-center text-white-50 pb-4 slide-up" style="animation-delay: 0.32s;">
            <small>&copy; 2026 Pienti Group. All rights reserved.</small>
        </div>

    </div>


    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
