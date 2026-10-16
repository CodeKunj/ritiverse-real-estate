<?php
require_once __DIR__ . '/auth.php';
requireAuth();

$pageTitle = 'SEO & Analytics Settings';
$currentPage = 'seo.php';

$seo = $dataStore->getSeoSettings();
$integrations = $seo['integrations'] ?? [];
$pages = $seo['pages'] ?? [];

$errorMsg = '';
$activeTab = $_GET['tab'] ?? 'tab-integrations';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf = $_POST['csrf_token'] ?? '';
    $section = $_POST['seo_section'] ?? '';

    if (!verifyCsrfToken($csrf)) {
        $errorMsg = 'Security token invalid or expired. Please reload and submit again.';
    } else {
        if ($section === 'integrations') {
            $seo['integrations']['google_analytics_id']   = trim($_POST['google_analytics_id'] ?? '');
            $seo['integrations']['google_analytics_code'] = trim($_POST['google_analytics_code'] ?? '');
            $seo['integrations']['google_search_console'] = trim($_POST['google_search_console'] ?? '');
            $seo['integrations']['meta_pixel_id']         = trim($_POST['meta_pixel_id'] ?? '');
            $seo['integrations']['meta_pixel_code']       = trim($_POST['meta_pixel_code'] ?? '');
            $seo['integrations']['bing_webmaster_code']   = trim($_POST['bing_webmaster_code'] ?? '');
            $seo['integrations']['custom_head_code']      = trim($_POST['custom_head_code'] ?? '');
            $seo['integrations']['custom_body_code']      = trim($_POST['custom_body_code'] ?? '');

            $dataStore->saveSeoSettings($seo);
            setFlash('success', 'Marketing integrations, Google Analytics, GSC, Meta Pixel and Bing settings saved.');
            header('Location: seo.php?tab=tab-integrations');
            exit;
        }

        if ($section === 'pages') {
            $pageKeys = ['index', 'gallery', 'contact'];
            foreach ($pageKeys as $pkey) {
                $seo['pages'][$pkey]['title']            = trim($_POST[$pkey . '_title'] ?? '');
                $seo['pages'][$pkey]['meta_description'] = trim($_POST[$pkey . '_desc'] ?? '');
                $seo['pages'][$pkey]['meta_keywords']    = trim($_POST[$pkey . '_keywords'] ?? '');
                $seo['pages'][$pkey]['canonical_url']    = trim($_POST[$pkey . '_canonical'] ?? '');
                $seo['pages'][$pkey]['og_title']         = trim($_POST[$pkey . '_og_title'] ?? '');
                $seo['pages'][$pkey]['og_description']   = trim($_POST[$pkey . '_og_desc'] ?? '');

                // Handle Page OG Image upload
                if (!empty($_FILES[$pkey . '_og_img']['name'])) {
                    $res = $dataStore->uploadAndConvertToWebp($_FILES[$pkey . '_og_img'], 'og_' . $pkey);
                    if ($res['success']) {
                        $seo['pages'][$pkey]['og_image'] = $res['path'];
                    } else {
                        $errorMsg .= " {$pkey} OG Image: " . $res['error'];
                    }
                }
            }

            $dataStore->saveSeoSettings($seo);
            if (empty($errorMsg)) {
                setFlash('success', 'Page-level SEO metadata and social graph tags saved successfully.');
                header('Location: seo.php?tab=tab-pages');
                exit;
            }
        }
    }

    $seo = $dataStore->getSeoSettings();
    $integrations = $seo['integrations'] ?? [];
    $pages = $seo['pages'] ?? [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($pageTitle) ?> | RiTiVerse Admin</title>
    <link rel="icon" type="image/svg+xml" href="../assets/ritiverse-logo-white.svg">
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>

    <?php require __DIR__ . '/includes/sidebar.php'; ?>

    <div class="admin-main">
        <?php require __DIR__ . '/includes/topbar.php'; ?>

        <main class="admin-content">
            <div class="page-header">
                <div>
                    <h2>SEO & Marketing Tracking Settings</h2>
                    <p>Configure Google Analytics, Google Search Console, Meta Pixel, Bing Webmaster, and per-page meta tags.</p>
                </div>
            </div>

            <?php if (!empty($errorMsg)): ?>
                <div class="alert alert-danger">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <span><?= htmlspecialchars($errorMsg) ?></span>
                </div>
            <?php endif; ?>

            <div class="tab-wrapper">
                <div class="tab-nav">
                    <button type="button" class="tab-btn <?= $activeTab === 'tab-integrations' ? 'active' : '' ?>" data-tab="tab-integrations">
                        Tracking Integrations (GA, GSC, Meta Pixel, Bing)
                    </button>
                    <button type="button" class="tab-btn <?= $activeTab === 'tab-pages' ? 'active' : '' ?>" data-tab="tab-pages">
                        Pages SEO & Open Graph Meta
                    </button>
                </div>

                <!-- TAB 1: Integrations -->
                <div id="tab-integrations" class="tab-content <?= $activeTab === 'tab-integrations' ? 'active' : '' ?>">
                    <form method="POST" action="seo.php?tab=tab-integrations">
                        <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                        <input type="hidden" name="seo_section" value="integrations">

                        <!-- Google Analytics (GA4) -->
                        <div class="panel">
                            <div class="panel-header">
                                <div>
                                    <h3>Google Analytics (GA4)</h3>
                                    <p>Track visitor traffic, demographics, and user behavior</p>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <label class="form-label" for="google_analytics_id">Google Analytics 4 Measurement ID</label>
                                    <input type="text" id="google_analytics_id" name="google_analytics_id" class="form-control" value="<?= htmlspecialchars($integrations['google_analytics_id'] ?? '') ?>" placeholder="e.g. G-XXXXXXXXXX">
                                    <div class="form-hint">Enter your GA4 Measurement ID starting with "G-". The tracking script is automatically generated in &lt;head&gt;.</div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="google_analytics_code">Or Custom Global Site Tag (gtag.js) Script Code</label>
                                    <textarea id="google_analytics_code" name="google_analytics_code" class="form-control" rows="3" placeholder="&lt;script async src=&quot;https://www.googletagmanager.com/gtag/js?id=...&quot;&gt;...&lt;/script&gt;"><?= htmlspecialchars($integrations['google_analytics_code'] ?? '') ?></textarea>
                                    <div class="form-hint">Optional: paste complete custom gtag script if you have customized tracking options.</div>
                                </div>
                            </div>
                        </div>

                        <!-- Google Search Console -->
                        <div class="panel">
                            <div class="panel-header">
                                <div>
                                    <h3>Google Search Console Verification</h3>
                                    <p>Verify site ownership with Google Search Console</p>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <label class="form-label" for="google_search_console">Verification Meta Tag or Code</label>
                                    <input type="text" id="google_search_console" name="google_search_console" class="form-control" value="<?= htmlspecialchars($integrations['google_search_console'] ?? '') ?>" placeholder='&lt;meta name="google-site-verification" content="abcdef123456" /&gt; or verification code'>
                                    <div class="form-hint">Paste either the full &lt;meta&gt; tag from Google Search Console, or just the verification string.</div>
                                </div>
                            </div>
                        </div>

                        <!-- Meta Pixel (Facebook Pixel) -->
                        <div class="panel">
                            <div class="panel-header">
                                <div>
                                    <h3>Meta Pixel (Facebook / Instagram Pixel)</h3>
                                    <p>Track ad campaigns, custom conversions and re-targeting audiences</p>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <label class="form-label" for="meta_pixel_id">Meta Pixel ID</label>
                                    <input type="text" id="meta_pixel_id" name="meta_pixel_id" class="form-control" value="<?= htmlspecialchars($integrations['meta_pixel_id'] ?? '') ?>" placeholder="e.g. 123456789012345">
                                    <div class="form-hint">Enter your 15-16 digit Meta Pixel ID. Standard PageView script is automatically injected in &lt;head&gt;.</div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="meta_pixel_code">Or Full Meta Pixel Base Script</label>
                                    <textarea id="meta_pixel_code" name="meta_pixel_code" class="form-control" rows="3" placeholder="&lt;script&gt; !function(f,b,e,v,n,t,s)... &lt;/script&gt;"><?= htmlspecialchars($integrations['meta_pixel_code'] ?? '') ?></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Bing Webmaster Integration -->
                        <div class="panel">
                            <div class="panel-header">
                                <div>
                                    <h3>Bing Webmaster Verification</h3>
                                    <p>Verify ownership on Microsoft Bing Webmaster Tools</p>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <label class="form-label" for="bing_webmaster_code">Bing Verification Meta Tag or Code</label>
                                    <input type="text" id="bing_webmaster_code" name="bing_webmaster_code" class="form-control" value="<?= htmlspecialchars($integrations['bing_webmaster_code'] ?? '') ?>" placeholder='&lt;meta name="msvalidate.01" content="1234567890ABCDEF" /&gt;'>
                                    <div class="form-hint">Paste full Bing verification tag or authentication token.</div>
                                </div>
                            </div>
                        </div>

                        <!-- Custom Code Injections -->
                        <div class="panel">
                            <div class="panel-header">
                                <div>
                                    <h3>Custom Code Injection (Head & Body)</h3>
                                    <p>For custom analytics, heatmaps (Hotjar, Clarity), or live chat widgets</p>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <label class="form-label" for="custom_head_code">Custom Header Code (&lt;head&gt; injection)</label>
                                    <textarea id="custom_head_code" name="custom_head_code" class="form-control" rows="4" placeholder="&lt;!-- Injected before &lt;/head&gt; --&gt;"><?= htmlspecialchars($integrations['custom_head_code'] ?? '') ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="custom_body_code">Custom Footer Code (&lt;body&gt; closing injection)</label>
                                    <textarea id="custom_body_code" name="custom_body_code" class="form-control" rows="4" placeholder="&lt;!-- Injected before &lt;/body&gt; --&gt;"><?= htmlspecialchars($integrations['custom_body_code'] ?? '') ?></textarea>
                                </div>
                            </div>
                            <div class="panel-footer">
                                <button type="submit" class="btn btn-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                                    Save Tracking & Integrations
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- TAB 2: Pages SEO -->
                <div id="tab-pages" class="tab-content <?= $activeTab === 'tab-pages' ? 'active' : '' ?>">
                    <form method="POST" action="seo.php?tab=tab-pages" enctype="multipart/form-data">
                        <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                        <input type="hidden" name="seo_section" value="pages">

                        <?php
                        $pageDefs = [
                            'index' => [
                                'title' => 'Homepage SEO (index.php)',
                                'desc'  => 'Main landmark residences showcase and hero experience'
                            ],
                            'gallery' => [
                                'title' => 'Gallery Page SEO (gallery.php)',
                                'desc'  => 'Curated architectural archives and video walkthroughs'
                            ],
                            'contact' => [
                                'title' => 'Contact Page SEO (contact.php)',
                                'desc'  => 'Private client advisory and acquisition desk'
                            ],
                        ];

                        foreach ($pageDefs as $pkey => $pinfo): 
                            $pdata = $pages[$pkey] ?? [];
                        ?>
                            <div class="panel">
                                <div class="panel-header">
                                    <div>
                                        <h3><?= htmlspecialchars($pinfo['title']) ?></h3>
                                        <p><?= htmlspecialchars($pinfo['desc']) ?></p>
                                    </div>
                                    <span style="font-size:0.75rem; color:var(--accent-gold); font-family:monospace; background:rgba(202,130,22,0.1); padding:0.2rem 0.5rem; border-radius:4px;"><?= $pkey ?>.php</span>
                                </div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <label class="form-label" for="<?= $pkey ?>_title">Browser Page Title</label>
                                        <input type="text" id="<?= $pkey ?>_title" name="<?= $pkey ?>_title" class="form-control" value="<?= htmlspecialchars($pdata['title'] ?? '') ?>">
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label" for="<?= $pkey ?>_desc">Meta Description (150-160 chars recommended)</label>
                                        <textarea id="<?= $pkey ?>_desc" name="<?= $pkey ?>_desc" class="form-control" rows="2"><?= htmlspecialchars($pdata['meta_description'] ?? '') ?></textarea>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group">
                                            <label class="form-label" for="<?= $pkey ?>_keywords">Meta Keywords (Comma separated)</label>
                                            <input type="text" id="<?= $pkey ?>_keywords" name="<?= $pkey ?>_keywords" class="form-control" value="<?= htmlspecialchars($pdata['meta_keywords'] ?? '') ?>">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label" for="<?= $pkey ?>_canonical">Canonical URL</label>
                                            <input type="text" id="<?= $pkey ?>_canonical" name="<?= $pkey ?>_canonical" class="form-control" value="<?= htmlspecialchars($pdata['canonical_url'] ?? '') ?>" placeholder="https://ritiverse.com/...">
                                        </div>
                                    </div>

                                    <div class="form-row" style="margin-top:1rem;">
                                        <div class="form-group">
                                            <label class="form-label" for="<?= $pkey ?>_og_title">Open Graph Title</label>
                                            <input type="text" id="<?= $pkey ?>_og_title" name="<?= $pkey ?>_og_title" class="form-control" value="<?= htmlspecialchars($pdata['og_title'] ?? '') ?>">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label" for="<?= $pkey ?>_og_desc">Open Graph Description</label>
                                            <input type="text" id="<?= $pkey ?>_og_desc" name="<?= $pkey ?>_og_desc" class="form-control" value="<?= htmlspecialchars($pdata['og_description'] ?? '') ?>">
                                        </div>
                                    </div>

                                    <div class="form-group" style="margin-top:0.5rem;">
                                        <label class="form-label">Social Share OG Image</label>
                                        <?php if (!empty($pdata['og_image'])): ?>
                                            <div style="display:flex; align-items:center; gap:1rem; margin-bottom: 0.75rem;">
                                                <img src="../<?= htmlspecialchars($pdata['og_image']) ?>" class="preview-target" alt="" style="width:120px; height:68px; object-fit:cover; border-radius:6px; border:1px solid var(--border-color);">
                                                <span class="webp-badge">Auto .webp conversion</span>
                                            </div>
                                        <?php endif; ?>
                                        <div class="dropzone" style="padding:1rem;">
                                            <input type="file" name="<?= $pkey ?>_og_img" accept="image/png, image/jpeg, image/webp">
                                            <div class="dropzone-text" style="font-size:0.8rem;">Click to upload customized OG Image for <?= $pkey ?>.php</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <div style="text-align:right; margin-bottom:2rem;">
                            <button type="submit" class="btn btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                                Save Pages SEO Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </main>

        <?php require __DIR__ . '/includes/footer.php'; ?>
    </div>

</body>
</html>
