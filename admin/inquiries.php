<?php
require_once __DIR__ . '/auth.php';
requireAuth();

$pageTitle = 'Advisory Inquiries & Client Leads';
$currentPage = 'inquiries.php';

$inquiries = $dataStore->getInquiries();
$errorMsg = '';

// Handle CSV Export
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename=ritiverse_inquiries_' . date('Y-m-d_His') . '.csv');

    $output = fopen('php://output', 'w');
    // Output BOM for Excel UTF-8 support
    fputs($output, "\xEF\xBB\xBF");

    fputcsv($output, ['Ref ID', 'Timestamp', 'Client Name', 'Email', 'Phone', 'Residence Interest', 'Budget', 'Profile', 'Consultation Mode', 'Message / Notes', 'IP Address']);

    foreach ($inquiries as $inq) {
        fputcsv($output, [
            $inq['id'] ?? '',
            $inq['timestamp'] ?? '',
            $inq['name'] ?? '',
            $inq['email'] ?? '',
            $inq['phone'] ?? '',
            $inq['residence'] ?? '',
            $inq['budget'] ?? '',
            $inq['profile'] ?? '',
            $inq['consultation_mode'] ?? '',
            $inq['message'] ?? '',
            $inq['ip'] ?? ''
        ]);
    }
    fclose($output);
    exit;
}

// Handle Delete Inquiry
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf = $_POST['csrf_token'] ?? '';
    $action = $_POST['form_action'] ?? '';

    if (!verifyCsrfToken($csrf)) {
        $errorMsg = 'Security session expired. Please try again.';
    } elseif ($action === 'delete') {
        $delId = trim($_POST['inquiry_id'] ?? '');
        if ($dataStore->deleteInquiry($delId)) {
            setFlash('success', 'Inquiry ' . htmlspecialchars($delId) . ' deleted successfully.');
        } else {
            setFlash('danger', 'Failed to delete inquiry.');
        }
        header('Location: inquiries.php');
        exit;
    }
}

