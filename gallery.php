<?php
/**
 * RiTiVerse - Curated Architectural Gallery & Visual Perspectives
 * Dedicated Full-Scale Visual Archive with Interactive Lightbox & Filters
 */

$config = require __DIR__ . '/config.php';

// Curated 12 Architectural Gallery Works
$galleryItems = [
    [
        'id' => 1,
        'title' => 'The Arabian Seafront Residence',
        'location' => 'Worli Sea Face, Mumbai',
        'category' => 'coastal',
        'category_name' => 'Coastal Sanctuaries',
        'image' => 'assets/project-1-new.0u9fesgwq9skw.webp',
        'dimensions' => '11,400 Sq. Ft.',
        'residence_slug' => 'worli-seaface',
        'description' => 'A contemporary beachfront villa shaped around open spaces, uninterrupted Arabian Sea horizons and seamless indoor-outdoor coastal living.'
    ],
    [
        'id' => 2,
        'title' => 'Malabar Crest Sky Penthouse',
        'location' => 'Malabar Hill, Mumbai',
        'category' => 'penthouses',
        'category_name' => 'Sky Penthouses',
        'image' => 'assets/project-2.0pf4-s2q1w0m3.webp',
        'dimensions' => '9,850 Sq. Ft.',
        'residence_slug' => 'malabar-crest',
        'description' => 'An expansive penthouse retreat with wraparound terraces, double-height entertaining spaces and 360-degree views of Marine Drive and the Arabian Sea.'
    ],
    [
        'id' => 3,
        'title' => 'Goa Tropical Sanctuary Villa',
        'location' => 'Assagao, North Goa',
        'category' => 'coastal',
        'category_name' => 'Coastal Sanctuaries',
        'image' => 'assets/project-3-new.1fr3t03j3hm3-.webp',
        'dimensions' => '10,200 Sq. Ft.',
        'residence_slug' => 'goa-courtyard',
        'description' => 'A tranquil private villa collection centered around lush tropical reflection gardens, private forest boundaries and Portuguese-modern teak accents.'
    ],
    [
        'id' => 4,
        'title' => 'The Camellias Reserve Mansion',
        'location' => 'Golf Course Road, Delhi NCR',
        'category' => 'penthouses',
        'category_name' => 'Sky Penthouses',
        'image' => 'assets/project-4-new.3svcowi2h1ysr.webp',
        'dimensions' => '13,500 Sq. Ft.',
        'residence_slug' => 'golf-estate',
        'description' => 'A statement sky mansion overlooking championship greens, pairing private wellness suites, grand banquet salons and a 7-star resort atmosphere.'
    ],
    [
        'id' => 5,
        'title' => 'Double-Height Living Salon',
        'location' => 'Worli Sea Face, Mumbai',
        'category' => 'interiors',
        'category_name' => 'Bespoke Interiors',
        'image' => 'assets/architecture-1.1ldiswkqp2nfk.webp',
        'dimensions' => 'Floor-to-Ceiling Glass',
        'residence_slug' => 'worli-seaface',
        'description' => 'Architectural double-height volume with floor-to-ceiling acoustic glass panels framing the Arabian Sea waves and the Bandra-Worli Sea Link.'
    ],
    [
        'id' => 6,
        'title' => 'Minimalist Teak & Marble Reception',
        'location' => 'Malabar Hill, Mumbai',
        'category' => 'interiors',
        'category_name' => 'Bespoke Interiors',
        'image' => 'assets/cta-1.0-_qg3kedekdn.webp',
        'dimensions' => 'Italian Statuario Marble',
        'residence_slug' => 'malabar-crest',
        'description' => 'A serene reception salon featuring monolithic bookmatched Italian marble, customized hand-rubbed Burma teak cabinetry, and ambient cove illumination.'
    ],
    [
        'id' => 7,
        'title' => "Artisan Chef's Kitchen & Island",
        'location' => 'Assagao, North Goa',
        'category' => 'interiors',
        'category_name' => 'Bespoke Interiors',
        'image' => 'assets/cta-2.3sxqfck474crs.webp',
        'dimensions' => 'Custom European Joinery',
        'residence_slug' => 'goa-courtyard',
        'description' => 'A culinary centerpiece fitted with bespoke fluted bronze accents, concealed Sub-Zero and Gaggenau appliances, and a waterfall quartzite breakfast bar.'
    ],
    [
        'id' => 8,
        'title' => 'Master Horizon Suite & Terraces',
        'location' => 'Golf Course Road, Delhi NCR',
        'category' => 'interiors',
        'category_name' => 'Bespoke Interiors',
        'image' => 'assets/cta-3.386hetcw1l51x.webp',
        'dimensions' => 'Private Sky Deck',
        'residence_slug' => 'golf-estate',
        'description' => 'A sprawling primary sanctuary featuring an ensuite spa bath carved from travertine stone, dual dressing galleries, and a private sunrise terrace.'
    ],
    [
        'id' => 9,
        'title' => 'Cantilevered Sky Sundeck & Pool',
        'location' => 'Worli Sea Face, Mumbai',
        'category' => 'amenities',
        'category_name' => 'Signature Amenities',
        'image' => 'assets/portfolio-img-new-1.3ulka13a-n5np.webp',
        'dimensions' => 'Heated Infinity Edge',
        'residence_slug' => 'worli-seaface',
        'description' => 'A gravity-defying cantilevered sundeck suspended over the coastline, featuring an Olympic-length heated lap pool and private cabana loungers.'
    ],
    [
        'id' => 10,
        'title' => 'Sculptural Cantilever Staircase',
        'location' => 'Malabar Hill, Mumbai',
        'category' => 'interiors',
        'category_name' => 'Bespoke Interiors',
        'image' => 'assets/portfolio-img-new-3.43tkm-o7u7q3-.webp',
        'dimensions' => 'Solid Teak & Bronze',
        'residence_slug' => 'malabar-crest',
        'description' => 'A spiral architectural statement connecting triplex levels, engineered from solid cast bronze stringers and cantilevered smoked-oak treads.'
    ],
    [
        'id' => 11,
        'title' => 'Sunken Oceanfront Firepit & Lounge',
        'location' => 'Assagao, North Goa',
        'category' => 'amenities',
        'category_name' => 'Signature Amenities',
        'image' => 'assets/highlight-1.2o4ruh3co9gp1.webp',
        'dimensions' => 'Natural Basalt Stone',
        'residence_slug' => 'goa-courtyard',
        'description' => 'An evening gathering space sunken into reflecting pool waters, anchored by a smokeless ethanol flame feature and surrounded by fragrant tropical flora.'
    ],
    [
        'id' => 12,
        'title' => 'Reflective Lotus Water Pavilion',
        'location' => 'Worli Sea Face, Mumbai',
        'category' => 'amenities',
        'category_name' => 'Signature Amenities',
        'image' => 'assets/gallery-1.2tr8oh4dnb21n.webp',
        'dimensions' => 'Zen Reflection Basin',
        'residence_slug' => 'worli-seaface',
        'description' => 'A meditative water court inspired by classical Indian architecture, bridging arrival portals with hand-chiseled slate channels and aquatic lotus blooms.'
    ],
];
?>
<!DOCTYPE html>
<html lang="en" class="dm_sans_90d73d94-module__WQ0eYW__variable">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Curated Architectural Gallery | <?= htmlspecialchars($config['site']['name']) ?></title>
    <meta name="description" content="Explore the visual perspectives, spatial geometry, and material purity of RiTiVerse's luxury coastal and metropolitan residences across India.">
    
    <!-- Open Graph -->
    <meta property="og:title" content="Curated Architectural Gallery | <?= htmlspecialchars($config['site']['name']) ?>">
    <meta property="og:description" content="Visual perspectives of India's most extraordinary private residences and sky estates.">
    <meta property="og:type" content="website">

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
        z-index: 2;
        padding: 0.35rem 0.85rem;
        border-radius: 9999px;
        background: rgba(18, 17, 16, 0.8);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.12);
        font-size: 0.72rem;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #CA8216;
        font-weight: 600;
      }
      .gallery-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(18, 17, 16, 0.95) 0%, rgba(18, 17, 16, 0.2) 60%, transparent 100%);
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
      .lightbox-img-box {
        position: relative;
        max-height: 68vh;
        width: auto;
        display: flex;
        justify-content: center;
        overflow: hidden;
        border-radius: 1rem;
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 30px 80px rgba(0, 0, 0, 0.8);
      }
      .lightbox-img-box img {
        max-height: 68vh;
        max-width: 100%;
        object-fit: contain;
        display: block;
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
                    <button type="button" class="filter-btn active" data-filter="all">All Perspectives (12)</button>
                    <button type="button" class="filter-btn" data-filter="coastal">Coastal Sanctuaries</button>
                    <button type="button" class="filter-btn" data-filter="penthouses">Sky Penthouses</button>
                    <button type="button" class="filter-btn" data-filter="interiors">Bespoke Interiors</button>
                    <button type="button" class="filter-btn" data-filter="amenities">Signature Amenities</button>
                </div>
            </section>

            <!-- Gallery Grid -->
            <section class="gallery-section">
                <div class="gallery-grid" id="galleryGrid">
                    <?php foreach ($galleryItems as $index => $item): ?>
                        <div class="gallery-card" data-category="<?= htmlspecialchars($item['category']) ?>" data-index="<?= $index ?>">
                            <div class="gallery-img-wrapper">
                                <span class="gallery-badge-top"><?= htmlspecialchars($item['category_name']) ?></span>
                                <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['title']) ?>" class="gallery-img" loading="lazy" decoding="async">
                                <div class="gallery-overlay">
                                    <h3 class="gallery-card-title"><?= htmlspecialchars($item['title']) ?></h3>
                                    <div class="gallery-card-location">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#CA8216" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                        <span><?= htmlspecialchars($item['location']) ?></span>
                                    </div>
                                    <div class="gallery-card-footer">
                                        <span><?= htmlspecialchars($item['dimensions']) ?></span>
                                        <span class="expand-indicator">
                                            <span>Expand View</span>
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

    <!-- Lightbox Modal -->
    <div id="lightboxModal" class="lightbox-modal" role="dialog" aria-modal="true">
        <div class="lightbox-content">
            <button type="button" class="lightbox-close-btn" id="lightboxClose" aria-label="Close Lightbox">
                <span>✕</span>
                <span style="font-size:0.85rem; letter-spacing:0.1em; text-transform:uppercase;">Close (Esc)</span>
            </button>

            <!-- Navigation Buttons -->
            <button type="button" class="lightbox-nav-btn lightbox-prev" id="lightboxPrev" aria-label="Previous Image">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
            </button>
            <button type="button" class="lightbox-nav-btn lightbox-next" id="lightboxNext" aria-label="Next Image">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
            </button>

            <!-- Image View -->
            <div class="lightbox-img-box">
                <img id="lightboxImg" src="" alt="Gallery Preview">
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

    <!-- Gallery Interactive Filters & Lightbox Engine -->
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
                    if (filter === 'all' || category === filter) {
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

            lightboxImg.src = item.image;
            lightboxImg.alt = item.title;
            lightboxTitle.textContent = item.title + ' — ' + item.location;
            lightboxDesc.textContent = item.description;
            lightboxInquireBtn.href = `contact.php?residence=${encodeURIComponent(item.residence_slug)}`;
        }

        function closeLightbox() {
            modal.classList.remove('is-active');
            document.body.style.overflow = '';
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
</body>
</html>
