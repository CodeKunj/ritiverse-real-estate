<?php
require_once __DIR__ . '/auth.php';
requireAuth();

$pageTitle = 'Dashboard Overview';
$currentPage = 'index.php';

$siteData = $dataStore->getSiteData();
$site = $siteData['site'] ?? [];
$projects = $dataStore->getProjects();
$gallery = $dataStore->getGalleryItems();
$inquiries = $dataStore->getInquiries();
$seo = $dataStore->getSeoSettings();
$mail = $dataStore->getMailSettings();

// Count video items vs image items
$videoCount = count(array_filter($gallery, fn($item) => ($item['type'] ?? '') === 'youtube'));
$imageCount = count($gallery) - $videoCount;

// SEO Integrations check
$hasGA = !empty($seo['integrations']['google_analytics_id']) || !empty($seo['integrations']['google_analytics_code']);
$hasPixel = !empty($seo['integrations']['meta_pixel_id']) || !empty($seo['integrations']['meta_pixel_code']);
$hasGSC = !empty($seo['integrations']['google_search_console']);
$hasBing = !empty($seo['integrations']['bing_webmaster_code']);
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
            <!-- Welcome Header -->
            <div class="page-header">
                <div>
                    <h2>Executive Control Suite</h2>
                    <p>Overview of your residences, architectural gallery works, client inquiries, and tracking systems.</p>
                </div>
                <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
                    <a href="projects.php?action=new" class="btn btn-primary btn-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Add New Project
                    </a>
                    <a href="gallery.php?action=new" class="btn btn-secondary btn-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Add Gallery Image / Video
                    </a>
                </div>
            </div>

            <!-- Stat Grid -->
            <div class="stat-grid">
                <!-- Stat 1: Projects -->
                <div class="stat-card">
                    <div class="stat-card-header">
                        <span class="stat-card-title">Residences</span>
                        <div class="stat-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                        </div>
                    </div>
                    <div class="stat-value"><?= count($projects) ?></div>
                    <div class="stat-desc">Active landmark properties on display</div>
                </div>

                <!-- Stat 2: Gallery Works -->
                <div class="stat-card">
                    <div class="stat-card-header">
                        <span class="stat-card-title">Gallery & Media</span>
                        <div class="stat-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                        </div>
                    </div>
                    <div class="stat-value"><?= count($gallery) ?></div>
                    <div class="stat-desc"><?= $imageCount ?> photos &bull; <?= $videoCount ?> YouTube tours</div>
                </div>

                <!-- Stat 3: Client Leads -->
                <div class="stat-card">
                    <div class="stat-card-header">
                        <span class="stat-card-title">Advisory Inquiries</span>
                        <div class="stat-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                        </div>
                    </div>
                    <div class="stat-value"><?= count($inquiries) ?></div>
                    <div class="stat-desc"><a href="inquiries.php" style="color:var(--accent-gold); text-decoration:none;">View all leads &rarr;</a></div>
                </div>

                <!-- Stat 4: Tracking & SEO -->
                <div class="stat-card">
                    <div class="stat-card-header">
                        <span class="stat-card-title">SEO & Pixels</span>
                        <div class="stat-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        </div>
                    </div>
                    <div class="stat-value" style="font-size:1.45rem; line-height: 1.4;">
                        <?= ($hasGA || $hasPixel || $hasGSC || $hasBing) ? '<span style="color:var(--success);">Connected</span>' : '<span style="color:var(--text-dim);">Ready</span>' ?>
                    </div>
                    <div class="stat-desc">
                        <?= $hasGA ? 'GA4 ✓' : 'GA4 ✗' ?> &bull; <?= $hasPixel ? 'Pixel ✓' : 'Pixel ✗' ?> &bull; <?= $hasGSC ? 'GSC ✓' : 'GSC ✗' ?>
                    </div>
                </div>
            </div>

            <!-- Two Column Layout: Inquiries & Quick Projects -->
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.75rem;" class="form-row">
                
                <!-- Recent Inquiries Panel -->
                <div class="panel">
                    <div class="panel-header">
                        <div>
                            <h3>Recent Client Inquiries</h3>
                            <p>Direct acquisition mandates submitted from user website</p>
                        </div>
                        <a href="inquiries.php" class="btn btn-secondary btn-sm">View All (<?= count($inquiries) ?>)</a>
                    </div>
                    <div class="table-responsive">
                        <?php if (empty($inquiries)): ?>
                            <div style="padding: 3rem 1.5rem; text-align:center; color:var(--text-dim);">
                                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin-bottom:0.75rem; opacity:0.6;"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                                <p>No client inquiries received yet.</p>
                            </div>
                        <?php else: ?>
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>Ref ID</th>
                                        <th>Client</th>
                                        <th>Interest</th>
                                        <th>Budget</th>
                                        <th>Received</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach (array_slice($inquiries, 0, 5) as $inq): ?>
                                        <tr>
                                            <td>
                                                <code style="color:var(--accent-gold); font-size:0.75rem;"><?= htmlspecialchars($inq['id'] ?? '') ?></code>
                                            </td>
                                            <td>
                                                <strong><?= htmlspecialchars($inq['name'] ?? '') ?></strong><br>
                                                <small style="color:var(--text-dim);"><?= htmlspecialchars($inq['phone'] ?? '') ?></small>
                                            </td>
                                            <td>
                                                <span style="font-size:0.82rem;"><?= htmlspecialchars(ucwords(str_replace('-', ' ', $inq['residence'] ?? 'General'))) ?></span>
                                            </td>
                                            <td>
                                                <span style="font-size:0.82rem; color:var(--accent-gold);"><?= htmlspecialchars($inq['budget'] ?? 'Unspecified') ?></span>
                                            </td>
                                            <td>
                                                <small style="color:var(--text-dim);"><?= date('M d, H:i', strtotime($inq['timestamp'] ?? 'now')) ?></small>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Live Projects Quick List -->
                <div class="panel">
                    <div class="panel-header">
                        <div>
                            <h3>Active Residences</h3>
                            <p>Featured projects on site</p>
                        </div>
                        <a href="projects.php" class="btn btn-secondary btn-sm">Manage</a>
                    </div>
                    <div class="panel-body" style="padding: 1rem 1.25rem;">
                        <div style="display:flex; flex-direction:column; gap: 0.85rem;">
                            <?php foreach ($projects as $proj): ?>
                                <div style="display:flex; align-items:center; gap: 0.85rem; padding: 0.65rem; border-radius: 0.75rem; background: rgba(255,255,255,0.02); border: 1px solid var(--border-color);">
                                    <img src="../<?= htmlspecialchars($proj['primary_image']) ?>" alt="" style="width: 52px; height: 42px; object-fit: cover; border-radius: 6px;">
                                    <div style="flex:1; min-width:0;">
                                        <div style="font-weight:600; font-size:0.85rem; color:var(--text-main); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                            <?= htmlspecialchars($proj['title']) ?>
                                        </div>
                                        <div style="font-size:0.75rem; color:var(--accent-gold);">
                                            <?= htmlspecialchars($proj['price']) ?>
                                        </div>
                                    </div>
                                    <a href="projects.php?action=edit&id=<?= urlencode($proj['id']) ?>" style="color:var(--text-muted); padding:0.4rem;" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

            </div>

            <!-- System Diagnostics & Fast Shortcuts -->
            <div class="panel">
                <div class="panel-header">
                    <div>
                        <h3>System Engine & Lightweight Image Optimizer</h3>
                        <p>Real-time server capabilities and image auto-conversion status</p>
                    </div>
                </div>
                <div class="panel-body">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.5rem;">
                        <div style="background: rgba(0,0,0,0.02); padding: 1.25rem; border-radius: 1rem; border: 1px solid var(--border-color);">
                            <div style="display:flex; align-items:center; gap:0.6rem; margin-bottom: 0.5rem;">
                                <span style="display:inline-block; width:10px; height:10px; border-radius:50%; background:var(--success);"></span>
                                <strong style="color:var(--text-main);">WebP Converter Active</strong>
                            </div>
                            <p style="font-size:0.82rem; color:var(--text-muted);">
                                Admin can upload images in <code>.png</code>, <code>.jpg</code>, or <code>.webp</code>. The system automatically converts all uploads to lightweight modern <code>.webp</code> to maximize website loading speed!
                            </p>
                        </div>

                        <div style="background: rgba(0,0,0,0.02); padding: 1.25rem; border-radius: 1rem; border: 1px solid var(--border-color);">
                            <div style="display:flex; align-items:center; gap:0.6rem; margin-bottom: 0.5rem;">
                                <span style="display:inline-block; width:10px; height:10px; border-radius:50%; background:var(--success);"></span>
                                <strong style="color:var(--text-main);">Mail System: <?= strtoupper($mail['driver'] ?? 'MAIL') ?></strong>
                            </div>
                            <p style="font-size:0.82rem; color:var(--text-muted);">
                                Notifications to <code><?= htmlspecialchars($mail['admin_notification_email'] ?? 'concierge@ritiverse.com') ?></code>. Test or configure SMTP in <a href="mail.php" style="color:var(--accent-gold); text-decoration:none;">Mail Settings</a>.
                            </p>
                        </div>

                        <div style="background: rgba(0,0,0,0.02); padding: 1.25rem; border-radius: 1rem; border: 1px solid var(--border-color);">
                            <div style="display:flex; align-items:center; gap:0.6rem; margin-bottom: 0.5rem;">
                                <span style="display:inline-block; width:10px; height:10px; border-radius:50%; background:var(--success);"></span>
                                <strong style="color:var(--text-main);">Fast Jumps</strong>
                            </div>
                            <div style="display:flex; gap:0.5rem; flex-wrap:wrap; margin-top:0.5rem;">
                                <a href="cms.php" class="btn btn-secondary btn-sm">Edit CMS Content</a>
                                <a href="seo.php" class="btn btn-secondary btn-sm">Edit SEO</a>
                                <a href="settings.php" class="btn btn-secondary btn-sm">Change Password</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </main>

        <?php require __DIR__ . '/includes/footer.php'; ?>
    </div>

</body>
</html>
