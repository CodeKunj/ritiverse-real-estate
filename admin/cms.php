<?php
require_once __DIR__ . '/auth.php';
requireAuth();

$pageTitle = 'Page CMS (User Content)';
$currentPage = 'cms.php';

$siteData = $dataStore->getSiteData();
$site = $siteData['site'] ?? [];
$cms = $siteData['cms'] ?? [];
$stats = $siteData['stats'] ?? [];
$faqs = $siteData['faqs'] ?? [];
$socials = $siteData['socials'] ?? [];

$successMsg = '';
$errorMsg = '';

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf = $_POST['csrf_token'] ?? '';
    $section = $_POST['cms_section'] ?? '';

    if (!verifyCsrfToken($csrf)) {
        $errorMsg = 'Security token invalid or expired. Please reload and submit again.';
    } else {
        // Section: Brand & Identity
        if ($section === 'brand') {
            $siteData['site']['name']        = trim($_POST['site_name'] ?? 'RiTiVerse');
            $siteData['site']['tagline']     = trim($_POST['site_tagline'] ?? '');
            $siteData['site']['description'] = trim($_POST['site_description'] ?? '');
            $siteData['site']['phone']       = trim($_POST['site_phone'] ?? '');
            $siteData['site']['phone_raw']   = trim($_POST['site_phone_raw'] ?? '');
            $siteData['site']['email']       = trim($_POST['site_email'] ?? '');
            $siteData['site']['address']     = trim($_POST['site_address'] ?? '');
            $siteData['site']['currency']    = trim($_POST['site_currency'] ?? '₹');

            // Header Logo Upload
            if (!empty($_FILES['logo_white']['name'])) {
                $res = $dataStore->uploadAndConvertToWebp($_FILES['logo_white'], 'logo_white');
                if ($res['success']) {
                    $siteData['site']['logo_white'] = $res['path'];
                } else {
                    $errorMsg .= ' Header Logo: ' . $res['error'];
                }
            }

            // Footer Logo Upload
            if (!empty($_FILES['logo_dark']['name'])) {
                $res = $dataStore->uploadAndConvertToWebp($_FILES['logo_dark'], 'logo_dark');
                if ($res['success']) {
                    $siteData['site']['logo_dark'] = $res['path'];
                } else {
                    $errorMsg .= ' Footer Logo: ' . $res['error'];
                }
            }

            // Favicon Upload
            if (!empty($_FILES['favicon']['name'])) {
                $res = $dataStore->uploadAndConvertToWebp($_FILES['favicon'], 'favicon');
                if ($res['success']) {
                    $siteData['site']['favicon'] = $res['path'];
                } else {
                    $errorMsg .= ' Favicon: ' . $res['error'];
                }
            }

            // OG Image Upload
            if (!empty($_FILES['og_image']['name'])) {
                $res = $dataStore->uploadAndConvertToWebp($_FILES['og_image'], 'og_image');
                if ($res['success']) {
                    $siteData['site']['og_image'] = $res['path'];
                } else {
                    $errorMsg .= ' OG Image: ' . $res['error'];
                }
            }

            $dataStore->saveSiteData($siteData);
            if (empty($errorMsg)) {
                setFlash('success', 'Brand identity, logos, favicon & OG image updated successfully.');
                header('Location: cms.php?tab=tab-brand');
                exit;
            }
        }

        // Section: Hero Section CMS
        if ($section === 'hero') {
            $siteData['cms']['hero']['headline_1']  = trim($_POST['headline_1'] ?? 'Exceptional');
            $siteData['cms']['hero']['headline_2']  = trim($_POST['headline_2'] ?? 'Living In India');
            $siteData['cms']['hero']['description'] = trim($_POST['hero_desc'] ?? '');
            $siteData['cms']['hero']['btn1_text']   = trim($_POST['btn1_text'] ?? 'Explore Our Collection');
            $siteData['cms']['hero']['btn1_url']    = trim($_POST['btn1_url'] ?? '#lumera-features');
            $siteData['cms']['hero']['btn2_text']   = trim($_POST['btn2_text'] ?? 'Speak With an Advisor');

            // Hero Background Image Upload
            if (!empty($_FILES['hero_bg']['name'])) {
                $res = $dataStore->uploadAndConvertToWebp($_FILES['hero_bg'], 'hero_bg');
                if ($res['success']) {
                    $siteData['cms']['hero']['bg_image'] = $res['path'];
                } else {
                    $errorMsg .= ' Hero Background: ' . $res['error'];
                }
            }

            // Stats Array (4 items)
            $statValues = $_POST['stat_value'] ?? [];
            $statUnits  = $_POST['stat_unit'] ?? [];
            $statLabels = $_POST['stat_label'] ?? [];

            $newStats = [];
            for ($i = 0; $i < count($statValues); $i++) {
                if (!empty($statLabels[$i])) {
                    $newStats[] = [
                        'value' => trim($statValues[$i]),
                        'unit'  => trim($statUnits[$i] ?? ''),
                        'label' => trim($statLabels[$i]),
                    ];
                }
            }
            if (!empty($newStats)) {
                $siteData['stats'] = $newStats;
            }

            $dataStore->saveSiteData($siteData);
            if (empty($errorMsg)) {
                setFlash('success', 'Hero section content and live stats updated successfully.');
                header('Location: cms.php?tab=tab-hero');
                exit;
            }
        }

        // Section: About Section CMS
        if ($section === 'about') {
            $siteData['cms']['about']['title'] = trim($_POST['about_title'] ?? '');
            $siteData['cms']['about']['p1']    = trim($_POST['about_p1'] ?? '');
            $siteData['cms']['about']['p2']    = trim($_POST['about_p2'] ?? '');

            $dataStore->saveSiteData($siteData);
            setFlash('success', 'About section content updated successfully.');
            header('Location: cms.php?tab=tab-about');
            exit;
        }

        // Section: Project Refinement CMS
        if ($section === 'project') {
            $siteData['cms']['project']['title']       = trim($_POST['project_title'] ?? '');
            $siteData['cms']['project']['description'] = trim($_POST['project_desc'] ?? '');
            $siteData['cms']['project']['btn_text']    = trim($_POST['project_btn'] ?? 'Enquire Now');

            $dataStore->saveSiteData($siteData);
            setFlash('success', 'Project Refinement content updated successfully.');
            header('Location: cms.php?tab=tab-project');
            exit;
        }

        // Section: FAQs CMS
        if ($section === 'faqs') {
            $questions = $_POST['faq_q'] ?? [];
            $answers   = $_POST['faq_a'] ?? [];

            $newFaqs = [];
            for ($i = 0; $i < count($questions); $i++) {
                $q = trim($questions[$i] ?? '');
                $a = trim($answers[$i] ?? '');
                if (!empty($q) && !empty($a)) {
                    $newFaqs[] = [
                        'q' => $q,
                        'a' => $a
                    ];
                }
            }

            // New FAQ added?
            $newQ = trim($_POST['new_faq_q'] ?? '');
            $newA = trim($_POST['new_faq_a'] ?? '');
            if (!empty($newQ) && !empty($newA)) {
                $newFaqs[] = [
                    'q' => $newQ,
                    'a' => $newA
                ];
            }

            $siteData['faqs'] = $newFaqs;
            $dataStore->saveSiteData($siteData);
            setFlash('success', 'FAQs list updated successfully.');
            header('Location: cms.php?tab=tab-faqs');
            exit;
        }

        // Section: CTA Section CMS
        if ($section === 'cta') {
            $siteData['cms']['cta']['title']       = trim($_POST['cta_title'] ?? '');
            $siteData['cms']['cta']['description'] = trim($_POST['cta_desc'] ?? '');
            $siteData['cms']['cta']['btn1_text']   = trim($_POST['cta_btn1_text'] ?? 'Arrange Private Consultation');
            $siteData['cms']['cta']['btn1_url']    = trim($_POST['cta_btn1_url'] ?? 'contact.php');
            $siteData['cms']['cta']['btn2_text']   = trim($_POST['cta_btn2_text'] ?? 'Contact Our Team');
            $siteData['cms']['cta']['btn2_url']    = trim($_POST['cta_btn2_url'] ?? 'contact.php');

            // Check if showcase images uploaded
            for ($idx = 0; $idx < 4; $idx++) {
                if (!empty($_FILES['cta_img_' . $idx]['name'])) {
                    $res = $dataStore->uploadAndConvertToWebp($_FILES['cta_img_' . $idx], 'cta_' . ($idx + 1));
                    if ($res['success']) {
                        $siteData['cms']['cta']['images'][$idx] = $res['path'];
                    }
                }
            }

            $dataStore->saveSiteData($siteData);
            setFlash('success', 'Call to Action (CTA) content & images updated successfully.');
            header('Location: cms.php?tab=tab-cta');
            exit;
        }

        // Section: Contact Page Details CMS
        if ($section === 'contact') {
            $siteData['cms']['contact']['badge']           = trim($_POST['contact_badge'] ?? '');
            $siteData['cms']['contact']['hero_title']      = trim($_POST['contact_title'] ?? '');
            $siteData['cms']['contact']['hero_sub']        = trim($_POST['contact_sub'] ?? '');
            $siteData['cms']['contact']['concierge_phone'] = trim($_POST['contact_phone'] ?? '');
            $siteData['cms']['contact']['advisory_email']  = trim($_POST['contact_email'] ?? '');
            $siteData['cms']['contact']['office_hours']    = trim($_POST['contact_hours'] ?? '');
            $siteData['cms']['contact']['address']         = trim($_POST['contact_address'] ?? '');

            $dataStore->saveSiteData($siteData);
            setFlash('success', 'Contact page advisory details updated successfully.');
            header('Location: cms.php?tab=tab-contact');
            exit;
        }

        // Section: Footer & Socials CMS
        if ($section === 'footer') {
            $siteData['cms']['footer']['copyright'] = trim($_POST['footer_copyright'] ?? '');
            $siteData['cms']['footer']['tagline']   = trim($_POST['footer_tagline'] ?? '');

            // Social links
            $fb = trim($_POST['social_facebook'] ?? '#');
            $tw = trim($_POST['social_twitter'] ?? '#');
            $ig = trim($_POST['social_instagram'] ?? '#');
            $li = trim($_POST['social_linkedin'] ?? '#');

            $siteData['socials'] = [
                ['name' => 'Facebook', 'url' => $fb, 'icon' => 'assets/facebook.3rvk6c12nuvo6.svg'],
                ['name' => 'X / Twitter', 'url' => $tw, 'icon' => 'assets/x.1r34ohx23d2is.svg'],
                ['name' => 'Instagram', 'url' => $ig, 'icon' => 'assets/instagram.3q4vb5gjm6944.svg'],
                ['name' => 'LinkedIn', 'url' => $li, 'icon' => 'assets/linkedin.3r_ty6i-5rokn.svg'],
            ];

            $dataStore->saveSiteData($siteData);
            setFlash('success', 'Footer & social media channels updated successfully.');
            header('Location: cms.php?tab=tab-footer');
            exit;
        }
    }

    // Refresh local cache if errors occurred
    $siteData = $dataStore->getSiteData();
    $site = $siteData['site'] ?? [];
    $cms = $siteData['cms'] ?? [];
    $stats = $siteData['stats'] ?? [];
    $faqs = $siteData['faqs'] ?? [];
    $socials = $siteData['socials'] ?? [];
}

