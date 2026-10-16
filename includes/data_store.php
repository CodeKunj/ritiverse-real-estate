<?php
/**
 * RiTiVerse - Central Data Store & Management Engine (MySQL / PDO)
 * Directly connected to MySQL database via phpMyAdmin.
 * Handles database persistence, WebP image conversion, SEO rendering, SMTP mailing & auth.
 */

require_once __DIR__ . '/db.php';

class DataStore
{
    private static ?DataStore $instance = null;
    private string $uploadsDir;

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct()
    {
        $this->uploadsDir = dirname(__DIR__) . '/assets/uploads';

        if (!is_dir($this->uploadsDir)) {
            @mkdir($this->uploadsDir, 0775, true);
        }

        $this->ensureTablesExist();
    }

    /**
     * Helper to get active PDO connection
     */
    private function db(): PDO
    {
        return getDb();
    }

    /**
     * Automatically verifies and creates required tables if database exists but tables not yet imported
     */
    private function ensureTablesExist(): void
    {
        try {
            $db = $this->db();
            // Check if site_settings exists
            $check = $db->query("SHOW TABLES LIKE 'site_settings'")->fetch();
            if (!$check) {
                $sqlFile = dirname(__DIR__) . '/database.sql';
                if (file_exists($sqlFile)) {
                    $sql = file_get_contents($sqlFile);
                    // Remove CREATE DATABASE / USE statements so it imports cleanly into current connection
                    $cleanSql = preg_replace('/CREATE DATABASE[^;]+;/i', '', $sql);
                    $cleanSql = preg_replace('/USE[^;]+;/i', '', $cleanSql);
                    $db->exec($cleanSql);
                }
            }
        } catch (Throwable $e) {
            // DB error is handled by Database::getConnection()
        }
    }

    // ==========================================
    // 1. SITE & CMS DATA (site_settings table)
    // ==========================================

    public function getSiteData(): array
    {
        try {
            $db = $this->db();
            $stmt = $db->query("SELECT setting_key, setting_value FROM site_settings");
            $rows = $stmt->fetchAll(PDO::FETCH_KEY_PAIR) ?: [];

            return [
                'site' => json_decode($rows['site'] ?? '{}', true) ?: [],
                'nav'  => json_decode($rows['nav'] ?? '[]', true) ?: [],
                'stats'=> json_decode($rows['stats'] ?? '[]', true) ?: [],
                'cms'  => [
                    'hero'    => json_decode($rows['cms_hero'] ?? '{}', true) ?: [],
                    'about'   => json_decode($rows['cms_about'] ?? '{}', true) ?: [],
                    'project' => json_decode($rows['cms_project'] ?? '{}', true) ?: [],
                    'cta'     => json_decode($rows['cms_cta'] ?? '{}', true) ?: [],
                    'contact' => json_decode($rows['cms_contact'] ?? '{}', true) ?: [],
                    'footer'  => json_decode($rows['cms_footer'] ?? '{}', true) ?: [],
                ],
                'socials' => json_decode($rows['socials'] ?? '[]', true) ?: [],
                'faqs'    => json_decode($rows['faqs'] ?? '[]', true) ?: [],
            ];
        } catch (Throwable $e) {
            return [];
        }
    }

