<?php
/**
 * RiTiVerse Admin Sidebar Component
 */
global $dataStore, $currentPage;
$siteData = $dataStore->getSiteData();
$site = $siteData['site'] ?? [];
$logoDark = !empty($site['logo_dark']) ? '/' . ltrim($site['logo_dark'], '/') : '/assets/ritiverse-logo-black.svg';
$adminUser = getAdminUser();
$inquiriesCount = count($dataStore->getInquiries());
$currentPage = $currentPage ?? basename($_SERVER['PHP_SELF']);
?>
<aside class="admin-sidebar">
    <div class="sidebar-brand">
        <a href="index.php" style="display:flex; align-items:center; gap:0.75rem; text-decoration:none;">
            <img src="<?= htmlspecialchars($logoDark) ?>" alt="<?= htmlspecialchars($site['name'] ?? 'RiTiVerse') ?>" class="sidebar-logo">
            <div class="sidebar-brand-text">
                <h1><?= htmlspecialchars($site['name'] ?? 'RiTiVerse') ?></h1>
                <span>Executive CMS</span>
            </div>
        </a>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section-label">General</div>
        <a href="index.php" class="nav-item <?= $currentPage === 'index.php' ? 'active' : '' ?>">
            <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
            <span>Dashboard</span>
        </a>

        <div class="nav-section-label">Content Management</div>
        <a href="cms.php" class="nav-item <?= $currentPage === 'cms.php' ? 'active' : '' ?>">
            <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
            <span>Page CMS (All Content)</span>
        </a>

        <a href="projects.php" class="nav-item <?= $currentPage === 'projects.php' ? 'active' : '' ?>">
            <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            <span>Projects / Residences</span>
        </a>

        <a href="gallery.php" class="nav-item <?= $currentPage === 'gallery.php' ? 'active' : '' ?>">
            <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
            <span>Gallery & Videos</span>
        </a>

        <div class="nav-section-label">Client Leads</div>
        <a href="inquiries.php" class="nav-item <?= $currentPage === 'inquiries.php' ? 'active' : '' ?>">
            <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
            <span>Inquiries / Leads</span>
            <?php if ($inquiriesCount > 0): ?>
                <span class="badge-count"><?= $inquiriesCount ?></span>
            <?php endif; ?>
        </a>

        <div class="nav-section-label">Configuration & Marketing</div>
        <a href="seo.php" class="nav-item <?= $currentPage === 'seo.php' ? 'active' : '' ?>">
            <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <span>SEO & Analytics</span>
        </a>

        <a href="mail.php" class="nav-item <?= $currentPage === 'mail.php' ? 'active' : '' ?>">
            <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
            <span>Mail & SMTP Settings</span>
        </a>

        <a href="settings.php" class="nav-item <?= $currentPage === 'settings.php' ? 'active' : '' ?>">
            <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
            <span>Password & Security</span>
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="user-mini-badge">
            <div class="user-avatar">
                <?= strtoupper(substr($adminUser['name'] ?? 'A', 0, 1)) ?>
            </div>
            <div class="user-info">
                <h4><?= htmlspecialchars($adminUser['name'] ?? 'Admin') ?></h4>
                <span>Master Admin</span>
            </div>
        </div>
        <a href="logout.php" title="Sign Out" style="color: #ef4444; display:flex; align-items:center; text-decoration:none;">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
        </a>
    </div>
</aside>
