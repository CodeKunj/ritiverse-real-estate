<?php
require_once __DIR__ . '/auth.php';
requireAuth();

$pageTitle = 'Projects / Residences Manager';
$currentPage = 'projects.php';

$projects = $dataStore->getProjects();
$action = $_GET['action'] ?? 'list';
$editId = $_GET['id'] ?? null;
$errorMsg = '';

// Find project if editing
$currentProject = null;
if ($action === 'edit' && $editId) {
    foreach ($projects as $p) {
        if ($p['id'] === $editId) {
            $currentProject = $p;
            break;
        }
    }
    if (!$currentProject) {
        setFlash('danger', 'Project not found.');
        header('Location: projects.php');
        exit;
    }
}

// Handle Form Actions (Add / Edit / Delete)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf = $_POST['csrf_token'] ?? '';
    $formAction = $_POST['form_action'] ?? '';

    if (!verifyCsrfToken($csrf)) {
        $errorMsg = 'Security token invalid or expired. Please reload and try again.';
    } else {
        // DELETE PROJECT
        if ($formAction === 'delete') {
            $delId = $_POST['project_id'] ?? '';
            $filtered = array_values(array_filter($projects, fn($p) => $p['id'] !== $delId));
            $dataStore->saveProjects($filtered);
            setFlash('success', 'Project successfully removed.');
            header('Location: projects.php');
            exit;
        }

        // SAVE (CREATE OR UPDATE)
        if ($formAction === 'save') {
            $isEditing = !empty($_POST['original_id']);
            $id = $isEditing ? $_POST['original_id'] : preg_replace('/[^a-z0-9\-]/', '', strtolower(str_replace(' ', '-', trim($_POST['title'] ?? 'residence-' . time()))));
            if (empty($id)) {
                $id = 'residence-' . uniqid();
            }

            $title = trim($_POST['title'] ?? '');
            $type = trim($_POST['type'] ?? '');
            $price = trim($_POST['price'] ?? '');
            $status = trim($_POST['status'] ?? 'Available');
            $description = trim($_POST['description'] ?? '');

            $specs = [
                'bedrooms'  => trim($_POST['spec_bedrooms'] ?? ''),
                'bathrooms' => trim($_POST['spec_bathrooms'] ?? ''),
                'area'      => trim($_POST['spec_area'] ?? ''),
                'view'      => trim($_POST['spec_view'] ?? ''),
            ];

            // Primary Image Handling
            $primaryImage = $isEditing ? ($currentProject['primary_image'] ?? 'assets/project-1-new.0u9fesgwq9skw.webp') : 'assets/project-1-new.0u9fesgwq9skw.webp';
            if (!empty($_FILES['primary_image']['name'])) {
                $res = $dataStore->uploadAndConvertToWebp($_FILES['primary_image'], 'proj_main');
                if ($res['success']) {
                    $primaryImage = $res['path'];
                } else {
                    $errorMsg .= ' Primary Image: ' . $res['error'];
                }
            }

            // Gallery Images Handling
            $galleryImages = $isEditing ? ($currentProject['gallery'] ?? []) : [];

            // Remove selected gallery images if requested
            $deleteGalleryItems = $_POST['delete_gallery_images'] ?? [];
            if (!empty($deleteGalleryItems)) {
                $galleryImages = array_values(array_filter($galleryImages, fn($img) => !in_array($img, $deleteGalleryItems)));
            }

            // Upload new gallery images (multi-upload)
            if (!empty($_FILES['gallery_images']['name'][0])) {
                $fileCount = count($_FILES['gallery_images']['name']);
                for ($i = 0; $i < $fileCount; $i++) {
                    if ($_FILES['gallery_images']['error'][$i] === UPLOAD_ERR_OK) {
                        $singleFile = [
                            'name'     => $_FILES['gallery_images']['name'][$i],
                            'type'     => $_FILES['gallery_images']['type'][$i],
                            'tmp_name' => $_FILES['gallery_images']['tmp_name'][$i],
                            'error'    => $_FILES['gallery_images']['error'][$i],
                            'size'     => $_FILES['gallery_images']['size'][$i],
                        ];
                        $res = $dataStore->uploadAndConvertToWebp($singleFile, 'proj_gal');
                        if ($res['success']) {
                            $galleryImages[] = $res['path'];
                        }
                    }
                }
            }

            if (empty($errorMsg)) {
                $projectItem = [
                    'id'            => $id,
                    'title'         => $title,
                    'type'          => $type,
                    'description'   => $description,
                    'price'         => $price,
                    'status'        => $status,
                    'featured'      => true,
                    'specs'         => $specs,
                    'primary_image' => $primaryImage,
                    'gallery'       => $galleryImages
                ];

                if ($isEditing) {
                    foreach ($projects as $k => $p) {
                        if ($p['id'] === $id) {
                            $projects[$k] = $projectItem;
                            break;
                        }
                    }
                } else {
                    $projects[] = $projectItem;
                }

                $dataStore->saveProjects($projects);
                setFlash('success', 'Project "' . htmlspecialchars($title) . '" saved successfully with WebP images.');
                header('Location: projects.php');
                exit;
            }
        }
    }
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

            <?php if (!empty($errorMsg)): ?>
                <div class="alert alert-danger">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <span><?= htmlspecialchars($errorMsg) ?></span>
                </div>
            <?php endif; ?>

            <?php if ($action === 'list'): ?>
                <!-- PROJECTS LIST VIEW -->
                <div class="page-header">
                    <div>
                        <h2>Signature Residences & Projects</h2>
                        <p>Curated landmark properties displayed on homepage and acquisition portfolios.</p>
                    </div>
                    <a href="projects.php?action=new" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Add New Residence
                    </a>
                </div>

                <div class="panel">
                    <div class="table-responsive">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th style="width: 90px;">Photo</th>
                                    <th>Residence Details</th>
                                    <th>Location / Type</th>
                                    <th>Valuation</th>
                                    <th>Specifications</th>
                                    <th>Gallery</th>
                                    <th style="text-align:right;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($projects)): ?>
                                    <tr>
                                        <td colspan="7" style="text-align:center; padding:3rem; color:var(--text-dim);">
                                            No residences found. Click "Add New Residence" to create one.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($projects as $proj): ?>
                                        <tr>
                                            <td>
                                                <img src="../<?= htmlspecialchars($proj['primary_image']) ?>" alt="" style="width: 80px; height: 58px; object-fit: cover; border-radius: 8px; border: 1px solid var(--border-color);">
                                            </td>
                                            <td>
                                                <strong style="font-size:0.95rem; color:var(--text-main);"><?= htmlspecialchars($proj['title']) ?></strong>
                                                <div style="font-size:0.75rem; color:var(--text-dim); margin-top:0.2rem;">
                                                    Slug: <code><?= htmlspecialchars($proj['id']) ?></code> &bull; 
                                                    <span style="color:var(--success);"><?= htmlspecialchars($proj['status'] ?? 'Available') ?></span>
                                                </div>
                                            </td>
                                            <td>
                                                <div style="font-size:0.85rem; color:var(--text-main);"><?= htmlspecialchars($proj['type']) ?></div>
                                            </td>
                                            <td>
                                                <span style="font-size:0.92rem; font-weight:700; color:var(--accent-gold);"><?= htmlspecialchars($proj['price']) ?></span>
                                            </td>
                                            <td>
                                                <div style="font-size:0.78rem; color:var(--text-muted);">
                                                    <?= htmlspecialchars($proj['specs']['bedrooms'] ?? '') ?> &bull; 
                                                    <?= htmlspecialchars($proj['specs']['bathrooms'] ?? '') ?><br>
                                                    <span style="color:var(--text-dim);"><?= htmlspecialchars($proj['specs']['area'] ?? '') ?></span>
                                                </div>
                                            </td>
                                            <td>
                                                <span style="font-size:0.8rem; background:rgba(255,255,255,0.05); padding:0.2rem 0.55rem; border-radius:9999px;">
                                                    <?= count($proj['gallery'] ?? []) ?> preview shots
                                                </span>
                                            </td>
                                            <td style="text-align:right;">
                                                <div style="display:inline-flex; gap:0.5rem;">
                                                    <a href="projects.php?action=edit&id=<?= urlencode($proj['id']) ?>" class="btn btn-secondary btn-sm" title="Edit Residence">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                                                        Edit
                                                    </a>
                                                    <button type="button" onclick="confirmDeleteProject('<?= htmlspecialchars($proj['id']) ?>', '<?= htmlspecialchars(addslashes($proj['title'])) ?>')" class="btn btn-danger btn-sm" title="Delete">
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

            <?php elseif ($action === 'new' || $action === 'edit'): ?>
                <!-- ADD / EDIT PROJECT VIEW -->
                <div class="page-header">
                    <div>
                        <h2><?= $action === 'edit' ? 'Edit Residence: ' . htmlspecialchars($currentProject['title']) : 'Add New Signature Residence' ?></h2>
                        <p>All uploaded images are automatically compressed and converted to lightweight .webp format.</p>
                    </div>
                    <a href="projects.php" class="btn btn-secondary">
                        &larr; Back to Residences List
                    </a>
                </div>

                <form method="POST" action="projects.php" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                    <input type="hidden" name="form_action" value="save">
                    <?php if ($action === 'edit'): ?>
                        <input type="hidden" name="original_id" value="<?= htmlspecialchars($currentProject['id']) ?>">
                    <?php endif; ?>

                    <div class="panel">
                        <div class="panel-header">
                            <div>
                                <h3>Residence Core Details</h3>
                                <p>Title, location, pricing, and architectural summary</p>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label" for="title">Residence Title <span class="required">*</span></label>
                                    <input type="text" id="title" name="title" class="form-control" value="<?= htmlspecialchars($currentProject['title'] ?? '') ?>" placeholder="e.g. The Arabian Seafront Residence" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="type">Location / Subtitle <span class="required">*</span></label>
                                    <input type="text" id="type" name="type" class="form-control" value="<?= htmlspecialchars($currentProject['type'] ?? '') ?>" placeholder="e.g. Worli Sea Face, Mumbai" required>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label" for="price">Valuation / Price <span class="required">*</span></label>
                                    <input type="text" id="price" name="price" class="form-control" value="<?= htmlspecialchars($currentProject['price'] ?? '') ?>" placeholder="e.g. ₹ 48,00,00,000" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="status">Availability Status</label>
                                    <input type="text" id="status" name="status" class="form-control" value="<?= htmlspecialchars($currentProject['status'] ?? 'Available') ?>" placeholder="e.g. Available / Handover Q4 2028">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="description">Architectural Description</label>
                                <textarea id="description" name="description" class="form-control" rows="4" placeholder="Describe the design, ocean horizons, privacy features..."><?= htmlspecialchars($currentProject['description'] ?? '') ?></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Specifications Panel -->
                    <div class="panel">
                        <div class="panel-header">
                            <div>
                                <h3>Architectural Specifications</h3>
                                <p>Room counts, square footage and horizon perspectives</p>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label" for="spec_bedrooms">Bedrooms</label>
                                    <input type="text" id="spec_bedrooms" name="spec_bedrooms" class="form-control" value="<?= htmlspecialchars($currentProject['specs']['bedrooms'] ?? '5 Bedrooms') ?>" placeholder="e.g. 5 Bedrooms">
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="spec_bathrooms">Bathrooms</label>
                                    <input type="text" id="spec_bathrooms" name="spec_bathrooms" class="form-control" value="<?= htmlspecialchars($currentProject['specs']['bathrooms'] ?? '6 Bathrooms') ?>" placeholder="e.g. 6 Bathrooms">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label" for="spec_area">Living Area (Sq. Ft.)</label>
                                    <input type="text" id="spec_area" name="spec_area" class="form-control" value="<?= htmlspecialchars($currentProject['specs']['area'] ?? '11,400 Sq. Ft.') ?>" placeholder="e.g. 11,400 Sq. Ft.">
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="spec_view">View / Horizon</label>
                                    <input type="text" id="spec_view" name="spec_view" class="form-control" value="<?= htmlspecialchars($currentProject['specs']['view'] ?? 'Full Arabian Sea & Coastline') ?>" placeholder="e.g. Full Arabian Sea & Bandra-Worli Sea Link">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Imagery & WebP Converter Panel -->
                    <div class="panel">
                        <div class="panel-header">
                            <div>
                                <h3>Imagery & Visual Showcase (Auto-WebP Converter)</h3>
                                <p>Upload PNG, JPG, or WEBP &mdash; our engine compresses and converts everything to lightweight .webp.</p>
                            </div>
                        </div>
                        <div class="panel-body">
                            <!-- Primary Image -->
                            <div class="form-group">
                                <label class="form-label">Primary Cover Image <span class="required">*</span></label>
                                <?php if (!empty($currentProject['primary_image'])): ?>
                                    <div style="display:flex; align-items:center; gap:1rem; margin-bottom: 0.85rem;">
                                        <img src="../<?= htmlspecialchars($currentProject['primary_image']) ?>" class="preview-target" alt="Current Cover" style="width:140px; height:90px; object-fit:cover; border-radius:8px; border:1px solid var(--border-color);">
                                        <div>
                                            <span style="font-size:0.8rem; color:var(--text-muted);">Current Cover Image (.webp)</span>
                                            <div class="webp-badge">Optimized WebP</div>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <img src="" class="preview-target" alt="Preview" style="display:none; width:140px; height:90px; object-fit:cover; border-radius:8px; margin-bottom:0.85rem;">
                                <?php endif; ?>

                                <div class="dropzone">
                                    <input type="file" name="primary_image" accept="image/png, image/jpeg, image/webp" <?= $action === 'new' ? 'required' : '' ?>>
                                    <div class="dropzone-text">Click or drag Primary Showcase Image here</div>
                                    <div class="dropzone-sub">PNG, JPG, or WEBP &bull; Converts to high-res .webp</div>
                                </div>
                            </div>

                            <!-- Additional Gallery Preview Shots -->
                            <div class="form-group" style="margin-top:2rem;">
                                <label class="form-label">Additional Gallery Previews (Multi-Select)</label>

                                <?php if (!empty($currentProject['gallery'])): ?>
                                    <div style="margin-bottom:1rem;">
                                        <span style="font-size:0.8rem; color:var(--text-muted); display:block; margin-bottom:0.5rem;">Existing Gallery Images (Check box to remove):</span>
                                        <div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)); gap:1rem;">
                                            <?php foreach ($currentProject['gallery'] as $gImg): ?>
                                                <div style="background:rgba(255,255,255,0.02); border:1px solid var(--border-color); border-radius:8px; padding:0.5rem; text-align:center;">
                                                    <img src="../<?= htmlspecialchars($gImg) ?>" alt="" style="width:100%; height:75px; object-fit:cover; border-radius:6px; margin-bottom:0.35rem;">
                                                    <label style="font-size:0.75rem; color:#ef4444; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:0.25rem;">
                                                        <input type="checkbox" name="delete_gallery_images[]" value="<?= htmlspecialchars($gImg) ?>">
                                                        Remove
                                                    </label>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <div class="dropzone">
                                    <input type="file" name="gallery_images[]" accept="image/png, image/jpeg, image/webp" multiple>
                                    <div class="dropzone-text">Select one or more additional gallery photos</div>
                                    <div class="dropzone-sub">Select multiple files (PNG, JPG, or WEBP) &bull; All converted to lightweight .webp</div>
                                </div>
                            </div>
                        </div>

                        <div class="panel-footer">
                            <a href="projects.php" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                                Save Residence Details
                            </button>
                        </div>
                    </div>
                </form>

            <?php endif; ?>

        </main>

        <?php require __DIR__ . '/includes/footer.php'; ?>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal-overlay">
        <div class="modal-dialog">
            <div class="modal-header">
                <h3>Confirm Residence Removal</h3>
                <button type="button" class="modal-close" onclick="closeModal('deleteModal')">&times;</button>
            </div>
            <form method="POST" action="projects.php">
                <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                <input type="hidden" name="form_action" value="delete">
                <input type="hidden" name="project_id" id="deleteProjectId" value="">

                <div class="modal-body">
                    <p>Are you sure you want to remove <strong id="deleteProjectTitle" style="color:var(--accent-gold);"></strong> from the portfolio?</p>
                    <p style="color:var(--text-dim); font-size:0.8rem; margin-top:0.5rem;">This will take it off the live website and homepage features slider immediately.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('deleteModal')">Cancel</button>
                    <button type="submit" class="btn btn-danger">Confirm Delete</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function confirmDeleteProject(id, title) {
            document.getElementById('deleteProjectId').value = id;
            document.getElementById('deleteProjectTitle').textContent = title;
            openModal('deleteModal');
        }
    </script>
</body>
</html>
