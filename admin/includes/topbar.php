<?php
/**
 * RiTiVerse Admin Topbar Component
 */
global $pageTitle, $dataStore;
$pageTitle = $pageTitle ?? 'Dashboard';
$flash = getFlash();
?>
<header class="admin-topbar">
    <div class="topbar-left">
        <button type="button" class="mobile-menu-toggle" id="mobileMenuToggle" aria-label="Toggle Navigation">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
        </button>
        <div class="page-breadcrumb">
            <span>Admin</span> / <span><?= htmlspecialchars($pageTitle) ?></span>
        </div>
    </div>

    <div class="topbar-right">
        <a href="../index.php" target="_blank" class="btn-view-site" title="Open live public website in new tab">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
            <span>Live Website</span>
        </a>
        <a href="logout.php" class="btn-logout" title="Sign Out">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
            <span>Logout</span>
        </a>
    </div>
</header>

<?php if ($flash): ?>
    <div style="padding: 1.5rem 2.25rem 0; max-width: 1440px; margin: 0 auto; width: 100%;">
        <div class="alert alert-<?= htmlspecialchars($flash['type']) ?>">
            <?php if ($flash['type'] === 'success'): ?>
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            <?php else: ?>
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <?php endif; ?>
            <span><?= htmlspecialchars($flash['message']) ?></span>
        </div>
    </div>
<?php endif; ?>
