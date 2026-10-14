<?php
/**
 * RiTiVerse - Luxury Indian Real Estate
 * Master PHP Template
 */

$config = require __DIR__ . '/config.php';
?>
<!DOCTYPE html>
<html lang="en" class="dm_sans_90d73d94-module__WQ0eYW__variable">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($config['site']['name']) ?> - Luxury Indian Real Estate</title>
    <meta name="description" content="<?= htmlspecialchars($config['site']['description']) ?>">
    
    <!-- Open Graph / Meta -->
    <meta property="og:title" content="<?= htmlspecialchars($config['site']['name']) ?> - Luxury Indian Real Estate">
    <meta property="og:description" content="<?= htmlspecialchars($config['site']['description']) ?>">
    <meta property="og:type" content="website">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= htmlspecialchars($config['site']['name']) ?> - Luxury Indian Real Estate">
    <meta name="twitter:description" content="<?= htmlspecialchars($config['site']['description']) ?>">

    <!-- Google Fonts: DM Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap" rel="stylesheet">

    <!-- Stylesheet -->
    <link rel="stylesheet" href="css/style.css?v=<?= filemtime(__DIR__ . '/css/style.css') ?>">
</head>
<body class="lumera-page relative antialiased selection:bg-[#1c1b1a] selection:text-[#eee]">

    <?php require __DIR__ . '/includes/preloader.php'; ?>
    <?php require __DIR__ . '/includes/header.php'; ?>

    <div class="site-content-wrapper">
        <main>
            <?php require __DIR__ . '/includes/hero.php'; ?>
            <?php require __DIR__ . '/includes/about.php'; ?>
            <?php require __DIR__ . '/includes/features.php'; ?>
            <?php require __DIR__ . '/includes/services.php'; ?>
            <?php require __DIR__ . '/includes/architecture.php'; ?>
            <?php require __DIR__ . '/includes/project.php'; ?>
            <?php require __DIR__ . '/includes/amenities.php'; ?>
            <?php require __DIR__ . '/includes/core_features.php'; ?>
            <?php require __DIR__ . '/includes/bringing.php'; ?>
            <?php require __DIR__ . '/includes/experience.php'; ?>
            <?php require __DIR__ . '/includes/faq.php'; ?>
            <?php require __DIR__ . '/includes/highlights.php'; ?>
            <?php require __DIR__ . '/includes/roi.php'; ?>
            <?php require __DIR__ . '/includes/cta.php'; ?>
        </main>
    </div>

    <?php require __DIR__ . '/includes/footer.php'; ?>
    <?php require __DIR__ . '/includes/enquire_modal.php'; ?>

    
    <!-- Local High-Performance Animation Libraries -->
    <script src="js/lenis.min.js"></script>
    <script src="js/gsap.min.js"></script>
    <script src="js/ScrollTrigger.min.js"></script>

    <!-- Main Application Scripts -->
    <script src="js/main.js?v=<?= filemtime(__DIR__ . '/js/main.js') ?>"></script>
</body>
</html>