// Search filter
$searchQuery = trim($_GET['search'] ?? '');
if (!empty($searchQuery)) {
    $inquiries = array_filter($inquiries, function($inq) use ($searchQuery) {
        $s = strtolower($searchQuery);
        return str_contains(strtolower($inq['name'] ?? ''), $s) ||
               str_contains(strtolower($inq['email'] ?? ''), $s) ||
               str_contains(strtolower($inq['phone'] ?? ''), $s) ||
               str_contains(strtolower($inq['id'] ?? ''), $s) ||
               str_contains(strtolower($inq['residence'] ?? ''), $s);
    });
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
                    <h2>Private Client Advisory Inquiries</h2>
                    <p>Review acquisition mandates submitted through the website, modal forms, and contact page.</p>
                </div>
                <div style="display:flex; gap:0.75rem; flex-wrap:wrap;">
                    <a href="inquiries.php?export=csv" class="btn btn-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        Export to CSV
                    </a>
                </div>
            </div>

            <!-- Search Bar -->
            <div class="panel" style="margin-bottom:1.5rem;">
                <div class="panel-body" style="padding:1rem 1.25rem;">
                    <form method="GET" action="inquiries.php" style="display:flex; gap:0.75rem; align-items:center;">
                        <input type="text" name="search" class="form-control" placeholder="Search by client name, email, phone, reference ID, residence..." value="<?= htmlspecialchars($searchQuery) ?>" style="flex:1;">
                        <button type="submit" class="btn btn-secondary btn-sm">Search</button>
                        <?php if (!empty($searchQuery)): ?>
                            <a href="inquiries.php" class="btn btn-secondary btn-sm">Clear</a>
                        <?php endif; ?>
                    </form>
                </div>
            </div>

            <!-- Inquiries List Table -->
            <div class="panel">
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Ref ID</th>
                                <th>Client Details</th>
                                <th>Property of Interest</th>
                                <th>Budget & Profile</th>
                                <th>Consultation</th>
                                <th>Date Received</th>
                                <th style="text-align:right;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($inquiries)): ?>
                                <tr>
                                    <td colspan="7" style="text-align:center; padding:3rem; color:var(--text-dim);">
                                        No client inquiries found matching your criteria.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($inquiries as $inq): 
                                    $inqJson = json_encode($inq, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
                                ?>
                                    <tr>
                                        <td>
                                            <code style="color:var(--accent-gold); font-weight:700; font-size:0.8rem;"><?= htmlspecialchars($inq['id'] ?? '') ?></code>
                                        </td>
                                        <td>
                                            <strong style="color:var(--text-main); font-size:0.92rem;"><?= htmlspecialchars($inq['name'] ?? '') ?></strong><br>
                                            <a href="mailto:<?= htmlspecialchars($inq['email'] ?? '') ?>" style="color:var(--accent-gold); text-decoration:none; font-size:0.78rem;"><?= htmlspecialchars($inq['email'] ?? '') ?></a><br>
                                            <a href="tel:<?= htmlspecialchars($inq['phone'] ?? '') ?>" style="color:var(--text-dim); text-decoration:none; font-size:0.78rem;"><?= htmlspecialchars($inq['phone'] ?? '') ?></a>
                                        </td>
                                        <td>
                                            <span style="font-size:0.85rem; font-weight:500; color:var(--text-main);">
                                                <?= htmlspecialchars(ucwords(str_replace('-', ' ', $inq['residence'] ?? 'General Advisory'))) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span style="font-size:0.82rem; color:var(--accent-gold); font-weight:600; display:block;">
                                                <?= htmlspecialchars($inq['budget'] ?? 'Unspecified') ?>
                                            </span>
                                            <small style="color:var(--text-dim); font-size:0.72rem;">
                                                <?= htmlspecialchars($inq['profile'] ?? '') ?>
                                            </small>
                                        </td>
                                        <td>
                                            <span style="font-size:0.78rem; background:rgba(255,255,255,0.05); padding:0.25rem 0.6rem; border-radius:9999px;">
                                                <?= htmlspecialchars($inq['consultation_mode'] ?? 'Direct Call') ?>
                                            </span>
                                        </td>
                                        <td>
                                            <small style="color:var(--text-dim);"><?= date('M d, Y H:i', strtotime($inq['timestamp'] ?? 'now')) ?></small>
                                        </td>
                                        <td style="text-align:right;">
                                            <div style="display:inline-flex; gap:0.5rem;">
                                                <button type="button" onclick="viewInquiryModal(<?= htmlspecialchars($inqJson) ?>)" class="btn btn-secondary btn-sm" title="View Full Details">
                                                    View Details
                                                </button>
                                                <button type="button" onclick="confirmDeleteInquiry('<?= htmlspecialchars($inq['id'] ?? '') ?>', '<?= htmlspecialchars(addslashes($inq['name'] ?? 'Client')) ?>')" class="btn btn-danger btn-sm" title="Delete Inquiry">
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
        </main>

        <?php require __DIR__ . '/includes/footer.php'; ?>
    </div>

    <!-- Inquiry Full Details Modal -->
    <div id="inquiryDetailModal" class="modal-overlay">
        <div class="modal-dialog">
            <div class="modal-header">
                <h3>Acquisition Inquiry Mandate</h3>
                <button type="button" class="modal-close" onclick="closeModal('inquiryDetailModal')">&times;</button>
            </div>
            <div class="modal-body">
                <div style="background:rgba(202,130,22,0.1); border:1px solid rgba(202,130,22,0.3); padding:1rem; border-radius:0.75rem; margin-bottom:1.25rem; display:flex; justify-content:space-between; align-items:center;">
                    <div>
                        <span style="font-size:0.75rem; text-transform:uppercase; color:var(--accent-gold); font-weight:700;">Reference ID</span>
                        <div id="mInqId" style="font-size:1.15rem; font-weight:700; color:var(--text-main); font-family:monospace;"></div>
                    </div>
                    <div style="text-align:right;">
                        <span style="font-size:0.75rem; text-transform:uppercase; color:var(--text-dim); font-weight:700;">Received</span>
                        <div id="mInqTime" style="font-size:0.8rem; color:var(--text-main);"></div>
                    </div>
                </div>

                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1rem; margin-bottom:1rem;">
                    <div>
                        <span style="font-size:0.75rem; color:var(--text-dim); text-transform:uppercase; font-weight:700;">Client Full Name</span>
                        <div id="mInqName" style="font-weight:600; font-size:1rem; color:var(--text-main);"></div>
                    </div>
                    <div>
                        <span style="font-size:0.75rem; color:var(--text-dim); text-transform:uppercase; font-weight:700;">Email Address</span>
                        <div><a id="mInqEmail" href="" style="color:var(--accent-gold); text-decoration:none;"></a></div>
                    </div>
                    <div>
                        <span style="font-size:0.75rem; color:var(--text-dim); text-transform:uppercase; font-weight:700;">Contact Phone</span>
                        <div><a id="mInqPhone" href="" style="color:var(--text-main); text-decoration:none; font-weight:600;"></a></div>
                    </div>
                    <div>
                        <span style="font-size:0.75rem; color:var(--text-dim); text-transform:uppercase; font-weight:700;">Residence Mandate</span>
                        <div id="mInqRes" style="color:var(--text-main); font-weight:500;"></div>
                    </div>
                    <div>
                        <span style="font-size:0.75rem; color:var(--text-dim); text-transform:uppercase; font-weight:700;">Budget Allocation</span>
                        <div id="mInqBudget" style="color:var(--accent-gold); font-weight:700;"></div>
                    </div>
                    <div>
                        <span style="font-size:0.75rem; color:var(--text-dim); text-transform:uppercase; font-weight:700;">Buyer Profile</span>
                        <div id="mInqProfile" style="color:var(--text-main);"></div>
                    </div>
                    <div>
                        <span style="font-size:0.75rem; color:var(--text-dim); text-transform:uppercase; font-weight:700;">Consultation Mode</span>
                        <div id="mInqMode" style="color:var(--text-main);"></div>
                    </div>
                    <div>
                        <span style="font-size:0.75rem; color:var(--text-dim); text-transform:uppercase; font-weight:700;">Origin IP</span>
                        <div id="mInqIp" style="color:var(--text-dim); font-family:monospace; font-size:0.8rem;"></div>
                    </div>
                </div>

                <div style="margin-top:1.25rem;">
                    <span style="font-size:0.75rem; color:var(--text-dim); text-transform:uppercase; font-weight:700;">Client Message / Bespoke Preferences</span>
                    <div id="mInqMessage" style="background:#121110; border:1px solid var(--border-color); border-radius:0.75rem; padding:1rem; margin-top:0.4rem; color:#e5e7eb; font-size:0.88rem; line-height:1.6; white-space:pre-wrap;"></div>
                </div>
            </div>
            <div class="modal-footer">
                <a id="mInqMailTo" href="" class="btn btn-primary btn-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/></svg>
                    Reply via Email
                </a>
                <button type="button" class="btn btn-secondary btn-sm" onclick="closeModal('inquiryDetailModal')">Close</button>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteInquiryModal" class="modal-overlay">
        <div class="modal-dialog">
            <div class="modal-header">
                <h3>Confirm Inquiry Removal</h3>
                <button type="button" class="modal-close" onclick="closeModal('deleteInquiryModal')">&times;</button>
            </div>
            <form method="POST" action="inquiries.php">
                <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                <input type="hidden" name="form_action" value="delete">
                <input type="hidden" name="inquiry_id" id="deleteInqId" value="">

                <div class="modal-body">
                    <p>Are you sure you want to delete inquiry <strong id="deleteInqName" style="color:var(--accent-gold);"></strong>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('deleteInquiryModal')">Cancel</button>
                    <button type="submit" class="btn btn-danger">Confirm Delete</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function viewInquiryModal(inq) {
            document.getElementById('mInqId').textContent = inq.id || '';
            document.getElementById('mInqTime').textContent = inq.timestamp ? new Date(inq.timestamp).toLocaleString() : '';
            document.getElementById('mInqName').textContent = inq.name || '';
            
            const emailEl = document.getElementById('mInqEmail');
            emailEl.textContent = inq.email || '';
            emailEl.href = 'mailto:' + (inq.email || '');

            const phoneEl = document.getElementById('mInqPhone');
            phoneEl.textContent = inq.phone || '';
            phoneEl.href = 'tel:' + (inq.phone || '');

            document.getElementById('mInqRes').textContent = inq.residence || 'General Advisory';
            document.getElementById('mInqBudget').textContent = inq.budget || 'Not specified';
            document.getElementById('mInqProfile').textContent = inq.profile || 'Individual / Family Office';
            document.getElementById('mInqMode').textContent = inq.consultation_mode || 'Direct Call';
            document.getElementById('mInqIp').textContent = inq.ip || '127.0.0.1';
            document.getElementById('mInqMessage').textContent = inq.message || inq.notes || 'No custom notes provided by client.';

            document.getElementById('mInqMailTo').href = `mailto:${encodeURIComponent(inq.email || '')}?subject=${encodeURIComponent('RiTiVerse Acquisition Mandate [' + (inq.id || '') + ']')}`;

            openModal('inquiryDetailModal');
        }

        function confirmDeleteInquiry(id, name) {
            document.getElementById('deleteInqId').value = id;
            document.getElementById('deleteInqName').textContent = name + ' (' + id + ')';
            openModal('deleteInquiryModal');
        }
    </script>
</body>
</html>