    public function saveSiteData(array $data): bool
    {
        try {
            $db = $this->db();
            $stmt = $db->prepare("INSERT INTO site_settings (setting_key, setting_value) VALUES (:k, :v) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)");

            if (isset($data['site'])) {
                $stmt->execute([':k' => 'site', ':v' => json_encode($data['site'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)]);
            }
            if (isset($data['nav'])) {
                $stmt->execute([':k' => 'nav', ':v' => json_encode($data['nav'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)]);
            }
            if (isset($data['stats'])) {
                $stmt->execute([':k' => 'stats', ':v' => json_encode($data['stats'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)]);
            }
            if (isset($data['socials'])) {
                $stmt->execute([':k' => 'socials', ':v' => json_encode($data['socials'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)]);
            }
            if (isset($data['faqs'])) {
                $stmt->execute([':k' => 'faqs', ':v' => json_encode($data['faqs'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)]);
            }
            if (isset($data['cms']) && is_array($data['cms'])) {
                foreach (['hero', 'about', 'project', 'cta', 'contact', 'footer'] as $sec) {
                    if (isset($data['cms'][$sec])) {
                        $stmt->execute([':k' => 'cms_' . $sec, ':v' => json_encode($data['cms'][$sec], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)]);
                    }
                }
            }
            return true;
        } catch (Throwable $e) {
            return false;
        }
    }

    // ==========================================
    // 2. PROJECTS / RESIDENCES (projects table)
    // ==========================================

    public function getProjects(): array
    {
        try {
            $db = $this->db();
            $stmt = $db->query("SELECT * FROM projects ORDER BY sort_order ASC, created_at ASC");
            $rows = $stmt->fetchAll() ?: [];

            $projects = [];
            foreach ($rows as $r) {
                $projects[] = [
                    'id'            => $r['id'],
                    'title'         => $r['title'],
                    'type'          => $r['type'],
                    'description'   => $r['description'],
                    'price'         => $r['price'],
                    'status'        => $r['status'],
                    'featured'      => (bool)$r['featured'],
                    'specs'         => json_decode($r['specs'] ?? '{}', true) ?: [],
                    'primary_image' => $r['primary_image'],
                    'gallery'       => json_decode($r['gallery'] ?? '[]', true) ?: [],
                    'sort_order'    => (int)$r['sort_order'],
                ];
            }
            return $projects;
        } catch (Throwable $e) {
            return [];
        }
    }

    public function saveProjects(array $projects): bool
    {
        try {
            $db = $this->db();
            $db->beginTransaction();

            // Synchronize deletions
            $existingIds = $db->query("SELECT id FROM projects")->fetchAll(PDO::FETCH_COLUMN) ?: [];
            $newIds = array_column($projects, 'id');
            $toDelete = array_diff($existingIds, $newIds);

            if (!empty($toDelete)) {
                $placeholders = implode(',', array_fill(0, count($toDelete), '?'));
                $delStmt = $db->prepare("DELETE FROM projects WHERE id IN ($placeholders)");
                $delStmt->execute(array_values($toDelete));
            }

            // Insert / Update
            $stmt = $db->prepare("INSERT INTO projects (id, title, type, description, price, status, featured, specs, primary_image, gallery, sort_order)
                VALUES (:id, :title, :type, :description, :price, :status, :featured, :specs, :primary_image, :gallery, :sort_order)
                ON DUPLICATE KEY UPDATE
                title = VALUES(title), type = VALUES(type), description = VALUES(description),
                price = VALUES(price), status = VALUES(status), featured = VALUES(featured),
                specs = VALUES(specs), primary_image = VALUES(primary_image), gallery = VALUES(gallery),
                sort_order = VALUES(sort_order)");

            foreach ($projects as $idx => $p) {
                $stmt->execute([
                    ':id'            => $p['id'],
                    ':title'         => $p['title'] ?? '',
                    ':type'          => $p['type'] ?? '',
                    ':description'   => $p['description'] ?? '',
                    ':price'         => $p['price'] ?? '',
                    ':status'        => $p['status'] ?? 'Available',
                    ':featured'      => !empty($p['featured']) ? 1 : 0,
                    ':specs'         => is_array($p['specs'] ?? null) ? json_encode($p['specs'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) : ($p['specs'] ?? '{}'),
                    ':primary_image' => $p['primary_image'] ?? '',
                    ':gallery'       => is_array($p['gallery'] ?? null) ? json_encode($p['gallery'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) : ($p['gallery'] ?? '[]'),
                    ':sort_order'    => (int)($p['sort_order'] ?? ($idx + 1))
                ]);
            }

            $db->commit();
            return true;
        } catch (Throwable $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            return false;
        }
    }

    // ==========================================
    // 3. GALLERY ITEMS (gallery table)
    // ==========================================

    public function getGalleryItems(): array
    {
        try {
            $db = $this->db();
            $stmt = $db->query("SELECT * FROM gallery ORDER BY sort_order ASC, id ASC");
            $rows = $stmt->fetchAll() ?: [];

            $items = [];
            foreach ($rows as $r) {
                $items[] = [
                    'id'            => (int)$r['id'],
                    'title'         => $r['title'],
                    'location'      => $r['location'],
                    'category'      => $r['category'],
                    'category_name' => $r['category_name'],
                    'type'          => $r['type'],
                    'image'         => $r['image'],
                    'youtube_url'   => $r['youtube_url'],
                    'youtube_id'    => $r['youtube_id'],
                    'dimensions'    => $r['dimensions'],
                    'residence_slug'=> $r['residence_slug'],
                    'description'   => $r['description'],
                    'sort_order'    => (int)$r['sort_order'],
                ];
            }
            return $items;
        } catch (Throwable $e) {
            return [];
        }
    }

    public function saveGalleryItems(array $items): bool
    {
        try {
            $db = $this->db();
            $db->beginTransaction();

            // Synchronize deletions
            $existingIds = $db->query("SELECT id FROM gallery")->fetchAll(PDO::FETCH_COLUMN) ?: [];
            $newIds = array_filter(array_column($items, 'id'));
            $toDelete = array_diff($existingIds, $newIds);

            if (!empty($toDelete)) {
                $placeholders = implode(',', array_fill(0, count($toDelete), '?'));
                $delStmt = $db->prepare("DELETE FROM gallery WHERE id IN ($placeholders)");
                $delStmt->execute(array_values($toDelete));
            }

            // Insert / Update
            $stmt = $db->prepare("INSERT INTO gallery (id, title, location, category, category_name, type, image, youtube_url, youtube_id, dimensions, residence_slug, description, sort_order)
                VALUES (:id, :title, :location, :category, :category_name, :type, :image, :youtube_url, :youtube_id, :dimensions, :residence_slug, :description, :sort_order)
                ON DUPLICATE KEY UPDATE
                title = VALUES(title), location = VALUES(location), category = VALUES(category),
                category_name = VALUES(category_name), type = VALUES(type), image = VALUES(image),
                youtube_url = VALUES(youtube_url), youtube_id = VALUES(youtube_id),
                dimensions = VALUES(dimensions), residence_slug = VALUES(residence_slug),
                description = VALUES(description), sort_order = VALUES(sort_order)");

            foreach ($items as $idx => $it) {
                $stmt->execute([
                    ':id'            => !empty($it['id']) ? (int)$it['id'] : null,
                    ':title'         => $it['title'] ?? '',
                    ':location'      => $it['location'] ?? '',
                    ':category'      => $it['category'] ?? 'coastal',
                    ':category_name' => $it['category_name'] ?? 'Coastal Sanctuaries',
                    ':type'          => ($it['type'] ?? 'image') === 'youtube' ? 'youtube' : 'image',
                    ':image'         => $it['image'] ?? '',
                    ':youtube_url'   => $it['youtube_url'] ?? '',
                    ':youtube_id'    => $it['youtube_id'] ?? '',
                    ':dimensions'    => $it['dimensions'] ?? '',
                    ':residence_slug'=> $it['residence_slug'] ?? '',
                    ':description'   => $it['description'] ?? '',
                    ':sort_order'    => (int)($it['sort_order'] ?? ($idx + 1))
                ]);
            }

            $db->commit();
            return true;
        } catch (Throwable $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            return false;
        }
    }

    // ==========================================
    // 4. SEO & TRACKING (seo_settings & seo_integrations)
    // ==========================================

    public function getSeoSettings(): array
    {
        try {
            $db = $this->db();
            $integrations = $db->query("SELECT integration_key, setting_value FROM seo_integrations")->fetchAll(PDO::FETCH_KEY_PAIR) ?: [];
            $pageRows = $db->query("SELECT page_key, title, meta_description, meta_keywords, canonical_url, og_title, og_description, og_image FROM seo_settings")->fetchAll() ?: [];

            $pages = [];
            foreach ($pageRows as $row) {
                $pkey = $row['page_key'];
                unset($row['page_key']);
                $pages[$pkey] = $row;
            }

            return [
                'integrations' => $integrations,
                'pages'        => $pages
            ];
        } catch (Throwable $e) {
            return ['integrations' => [], 'pages' => []];
        }
    }

    public function saveSeoSettings(array $seo): bool
    {
        try {
            $db = $this->db();

            if (!empty($seo['integrations'])) {
                $stmt = $db->prepare("INSERT INTO seo_integrations (integration_key, setting_value) VALUES (:k, :v) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)");
                foreach ($seo['integrations'] as $k => $v) {
                    $stmt->execute([':k' => $k, ':v' => (string)$v]);
                }
            }

            if (!empty($seo['pages'])) {
                $stmt = $db->prepare("INSERT INTO seo_settings (page_key, title, meta_description, meta_keywords, canonical_url, og_title, og_description, og_image)
                    VALUES (:pk, :title, :desc, :keys, :canon, :og_t, :og_d, :og_img)
                    ON DUPLICATE KEY UPDATE
                    title = VALUES(title), meta_description = VALUES(meta_description), meta_keywords = VALUES(meta_keywords),
                    canonical_url = VALUES(canonical_url), og_title = VALUES(og_title), og_description = VALUES(og_description),
                    og_image = VALUES(og_image)");

                foreach ($seo['pages'] as $pk => $pdata) {
                    $stmt->execute([
                        ':pk'     => $pk,
                        ':title'  => $pdata['title'] ?? '',
                        ':desc'   => $pdata['meta_description'] ?? '',
                        ':keys'   => $pdata['meta_keywords'] ?? '',
                        ':canon'  => $pdata['canonical_url'] ?? '',
                        ':og_t'   => $pdata['og_title'] ?? '',
                        ':og_d'   => $pdata['og_description'] ?? '',
                        ':og_img' => $pdata['og_image'] ?? '',
                    ]);
                }
            }

            return true;
        } catch (Throwable $e) {
            return false;
        }
    }

    // ==========================================
    // 5. MAIL SETTINGS (mail_settings table)
    // ==========================================

    public function getMailSettings(): array
    {
        try {
            $db = $this->db();
            $row = $db->query("SELECT * FROM mail_settings WHERE id = 1 LIMIT 1")->fetch();
            if ($row) {
                $row['notify_on_new_inquiry'] = (bool)($row['notify_on_new_inquiry'] ?? true);
                return $row;
            }
        } catch (Throwable $e) {}

        return [
            'driver'                   => 'mail',
            'smtp_host'                => '',
            'smtp_port'                => 587,
            'smtp_encryption'          => 'tls',
            'smtp_username'            => '',
            'smtp_password'            => '',
            'from_email'               => 'concierge@ritiverse.com',
            'from_name'                => 'RiTiVerse Concierge',
            'admin_notification_email' => 'concierge@ritiverse.com',
            'notify_on_new_inquiry'    => true,
        ];
    }

    public function saveMailSettings(array $mail): bool
    {
        try {
            $db = $this->db();
            $stmt = $db->prepare("INSERT INTO mail_settings (id, driver, smtp_host, smtp_port, smtp_encryption, smtp_username, smtp_password, from_email, from_name, admin_notification_email, notify_on_new_inquiry)
                VALUES (1, :driver, :host, :port, :enc, :user, :pass, :from_em, :from_nm, :admin_em, :notify)
                ON DUPLICATE KEY UPDATE
                driver = VALUES(driver), smtp_host = VALUES(smtp_host), smtp_port = VALUES(smtp_port),
                smtp_encryption = VALUES(smtp_encryption), smtp_username = VALUES(smtp_username),
                smtp_password = VALUES(smtp_password), from_email = VALUES(from_email),
                from_name = VALUES(from_name), admin_notification_email = VALUES(admin_notification_email),
                notify_on_new_inquiry = VALUES(notify_on_new_inquiry)");

            return $stmt->execute([
                ':driver'   => $mail['driver'] ?? 'mail',
                ':host'     => $mail['smtp_host'] ?? '',
                ':port'     => (int)($mail['smtp_port'] ?? 587),
                ':enc'      => $mail['smtp_encryption'] ?? 'tls',
                ':user'     => $mail['smtp_username'] ?? '',
                ':pass'     => $mail['smtp_password'] ?? '',
                ':from_em'  => $mail['from_email'] ?? 'concierge@ritiverse.com',
                ':from_nm'  => $mail['from_name'] ?? 'RiTiVerse Concierge',
                ':admin_em' => $mail['admin_notification_email'] ?? 'concierge@ritiverse.com',
                ':notify'   => !empty($mail['notify_on_new_inquiry']) ? 1 : 0
            ]);
        } catch (Throwable $e) {
            return false;
        }
    }

    // ==========================================
    // 6. ADMIN AUTHENTICATION (admins table)
    // ==========================================

    public function getAdminAuth(): array
    {
        try {
            $db = $this->db();
            $row = $db->query("SELECT id, username, name, password_hash, created_at, last_login FROM admins ORDER BY id ASC LIMIT 1")->fetch();
            if ($row) {
                return $row;
            }
        } catch (Throwable $e) {}

        return [
            'id'            => 1,
            'username'      => 'admin@ritiverse.com',
            'name'          => 'RiTiVerse Administrator',
            'password_hash' => password_hash('admin123456', PASSWORD_DEFAULT),
            'created_at'    => date('Y-m-d H:i:s'),
            'last_login'    => null,
        ];
    }

    public function saveAdminAuth(array $admin): bool
    {
        try {
            $db = $this->db();
            if (!empty($admin['id'])) {
                $stmt = $db->prepare("UPDATE admins SET username = :username, name = :name, password_hash = :hash, last_login = :last_login WHERE id = :id");
                return $stmt->execute([
                    ':username'   => $admin['username'],
                    ':name'       => $admin['name'],
                    ':hash'       => $admin['password_hash'],
                    ':last_login' => $admin['last_login'] ?? null,
                    ':id'         => $admin['id']
                ]);
            } else {
                $first = $db->query("SELECT id FROM admins ORDER BY id ASC LIMIT 1")->fetch();
                if ($first) {
                    $stmt = $db->prepare("UPDATE admins SET username = :username, name = :name, password_hash = :hash, last_login = :last_login WHERE id = :id");
                    return $stmt->execute([
                        ':username'   => $admin['username'],
                        ':name'       => $admin['name'],
                        ':hash'       => $admin['password_hash'],
                        ':last_login' => $admin['last_login'] ?? null,
                        ':id'         => $first['id']
                    ]);
                } else {
                    $stmt = $db->prepare("INSERT INTO admins (username, name, password_hash, created_at, last_login) VALUES (:username, :name, :hash, NOW(), :last_login)");
                    return $stmt->execute([
                        ':username'   => $admin['username'],
                        ':name'       => $admin['name'],
                        ':hash'       => $admin['password_hash'],
                        ':last_login' => $admin['last_login'] ?? null
                    ]);
                }
            }
        } catch (Throwable $e) {
            return false;
        }
    }

    // ==========================================
    // 7. CLIENT INQUIRIES (inquiries table)
    // ==========================================

    public function getInquiries(): array
    {
        try {
            $db = $this->db();
            $stmt = $db->query("SELECT id, name, email, phone, residence, budget, profile, consultation_mode, message, ip, created_at as timestamp FROM inquiries ORDER BY created_at DESC");
            return $stmt->fetchAll() ?: [];
        } catch (Throwable $e) {
            return [];
        }
    }

    public function saveInquiry(array $inquiry): bool
    {
        try {
            $db = $this->db();
            $stmt = $db->prepare("INSERT INTO inquiries (id, name, email, phone, residence, budget, profile, consultation_mode, message, ip, created_at)
                VALUES (:id, :name, :email, :phone, :residence, :budget, :profile, :consultation_mode, :message, :ip, NOW())");

            return $stmt->execute([
                ':id'                => $inquiry['id'],
                ':name'              => $inquiry['name'],
                ':email'             => $inquiry['email'],
                ':phone'             => $inquiry['phone'],
                ':residence'         => $inquiry['residence'] ?? null,
                ':budget'            => $inquiry['budget'] ?? null,
                ':profile'           => $inquiry['profile'] ?? null,
                ':consultation_mode' => $inquiry['consultation_mode'] ?? null,
                ':message'           => $inquiry['message'] ?? null,
                ':ip'                => $inquiry['ip'] ?? null
            ]);
        } catch (Throwable $e) {
            return false;
        }
    }

    public function deleteInquiry(string $id): bool
    {
        try {
            $db = $this->db();
            $stmt = $db->prepare("DELETE FROM inquiries WHERE id = :id");
            return $stmt->execute([':id' => $id]);
        } catch (Throwable $e) {
            return false;
        }
    }

    /**
     * Consolidated config array compatible with old config.php structure
     */
    public function getConfig(): array
    {
        $siteData = $this->getSiteData();
        $projects = $this->getProjects();
        $siteData['residences'] = $projects;
        return $siteData;
    }

    // ==========================================
    // 8. IMAGE CONVERSION ENGINE (PNG/JPG -> WEBP)
    // ==========================================

    public function uploadAndConvertToWebp(array $file, string $prefix = 'img', int $quality = 85): array
    {
        if (!isset($file['tmp_name']) || (!is_uploaded_file($file['tmp_name']) && (php_sapi_name() !== 'cli' || !file_exists($file['tmp_name'])))) {
            return ['success' => false, 'error' => 'No valid uploaded file found.'];
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'error' => 'Upload error code: ' . $file['error']];
        }

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'svg'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowedExtensions)) {
            return ['success' => false, 'error' => 'Only JPG, PNG, WEBP and SVG files are supported.'];
        }

        if ($ext === 'svg') {
            $newFileName = $prefix . '_' . uniqid() . '_' . time() . '.svg';
            $destPath = $this->uploadsDir . '/' . $newFileName;
            $moved = is_uploaded_file($file['tmp_name']) ? move_uploaded_file($file['tmp_name'], $destPath) : copy($file['tmp_name'], $destPath);
            if ($moved) {
                return [
                    'success' => true,
                    'path'    => 'assets/uploads/' . $newFileName,
                    'name'    => $newFileName
                ];
            }
            return ['success' => false, 'error' => 'Failed to save SVG file.'];
        }

        if (!function_exists('imagewebp')) {
            return ['success' => false, 'error' => 'PHP GD extension with WebP support is required on server.'];
        }

        $image = null;
        $info = @getimagesize($file['tmp_name']);
        if (!$info) {
            return ['success' => false, 'error' => 'Invalid image format.'];
        }

        $mime = $info['mime'];

        switch ($mime) {
            case 'image/jpeg':
            case 'image/jpg':
                $image = @imagecreatefromjpeg($file['tmp_name']);
                break;
            case 'image/png':
                $image = @imagecreatefrompng($file['tmp_name']);
                if ($image) {
                    imagepalettetotruecolor($image);
                    imagealphablending($image, true);
                    imagesavealpha($image, true);
                }
                break;
            case 'image/webp':
                $image = @imagecreatefromwebp($file['tmp_name']);
                break;
            default:
                return ['success' => false, 'error' => 'Unsupported image MIME type: ' . $mime];
        }

        if (!$image) {
            return ['success' => false, 'error' => 'Failed to read image content for conversion.'];
        }

        $newFileName = $prefix . '_' . uniqid() . '_' . time() . '.webp';
        $destPath = $this->uploadsDir . '/' . $newFileName;

        $saved = imagewebp($image, $destPath, $quality);
        if (function_exists('imagedestroy')) {
            @imagedestroy($image);
        }

        if (!$saved || !file_exists($destPath)) {
            return ['success' => false, 'error' => 'Failed to write converted .webp file to disk.'];
        }

        return [
            'success' => true,
            'path'    => 'assets/uploads/' . $newFileName,
            'name'    => $newFileName
        ];
    }

    public function fetchYoutubeThumbnailAsWebp(string $youtubeId, string $prefix = 'yt_thumb'): ?string
    {
        $urls = [
            "https://img.youtube.com/vi/{$youtubeId}/maxresdefault.jpg",
            "https://img.youtube.com/vi/{$youtubeId}/sddefault.jpg",
            "https://img.youtube.com/vi/{$youtubeId}/hqdefault.jpg",
        ];

        $rawImage = null;
        foreach ($urls as $url) {
            $ctx = stream_context_create([
                'http' => [
                    'timeout' => 5,
                    'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)'
                ]
            ]);
            $data = @file_get_contents($url, false, $ctx);
            if ($data && strlen($data) > 1000) {
                $rawImage = $data;
                break;
            }
        }

        if (!$rawImage) {
            return null;
        }

        $tmpFile = tempnam(sys_get_temp_dir(), 'yt_') . '.jpg';
        file_put_contents($tmpFile, $rawImage);

        $image = @imagecreatefromjpeg($tmpFile);
        @unlink($tmpFile);

        if (!$image) {
            return null;
        }

        $newFileName = $prefix . '_' . $youtubeId . '_' . time() . '.webp';
        $destPath = $this->uploadsDir . '/' . $newFileName;

        if (imagewebp($image, $destPath, 85)) {
            imagedestroy($image);
            return 'assets/uploads/' . $newFileName;
        }

        imagedestroy($image);
        return null;
    }

    public function extractYoutubeId(string $url): string
    {
        $url = trim($url);
        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i', $url, $match)) {
            return $match[1];
        }
        if (strlen($url) === 11 && !str_contains($url, '/')) {
            return $url;
        }
        return '';
    }

    // ==========================================
    // 9. SEO DYNAMIC RENDERING
    // ==========================================

    public function renderHead(string $pageKey = 'index'): string
    {
        $seo = $this->getSeoSettings();
        $site = $this->getSiteData()['site'] ?? [];
        $page = $seo['pages'][$pageKey] ?? ($seo['pages']['index'] ?? []);
        $integrations = $seo['integrations'] ?? [];

        $title = !empty($page['title']) ? $page['title'] : (($site['name'] ?? 'RiTiVerse') . ' - ' . ($site['tagline'] ?? 'Luxury Real Estate'));
        $description = !empty($page['meta_description']) ? $page['meta_description'] : ($site['description'] ?? '');
        $keywords = $page['meta_keywords'] ?? '';
        $canonical = $page['canonical_url'] ?? '';
        $ogTitle = !empty($page['og_title']) ? $page['og_title'] : $title;
        $ogDesc = !empty($page['og_description']) ? $page['og_description'] : $description;
        $ogImage = !empty($page['og_image']) ? $page['og_image'] : ($site['og_image'] ?? 'assets/hero.1vkt8ape_254z.webp');
        $favicon = $site['favicon'] ?? 'assets/ritiverse-logo-white.svg';

        $html = "<!-- Dynamic SEO & Meta Tags generated by RiTiVerse Database -->\n";
        $html .= '<title>' . htmlspecialchars($title) . "</title>\n";
        $html .= '<meta name="description" content="' . htmlspecialchars($description) . "\">\n";
        if (!empty($keywords)) {
            $html .= '<meta name="keywords" content="' . htmlspecialchars($keywords) . "\">\n";
        }
        if (!empty($canonical)) {
            $html .= '<link rel="canonical" href="' . htmlspecialchars($canonical) . "\">\n";
        }

        $favExt = strtolower(pathinfo($favicon, PATHINFO_EXTENSION));
        $favMime = $favExt === 'svg' ? 'image/svg+xml' : ($favExt === 'webp' ? 'image/webp' : 'image/png');
        $html .= '<link rel="icon" type="' . $favMime . '" href="' . htmlspecialchars($favicon) . "\">\n";

        $html .= '<meta property="og:title" content="' . htmlspecialchars($ogTitle) . "\">\n";
        $html .= '<meta property="og:description" content="' . htmlspecialchars($ogDesc) . "\">\n";
        $html .= '<meta property="og:type" content="website">' . "\n";
        $html .= '<meta property="og:image" content="' . htmlspecialchars($ogImage) . "\">\n";
        $html .= '<meta name="twitter:card" content="summary_large_image">' . "\n";
        $html .= '<meta name="twitter:title" content="' . htmlspecialchars($ogTitle) . "\">\n";
        $html .= '<meta name="twitter:description" content="' . htmlspecialchars($ogDesc) . "\">\n";
        $html .= '<meta name="twitter:image" content="' . htmlspecialchars($ogImage) . "\">\n";

        if (!empty($integrations['google_search_console'])) {
            $gsc = trim($integrations['google_search_console']);
            if (str_starts_with($gsc, '<meta')) {
                $html .= $gsc . "\n";
            } else {
                $html .= '<meta name="google-site-verification" content="' . htmlspecialchars($gsc) . "\">\n";
            }
        }

        if (!empty($integrations['bing_webmaster_code'])) {
            $bing = trim($integrations['bing_webmaster_code']);
            if (str_starts_with($bing, '<meta')) {
                $html .= $bing . "\n";
            } else {
                $html .= '<meta name="msvalidate.01" content="' . htmlspecialchars($bing) . "\">\n";
            }
        }

        if (!empty($integrations['google_analytics_id'])) {
            $gaId = htmlspecialchars(trim($integrations['google_analytics_id']));
            $html .= "<!-- Google tag (gtag.js) -->\n";
            $html .= '<script async src="https://www.googletagmanager.com/gtag/js?id=' . $gaId . '"></script>' . "\n";
            $html .= "<script>\n";
            $html .= "  window.dataLayer = window.dataLayer || [];\n";
            $html .= "  function gtag(){dataLayer.push(arguments);}\n";
            $html .= "  gtag('js', new Date());\n";
            $html .= "  gtag('config', '" . $gaId . "');\n";
            $html .= "</script>\n";
        } elseif (!empty($integrations['google_analytics_code'])) {
            $html .= $integrations['google_analytics_code'] . "\n";
        }

        if (!empty($integrations['meta_pixel_id'])) {
            $pixelId = htmlspecialchars(trim($integrations['meta_pixel_id']));
            $html .= "<!-- Meta Pixel Code -->\n";
            $html .= "<script>\n";
            $html .= "!function(f,b,e,v,n,t,s)\n";
            $html .= "{if(f.fbq)return;n=f.fbq=function(){n.callMethod?\n";
            $html .= "n.callMethod.apply(n,arguments):n.queue.push(arguments)};\n";
            $html .= "if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';\n";
            $html .= "n.queue=[];t=b.createElement(e);t.async=!0;\n";
            $html .= "t.src=v;s=b.getElementsByTagName(e)[0];\n";
            $html .= "s.parentNode.insertBefore(t,s)}(window, document,'script',\n";
            $html .= "'https://connect.facebook.net/en_US/fbevents.js');\n";
            $html .= "fbq('init', '" . $pixelId . "');\n";
            $html .= "fbq('track', 'PageView');\n";
            $html .= "</script>\n";
            $html .= '<noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=' . $pixelId . '&ev=PageView&noscript=1"/></noscript>' . "\n";
            $html .= "<!-- End Meta Pixel Code -->\n";
        } elseif (!empty($integrations['meta_pixel_code'])) {
            $html .= $integrations['meta_pixel_code'] . "\n";
        }

        if (!empty($integrations['custom_head_code'])) {
            $html .= "<!-- Custom Head Code Injection -->\n" . $integrations['custom_head_code'] . "\n";
        }

        return $html;
    }

    public function renderFooterScripts(): string
    {
        $seo = $this->getSeoSettings();
        $code = $seo['integrations']['custom_body_code'] ?? '';
        return !empty($code) ? ("<!-- Custom Body Code Injection -->\n" . $code . "\n") : '';
    }

    // ==========================================
    // 10. EMAIL SENDER & SMTP ENGINE
    // ==========================================

    public function sendEmail(string $to, string $subject, string $bodyHtml, ?string $replyTo = null): array
    {
        $mailSettings = $this->getMailSettings();
        $driver = $mailSettings['driver'] ?? 'mail';
        $fromEmail = !empty($mailSettings['from_email']) ? $mailSettings['from_email'] : 'concierge@ritiverse.com';
        $fromName = !empty($mailSettings['from_name']) ? $mailSettings['from_name'] : 'RiTiVerse Concierge';

        if ($driver === 'smtp') {
            return $this->sendViaSmtp($to, $subject, $bodyHtml, $fromEmail, $fromName, $replyTo, $mailSettings);
        }

        $headers = [];
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-type: text/html; charset=UTF-8';
        $headers[] = 'From: ' . $fromName . ' <' . $fromEmail . '>';
        if ($replyTo) {
            $headers[] = 'Reply-To: ' . $replyTo;
        }
        $headers[] = 'X-Mailer: PHP/' . phpversion();

        $sent = @mail($to, $subject, $bodyHtml, implode("\r\n", $headers));
        if ($sent) {
            return ['success' => true, 'log' => 'Dispatched successfully via PHP native mail() function.', 'error' => ''];
        }
        return ['success' => false, 'log' => 'PHP mail() function returned false. Check server mail logs.', 'error' => 'Mail delivery failed.'];
    }

    private function sendViaSmtp(string $to, string $subject, string $bodyHtml, string $fromEmail, string $fromName, ?string $replyTo, array $settings): array
    {
        $host = trim($settings['smtp_host'] ?? '');
        $port = (int)($settings['smtp_port'] ?? 587);
        $encryption = strtolower($settings['smtp_encryption'] ?? 'tls');
        $username = trim($settings['smtp_username'] ?? '');
        $password = $settings['smtp_password'] ?? '';

        $log = [];
        $log[] = "Connecting to SMTP server: {$host}:{$port} (encryption: {$encryption})...";

        if (empty($host)) {
            return ['success' => false, 'log' => implode("\n", $log), 'error' => 'SMTP Host is not configured.'];
        }

        $remoteSocket = ($encryption === 'ssl' ? 'ssl://' : '') . $host . ':' . $port;
        $timeout = 15;
        $socket = @stream_socket_client($remoteSocket, $errno, $errstr, $timeout, STREAM_CLIENT_CONNECT);

        if (!$socket) {
            $log[] = "Connection failed: $errstr ($errno)";
            return ['success' => false, 'log' => implode("\n", $log), 'error' => "Cannot connect to SMTP host {$host}:{$port} ($errstr)"];
        }

        stream_set_timeout($socket, $timeout);

        $read = function() use ($socket, &$log): string {
            $response = '';
            while ($line = fgets($socket, 515)) {
                $response .= $line;
                if (isset($line[3]) && $line[3] === ' ') {
                    break;
                }
            }
            $log[] = '< ' . trim($response);
            return $response;
        };

        $write = function(string $cmd) use ($socket, &$log) {
            $log[] = '> ' . (str_starts_with($cmd, 'AUTH') ? 'AUTH [redacted]' : $cmd);
            fputs($socket, $cmd . "\r\n");
        };

        $resp = $read();
        if (!str_starts_with($resp, '220')) {
            fclose($socket);
            return ['success' => false, 'log' => implode("\n", $log), 'error' => 'Server did not respond with 220 greeting.'];
        }

        $write('EHLO ' . ($_SERVER['SERVER_NAME'] ?? 'localhost'));
        $resp = $read();

        if ($encryption === 'tls') {
            $write('STARTTLS');
            $resp = $read();
            if (str_starts_with($resp, '220')) {
                $crypto = stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT | STREAM_CRYPTO_METHOD_TLSv1_3_CLIENT);
                if (!$crypto) {
                    fclose($socket);
                    return ['success' => false, 'log' => implode("\n", $log), 'error' => 'TLS handshake failed.'];
                }
                $log[] = "TLS handshake established successfully.";
                $write('EHLO ' . ($_SERVER['SERVER_NAME'] ?? 'localhost'));
                $read();
            }
        }

        if (!empty($username)) {
            $write('AUTH LOGIN');
            $resp = $read();
            if (!str_starts_with($resp, '334')) {
                fclose($socket);
                return ['success' => false, 'log' => implode("\n", $log), 'error' => 'AUTH LOGIN command rejected: ' . $resp];
            }

            $write(base64_encode($username));
            $resp = $read();
            if (!str_starts_with($resp, '334')) {
                fclose($socket);
                return ['success' => false, 'log' => implode("\n", $log), 'error' => 'Username rejected by SMTP server.'];
            }

            $write(base64_encode($password));
            $resp = $read();
            if (!str_starts_with($resp, '235')) {
                fclose($socket);
                return ['success' => false, 'log' => implode("\n", $log), 'error' => 'Authentication failed. Please check SMTP username/password.'];
            }
        }

        $write("MAIL FROM:<{$fromEmail}>");
        $resp = $read();
        if (!str_starts_with($resp, '250')) {
            fclose($socket);
            return ['success' => false, 'log' => implode("\n", $log), 'error' => 'MAIL FROM rejected: ' . $resp];
        }

        $write("RCPT TO:<{$to}>");
        $resp = $read();
        if (!str_starts_with($resp, '250')) {
            fclose($socket);
            return ['success' => false, 'log' => implode("\n", $log), 'error' => 'Recipient rejected: ' . $resp];
        }

        $write('DATA');
        $resp = $read();
        if (!str_starts_with($resp, '354')) {
            fclose($socket);
            return ['success' => false, 'log' => implode("\n", $log), 'error' => 'DATA command rejected: ' . $resp];
        }

        $headers = [];
        $headers[] = "Date: " . date('r');
        $headers[] = "To: <{$to}>";
        $headers[] = "From: =?UTF-8?B?" . base64_encode($fromName) . "?= <{$fromEmail}>";
        if ($replyTo) {
            $headers[] = "Reply-To: <{$replyTo}>";
        }
        $headers[] = "Subject: =?UTF-8?B?" . base64_encode($subject) . "?=";
        $headers[] = "MIME-Version: 1.0";
        $headers[] = "Content-Type: text/html; charset=UTF-8";
        $headers[] = "Content-Transfer-Encoding: base64";

        $message = implode("\r\n", $headers) . "\r\n\r\n" . chunk_split(base64_encode($bodyHtml));
        $write($message . "\r\n.");

        $resp = $read();
        $write('QUIT');
        $read();
        fclose($socket);

        if (str_starts_with($resp, '250')) {
            $log[] = "Message accepted for delivery: " . trim($resp);
            return ['success' => true, 'log' => implode("\n", $log), 'error' => ''];
        }

        return ['success' => false, 'log' => implode("\n", $log), 'error' => 'Server did not accept message: ' . $resp];
    }

    public function notifyAdminNewInquiry(array $inquiry): void
    {
        $settings = $this->getMailSettings();
        if (empty($settings['notify_on_new_inquiry'])) {
            return;
        }

        $adminEmail = !empty($settings['admin_notification_email']) ? $settings['admin_notification_email'] : 'concierge@ritiverse.com';
        $subject = 'New Acquisition Inquiry: ' . ($inquiry['name'] ?? 'Client') . ' [' . ($inquiry['id'] ?? 'REF') . ']';

        $body = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; background: #141312; color: #ffffff; padding: 25px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.1);'>
            <div style='text-align: center; margin-bottom: 25px;'>
                <h2 style='color: #CA8216; margin: 0; font-size: 24px; text-transform: uppercase; letter-spacing: 2px;'>RiTiVerse</h2>
                <p style='color: #aaaaaa; margin: 5px 0 0; font-size: 13px;'>Private Client Advisory & Acquisitions Mandate</p>
            </div>
            <div style='background: #1e1d1c; padding: 20px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.08);'>
                <p><strong>Reference ID:</strong> <span style='color: #CA8216;'>" . htmlspecialchars($inquiry['id'] ?? '') . "</span></p>
                <p><strong>Client Name:</strong> " . htmlspecialchars($inquiry['name'] ?? '') . "</p>
                <p><strong>Email:</strong> <a href='mailto:" . htmlspecialchars($inquiry['email'] ?? '') . "' style='color: #CA8216;'>" . htmlspecialchars($inquiry['email'] ?? '') . "</a></p>
                <p><strong>Phone:</strong> <a href='tel:" . htmlspecialchars($inquiry['phone'] ?? '') . "' style='color: #ffffff;'>" . htmlspecialchars($inquiry['phone'] ?? '') . "</a></p>
                <p><strong>Residence Interest:</strong> " . htmlspecialchars($inquiry['residence'] ?? 'General Advisory') . "</p>
                <p><strong>Target Budget:</strong> " . htmlspecialchars($inquiry['budget'] ?? 'Not specified') . "</p>
                <p><strong>Buyer Profile:</strong> " . htmlspecialchars($inquiry['profile'] ?? 'Not specified') . "</p>
                <p><strong>Consultation Mode:</strong> " . htmlspecialchars($inquiry['consultation_mode'] ?? 'Direct Call') . "</p>
                <p><strong>Client Notes:</strong><br><em style='color: #dddddd;'>" . nl2br(htmlspecialchars($inquiry['message'] ?? 'None provided')) . "</em></p>
                <p style='font-size: 11px; color: #777777; margin-top: 20px;'>Submitted at " . htmlspecialchars($inquiry['timestamp'] ?? date('c')) . " from IP " . htmlspecialchars($inquiry['ip'] ?? '127.0.0.1') . "</p>
            </div>
        </div>";

        $this->sendEmail($adminEmail, $subject, $body, $inquiry['email'] ?? null);
    }
}

return DataStore::getInstance();
