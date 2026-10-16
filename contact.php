<?php
/**
 * RiTiVerse - Private Client Advisory & Acquisitions
 * Dedicated Ultra-Luxury Real Estate Contact & Advisory Experience
 */

$dataStore = require_once __DIR__ . '/includes/data_store.php';
$config = $dataStore->getConfig();
$selectedResidence = isset($_GET['residence']) ? trim(htmlspecialchars($_GET['residence'])) : '';

$contactCms = $config['cms']['contact'] ?? [];
$contactBadge = $contactCms['badge'] ?? 'Private Client Advisory & Acquisitions';
$contactHeroTitle = $contactCms['hero_title'] ?? 'Initiate a Confidential Acquisition Consultation';
$contactHeroSub = $contactCms['hero_sub'] ?? 'Whether commissioning a seafront estate in Worli, acquiring an architectural courtyard villa in Assagao, or securing an off-market sky mansion in Delhi NCR, our senior partners provide discrete, comprehensive counsel.';
$contactPhone = $contactCms['concierge_phone'] ?? $config['site']['phone'];
$contactPhoneRaw = $config['site']['phone_raw'] ?? preg_replace('/[^0-9+]/', '', $contactPhone);
$contactEmail = $contactCms['advisory_email'] ?? $config['site']['email'];
$contactAddress = $contactCms['address'] ?? $config['site']['address'];
$contactHours = $contactCms['office_hours'] ?? 'Daily 8:00 AM – 10:00 PM IST';
?>
<!DOCTYPE html>
<html lang="en" class="dm_sans_90d73d94-module__WQ0eYW__variable">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Dynamic SEO & Meta Tags (configured in Admin SEO settings) -->
    <?= $dataStore->renderHead('contact') ?>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap" rel="stylesheet">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="css/style.css?v=<?= filemtime(__DIR__ . '/css/style.css') ?>">

    <!-- Bespoke Scoped Advisory Page Styles -->
    <style>
      .advisory-page {
        background-color: #141312;
        color: #ffffff;
        font-family: var(--font-dm-sans, 'DM Sans', sans-serif);
        min-height: 100vh;
        overflow-x: clip;
      }
      .advisory-hero {
        position: relative;
        padding: 11rem 5% 5rem;
        background: radial-gradient(circle at 50% 0%, rgba(202, 130, 22, 0.12) 0%, rgba(20, 19, 18, 0) 70%), #141312;
        border-bottom: 1px solid rgba(255, 255, 255, 0.06);
      }
      .advisory-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.45rem 1.1rem;
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
      .advisory-hero-title {
        font-size: clamp(2.4rem, 4.8vw, 4.2rem);
        line-height: 1.08;
        font-weight: 500;
        letter-spacing: -0.03em;
        color: #ffffff;
        max-width: 900px;
        margin-bottom: 1.25rem;
      }
      .advisory-hero-sub {
        font-size: clamp(1rem, 1.35vw, 1.2rem);
        line-height: 1.6;
        color: rgba(255, 255, 255, 0.65);
        max-width: 720px;
        margin-bottom: 2.25rem;
      }
      .sla-pill-container {
        display: flex;
        flex-wrap: wrap;
        gap: 1.5rem;
        align-items: center;
      }
      .sla-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.6rem;
        font-size: 0.85rem;
        color: rgba(255, 255, 255, 0.8);
      }
      .pulse-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background-color: #22c55e;
        box-shadow: 0 0 10px #22c55e;
        display: inline-block;
        animation: pulseDot 2s infinite;
      }
      @keyframes pulseDot {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.4; transform: scale(0.85); }
      }

      /* Advisory Grid */
      .advisory-section {
        padding: 5rem 5% 7rem;
        background-color: #141312;
      }
      .advisory-layout {
        display: grid;
        grid-template-columns: 1fr 1.25fr;
        gap: 4rem;
        max-width: 1440px;
        margin: 0 auto;
      }
      @media (max-width: 1024px) {
        .advisory-hero {
          padding: 8rem 5% 4rem;
        }
        .advisory-layout {
          grid-template-columns: 1fr;
          gap: 3.5rem;
        }
      }

      /* Channels & Lounges Column */
      .channel-card {
        background: rgba(255, 255, 255, 0.025);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 1.25rem;
        padding: 2rem;
        margin-bottom: 2rem;
        transition: border-color 0.3s ease, transform 0.3s ease;
      }
      .channel-card:hover {
        border-color: rgba(202, 130, 22, 0.4);
      }
      .channel-title {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.15em;
        color: #CA8216;
        margin-bottom: 1.25rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
      }
      .channel-list {
        display: flex;
        flex-col: column;
        gap: 1.25rem;
        flex-direction: column;
      }
      .channel-item {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        text-decoration: none;
        color: inherit;
        transition: transform 0.25s ease;
      }
      .channel-item:hover {
        transform: translateX(4px);
      }
      .channel-icon {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        color: #CA8216;
      }
      .channel-info h4 {
        font-size: 1.1rem;
        font-weight: 500;
        color: #ffffff;
        margin-bottom: 0.2rem;
      }
      .channel-info p {
        font-size: 0.85rem;
        color: rgba(255, 255, 255, 0.55);
      }

      /* Lounges */
      .lounge-card {
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid rgba(255, 255, 255, 0.06);
        border-radius: 1rem;
        padding: 1.5rem;
        margin-bottom: 1rem;
        transition: background 0.3s ease, border-color 0.3s ease;
      }
      .lounge-card:hover {
        background: rgba(255, 255, 255, 0.04);
        border-color: rgba(202, 130, 22, 0.35);
      }
      .lounge-city {
        font-size: 1.05rem;
        font-weight: 600;
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0.35rem;
      }
      .lounge-city span.badge {
        font-size: 0.7rem;
        color: #CA8216;
        border: 1px solid rgba(202, 130, 22, 0.3);
        border-radius: 9999px;
        padding: 0.15rem 0.6rem;
      }
      .lounge-address {
        font-size: 0.85rem;
        color: rgba(255, 255, 255, 0.6);
        line-height: 1.5;
        margin-bottom: 0.5rem;
      }
      .lounge-meta {
        font-size: 0.75rem;
        color: rgba(255, 255, 255, 0.4);
      }

      /* Form Column */
      .mandate-form-card {
        background: rgba(26, 25, 24, 0.85);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(202, 130, 22, 0.25);
        border-radius: 1.5rem;
        padding: 3rem;
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.5);
      }
      @media (max-width: 640px) {
        .mandate-form-card {
          padding: 1.75rem 1.25rem;
        }
      }
      .form-header {
        margin-bottom: 2rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        padding-bottom: 1.5rem;
      }
      .form-header h3 {
        font-size: 1.6rem;
        font-weight: 500;
        color: #ffffff;
        margin-bottom: 0.5rem;
      }
      .form-header p {
        font-size: 0.9rem;
        color: rgba(255, 255, 255, 0.6);
      }
      .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.25rem;
        margin-bottom: 1.25rem;
      }
      @media (max-width: 640px) {
        .form-row {
          grid-template-columns: 1fr;
        }
      }
      .form-group {
        margin-bottom: 1.25rem;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
      }
      .form-label {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: rgba(255, 255, 255, 0.7);
        font-weight: 500;
      }
      .form-input, .form-select, .form-textarea {
        width: 100%;
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid rgba(255, 255, 255, 0.14);
        border-radius: 0.75rem;
        padding: 0.85rem 1.1rem;
        color: #ffffff;
        font-size: 0.95rem;
        font-family: inherit;
        outline: none;
        transition: border-color 0.3s ease, background 0.3s ease, box-shadow 0.3s ease;
      }
      .form-input:focus, .form-select:focus, .form-textarea:focus {
        border-color: #CA8216;
        background: rgba(255, 255, 255, 0.07);
        box-shadow: 0 0 0 3px rgba(202, 130, 22, 0.15);
      }
      .form-select option {
        background-color: #1C1B1A;
        color: #ffffff;
      }
      .form-textarea {
        resize: vertical;
        min-height: 110px;
      }

      /* Phone Input with Country Code */
      .phone-group-wrapper {
        display: flex;
        gap: 0.5rem;
      }
      .country-code-select {
        width: 110px;
        flex-shrink: 0;
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid rgba(255, 255, 255, 0.14);
        border-radius: 0.75rem;
        padding: 0.85rem 0.5rem 0.85rem 0.85rem;
        color: #ffffff;
        font-size: 0.9rem;
        font-family: inherit;
        outline: none;
      }

      /* Budget Bracket Selector Pills */
      .budget-pills {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.6rem;
      }
      .budget-pill-label {
        display: block;
        position: relative;
        cursor: pointer;
      }
      .budget-pill-label input {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
      }
      .budget-pill-custom {
        display: block;
        padding: 0.75rem 0.9rem;
        text-align: center;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 0.65rem;
        font-size: 0.85rem;
        color: rgba(255, 255, 255, 0.8);
        transition: all 0.25s ease;
      }
      .budget-pill-label input:checked + .budget-pill-custom {
        background: rgba(202, 130, 22, 0.18);
        border-color: #CA8216;
        color: #ffffff;
        font-weight: 600;
      }

      /* Consultation Mode Selector */
      .mode-pills {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0.6rem;
      }
      @media (max-width: 640px) {
        .mode-pills {
          grid-template-columns: 1fr;
        }
      }
      .mode-pill-custom {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 0.75rem 0.5rem;
        text-align: center;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 0.65rem;
        font-size: 0.8rem;
        color: rgba(255, 255, 255, 0.8);
        cursor: pointer;
        transition: all 0.25s ease;
      }
      .budget-pill-label input:checked + .mode-pill-custom {
        background: rgba(202, 130, 22, 0.18);
        border-color: #CA8216;
        color: #ffffff;
        font-weight: 600;
      }

      /* Submit Button */
      .mandate-submit-btn {
        width: 100%;
        padding: 1.1rem 2rem;
        background: #CA8216;
        color: #ffffff;
        border: none;
        border-radius: 9999px;
        font-size: 1.05rem;
        font-weight: 600;
        letter-spacing: 0.02em;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        transition: background 0.3s ease, transform 0.25s ease, box-shadow 0.3s ease;
        margin-top: 1rem;
      }
      .mandate-submit-btn:hover {
        background: #d98d1a;
        transform: translateY(-2px);
        box-shadow: 0 12px 25px rgba(202, 130, 22, 0.35);
      }
      .mandate-submit-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
      }

      /* Success Feedback View */
      .mandate-success-card {
        display: none;
        text-align: center;
        padding: 3rem 1.5rem;
      }
      .success-icon-wrap {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        background: rgba(34, 197, 94, 0.15);
        border: 2px solid #22c55e;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        color: #22c55e;
      }
      .mandate-ref-badge {
        display: inline-block;
        background: rgba(202, 130, 22, 0.15);
        border: 1px dashed #CA8216;
        padding: 0.6rem 1.4rem;
        border-radius: 0.5rem;
        font-family: monospace;
        font-size: 1.15rem;
        color: #CA8216;
        letter-spacing: 0.1em;
        margin: 1.25rem 0;
      }

      /* FAQ Strip */
      .advisory-faq {
        padding: 5rem 5% 7rem;
        background: #171615;
        border-top: 1px solid rgba(255, 255, 255, 0.06);
      }
      .advisory-faq-header {
        text-align: center;
        max-width: 680px;
        margin: 0 auto 3.5rem;
      }
      .advisory-faq-header h3 {
        font-size: 2.2rem;
        font-weight: 500;
        color: #ffffff;
        margin-bottom: 0.75rem;
      }
      .advisory-faq-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        max-width: 1200px;
        margin: 0 auto;
      }
      @media (max-width: 768px) {
        .advisory-faq-grid {
          grid-template-columns: 1fr;
        }
      }
      .faq-card {
        background: rgba(255, 255, 255, 0.025);
        border: 1px solid rgba(255, 255, 255, 0.07);
        border-radius: 1rem;
        padding: 1.75rem;
      }
      .faq-card h4 {
        font-size: 1.05rem;
        font-weight: 600;
        color: #ffffff;
        margin-bottom: 0.6rem;
      }
      .faq-card p {
        font-size: 0.88rem;
        color: rgba(255, 255, 255, 0.65);
        line-height: 1.6;
      }
    </style>