$activeTab = $_GET['tab'] ?? 'tab-brand';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($pageTitle) ?> | <?= htmlspecialchars($site['name'] ?? 'RiTiVerse') ?> Admin</title>
    <link rel="icon" type="image/svg+xml" href="<?= htmlspecialchars(!empty($site['favicon']) ? '../' . $site['favicon'] : '../assets/ritiverse-logo-white.svg') ?>">
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>

    <?php require __DIR__ . '/includes/sidebar.php'; ?>

    <div class="admin-main">
        <?php require __DIR__ . '/includes/topbar.php'; ?>

        <main class="admin-content">
            <div class="page-header">
                <div>
                    <h2>Page CMS Engine</h2>
                    <p>Customize all copy, headings, logos, favicon, OG images, and user-facing sections across the website.</p>
                </div>
            </div>

            <?php if (!empty($errorMsg)): ?>
                <div class="alert alert-danger">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <span><?= htmlspecialchars($errorMsg) ?></span>
                </div>
            <?php endif; ?>

            <!-- Navigation Tabs -->
            <div class="tab-wrapper">
                <div class="tab-nav">
                    <button type="button" class="tab-btn <?= $activeTab === 'tab-brand' ? 'active' : '' ?>" data-tab="tab-brand">Brand & Identity (Logos, Favicon)</button>
                    <button type="button" class="tab-btn <?= $activeTab === 'tab-hero' ? 'active' : '' ?>" data-tab="tab-hero">Hero Section & Stats</button>
                    <button type="button" class="tab-btn <?= $activeTab === 'tab-about' ? 'active' : '' ?>" data-tab="tab-about">About Section</button>
                    <button type="button" class="tab-btn <?= $activeTab === 'tab-project' ? 'active' : '' ?>" data-tab="tab-project">Project Refinement</button>
                    <button type="button" class="tab-btn <?= $activeTab === 'tab-faqs' ? 'active' : '' ?>" data-tab="tab-faqs">FAQs Accordion (<?= count($faqs) ?>)</button>
                    <button type="button" class="tab-btn <?= $activeTab === 'tab-cta' ? 'active' : '' ?>" data-tab="tab-cta">Call To Action (CTA)</button>
                    <button type="button" class="tab-btn <?= $activeTab === 'tab-contact' ? 'active' : '' ?>" data-tab="tab-contact">Contact Advisory Desk</button>
                    <button type="button" class="tab-btn <?= $activeTab === 'tab-footer' ? 'active' : '' ?>" data-tab="tab-footer">Footer & Socials</button>
                </div>

                <!-- TAB 1: Brand & Identity -->
                <div id="tab-brand" class="tab-content <?= $activeTab === 'tab-brand' ? 'active' : '' ?>">
                    <form method="POST" action="cms.php?tab=tab-brand" enctype="multipart/form-data">
                        <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                        <input type="hidden" name="cms_section" value="brand">

                        <div class="panel">
                            <div class="panel-header">
                                <div>
                                    <h3>Brand Identity & Core Details</h3>
                                    <p>Global site name, tagline, concierge contacts and currencies</p>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="form-row">
                                    <div class="form-group">
                                        <label class="form-label" for="site_name">Brand Name <span class="required">*</span></label>
                                        <input type="text" id="site_name" name="site_name" class="form-control" value="<?= htmlspecialchars($site['name'] ?? 'RiTiVerse') ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="site_tagline">Tagline</label>
                                        <input type="text" id="site_tagline" name="site_tagline" class="form-control" value="<?= htmlspecialchars($site['tagline'] ?? '') ?>">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="site_description">Site Description / Meta Summary</label>
                                    <textarea id="site_description" name="site_description" class="form-control" rows="3"><?= htmlspecialchars($site['description'] ?? '') ?></textarea>
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label class="form-label" for="site_phone">Concierge Phone (Display)</label>
                                        <input type="text" id="site_phone" name="site_phone" class="form-control" value="<?= htmlspecialchars($site['phone'] ?? '') ?>">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="site_phone_raw">Concierge Phone (Raw for tel: link)</label>
                                        <input type="text" id="site_phone_raw" name="site_phone_raw" class="form-control" value="<?= htmlspecialchars($site['phone_raw'] ?? '') ?>">
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label class="form-label" for="site_email">Concierge Email</label>
                                        <input type="email" id="site_email" name="site_email" class="form-control" value="<?= htmlspecialchars($site['email'] ?? '') ?>">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="site_currency">Currency Symbol</label>
                                        <input type="text" id="site_currency" name="site_currency" class="form-control" value="<?= htmlspecialchars($site['currency'] ?? '₹') ?>">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="site_address">Headquarters Address</label>
                                    <input type="text" id="site_address" name="site_address" class="form-control" value="<?= htmlspecialchars($site['address'] ?? '') ?>">
                                </div>
                            </div>
                        </div>

                        <!-- Brand Media & Visual Assets -->
                        <div class="panel">
                            <div class="panel-header">
                                <div>
                                    <h3>Brand Assets (Logo, Favicon & OG Image)</h3>
                                    <p>Upload in PNG, JPG, or WEBP — all images are auto-converted to lightweight .webp!</p>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="form-row">
                                    <!-- Header Logo White -->
                                    <div class="form-group">
                                        <label class="form-label">Header Logo (White / Light Version)</label>
                                        <div style="display:flex; align-items:center; gap:1rem; margin-bottom: 0.75rem;">
                                            <div style="background: #141312; padding: 0.75rem; border-radius: 8px; border: 1px solid var(--border-color);">
                                                <img src="../<?= htmlspecialchars($site['logo_white'] ?? 'assets/ritiverse-logo-white.svg') ?>" class="preview-target" alt="Header Logo" style="height:32px; max-width:140px; object-fit:contain;">
                                            </div>
                                            <div>
                                                <span class="webp-badge">Auto .webp conversion</span>
                                            </div>
                                        </div>
                                        <div class="dropzone">
                                            <input type="file" name="logo_white" accept="image/png, image/jpeg, image/webp, image/svg+xml">
                                            <div class="dropzone-text">Click or drag new Header Logo</div>
                                            <div class="dropzone-sub">PNG, JPG, WEBP, or SVG &bull; Will convert to .webp</div>
                                        </div>
                                    </div>

                                    <!-- Footer Logo Dark -->
                                    <div class="form-group">
                                        <label class="form-label">Footer Logo (Dark Version)</label>
                                        <div style="display:flex; align-items:center; gap:1rem; margin-bottom: 0.75rem;">
                                            <div style="background: #E0D4C6; padding: 0.75rem; border-radius: 8px; border: 1px solid var(--border-color);">
                                                <img src="../<?= htmlspecialchars($site['logo_dark'] ?? 'assets/ritiverse-logo-black.svg') ?>" class="preview-target" alt="Footer Logo" style="height:32px; max-width:140px; object-fit:contain;">
                                            </div>
                                            <div>
                                                <span class="webp-badge">Auto .webp conversion</span>
                                            </div>
                                        </div>
                                        <div class="dropzone">
                                            <input type="file" name="logo_dark" accept="image/png, image/jpeg, image/webp, image/svg+xml">
                                            <div class="dropzone-text">Click or drag new Footer Logo</div>
                                            <div class="dropzone-sub">PNG, JPG, WEBP, or SVG &bull; Will convert to .webp</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-row" style="margin-top:1.5rem;">
                                    <!-- Favicon -->
                                    <div class="form-group">
                                        <label class="form-label">Browser Favicon</label>
                                        <div style="display:flex; align-items:center; gap:1rem; margin-bottom: 0.75rem;">
                                            <div style="background: #1c1b1a; padding: 0.6rem; border-radius: 8px; border: 1px solid var(--border-color);">
                                                <img src="../<?= htmlspecialchars($site['favicon'] ?? 'assets/ritiverse-logo-white.svg') ?>" class="preview-target" alt="Favicon" style="width:28px; height:28px; object-fit:contain;">
                                            </div>
                                            <div>
                                                <span class="webp-badge">Auto .webp conversion</span>
                                            </div>
                                        </div>
                                        <div class="dropzone">
                                            <input type="file" name="favicon" accept="image/png, image/jpeg, image/webp, image/svg+xml">
                                            <div class="dropzone-text">Upload new Favicon</div>
                                            <div class="dropzone-sub">PNG, JPG, WEBP, or SVG &bull; 64x64 or vector recommended</div>
                                        </div>
                                    </div>

                                    <!-- OG Social Share Image -->
                                    <div class="form-group">
                                        <label class="form-label">Default OG Social Share Image (1200x630)</label>
                                        <div style="display:flex; align-items:center; gap:1rem; margin-bottom: 0.75rem;">
                                            <img src="../<?= htmlspecialchars($site['og_image'] ?? 'assets/hero.1vkt8ape_254z.webp') ?>" class="preview-target" alt="OG Preview" style="height:55px; width:100px; object-fit:cover; border-radius:6px; border:1px solid var(--border-color);">
                                            <div>
                                                <span class="webp-badge">Auto .webp conversion</span>
                                            </div>
                                        </div>
                                        <div class="dropzone">
                                            <input type="file" name="og_image" accept="image/png, image/jpeg, image/webp">
                                            <div class="dropzone-text">Upload new OG Image</div>
                                            <div class="dropzone-sub">PNG, JPG, or WEBP &bull; Will convert to .webp</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-footer">
                                <button type="submit" class="btn btn-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                                    Save Brand & Identity Settings
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- TAB 2: Hero Section & Stats -->
                <div id="tab-hero" class="tab-content <?= $activeTab === 'tab-hero' ? 'active' : '' ?>">
                    <form method="POST" action="cms.php?tab=tab-hero" enctype="multipart/form-data">
                        <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                        <input type="hidden" name="cms_section" value="hero">

                        <div class="panel">
                            <div class="panel-header">
                                <div>
                                    <h3>Homepage Hero Section</h3>
                                    <p>Main title lines, paragraph text, CTA buttons, and background backdrop</p>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="form-row">
                                    <div class="form-group">
                                        <label class="form-label" for="headline_1">Headline Line 1</label>
                                        <input type="text" id="headline_1" name="headline_1" class="form-control" value="<?= htmlspecialchars($cms['hero']['headline_1'] ?? 'Exceptional') ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="headline_2">Headline Line 2</label>
                                        <input type="text" id="headline_2" name="headline_2" class="form-control" value="<?= htmlspecialchars($cms['hero']['headline_2'] ?? 'Living In India') ?>" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="hero_desc">Hero Description Paragraph</label>
                                    <textarea id="hero_desc" name="hero_desc" class="form-control" rows="3"><?= htmlspecialchars($cms['hero']['description'] ?? '') ?></textarea>
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label class="form-label" for="btn1_text">Primary Button Label</label>
                                        <input type="text" id="btn1_text" name="btn1_text" class="form-control" value="<?= htmlspecialchars($cms['hero']['btn1_text'] ?? 'Explore Our Collection') ?>">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="btn1_url">Primary Button URL Anchor</label>
                                        <input type="text" id="btn1_url" name="btn1_url" class="form-control" value="<?= htmlspecialchars($cms['hero']['btn1_url'] ?? '#lumera-features') ?>">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="btn2_text">Secondary Button Label</label>
                                        <input type="text" id="btn2_text" name="btn2_text" class="form-control" value="<?= htmlspecialchars($cms['hero']['btn2_text'] ?? 'Speak With an Advisor') ?>">
                                    </div>
                                </div>

                                <div class="form-group" style="margin-top:1.5rem;">
                                    <label class="form-label">Hero Background Image</label>
                                    <div style="display:flex; align-items:center; gap:1rem; margin-bottom: 0.75rem;">
                                        <img src="../<?= htmlspecialchars($cms['hero']['bg_image'] ?? 'assets/hero.1vkt8ape_254z.webp') ?>" class="preview-target" alt="Hero BG" style="height:60px; width:120px; object-fit:cover; border-radius:6px; border:1px solid var(--border-color);">
                                        <span class="webp-badge">Auto .webp conversion</span>
                                    </div>
                                    <div class="dropzone">
                                        <input type="file" name="hero_bg" accept="image/png, image/jpeg, image/webp">
                                        <div class="dropzone-text">Upload new Hero Background Image</div>
                                        <div class="dropzone-sub">PNG, JPG, or WEBP &bull; Will convert to .webp</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 4 Hero Stat Cards -->
                        <div class="panel">
                            <div class="panel-header">
                                <div>
                                    <h3>4 Hero Live Stat Cards</h3>
                                    <p>The 4 prominent metrics displayed on the hero section</p>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap:1.25rem;">
                                    <?php for ($i = 0; $i < 4; $i++): 
                                        $val = $stats[$i]['value'] ?? '';
                                        $unit = $stats[$i]['unit'] ?? '';
                                        $label = $stats[$i]['label'] ?? '';
                                    ?>
                                        <div style="background: rgba(255,255,255,0.02); padding: 1.25rem; border-radius: 1rem; border: 1px solid var(--border-color);">
                                            <div style="font-weight:600; color:var(--accent-gold); font-size:0.75rem; text-transform:uppercase; margin-bottom:0.75rem;">Stat #<?= $i + 1 ?></div>
                                            <div class="form-group" style="margin-bottom:0.75rem;">
                                                <label class="form-label" style="font-size:0.75rem;">Value (e.g. 12.5 Cr+)</label>
                                                <input type="text" name="stat_value[]" class="form-control" value="<?= htmlspecialchars($val) ?>">
                                            </div>
                                            <div class="form-group" style="margin-bottom:0.75rem;">
                                                <label class="form-label" style="font-size:0.75rem;">Unit (e.g. /₹ or empty)</label>
                                                <input type="text" name="stat_unit[]" class="form-control" value="<?= htmlspecialchars($unit) ?>">
                                            </div>
                                            <div class="form-group" style="margin-bottom:0;">
                                                <label class="form-label" style="font-size:0.75rem;">Label (e.g. Starting Price)</label>
                                                <input type="text" name="stat_label[]" class="form-control" value="<?= htmlspecialchars($label) ?>">
                                            </div>
                                        </div>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            <div class="panel-footer">
                                <button type="submit" class="btn btn-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                                    Save Hero Section & Stats
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- TAB 3: About Section -->
                <div id="tab-about" class="tab-content <?= $activeTab === 'tab-about' ? 'active' : '' ?>">
                    <form method="POST" action="cms.php?tab=tab-about">
                        <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                        <input type="hidden" name="cms_section" value="about">

                        <div class="panel">
                            <div class="panel-header">
                                <div>
                                    <h3>About Section Content</h3>
                                    <p>Editorial headline and philosophy copy displayed on the about section</p>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <label class="form-label" for="about_title">Main Heading</label>
                                    <textarea id="about_title" name="about_title" class="form-control" rows="2"><?= htmlspecialchars($cms['about']['title'] ?? "Real Estate,\nConsidered Differently") ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="about_p1">Philosophy Paragraph 1</label>
                                    <textarea id="about_p1" name="about_p1" class="form-control" rows="3"><?= htmlspecialchars($cms['about']['p1'] ?? '') ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="about_p2">Philosophy Paragraph 2</label>
                                    <textarea id="about_p2" name="about_p2" class="form-control" rows="3"><?= htmlspecialchars($cms['about']['p2'] ?? '') ?></textarea>
                                </div>
                            </div>
                            <div class="panel-footer">
                                <button type="submit" class="btn btn-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                                    Save About Section
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- TAB 4: Project Refinement -->
                <div id="tab-project" class="tab-content <?= $activeTab === 'tab-project' ? 'active' : '' ?>">
                    <form method="POST" action="cms.php?tab=tab-project">
                        <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                        <input type="hidden" name="cms_section" value="project">

                        <div class="panel">
                            <div class="panel-header">
                                <div>
                                    <h3>Project Refinement Section</h3>
                                    <p>Headline and copy for the horizontal scrolling interior refinement section (#project)</p>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <label class="form-label" for="project_title">Section Heading</label>
                                    <input type="text" id="project_title" name="project_title" class="form-control" value="<?= htmlspecialchars($cms['project']['title'] ?? 'A World of Refinement') ?>">
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="project_desc">Section Description</label>
                                    <textarea id="project_desc" name="project_desc" class="form-control" rows="3"><?= htmlspecialchars($cms['project']['description'] ?? '') ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="project_btn">CTA Button Text</label>
                                    <input type="text" id="project_btn" name="project_btn" class="form-control" value="<?= htmlspecialchars($cms['project']['btn_text'] ?? 'Enquire Now') ?>">
                                </div>
                            </div>
                            <div class="panel-footer">
                                <button type="submit" class="btn btn-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                                    Save Refinement Content
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- TAB 5: FAQs Manager -->
                <div id="tab-faqs" class="tab-content <?= $activeTab === 'tab-faqs' ? 'active' : '' ?>">
                    <form method="POST" action="cms.php?tab=tab-faqs">
                        <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                        <input type="hidden" name="cms_section" value="faqs">

                        <div class="panel">
                            <div class="panel-header">
                                <div>
                                    <h3>Frequently Asked Questions Manager</h3>
                                    <p>Add, edit, or delete interactive accordion questions displayed on the homepage</p>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div style="display:flex; flex-direction:column; gap:1.5rem;" id="faqsContainer">
                                    <?php foreach ($faqs as $i => $faq): ?>
                                        <div style="background: rgba(255,255,255,0.02); padding: 1.25rem; border-radius: 1rem; border: 1px solid var(--border-color); position:relative;">
                                            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.75rem;">
                                                <strong style="color:var(--accent-gold); font-size:0.85rem;">Question #<?= $i + 1 ?></strong>
                                                <button type="button" onclick="this.closest('div[style*=\'position:relative\']').remove()" class="btn btn-danger btn-sm" style="padding:0.25rem 0.65rem; font-size:0.72rem;">Remove</button>
                                            </div>
                                            <div class="form-group" style="margin-bottom:0.75rem;">
                                                <label class="form-label" style="font-size:0.78rem;">Question</label>
                                                <input type="text" name="faq_q[]" class="form-control" value="<?= htmlspecialchars($faq['q'] ?? '') ?>" required>
                                            </div>
                                            <div class="form-group" style="margin-bottom:0;">
                                                <label class="form-label" style="font-size:0.78rem;">Answer</label>
                                                <textarea name="faq_a[]" class="form-control" rows="3" required><?= htmlspecialchars($faq['a'] ?? '') ?></textarea>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>

                                <!-- Add New FAQ Box -->
                                <div style="margin-top: 2rem; background: var(--accent-gold-light); padding: 1.5rem; border-radius: 1rem; border: 1px dashed rgba(202, 130, 22, 0.4);">
                                    <div style="font-weight:600; color:var(--accent-gold); font-size:0.95rem; margin-bottom:1rem; display:flex; align-items:center; gap:0.5rem;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                        Add Another FAQ
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">New Question</label>
                                        <input type="text" name="new_faq_q" class="form-control" placeholder="e.g. Can buyers request bespoke floor customizations?">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">New Answer</label>
                                        <textarea name="new_faq_a" class="form-control" rows="3" placeholder="Provide full detailed response for clients..."></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-footer">
                                <button type="submit" class="btn btn-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                                    Save FAQs Changes
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- TAB 6: Call To Action (CTA) -->
                <div id="tab-cta" class="tab-content <?= $activeTab === 'tab-cta' ? 'active' : '' ?>">
                    <form method="POST" action="cms.php?tab=tab-cta" enctype="multipart/form-data">
                        <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                        <input type="hidden" name="cms_section" value="cta">

                        <div class="panel">
                            <div class="panel-header">
                                <div>
                                    <h3>Call to Action (CTA) Section</h3>
                                    <p>Headline, descriptions, and 4 showcase interior preview images</p>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <label class="form-label" for="cta_title">CTA Headline</label>
                                    <input type="text" id="cta_title" name="cta_title" class="form-control" value="<?= htmlspecialchars($cms['cta']['title'] ?? 'Some Properties Need to be Experienced in Person') ?>">
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="cta_desc">CTA Description</label>
                                    <textarea id="cta_desc" name="cta_desc" class="form-control" rows="3"><?= htmlspecialchars($cms['cta']['description'] ?? '') ?></textarea>
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label class="form-label" for="cta_btn1_text">Button 1 Label</label>
                                        <input type="text" id="cta_btn1_text" name="cta_btn1_text" class="form-control" value="<?= htmlspecialchars($cms['cta']['btn1_text'] ?? 'Arrange Private Consultation') ?>">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="cta_btn1_url">Button 1 Link</label>
                                        <input type="text" id="cta_btn1_url" name="cta_btn1_url" class="form-control" value="<?= htmlspecialchars($cms['cta']['btn1_url'] ?? 'contact.php') ?>">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label class="form-label" for="cta_btn2_text">Button 2 Label</label>
                                        <input type="text" id="cta_btn2_text" name="cta_btn2_text" class="form-control" value="<?= htmlspecialchars($cms['cta']['btn2_text'] ?? 'Contact Our Team') ?>">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="cta_btn2_url">Button 2 Link</label>
                                        <input type="text" id="cta_btn2_url" name="cta_btn2_url" class="form-control" value="<?= htmlspecialchars($cms['cta']['btn2_url'] ?? 'contact.php') ?>">
                                    </div>
                                </div>

                                <!-- 4 Showcase Images -->
                                <div style="margin-top:2rem;">
                                    <h4 style="font-size:0.95rem; color:var(--text-main); margin-bottom:1rem; font-weight:600;">4 CTA Showcase Images (Auto converted to .webp)</h4>
                                    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap:1.25rem;">
                                        <?php for ($idx = 0; $idx < 4; $idx++): 
                                            $imgSrc = $cms['cta']['images'][$idx] ?? "assets/cta-" . ($idx + 1) . ".webp";
                                        ?>
                                            <div style="background: var(--bg-card-hover); padding: 1rem; border-radius: 0.85rem; border: 1px solid var(--border-color); text-align:center;">
                                                <img src="../<?= htmlspecialchars($imgSrc) ?>" class="preview-target" alt="CTA Image <?= $idx + 1 ?>" style="width:100%; height:110px; object-fit:cover; border-radius:6px; margin-bottom:0.75rem;">
                                                <div class="dropzone" style="padding:0.85rem 0.5rem;">
                                                    <input type="file" name="cta_img_<?= $idx ?>" accept="image/png, image/jpeg, image/webp">
                                                    <div style="font-size:0.75rem; color:var(--accent-gold);">Replace Image <?= $idx + 1 ?></div>
                                                </div>
                                            </div>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-footer">
                                <button type="submit" class="btn btn-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                                    Save CTA Section
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- TAB 7: Contact Page Details -->
                <div id="tab-contact" class="tab-content <?= $activeTab === 'tab-contact' ? 'active' : '' ?>">
                    <form method="POST" action="cms.php?tab=tab-contact">
                        <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                        <input type="hidden" name="cms_section" value="contact">

                        <div class="panel">
                            <div class="panel-header">
                                <div>
                                    <h3>Contact Page Content & Advisory Desk Details</h3>
                                    <p>Hero banner and office operational schedule on contact.php</p>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="form-row">
                                    <div class="form-group">
                                        <label class="form-label" for="contact_badge">Header Badge Text</label>
                                        <input type="text" id="contact_badge" name="contact_badge" class="form-control" value="<?= htmlspecialchars($cms['contact']['badge'] ?? 'Private Client Advisory') ?>">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="contact_title">Page Main Title</label>
                                        <input type="text" id="contact_title" name="contact_title" class="form-control" value="<?= htmlspecialchars($cms['contact']['hero_title'] ?? 'Private Client Advisory & Acquisitions') ?>">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="contact_sub">Subheading / Introduction</label>
                                    <textarea id="contact_sub" name="contact_sub" class="form-control" rows="3"><?= htmlspecialchars($cms['contact']['hero_sub'] ?? '') ?></textarea>
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label class="form-label" for="contact_phone">Advisory Desk Direct Phone</label>
                                        <input type="text" id="contact_phone" name="contact_phone" class="form-control" value="<?= htmlspecialchars($cms['contact']['concierge_phone'] ?? '+91 (022) 6985 4100') ?>">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="contact_email">Advisory Desk Email</label>
                                        <input type="email" id="contact_email" name="contact_email" class="form-control" value="<?= htmlspecialchars($cms['contact']['advisory_email'] ?? 'concierge@ritiverse.com') ?>">
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label class="form-label" for="contact_hours">Operational Working Hours</label>
                                        <input type="text" id="contact_hours" name="contact_hours" class="form-control" value="<?= htmlspecialchars($cms['contact']['office_hours'] ?? 'Monday to Saturday, 9:00 AM – 8:00 PM IST') ?>">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="contact_address">Private Office Address</label>
                                        <input type="text" id="contact_address" name="contact_address" class="form-control" value="<?= htmlspecialchars($cms['contact']['address'] ?? '') ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="panel-footer">
                                <button type="submit" class="btn btn-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                                    Save Contact Advisory Content
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- TAB 8: Footer & Socials -->
                <div id="tab-footer" class="tab-content <?= $activeTab === 'tab-footer' ? 'active' : '' ?>">
                    <form method="POST" action="cms.php?tab=tab-footer">
                        <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                        <input type="hidden" name="cms_section" value="footer">

                        <div class="panel">
                            <div class="panel-header">
                                <div>
                                    <h3>Footer Content & Social Media Profiles</h3>
                                    <p>Global copyright line and social media URLs across all pages</p>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <label class="form-label" for="footer_copyright">Copyright Text</label>
                                    <input type="text" id="footer_copyright" name="footer_copyright" class="form-control" value="<?= htmlspecialchars($cms['footer']['copyright'] ?? ('© ' . date('Y') . ' RiTiVerse India Landmark Residences. All rights reserved.')) ?>">
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="footer_tagline">Footer Tagline</label>
                                    <input type="text" id="footer_tagline" name="footer_tagline" class="form-control" value="<?= htmlspecialchars($cms['footer']['tagline'] ?? '') ?>">
                                </div>

                                <div class="form-row" style="margin-top:1.5rem;">
                                    <?php 
                                        $fbUrl = ''; $twUrl = ''; $igUrl = ''; $liUrl = '';
                                        foreach ($socials as $soc) {
                                            if (stripos($soc['name'], 'facebook') !== false) $fbUrl = $soc['url'];
                                            if (stripos($soc['name'], 'twitter') !== false || stripos($soc['name'], 'x') !== false) $twUrl = $soc['url'];
                                            if (stripos($soc['name'], 'instagram') !== false) $igUrl = $soc['url'];
                                            if (stripos($soc['name'], 'linkedin') !== false) $liUrl = $soc['url'];
                                        }
                                    ?>
                                    <div class="form-group">
                                        <label class="form-label" for="social_facebook">Facebook Profile URL</label>
                                        <input type="text" id="social_facebook" name="social_facebook" class="form-control" value="<?= htmlspecialchars($fbUrl) ?>">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="social_twitter">X / Twitter Profile URL</label>
                                        <input type="text" id="social_twitter" name="social_twitter" class="form-control" value="<?= htmlspecialchars($twUrl) ?>">
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label class="form-label" for="social_instagram">Instagram Profile URL</label>
                                        <input type="text" id="social_instagram" name="social_instagram" class="form-control" value="<?= htmlspecialchars($igUrl) ?>">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="social_linkedin">LinkedIn Profile URL</label>
                                        <input type="text" id="social_linkedin" name="social_linkedin" class="form-control" value="<?= htmlspecialchars($liUrl) ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="panel-footer">
                                <button type="submit" class="btn btn-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                                    Save Footer & Social Channels
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </main>

        <?php require __DIR__ . '/includes/footer.php'; ?>
    </div>

</body>
</html>
