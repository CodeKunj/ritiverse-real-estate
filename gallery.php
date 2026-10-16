<?php
/**
 * RiTiVerse - Curated Architectural Gallery & Visual Perspectives
 * Dedicated Full-Scale Visual Archive with Interactive Lightbox & Filters
 * Supports high-resolution WebP photography & YouTube Cinematic Video Tours
 */

$dataStore = require_once __DIR__ . '/includes/data_store.php';
$config = $dataStore->getConfig();
$galleryItems = $dataStore->getGalleryItems();

// Compute dynamic categories for filter bar
$categories = [];
$hasVideos = false;
foreach ($galleryItems as $item) {
    $catSlug = $item['category'] ?? 'general';
    $catName = $item['category_name'] ?? ucfirst($catSlug);
    if (!isset($categories[$catSlug])) {
        $categories[$catSlug] = $catName;
    }
    if (($item['type'] ?? '') === 'youtube') {
        $hasVideos = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="dm_sans_90d73d94-module__WQ0eYW__variable">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Dynamic SEO & Meta Tags (configured in Admin SEO settings) -->
    <?= $dataStore->renderHead('gallery') ?>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap" rel="stylesheet">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="css/style.css?v=<?= filemtime(__DIR__ . '/css/style.css') ?>">

    <!-- Bespoke Scoped Gallery Styles -->
    <style>
      .gallery-page {
        background-color: #121110;
        color: #ffffff;
        font-family: var(--font-dm-sans, 'DM Sans', sans-serif);
        min-height: 100vh;
        overflow-x: clip;
      }
      .gallery-hero {
        position: relative;
        padding: 11rem 5% 4rem;
        text-align: center;
        background: radial-gradient(circle at 50% 0%, rgba(202, 130, 22, 0.14) 0%, rgba(18, 17, 16, 0) 70%), #121110;
      }
      .gallery-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.45rem 1.2rem;
        border-radius: 9999px;
        background: rgba(202, 130, 22, 0.12);
        border: 1px solid rgba(202, 130, 22, 0.35);
        color: #CA8216;
        font-size: 0.75rem;
        letter-spacing: 0.18em;
        text-transform: uppercase;
        font-weight: 600;
        margin-bottom: 1.5rem;
      }
      .gallery-hero-title {
        font-size: clamp(2.5rem, 5.2vw, 4.6rem);
        line-height: 1.05;
        font-weight: 500;
        letter-spacing: -0.03em;
        color: #ffffff;
        max-width: 960px;
        margin: 0 auto 1.25rem;
      }
      .gallery-hero-sub {
        font-size: clamp(1rem, 1.35vw, 1.2rem);
        line-height: 1.6;
        color: rgba(255, 255, 255, 0.65);
        max-width: 720px;
        margin: 0 auto 3rem;
      }

      /* Filter Bar */
      .gallery-filter-bar {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        padding: 0.5rem;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 9999px;
        width: fit-content;
        margin: 0 auto;
        backdrop-filter: blur(12px);
      }
      @media (max-width: 768px) {
        .gallery-filter-bar {
          border-radius: 1.25rem;
          padding: 0.75rem;
        }
      }
      .filter-btn {
        background: transparent;
        border: 1px solid transparent;
        color: rgba(255, 255, 255, 0.7);
        padding: 0.6rem 1.4rem;
        border-radius: 9999px;
        font-size: 0.85rem;
        font-weight: 500;
        letter-spacing: 0.02em;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
      }
      .filter-btn:hover {
        color: #ffffff;
        background: rgba(255, 255, 255, 0.06);
      }
      .filter-btn.active {
        background: #CA8216;
        color: #ffffff;
        border-color: #CA8216;
        box-shadow: 0 4px 15px rgba(202, 130, 22, 0.35);
      }

      /* Gallery Grid */
      .gallery-section {
        padding: 4rem 5% 8rem;
        max-width: 1560px;
        margin: 0 auto;
      }
      .gallery-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 2rem;
      }
      @media (max-width: 1100px) {
        .gallery-grid {
          grid-template-columns: repeat(2, 1fr);
          gap: 1.5rem;
        }
      }
      @media (max-width: 680px) {
        .gallery-grid {
          grid-template-columns: 1fr;
          gap: 1.5rem;
        }
      }

      /* Gallery Card */
      .gallery-card {
        position: relative;
        border-radius: 1.25rem;
        overflow: hidden;
        background-color: #1a1918;
        border: 1px solid rgba(255, 255, 255, 0.07);
        cursor: pointer;
        transition: transform 0.45s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.4s ease, box-shadow 0.45s ease;
      }
      .gallery-card:hover {
        transform: translateY(-6px);
        border-color: rgba(202, 130, 22, 0.5);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.6);
      }
      .gallery-img-wrapper {
        position: relative;
        width: 100%;
        padding-top: 72%; /* 4:3 Aspect Ratio */
        overflow: hidden;
      }
      .gallery-img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
      }
      .gallery-card:hover .gallery-img {
        transform: scale(1.08);
      }
      .gallery-badge-top {
        position: absolute;
        top: 1rem;
        left: 1rem;
        z-index: 4;
        padding: 0.35rem 0.85rem;
        border-radius: 9999px;
        background: rgba(18, 17, 16, 0.85);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.12);
        font-size: 0.72rem;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #CA8216;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.4rem;
      }
      .gallery-badge-top.video-badge {
        color: #ff4757;
        border-color: rgba(255, 71, 87, 0.35);
        background: rgba(20, 10, 12, 0.88);
      }

      /* Play Button Overlay for Videos */
      .gallery-play-btn {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) scale(0.95);
        width: 62px;
        height: 62px;
        background: rgba(202, 130, 22, 0.92);
        color: #ffffff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.65), 0 0 20px rgba(202, 130, 22, 0.4);
        z-index: 3;
        transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1), background 0.3s ease;
        pointer-events: none;
      }
      .gallery-card:hover .gallery-play-btn {
        transform: translate(-50%, -50%) scale(1.12);
        background: #CA8216;
      }

      .gallery-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(18, 17, 16, 0.95) 0%, rgba(18, 17, 16, 0.25) 55%, transparent 100%);
        z-index: 2;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        padding: 1.75rem;
        opacity: 0.95;
      }
      .gallery-card-title {
        font-size: 1.25rem;
        font-weight: 500;
        color: #ffffff;
        margin-bottom: 0.35rem;
        line-height: 1.2;
      }
      .gallery-card-location {
        font-size: 0.85rem;
        color: rgba(255, 255, 255, 0.6);
        display: flex;
        align-items: center;
        gap: 0.4rem;
        margin-bottom: 0.85rem;
      }
      .gallery-card-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 0.75rem;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        font-size: 0.78rem;
        color: #CA8216;
      }
      .expand-indicator {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        transition: transform 0.25s ease;
      }
      .gallery-card:hover .expand-indicator {
        transform: translateX(4px);
      }

      /* Lightbox Modal */
      .lightbox-modal {
        position: fixed;
        inset: 0;
        z-index: 9999;
        background: rgba(10, 10, 10, 0.95);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        display: none;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.35s ease;
      }
      .lightbox-modal.is-active {
        display: flex;
        opacity: 1;
      }
      .lightbox-content {
        position: relative;
        max-width: 90vw;
        max-height: 90vh;
        width: 1200px;
        display: flex;
        flex-direction: column;
        align-items: center;
      }
      .lightbox-media-box {
        position: relative;
        max-height: 68vh;
        width: auto;
        display: flex;
        justify-content: center;
        align-items: center;
        overflow: hidden;
        border-radius: 1rem;
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 30px 80px rgba(0, 0, 0, 0.8);
        background: #000000;
      }
      .lightbox-media-box img {
        max-height: 68vh;
        max-width: 100%;
        object-fit: contain;
        display: block;
      }
      .lightbox-media-box iframe {
        width: 75vw;
        max-width: 1000px;
        height: 42vw;
        max-height: 560px;
        border: none;
        display: none;
      }
      @media (max-width: 768px) {
        .lightbox-media-box iframe {
          width: 90vw;
          height: 52vw;
        }
      }
      .lightbox-caption-bar {
        width: 100%;
        margin-top: 1.25rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 1rem;
        padding: 1.25rem 2rem;
        backdrop-filter: blur(10px);
      }
      @media (max-width: 768px) {
        .lightbox-caption-bar {
          flex-direction: column;
          gap: 1rem;
          align-items: flex-start;
          padding: 1rem;
        }
      }
      .lightbox-info h3 {
        font-size: 1.25rem;
        font-weight: 600;
        color: #ffffff;
        margin-bottom: 0.25rem;
      }
      .lightbox-info p {
        font-size: 0.85rem;
        color: rgba(255, 255, 255, 0.65);
      }
      .lightbox-inquire-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.6rem;
        border-radius: 9999px;
        background: #CA8216;
        color: #ffffff;
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 600;
        white-space: nowrap;
        transition: background 0.25s ease, transform 0.25s ease;
      }
      .lightbox-inquire-btn:hover {
        background: #d98d1a;
        transform: translateY(-2px);
      }
      .lightbox-nav-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 52px;
        height: 52px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.15);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background 0.25s ease, transform 0.25s ease;
        z-index: 10;
      }
      .lightbox-nav-btn:hover {
        background: #CA8216;
        border-color: #CA8216;
        transform: translateY(-50%) scale(1.1);
      }
      .lightbox-prev {
        left: -4rem;
      }
      .lightbox-next {
        right: -4rem;
      }
      @media (max-width: 1024px) {
        .lightbox-prev { left: 0.5rem; }
        .lightbox-next { right: 0.5rem; }
      }
      .lightbox-close-btn {
        position: absolute;
        top: -3.5rem;
        right: 0;
        background: transparent;
        border: none;
        color: rgba(255, 255, 255, 0.7);
        font-size: 1.8rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem;
        transition: color 0.25s ease;
      }
      .lightbox-close-btn:hover {
        color: #ffffff;
      }

      /* Bottom CTA Banner */
      .gallery-cta-banner {
        padding: 6rem 5%;
        background: radial-gradient(circle at 50% 50%, rgba(202, 130, 22, 0.1) 0%, rgba(20, 19, 18, 0.95) 100%), #141312;
        border-top: 1px solid rgba(255, 255, 255, 0.06);
        text-align: center;
      }
      .gallery-cta-banner h2 {
        font-size: clamp(2rem, 3.8vw, 3.2rem);
        font-weight: 500;
        color: #ffffff;
        margin-bottom: 1rem;
      }
      .gallery-cta-banner p {
        font-size: 1.05rem;
        color: rgba(255, 255, 255, 0.65);
        max-width: 600px;
        margin: 0 auto 2.5rem;
      }
      .gallery-cta-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1.1rem 2.4rem;
        border-radius: 9999px;
        background: #ffffff;
        color: #1C1B1A;
        font-weight: 600;
        font-size: 1.05rem;
        text-decoration: none;
        transition: background 0.3s ease, transform 0.25s ease, box-shadow 0.3s ease;
      }
      .gallery-cta-btn:hover {
        background: #CA8216;
        color: #ffffff;
        transform: translateY(-3px);
        box-shadow: 0 15px 30px rgba(202, 130, 22, 0.4);
      }
    </style>
