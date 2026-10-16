<?php
require_once __DIR__ . '/auth.php';
requireAuth();

$pageTitle = 'Gallery & Video Tour Manager';
$currentPage = 'gallery.php';

$galleryItems = $dataStore->getGalleryItems();
$projects = $dataStore->getProjects();
$action = $_GET['action'] ?? 'list';
$editId = isset($_GET['id']) ? (int)$_GET['id'] : null;
$errorMsg = '';

// Find item if editing
$currentItem = null;
if ($action === 'edit' && $editId !== null) {
    foreach ($galleryItems as $item) {
        if ((int)($item['id'] ?? 0) === $editId) {
            $currentItem = $item;
            break;
        }
    }
    if (!$currentItem) {
        setFlash('danger', 'Gallery item not found.');
        header('Location: gallery.php');
        exit;
    }
}

// Handle Form Submissions (Create, Update, Delete)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf = $_POST['csrf_token'] ?? '';
    $formAction = $_POST['form_action'] ?? '';

    if (!verifyCsrfToken($csrf)) {
        $errorMsg = 'Security token invalid or expired. Please reload and try again.';
    } else {
        // DELETE ITEM
        if ($formAction === 'delete') {
            $delId = (int)($_POST['item_id'] ?? 0);
            $filtered = array_values(array_filter($galleryItems, fn($it) => (int)($it['id'] ?? 0) !== $delId));
            $dataStore->saveGalleryItems($filtered);
            setFlash('success', 'Gallery item removed successfully.');
            header('Location: gallery.php');
            exit;
        }

        // SAVE ITEM (CREATE OR EDIT)
        if ($formAction === 'save') {
            $isEditing = !empty($_POST['original_id']);
            $id = $isEditing ? (int)$_POST['original_id'] : (count($galleryItems) > 0 ? max(array_column($galleryItems, 'id')) + 1 : 1);

            $type = trim($_POST['media_type'] ?? 'image'); // 'image' or 'youtube'
            $title = trim($_POST['title'] ?? '');
            $location = trim($_POST['location'] ?? '');
            $category = trim($_POST['category'] ?? 'coastal');
            $categoryName = trim($_POST['category_name'] ?? '');
            if (empty($categoryName)) {
                $categoriesMap = [
                    'coastal'    => 'Coastal Sanctuaries',
                    'penthouses' => 'Sky Penthouses',
                    'interiors'  => 'Bespoke Interiors',
                    'amenities'  => 'Signature Amenities',
                    'video'      => 'Cinematic Tours'
                ];
                $categoryName = $categoriesMap[$category] ?? ucfirst($category);
            }

            $dimensions = trim($_POST['dimensions'] ?? '');
            $residenceSlug = trim($_POST['residence_slug'] ?? 'worli-seaface');
            $description = trim($_POST['description'] ?? '');

            $youtubeUrl = trim($_POST['youtube_url'] ?? '');
            $youtubeId = '';
            if ($type === 'youtube' && !empty($youtubeUrl)) {
                $youtubeId = $dataStore->extractYoutubeId($youtubeUrl);
            }

            // Image handling (Cover or Photo)
            $imagePath = $isEditing ? ($currentItem['image'] ?? '') : '';

            // If new file uploaded: convert to .webp
            if (!empty($_FILES['gallery_file']['name'])) {
                $res = $dataStore->uploadAndConvertToWebp($_FILES['gallery_file'], 'gallery');
                if ($res['success']) {
                    $imagePath = $res['path'];
                } else {
                    $errorMsg .= ' Media Upload: ' . $res['error'];
                }
            } elseif ($type === 'youtube' && !empty($youtubeId) && empty($imagePath)) {
                // Auto fetch YouTube maxres thumbnail as WebP
                $thumbWebp = $dataStore->fetchYoutubeThumbnailAsWebp($youtubeId);
                if ($thumbWebp) {
                    $imagePath = $thumbWebp;
                }
            }

            if (empty($imagePath)) {
                $imagePath = 'assets/project-1-new.0u9fesgwq9skw.webp';
            }

            if (empty($errorMsg)) {
                $newItem = [
                    'id'             => $id,
                    'title'          => $title,
                    'location'       => $location,
                    'category'       => $category,
                    'category_name'  => $categoryName,
                    'type'           => $type,
                    'image'          => $imagePath,
                    'youtube_url'    => $youtubeUrl,
                    'youtube_id'     => $youtubeId,
                    'dimensions'     => $dimensions,
                    'residence_slug' => $residenceSlug,
                    'description'    => $description
                ];

                if ($isEditing) {
                    foreach ($galleryItems as $k => $it) {
                        if ((int)($it['id'] ?? 0) === $id) {
                            $galleryItems[$k] = $newItem;
                            break;
                        }
                    }
                } else {
                    $galleryItems[] = $newItem;
                }

                $dataStore->saveGalleryItems($galleryItems);
                setFlash('success', 'Gallery work "' . htmlspecialchars($title) . '" saved successfully with WebP image.');
                header('Location: gallery.php');
                exit;
            }
        }
    }
}

