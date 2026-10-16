<?php
/**
 * RiTiVerse Admin Footer Component
 */
?>
    <footer style="padding: 1.5rem 2.25rem; border-top: 1px solid var(--border-color); color: var(--text-dim); font-size: 0.78rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; margin-top: auto;">
        <div>
            &copy; <?= date('Y') ?> <strong>RiTiVerse</strong> &bull; Executive Real Estate Content Management System
        </div>
        <div style="display:flex; align-items:center; gap: 1.5rem;">
            <span>PHP <?= phpversion() ?></span>
            <span>WebP Engine: <strong style="color:var(--success);">Active</strong></span>
            <span>Storage: <strong style="color:var(--accent-gold);">Persistent JSON</strong></span>
        </div>
    </footer>

    <script src="js/admin.js"></script>
</body>
</html>