</head>
<body class="gallery-page selection:bg-[#ca8216] selection:text-white">

    <!-- Header Navigation -->
    <?php require __DIR__ . '/includes/header.php'; ?>

    <div class="site-content-wrapper" style="background-color: #121110;">
        <main>
            <!-- Hero Banner -->
            <section class="gallery-hero">
                <div class="gallery-badge">
                    ✦ Curated Architectural Archive
                </div>
                <h1 class="gallery-hero-title">
                    Spaces Sculpted for Eternity
                </h1>
                <p class="gallery-hero-sub">
                    Explore the spatial geometry, material purity, and uninterrupted horizons across RiTiVerse private residences in Mumbai, Goa, and Delhi NCR.
                </p>

                <!-- Category Filters -->
                <div class="gallery-filter-bar" id="galleryFilters">
                    <button type="button" class="filter-btn active" data-filter="all">All Perspectives (<?= count($galleryItems) ?>)</button>
                    <?php foreach ($categories as $catKey => $catLabel): ?>
                        <button type="button" class="filter-btn" data-filter="<?= htmlspecialchars($catKey) ?>"><?= htmlspecialchars($catLabel) ?></button>
                    <?php endforeach; ?>
                    <?php if ($hasVideos && !isset($categories['youtube'])): ?>
                        <button type="button" class="filter-btn" data-filter="youtube">▶ Video Tours</button>
                    <?php endif; ?>
                </div>
            </section>

            <!-- Gallery Grid -->
            <section class="gallery-section">
                <div class="gallery-grid" id="galleryGrid">
                    <?php foreach ($galleryItems as $index => $item): 
                        $isYoutube = ($item['type'] ?? '') === 'youtube';
                    ?>
                        <div class="gallery-card" data-category="<?= htmlspecialchars($item['category'] ?? 'general') ?>" data-type="<?= htmlspecialchars($item['type'] ?? 'image') ?>" data-index="<?= $index ?>">
                            <div class="gallery-img-wrapper">
                                <span class="gallery-badge-top <?= $isYoutube ? 'video-badge' : '' ?>">
                                    <?php if ($isYoutube): ?>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                                    <?php endif; ?>
                                    <?= htmlspecialchars($item['category_name'] ?? 'Gallery') ?>
                                </span>

                                <?php if ($isYoutube): ?>
                                    <!-- Play Button Overlay -->
                                    <div class="gallery-play-btn" aria-hidden="true">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" style="margin-left: 3px;"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                                    </div>
                                <?php endif; ?>

                                <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['title']) ?>" class="gallery-img" loading="lazy" decoding="async">
                                
                                <div class="gallery-overlay">
                                    <h3 class="gallery-card-title"><?= htmlspecialchars($item['title']) ?></h3>
                                    <div class="gallery-card-location">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#CA8216" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                        <span><?= htmlspecialchars($item['location']) ?></span>
                                    </div>
                                    <div class="gallery-card-footer">
                                        <span><?= htmlspecialchars($item['dimensions'] ?? ($isYoutube ? '4K Video Tour' : 'Architectural View')) ?></span>
                                        <span class="expand-indicator">
                                            <span><?= $isYoutube ? 'Play Video' : 'Expand View' ?></span>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 3 21 3 21 9"/><polyline points="9 21 3 21 3 15"/><line x1="21" y1="3" x2="14" y2="10"/><line x1="3" y1="21" x2="10" y2="14"/></svg>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <!-- Bottom CTA Banner -->
            <section class="gallery-cta-banner">
                <h2>Experience These Residences In Person</h2>
                <p>Private walkthroughs, helicopter arrivals, and confidential owner previews can be arranged through our senior acquisitions desk.</p>
                <a href="contact.php" class="gallery-cta-btn">
                    <span>Schedule Private Advisory Walkthrough</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                </a>
            </section>
        </main>
    </div>

    <!-- Lightbox Modal with Image & YouTube Video Player Support -->
    <div id="lightboxModal" class="lightbox-modal" role="dialog" aria-modal="true">
        <div class="lightbox-content">
            <button type="button" class="lightbox-close-btn" id="lightboxClose" aria-label="Close Lightbox">
                <span>✕</span>
                <span style="font-size:0.85rem; letter-spacing:0.1em; text-transform:uppercase;">Close (Esc)</span>
            </button>

            <!-- Navigation Buttons -->
            <button type="button" class="lightbox-nav-btn lightbox-prev" id="lightboxPrev" aria-label="Previous Item">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
            </button>
            <button type="button" class="lightbox-nav-btn lightbox-next" id="lightboxNext" aria-label="Next Item">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
            </button>

            <!-- Media Box (Switches between Photo and YouTube Iframe) -->
            <div class="lightbox-media-box">
                <img id="lightboxImg" src="" alt="Gallery Preview">
                <iframe id="lightboxVideo" src="" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>

            <!-- Caption Bar -->
            <div class="lightbox-caption-bar">
                <div class="lightbox-info">
                    <h3 id="lightboxTitle">Residence Title</h3>
                    <p id="lightboxDesc">Description of this architectural perspective</p>
                </div>
                <div>
                    <a id="lightboxInquireBtn" href="contact.php" class="lightbox-inquire-btn">
                        <span>Inquire About This Space</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Component -->
    <?php require __DIR__ . '/includes/footer.php'; ?>

    <!-- Animation Libraries -->
    <script src="js/lenis.min.js"></script>
    <script src="js/gsap.min.js"></script>
    <script src="js/ScrollTrigger.min.js"></script>
    <script src="js/main.js?v=<?= filemtime(__DIR__ . '/js/main.js') ?>"></script>

    <!-- Gallery Interactive Filters & Lightbox Engine with YouTube Support -->
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const galleryData = <?= json_encode($galleryItems, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
        const filterBtns = document.querySelectorAll('#galleryFilters .filter-btn');
        const cards = document.querySelectorAll('.gallery-card');
        const modal = document.getElementById('lightboxModal');
        const closeBtn = document.getElementById('lightboxClose');
        const prevBtn = document.getElementById('lightboxPrev');
        const nextBtn = document.getElementById('lightboxNext');
        const lightboxImg = document.getElementById('lightboxImg');
        const lightboxVideo = document.getElementById('lightboxVideo');
        const lightboxTitle = document.getElementById('lightboxTitle');
        const lightboxDesc = document.getElementById('lightboxDesc');
        const lightboxInquireBtn = document.getElementById('lightboxInquireBtn');

        let currentIndex = 0;
        let visibleIndices = galleryData.map((_, i) => i);

        // 1. Filtering Logic
        filterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                filterBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                const filter = btn.getAttribute('data-filter');

                visibleIndices = [];
                cards.forEach((card, idx) => {
                    const category = card.getAttribute('data-category');
                    const itemType = card.getAttribute('data-type');
                    
                    const match = (filter === 'all') || 
                                  (category === filter) || 
                                  (filter === 'youtube' && itemType === 'youtube');

                    if (match) {
                        card.style.display = 'block';
                        visibleIndices.push(idx);
                    } else {
                        card.style.display = 'none';
                    }
                });

                if (typeof ScrollTrigger !== 'undefined') {
                    ScrollTrigger.refresh();
                }
            });
        });

        // 2. Lightbox Open
        cards.forEach((card, idx) => {
            card.addEventListener('click', () => {
                openLightbox(idx);
            });
        });

        function openLightbox(index) {
            currentIndex = index;
            updateLightboxView();
            modal.classList.add('is-active');
            document.body.style.overflow = 'hidden';
        }

        function updateLightboxView() {
            const item = galleryData[currentIndex];
            if (!item) return;

            lightboxTitle.textContent = item.title + (item.location ? ' — ' + item.location : '');
            lightboxDesc.textContent = item.description || '';
            const slug = item.residence_slug || '';
            lightboxInquireBtn.href = slug ? `contact.php?residence=${encodeURIComponent(slug)}` : 'contact.php';

            if (item.type === 'youtube' && item.youtube_id) {
                // Video Mode
                lightboxImg.style.display = 'none';
                lightboxImg.src = '';
                lightboxVideo.style.display = 'block';
                lightboxVideo.src = `https://www.youtube-nocookie.com/embed/${item.youtube_id}?autoplay=1&rel=0`;
            } else {
                // Image Mode
                lightboxVideo.style.display = 'none';
                lightboxVideo.src = ''; // stop any playing video
                lightboxImg.style.display = 'block';
                lightboxImg.src = item.image;
                lightboxImg.alt = item.title;
            }
        }

        function closeLightbox() {
            modal.classList.remove('is-active');
            document.body.style.overflow = '';
            // Critical: stop YouTube playback immediately
            if (lightboxVideo) {
                lightboxVideo.src = '';
                lightboxVideo.style.display = 'none';
            }
        }

        function showNext() {
            if (visibleIndices.length === 0) return;
            const currentPos = visibleIndices.indexOf(currentIndex);
            const nextPos = (currentPos + 1) % visibleIndices.length;
            currentIndex = visibleIndices[nextPos];
            updateLightboxView();
        }

        function showPrev() {
            if (visibleIndices.length === 0) return;
            const currentPos = visibleIndices.indexOf(currentIndex);
            const prevPos = (currentPos - 1 + visibleIndices.length) % visibleIndices.length;
            currentIndex = visibleIndices[prevPos];
            updateLightboxView();
        }

        closeBtn.addEventListener('click', closeLightbox);
        nextBtn.addEventListener('click', showNext);
        prevBtn.addEventListener('click', showPrev);

        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                closeLightbox();
            }
        });

        // Keyboard Navigation
        window.addEventListener('keydown', (e) => {
            if (!modal.classList.contains('is-active')) return;
            if (e.key === 'Escape') closeLightbox();
            if (e.key === 'ArrowRight') showNext();
            if (e.key === 'ArrowLeft') showPrev();
        });
    });
    </script>

    <!-- Dynamic Footer Scripts Injection (Meta Pixel, custom scripts) -->
    <?= $dataStore->renderFooterScripts() ?>
</body>
</html>