</head>
<body class="advisory-page selection:bg-[#ca8216] selection:text-white">

    <!-- Header Navigation -->
    <?php require __DIR__ . '/includes/header.php'; ?>

    <div class="site-content-wrapper" style="background-color: #141312;">
        <main>
            <!-- Hero Banner -->
            <section class="advisory-hero">
                <div class="max-w-[1440px] mx-auto">
                    <div class="advisory-badge">
                        ✦ <?= htmlspecialchars($contactBadge) ?>
                    </div>
                    <h1 class="advisory-hero-title">
                        <?= htmlspecialchars($contactHeroTitle) ?>
                    </h1>
                    <p class="advisory-hero-sub">
                        <?= htmlspecialchars($contactHeroSub) ?>
                    </p>
                    <div class="sla-pill-container">
                        <div class="sla-pill">
                            <span class="pulse-dot"></span>
                            <span>Direct Partner Response within 2 Hours</span>
                        </div>
                        <div class="sla-pill">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#CA8216" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                            <span>100% Confidentiality & Strict NDA Protocol</span>
                        </div>
                        <div class="sla-pill">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#CA8216" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            <span>Private Lounges in Mumbai, Goa & Delhi NCR</span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Main Advisory Content -->
            <section class="advisory-section" id="consultation-section">
                <div class="advisory-layout">
                    
                    <!-- Left Column: Direct Channels & Lounges -->
                    <div>
                        <!-- Direct Concierge Desk -->
                        <div class="channel-card">
                            <div class="channel-title">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                                Priority Concierge Desk
                            </div>
                            <div class="channel-list">
                                <a href="tel:<?= htmlspecialchars($contactPhoneRaw) ?>" class="channel-item">
                                    <div class="channel-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                                    </div>
                                    <div class="channel-info">
                                        <h4><?= htmlspecialchars($contactPhone) ?></h4>
                                        <p>Direct Senior Partner Hotline (<?= htmlspecialchars($contactHours) ?>)</p>
                                    </div>
                                </a>

                                <a href="mailto:<?= htmlspecialchars($contactEmail) ?>" class="channel-item">
                                    <div class="channel-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                                    </div>
                                    <div class="channel-info">
                                        <h4><?= htmlspecialchars($contactEmail) ?></h4>
                                        <p>Confidential Acquisitions & Family Office Enquiries</p>
                                    </div>
                                </a>

                                <a href="https://wa.me/<?= ltrim(htmlspecialchars($contactPhoneRaw), '+') ?>?text=Hello%20RiTiVerse%20Concierge%2C%20I%20would%20like%20to%20request%20a%20private%20advisory%20consultation." target="_blank" rel="noopener noreferrer" class="channel-item">
                                    <div class="channel-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
                                    </div>
                                    <div class="channel-info">
                                        <h4>WhatsApp Private Desk</h4>
                                        <p>Instant encrypted messaging with our acquisitions concierge</p>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <!-- Experience Lounges -->
                        <div class="channel-card">
                            <div class="channel-title">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                                Private Experience Lounges
                            </div>

                            <div class="lounge-card">
                                <div class="lounge-city">
                                    Mumbai Flagship Lounge
                                    <span class="badge">Headquarters</span>
                                </div>
                                <div class="lounge-address">
                                    Worli Sea Face, Worli, Mumbai, Maharashtra 400030
                                </div>
                                <div class="lounge-meta">
                                    Valet parking & private seafront boardroom available by appointment
                                </div>
                            </div>

                            <div class="lounge-card">
                                <div class="lounge-city">
                                    Goa Coastal Design Studio
                                    <span class="badge">Villa Atelier</span>
                                </div>
                                <div class="lounge-address">
                                    Badem Road, Assagao, North Goa 403507
                                </div>
                                <div class="lounge-meta">
                                    Tropical courtyard & architectural material archive
                                </div>
                            </div>

                            <div class="lounge-card">
                                <div class="lounge-city">
                                    Delhi NCR Advisory Suite
                                    <span class="badge">Private Wealth</span>
                                </div>
                                <div class="lounge-address">
                                    The Horizon Center, Golf Course Road, Gurugram 122002
                                </div>
                                <div class="lounge-meta">
                                    Sky meeting lounge overlooking championship greens
                                </div>
                            </div>
                        </div>

                        <!-- Protocols Card -->
                        <div class="channel-card" style="background: rgba(202, 130, 22, 0.04); border-color: rgba(202, 130, 22, 0.2);">
                            <div class="channel-title" style="color: #CA8216;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                                Private Wealth & NRI Advisory Desk
                            </div>
                            <p style="font-size: 0.85rem; color: rgba(255,255,255,0.7); line-height: 1.6;">
                                Dedicated legal & financial advisory for global Indians across North America, the UK, Europe, UAE, and Singapore. Comprehensive FEMA compliance, NRE/NRO repatriation structures, and clear title diligence.
                            </p>
                        </div>
                    </div>

                    <!-- Right Column: The Bespoke Consultation Form -->
                    <div>
                        <div class="mandate-form-card" id="formContainer">
                            <div class="form-header">
                                <h3>Private Acquisition Mandate</h3>
                                <p>Please share your parameters. A designated Senior Advisory Partner will contact you under complete confidentiality.</p>
                            </div>

                            <form id="mandateConsultationForm" novalidate>
                                <!-- Name and Profile -->
                                <div class="form-row">
                                    <div class="form-group">
                                        <label class="form-label" for="clientName">Full Name *</label>
                                        <input type="text" id="clientName" name="name" class="form-input" placeholder="e.g. Rahul Singhania" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="clientProfile">Client Profile</label>
                                        <select id="clientProfile" name="profile" class="form-select">
                                            <option value="HNW Individual">Private HNW Individual</option>
                                            <option value="NRI / Global Indian">NRI / Global Indian</option>
                                            <option value="Family Office">Family Office / Trust</option>
                                            <option value="Corporate Principal">Corporate Principal / CXO</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Contact Info -->
                                <div class="form-row">
                                    <div class="form-group">
                                        <label class="form-label" for="clientPhone">Direct Telephone *</label>
                                        <div class="phone-group-wrapper">
                                            <select id="countryCode" name="country_code" class="country-code-select">
                                                <option value="+91" selected>🇮🇳 +91</option>
                                                <option value="+1">🇺🇸 +1</option>
                                                <option value="+44">🇬🇧 +44</option>
                                                <option value="+971">🇦🇪 +971</option>
                                                <option value="+65">🇸🇬 +65</option>
                                                <option value="+61">🇦🇺 +61</option>
                                                <option value="+41">🇨🇭 +41</option>
                                            </select>
                                            <input type="tel" id="clientPhone" name="phone" class="form-input" placeholder="98200 12345" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="clientEmail">Confidential Email *</label>
                                        <input type="email" id="clientEmail" name="email" class="form-input" placeholder="r.singhania@domain.com" required>
                                    </div>
                                </div>

                                <!-- Residence Selection -->
                                <div class="form-group">
                                    <label class="form-label" for="residenceInterest">Residence / Collection of Interest</label>
                                    <select id="residenceInterest" name="residence" class="form-select">
                                        <option value="General Portfolio Consultation">General Portfolio Consultation (All Addresses)</option>
                                        <option value="The Arabian Seafront Residence - Worli" <?= ($selectedResidence === 'worli-seaface' || stripos($selectedResidence, 'worli') !== false) ? 'selected' : '' ?>>The Arabian Seafront Residence — Worli Sea Face, Mumbai (₹48 Cr)</option>
                                        <option value="Malabar Crest Penthouse - Malabar Hill" <?= ($selectedResidence === 'malabar-crest' || stripos($selectedResidence, 'malabar') !== false) ? 'selected' : '' ?>>Malabar Crest Penthouse — Malabar Hill, Mumbai (₹65 Cr)</option>
                                        <option value="Goa Coastal Villa - Assagao" <?= ($selectedResidence === 'goa-courtyard' || stripos($selectedResidence, 'goa') !== false) ? 'selected' : '' ?>>Goa Coastal Villa — Assagao Sanctuary, North Goa (₹24.5 Cr)</option>
                                        <option value="The Camellias Reserve - Delhi NCR" <?= ($selectedResidence === 'golf-estate' || stripos($selectedResidence, 'delhi') !== false || stripos($selectedResidence, 'camellias') !== false) ? 'selected' : '' ?>>The Camellias Reserve — Golf Course Road, Delhi NCR (₹52 Cr)</option>
                                        <option value="Off-Market Private Sanctuaries">Off-Market Private Sanctuaries (By Invitation Only)</option>
                                    </select>
                                </div>

                                <!-- Budget Bracket -->
                                <div class="form-group">
                                    <label class="form-label">Anticipated Investment Allocation</label>
                                    <div class="budget-pills">
                                        <label class="budget-pill-label">
                                            <input type="radio" name="budget" value="₹15 Cr – ₹25 Cr">
                                            <span class="budget-pill-custom">₹15 Cr – ₹25 Cr</span>
                                        </label>
                                        <label class="budget-pill-label">
                                            <input type="radio" name="budget" value="₹25 Cr – ₹45 Cr" checked>
                                            <span class="budget-pill-custom">₹25 Cr – ₹45 Cr</span>
                                        </label>
                                        <label class="budget-pill-label">
                                            <input type="radio" name="budget" value="₹45 Cr – ₹70 Cr">
                                            <span class="budget-pill-custom">₹45 Cr – ₹70 Cr</span>
                                        </label>
                                        <label class="budget-pill-label">
                                            <input type="radio" name="budget" value="₹70 Cr+">
                                            <span class="budget-pill-custom">₹70 Cr+ (Sky Estate)</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Preferred Consultation Mode -->
                                <div class="form-group">
                                    <label class="form-label">Preferred Consultation Mode</label>
                                    <div class="mode-pills">
                                        <label class="budget-pill-label">
                                            <input type="radio" name="mode" value="In-Person at Lounge" checked>
                                            <span class="mode-pill-custom">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#CA8216" stroke-width="2" style="margin-bottom:0.25rem;"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
                                                Private Lounge
                                            </span>
                                        </label>
                                        <label class="budget-pill-label">
                                            <input type="radio" name="mode" value="Private Video Conference">
                                            <span class="mode-pill-custom">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#CA8216" stroke-width="2" style="margin-bottom:0.25rem;"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/></svg>
                                                Video Conference
                                            </span>
                                        </label>
                                        <label class="budget-pill-label">
                                            <input type="radio" name="mode" value="On-Site Residence Preview">
                                            <span class="mode-pill-custom">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#CA8216" stroke-width="2" style="margin-bottom:0.25rem;"><path d="M2 12h20"/><path d="M20 12v8H4v-8"/><path d="m4 4 8-2 8 2"/></svg>
                                                On-Site Preview
                                            </span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Confidential Notes -->
                                <div class="form-group">
                                    <label class="form-label" for="clientMessage">Confidential Notes & Architectural Criteria</label>
                                    <textarea id="clientMessage" name="message" class="form-textarea" placeholder="Share specific requirements, such as sea view orientation, number of suites, private pool, or expected acquisition timeline..."></textarea>
                                </div>

                                <!-- Feedback Area -->
                                <div id="formFeedback" style="display:none; padding: 0.85rem; border-radius: 0.5rem; margin-bottom: 1rem; font-size: 0.9rem;"></div>

                                <!-- Submit Button -->
                                <button type="submit" id="submitBtn" class="mandate-submit-btn">
                                    <span>Initiate Acquisition Mandate</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                                </button>
                            </form>

                            <!-- Success Screen (Replaces Form on completion) -->
                            <div id="successScreen" class="mandate-success-card">
                                <div class="success-icon-wrap">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                </div>
                                <h3 style="font-size: 1.8rem; font-weight: 500; color: #ffffff; margin-bottom: 0.5rem;">Mandate Received</h3>
                                <p style="font-size: 0.95rem; color: rgba(255,255,255,0.7); max-width: 480px; margin: 0 auto;">
                                    Your acquisition mandate has been registered with priority standing. A designated Senior Advisory Partner will reach out within 2 hours.
                                </p>
                                
                                <div>
                                    <span class="mandate-ref-badge" id="successRefCode">MANDATE-67890</span>
                                </div>

                                <div style="display: flex; justify-content: center; gap: 1rem; margin-top: 1.5rem; flex-wrap: wrap;">
                                    <a id="successWhatsAppBtn" href="#" target="_blank" rel="noopener noreferrer" style="display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(34, 197, 94, 0.2); border: 1px solid #22c55e; color: #ffffff; padding: 0.75rem 1.4rem; border-radius: 9999px; text-decoration: none; font-size: 0.9rem; font-weight: 500;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
                                        Chat with Concierge Now
                                    </a>
                                    <button type="button" onclick="window.print()" style="display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.15); color: #ffffff; padding: 0.75rem 1.4rem; border-radius: 9999px; font-size: 0.9rem; cursor: pointer;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                                        Save Mandate PDF
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </section>

            <!-- FAQ Section -->
            <section class="advisory-faq">
                <div class="advisory-faq-header">
                    <div class="advisory-badge" style="margin-bottom: 1rem;">✦ Due Diligence & Discretion</div>
                    <h3>Acquisitions & Title Integrity</h3>
                    <p style="color: rgba(255,255,255,0.6); font-size: 0.95rem;">Key protocols governing private acquisitions across RiTiVerse portfolios in India.</p>
                </div>

                <div class="advisory-faq-grid">
                    <div class="faq-card">
                        <h4>What level of confidentiality is assured?</h4>
                        <p>All inquiries are handled exclusively by Senior Advisory Partners under institutional Non-Disclosure Agreements (NDAs). Buyer identities, family office registrations, and contract milestones remain completely private.</p>
                    </div>

                    <div class="faq-card">
                        <h4>Are all residences RERA registered with clear freehold titles?</h4>
                        <p>Yes, 100%. Every address in our collection undergoes multi-tiered legal title diligence by premier Indian law chambers. Freehold conveyances, statutory escrow accounts, and MahaRERA / RERA registrations are guaranteed.</p>
                    </div>

                    <div class="faq-card">
                        <h4>How does the NRI & Global Indian Desk facilitate transactions?</h4>
                        <p>We provide full structural support covering FEMA guidelines, NRE/NRO repatriation accounts, power of attorney execution via Indian consulates, and bespoke currency hedging channels through Tier-1 banking partners.</p>
                    </div>

                    <div class="faq-card">
                        <h4>Can private helicopter or chartered site previews be arranged?</h4>
                        <p>Yes. For qualified principals, our concierge arranges discrete on-site visits with private helipad access (where available), chauffeur transfers, and exclusive owner previews before public announcements.</p>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <!-- Footer Component -->
    <?php require __DIR__ . '/includes/footer.php'; ?>

    <!-- Animation Libraries -->
    <script src="js/lenis.min.js"></script>
    <script src="js/gsap.min.js"></script>
    <script src="js/ScrollTrigger.min.js"></script>
    <script src="js/main.js?v=<?= filemtime(__DIR__ . '/js/main.js') ?>"></script>

    <!-- AJAX Mandate Submission Script -->
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('mandateConsultationForm');
        const submitBtn = document.getElementById('submitBtn');
        const feedback = document.getElementById('formFeedback');
        const successScreen = document.getElementById('successScreen');
        const successRefCode = document.getElementById('successRefCode');
        const successWhatsAppBtn = document.getElementById('successWhatsAppBtn');

        if (!form) return;

        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            // Validation
            const name = document.getElementById('clientName').value.trim();
            const phone = document.getElementById('clientPhone').value.trim();
            const email = document.getElementById('clientEmail').value.trim();
            const countryCode = document.getElementById('countryCode').value;
            const fullPhone = countryCode + ' ' + phone;

            if (!name || !phone || !email) {
                feedback.style.display = 'block';
                feedback.style.background = 'rgba(239, 68, 68, 0.15)';
                feedback.style.border = '1px solid #ef4444';
                feedback.style.color = '#ef4444';
                feedback.textContent = 'Please complete all required fields marked with an asterisk (*).';
                return;
            }

            // Loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <svg class="animate-spin" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 2a10 10 0 0 1 10 10"/></svg>
                <span>Transmitting Mandate...</span>
            `;
            feedback.style.display = 'none';

            const formData = new FormData(form);
            formData.set('phone', fullPhone);

            try {
                const response = await fetch('enquire_handler.php', {
                    method: 'POST',
                    body: formData,
                    headers: { 'Accept': 'application/json' }
                });

                const data = await response.json();

                if (data.success || data.status === 'success') {
                    // Smooth Transition to Success
                    form.style.display = 'none';
                    successScreen.style.display = 'block';
                    const ref = data.ref || data.reference || 'MANDATE-' + Math.floor(100000 + Math.random() * 900000);
                    successRefCode.textContent = 'MANDATE REF: ' + ref;

                    const waText = encodeURIComponent(`Hello RiTiVerse Concierge, I have submitted acquisition mandate ${ref} for ${name}.`);
                    successWhatsAppBtn.href = `https://wa.me/912269854100?text=${waText}`;

                    // Scroll nicely to top of container
                    document.getElementById('formContainer').scrollIntoView({ behavior: 'smooth' });
                } else {
                    throw new Error(data.message || 'Transmission failed');
                }
            } catch (err) {
                feedback.style.display = 'block';
                feedback.style.background = 'rgba(239, 68, 68, 0.15)';
                feedback.style.border = '1px solid #ef4444';
                feedback.style.color = '#ef4444';
                feedback.textContent = err.message || 'A network error occurred. Please call +91 (022) 6985 4100 directly.';
                submitBtn.disabled = false;
                submitBtn.innerHTML = `
                    <span>Initiate Acquisition Mandate</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                `;
            }
        });
    });
    </script>
    <!-- Dynamic Footer Scripts Injection (Meta Pixel, custom scripts) -->
    <?= $dataStore->renderFooterScripts() ?>
</body>
</html>