// Category counts
$categories = array_unique(array_column($galleryItems, 'category'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($pageTitle) ?> | RiTiVerse Admin</title>
    <link rel="icon" type="image/svg+xml" href="../assets/ritiverse-logo-white.svg">
    <link rel="stylesheet" href="css/admin.css">
    <style>
        .media-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.2rem 0.6rem;
            border-radius: 9999px;
            font-size: 0.72rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        .media-badge-photo {
            background: rgba(59, 130, 246, 0.15);
            color: #60a5fa;
            border: 1px solid rgba(59, 130, 246, 0.3);
        }
        .media-badge-youtube {
            background: rgba(239, 68, 68, 0.15);
            color: #f87171;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }
    </style>
</head>
<body>

    <?php require __DIR__ . '/includes/sidebar.php'; ?>

    <div class="admin-main">
        <?php require __DIR__ . '/includes/topbar.php'; ?>

        <main class="admin-content">

            <?php if (!empty($errorMsg)): ?>
                <div class="alert alert-danger">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <span><?= htmlspecialchars($errorMsg) ?></span>
                </div>
            <?php endif; ?>

            <?php if ($action === 'list'): ?>
                <!-- GALLERY LIST VIEW -->
                <div class="page-header">
                    <div>
                        <h2>Curated Architectural Gallery & Films</h2>
                        <p>Manage high-resolution photography and YouTube video walkthroughs on gallery.php</p>
                    </div>
                    <a href="gallery.php?action=new" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Add Media Item
                    </a>
                </div>

                <div class="panel">
                    <div class="table-responsive">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th style="width: 90px;">Thumbnail</th>
                                    <th>Media Work Title</th>
                                    <th>Format</th>
                                    <th>Category</th>
                                    <th>Location</th>
                                    <th>Dimensions / Tag</th>
                                    <th style="text-align:right;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($galleryItems)): ?>
                                    <tr>
                                        <td colspan="7" style="text-align:center; padding:3rem; color:var(--text-dim);">
                                            No gallery works yet. Click "Add Media Item" to create one.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($galleryItems as $item): 
                                        $isYt = ($item['type'] ?? '') === 'youtube';
                                    ?>
                                        <tr>
                                            <td>
                                                <div style="position:relative; width:80px; height:58px; border-radius:8px; overflow:hidden; border:1px solid var(--border-color);">
                                                    <img src="../<?= htmlspecialchars($item['image']) ?>" alt="" style="width:100%; height:100%; object-fit:cover;">
                                                    <?php if ($isYt): ?>
                                                        <div style="position:absolute; inset:0; background:rgba(0,0,0,0.4); display:flex; align-items:center; justify-content:center;">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="#ef4444" stroke="#ef4444"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td>
                                                <strong style="font-size:0.95rem; color:var(--text-main);"><?= htmlspecialchars($item['title']) ?></strong>
                                                <?php if ($isYt && !empty($item['youtube_id'])): ?>
                                                    <div style="font-size:0.75rem; color:var(--text-dim); margin-top:0.2rem;">
                                                        YouTube ID: <code><?= htmlspecialchars($item['youtube_id']) ?></code>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($isYt): ?>
                                                    <span class="media-badge media-badge-youtube">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                                                        YouTube Video
                                                    </span>
                                                <?php else: ?>
                                                    <span class="media-badge media-badge-photo">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/></svg>
                                                        Photo (.webp)
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span style="font-size:0.82rem; color:var(--accent-gold); background:var(--accent-gold-light); padding:0.2rem 0.6rem; border-radius:9999px;">
                                                    <?= htmlspecialchars($item['category_name']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div style="font-size:0.85rem; color:var(--text-main);"><?= htmlspecialchars($item['location']) ?></div>
                                            </td>
                                            <td>
                                                <small style="color:var(--text-muted);"><?= htmlspecialchars($item['dimensions'] ?? '') ?></small>
                                            </td>
                                            <td style="text-align:right;">
                                                <div style="display:inline-flex; gap:0.5rem;">
                                                    <a href="gallery.php?action=edit&id=<?= (int)$item['id'] ?>" class="btn btn-secondary btn-sm" title="Edit">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                                                        Edit
                                                    </a>
                                                    <button type="button" onclick="confirmDeleteGallery(<?= (int)$item['id'] ?>, '<?= htmlspecialchars(addslashes($item['title'])) ?>')" class="btn btn-danger btn-sm" title="Delete">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            <?php elseif ($action === 'new' || $action === 'edit'): 
                $isYtMode = ($currentItem['type'] ?? '') === 'youtube';
            ?>
                <!-- ADD / EDIT GALLERY ITEM VIEW -->
                <div class="page-header">
                    <div>
                        <h2><?= $action === 'edit' ? 'Edit Gallery Work: ' . htmlspecialchars($currentItem['title']) : 'Add Architectural Photo or YouTube Video' ?></h2>
                        <p>Upload photography or embed YouTube walkthrough tours with full interactive lightbox playback.</p>
                    </div>
                    <a href="gallery.php" class="btn btn-secondary">
                        &larr; Back to Gallery
                    </a>
                </div>

                <form method="POST" action="gallery.php" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                    <input type="hidden" name="form_action" value="save">
                    <?php if ($action === 'edit'): ?>
                        <input type="hidden" name="original_id" value="<?= (int)$currentItem['id'] ?>">
                    <?php endif; ?>

                    <div class="panel">
                        <div class="panel-header">
                            <div>
                                <h3>Media Format Selection</h3>
                                <p>Choose whether this gallery entry is a still photograph or a YouTube video walkthrough</p>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label class="form-label">Media Type</label>
                                <div style="display:flex; gap:1.5rem; align-items:center;">
                                    <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer; color:var(--text-main); font-size:0.95rem;">
                                        <input type="radio" name="media_type" value="image" <?= !$isYtMode ? 'checked' : '' ?> onchange="toggleMediaType('image')">
                                        Architectural Photography (Image)
                                    </label>
                                    <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer; color:var(--text-main); font-size:0.95rem;">
                                        <input type="radio" name="media_type" value="youtube" <?= $isYtMode ? 'checked' : '' ?> onchange="toggleMediaType('youtube')">
                                        YouTube Video Tour (Player)
                                    </label>
                                </div>
                            </div>

                            <!-- YouTube URL Input Container -->
                            <div id="youtubeFields" style="display: <?= $isYtMode ? 'block' : 'none' ?>; margin-top:1.5rem; background:rgba(239, 68, 68, 0.05); border:1px solid rgba(239, 68, 68, 0.2); border-radius:1rem; padding:1.5rem;">
                                <div class="form-group">
                                    <label class="form-label" for="youtubeUrlInput">
                                        YouTube Video URL or Video ID <span class="required">*</span>
                                    </label>
                                    <input type="text" id="youtubeUrlInput" name="youtube_url" class="form-control" value="<?= htmlspecialchars($currentItem['youtube_url'] ?? '') ?>" placeholder="https://www.youtube.com/watch?v=dQw4w9WgXcQ or https://youtu.be/...">
                                    <div class="form-hint">Paste full YouTube link. The system extracts the video ID and auto-fetches thumbnail converted to .webp!</div>
                                </div>

                                <div id="youtubePreviewBox" style="display: <?= !empty($currentItem['youtube_id']) ? 'block' : 'none' ?>; margin-top:1rem;">
                                    <label class="form-label">Live YouTube Player Preview</label>
                                    <div style="position:relative; padding-top:56.25%; border-radius:8px; overflow:hidden; background:#000;">
                                        <iframe id="youtubePreviewIframe" src="<?= !empty($currentItem['youtube_id']) ? 'https://www.youtube.com/embed/' . htmlspecialchars($currentItem['youtube_id']) : '' ?>" style="position:absolute; inset:0; width:100%; height:100%; border:none;" allowfullscreen></iframe>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Work Details -->
                    <div class="panel">
                        <div class="panel-header">
                            <div>
                                <h3>Work Details & Categorization</h3>
                                <p>Perspective title, collection filter category, and architectural notes</p>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label" for="title">Title <span class="required">*</span></label>
                                    <input type="text" id="title" name="title" class="form-control" value="<?= htmlspecialchars($currentItem['title'] ?? '') ?>" placeholder="e.g. Minimalist Teak & Marble Reception" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="location">Location <span class="required">*</span></label>
                                    <input type="text" id="location" name="location" class="form-control" value="<?= htmlspecialchars($currentItem['location'] ?? '') ?>" placeholder="e.g. Worli Sea Face, Mumbai" required>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label" for="category">Filter Category Key <span class="required">*</span></label>
                                    <select id="category" name="category" class="form-control" onchange="syncCategoryName(this.value)">
                                        <option value="coastal" <?= ($currentItem['category'] ?? '') === 'coastal' ? 'selected' : '' ?>>coastal (Coastal Sanctuaries)</option>
                                        <option value="penthouses" <?= ($currentItem['category'] ?? '') === 'penthouses' ? 'selected' : '' ?>>penthouses (Sky Penthouses)</option>
                                        <option value="interiors" <?= ($currentItem['category'] ?? '') === 'interiors' ? 'selected' : '' ?>>interiors (Bespoke Interiors)</option>
                                        <option value="amenities" <?= ($currentItem['category'] ?? '') === 'amenities' ? 'selected' : '' ?>>amenities (Signature Amenities)</option>
                                        <option value="video" <?= ($currentItem['category'] ?? '') === 'video' ? 'selected' : '' ?>>video (Cinematic Tours)</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="category_name">Category Display Badge</label>
                                    <input type="text" id="category_name" name="category_name" class="form-control" value="<?= htmlspecialchars($currentItem['category_name'] ?? 'Coastal Sanctuaries') ?>" placeholder="e.g. Coastal Sanctuaries">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label" for="dimensions">Dimensions / Tag Info</label>
                                    <input type="text" id="dimensions" name="dimensions" class="form-control" value="<?= htmlspecialchars($currentItem['dimensions'] ?? '') ?>" placeholder="e.g. 11,400 Sq. Ft. or 4K Ultra HD Tour">
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="residence_slug">Linked Residence (Inquiry routing)</label>
                                    <select id="residence_slug" name="residence_slug" class="form-control">
                                        <?php foreach ($projects as $p): ?>
                                            <option value="<?= htmlspecialchars($p['id']) ?>" <?= ($currentItem['residence_slug'] ?? '') === $p['id'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($p['title']) ?> (<?= htmlspecialchars($p['id']) ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="description">Perspective Description / Lightbox Caption</label>
                                <textarea id="description" name="description" class="form-control" rows="3" placeholder="Describe the materials, craftsmanship and horizons..."><?= htmlspecialchars($currentItem['description'] ?? '') ?></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Image / Cover Upload Panel (Auto-WebP) -->
                    <div class="panel">
                        <div class="panel-header">
                            <div>
                                <h3 id="imagePanelTitle"><?= $isYtMode ? 'Custom Video Thumbnail Cover' : 'Image Photo (Auto-WebP Converter)' ?></h3>
                                <p>Upload PNG, JPG, or WEBP &mdash; our engine automatically converts it to lightweight .webp.</p>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label class="form-label">Current Photo / Thumbnail</label>
                                <?php if (!empty($currentItem['image'])): ?>
                                    <div style="display:flex; align-items:center; gap:1rem; margin-bottom: 0.85rem;">
                                        <img src="../<?= htmlspecialchars($currentItem['image']) ?>" class="preview-target" alt="Current Image" style="width:140px; height:95px; object-fit:cover; border-radius:8px; border:1px solid var(--border-color);">
                                        <div>
                                            <span style="font-size:0.8rem; color:var(--text-muted);">Current File (.webp)</span>
                                            <div class="webp-badge">Optimized WebP</div>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <img src="" class="preview-target" alt="Preview" style="display:none; width:140px; height:95px; object-fit:cover; border-radius:8px; margin-bottom:0.85rem;">
                                <?php endif; ?>

                                <div class="dropzone">
                                    <input type="file" name="gallery_file" accept="image/png, image/jpeg, image/webp" <?= ($action === 'new' && !$isYtMode) ? 'required' : '' ?>>
                                    <div class="dropzone-text">Click or drag photo here</div>
                                    <div class="dropzone-sub">PNG, JPG, or WEBP &bull; Converts to high-quality .webp</div>
                                </div>
                                <div class="form-hint" id="ytThumbNote" style="display:<?= $isYtMode ? 'block' : 'none' ?>; margin-top:0.5rem;">
                                    Tip: For YouTube videos, if you leave this empty, the system will auto-download YouTube's high-res cover and convert it to .webp!
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <a href="gallery.php" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                                Save Gallery Work
                            </button>
                        </div>
                    </div>
                </form>

            <?php endif; ?>

        </main>

        <?php require __DIR__ . '/includes/footer.php'; ?>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteGalleryModal" class="modal-overlay">
        <div class="modal-dialog">
            <div class="modal-header">
                <h3>Confirm Media Removal</h3>
                <button type="button" class="modal-close" onclick="closeModal('deleteGalleryModal')">&times;</button>
            </div>
            <form method="POST" action="gallery.php">
                <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                <input type="hidden" name="form_action" value="delete">
                <input type="hidden" name="item_id" id="deleteGalleryId" value="">

                <div class="modal-body">
                    <p>Are you sure you want to remove <strong id="deleteGalleryTitle" style="color:var(--accent-gold);"></strong> from the gallery?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('deleteGalleryModal')">Cancel</button>
                    <button type="submit" class="btn btn-danger">Confirm Delete</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleMediaType(type) {
            const ytBox = document.getElementById('youtubeFields');
            const ytNote = document.getElementById('ytThumbNote');
            const panelTitle = document.getElementById('imagePanelTitle');

            if (type === 'youtube') {
                ytBox.style.display = 'block';
                ytNote.style.display = 'block';
                panelTitle.textContent = 'Custom Video Thumbnail Cover (Optional)';
            } else {
                ytBox.style.display = 'none';
                ytNote.style.display = 'none';
                panelTitle.textContent = 'Image Photo (Auto-WebP Converter)';
            }
        }

        function syncCategoryName(cat) {
            const map = {
                'coastal': 'Coastal Sanctuaries',
                'penthouses': 'Sky Penthouses',
                'interiors': 'Bespoke Interiors',
                'amenities': 'Signature Amenities',
                'video': 'Cinematic Tours'
            };
            const input = document.getElementById('category_name');
            if (map[cat]) {
                input.value = map[cat];
            }
        }

        function confirmDeleteGallery(id, title) {
            document.getElementById('deleteGalleryId').value = id;
            document.getElementById('deleteGalleryTitle').textContent = title;
            openModal('deleteGalleryModal');
        }
    </script>
</body>
</html>
