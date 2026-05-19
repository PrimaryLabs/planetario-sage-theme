<?php

namespace App\Admin;

class TeamImportPage
{
    public const MENU_SLUG      = 'planetario-team-import';
    public const NONCE_ACTION   = 'planetario_team_import';
    public const TRANSIENT_TTL  = 3600; // 1 hour

    public static function register(): void
    {
        \add_action('admin_menu', [self::class, 'addMenu']);
        \add_action('admin_init', [self::class, 'handleRequest']);
    }

    public static function addMenu(): void
    {
        \add_submenu_page(
            'edit.php?post_type=team_member',
            'Import Team Members',
            'Import from ZIP',
            'manage_options',
            self::MENU_SLUG,
            [self::class, 'renderPage']
        );
    }

    public static function handleRequest(): void
    {
        $action = \sanitize_key($_POST['_action'] ?? '');

        if ($action === 'upload_zip') {
            self::handleUpload();
        } elseif ($action === 'bulk_create') {
            self::handleBulkCreate();
        }
    }

    public static function renderPage(): void
    {
        if (! \current_user_can('manage_options')) {
            \wp_die(\esc_html__('Insufficient permissions.'));
        }

        $step = \sanitize_key($_GET['step'] ?? 'upload');

        if ($step === 'review') {
            self::renderReview();
            return;
        }

        self::renderUploadForm();
    }

    // -------------------------------------------------------------------------
    // Step 1 — upload + extract
    // -------------------------------------------------------------------------

    private static function handleUpload(): void
    {
        \check_admin_referer(self::NONCE_ACTION);

        if (empty($_FILES['team_zip']['tmp_name'])) {
            self::redirectWithError('No file uploaded.');
            return;
        }

        $file    = $_FILES['team_zip'];
        $ext     = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if ($ext !== 'zip') {
            self::redirectWithError('Please upload a .zip file.');
            return;
        }

        $result = TeamImportHandler::extractAndScan($file['tmp_name']);

        if (isset($result['error'])) {
            self::redirectWithError($result['error']);
            return;
        }

        $token = \wp_generate_password(24, false);
        \set_transient('planetario_team_import_' . $token, $result, self::TRANSIENT_TTL);

        \wp_safe_redirect(\add_query_arg([
            'page'  => self::MENU_SLUG,
            'step'  => 'review',
            'token' => $token,
        ], \admin_url('edit.php?post_type=team_member')));
        exit;
    }

    // -------------------------------------------------------------------------
    // Step 2 — review form
    // -------------------------------------------------------------------------

    private static function renderReview(): void
    {
        $token = \sanitize_text_field($_GET['token'] ?? '');
        $data  = \get_transient('planetario_team_import_' . $token);

        if (! $data || empty($data['members'])) {
            self::redirectWithError('Import session expired or invalid. Please upload the ZIP again.');
            return;
        }

        $members    = $data['members'];
        $tempDir    = $data['temp_dir'];
        $uploadBase = \wp_upload_dir();
        // Derive the URL by replacing the basedir prefix — works regardless of nesting depth
        $uploadUrl  = rtrim($uploadBase['baseurl'] . str_replace($uploadBase['basedir'], '', $tempDir), '/');

        // Existing team_role terms for datalist
        $existingTerms = \get_terms(['taxonomy' => 'team_role', 'hide_empty' => false, 'fields' => 'names']);
        if (\is_wp_error($existingTerms)) {
            $existingTerms = [];
        }

        // Group members by folder for display
        $byFolder = [];
        foreach ($members as $i => $m) {
            $byFolder[$m['folder']][] = array_merge($m, ['_index' => $i]);
        }
        ?>
        <div class="wrap" id="planetario-team-import">
            <h1>Import Team Members — Review</h1>
            <p>Review and adjust the details below, then click <strong>Save All</strong> to bulk-create the posts.</p>

            <form method="post" action="<?php echo \esc_url(\admin_url('edit.php?post_type=team_member&page=' . self::MENU_SLUG)); ?>">
                <?php \wp_nonce_field(self::NONCE_ACTION); ?>
                <input type="hidden" name="_action" value="bulk_create">
                <input type="hidden" name="token"   value="<?php echo \esc_attr($token); ?>">

                <datalist id="team-roles">
                    <?php foreach ($existingTerms as $term) : ?>
                        <option value="<?php echo \esc_attr($term); ?>">
                    <?php endforeach; ?>
                </datalist>

                <?php foreach ($byFolder as $folder => $folderMembers) : ?>
                    <h2 style="margin-top:2em;border-bottom:1px solid #ddd;padding-bottom:.3em;">
                        📁 <?php echo \esc_html($folder); ?>
                        <span style="font-weight:400;font-size:.8em;color:#666;"><?php echo count($folderMembers); ?> member(s)</span>
                    </h2>

                    <table class="wp-list-table widefat fixed striped" style="margin-bottom:1.5em;">
                        <thead>
                            <tr>
                                <th style="width:36px;text-align:center;">
                                    <input type="checkbox" class="ti-select-all" data-folder="<?php echo \esc_attr($folder); ?>" checked title="Select / deselect all in this folder">
                                </th>
                                <th style="width:60px;">Photo</th>
                                <th>Name</th>
                                <th>Position</th>
                                <th style="width:200px;">Category</th>
                                <th style="width:120px;">Region</th>
                                <th style="width:70px;">Order</th>
                                <th style="width:76px;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($folderMembers as $m) :
                                $idx      = $m['_index'];
                                $thumbUrl = $uploadUrl . '/' . $m['image_path'];
                            ?>
                            <tr>
                                <td style="text-align:center;">
                                    <input
                                        type="checkbox"
                                        name="members[<?php echo $idx; ?>][include]"
                                        value="1"
                                        class="ti-member-check"
                                        data-folder="<?php echo \esc_attr($folder); ?>"
                                        checked
                                    >
                                </td>
                                <td>
                                    <img
                                        src="<?php echo \esc_url($thumbUrl); ?>"
                                        alt=""
                                        style="width:48px;height:48px;object-fit:cover;border-radius:4px;"
                                        onerror="this.style.display='none'"
                                    >
                                    <input type="hidden" name="members[<?php echo $idx; ?>][image_path]" value="<?php echo \esc_attr($m['image_path']); ?>">
                                    <input type="hidden" name="members[<?php echo $idx; ?>][folder]"     value="<?php echo \esc_attr($m['folder']); ?>">
                                </td>
                                <td>
                                    <input
                                        type="text"
                                        name="members[<?php echo $idx; ?>][name]"
                                        value="<?php echo \esc_attr($m['name']); ?>"
                                        class="regular-text"
                                        required
                                    >
                                </td>
                                <td>
                                    <input
                                        type="text"
                                        name="members[<?php echo $idx; ?>][position]"
                                        value="<?php echo \esc_attr($m['position']); ?>"
                                        class="regular-text"
                                    >
                                </td>
                                <td>
                                    <input
                                        type="text"
                                        name="members[<?php echo $idx; ?>][category]"
                                        value="<?php echo \esc_attr($m['category']); ?>"
                                        list="team-roles"
                                        class="regular-text"
                                        placeholder="e.g. Board of Directors"
                                    >
                                </td>
                                <td>
                                    <select name="members[<?php echo $idx; ?>][region]">
                                        <option value="all"   <?php \selected($m['region'], 'all');   ?>>All</option>
                                        <option value="bohol" <?php \selected($m['region'], 'bohol'); ?>>Bohol</option>
                                        <option value="cebu"  <?php \selected($m['region'], 'cebu');  ?>>Cebu</option>
                                    </select>
                                </td>
                                <td>
                                    <input
                                        type="number"
                                        name="members[<?php echo $idx; ?>][order]"
                                        value="<?php echo \esc_attr($idx); ?>"
                                        min="0"
                                        style="width:60px;"
                                    >
                                </td>
                                <td>
                                    <button
                                        type="button"
                                        class="button ti-preview-btn"
                                        data-img="<?php echo \esc_url($thumbUrl); ?>"
                                        data-idx="<?php echo $idx; ?>"
                                        title="Preview card"
                                    >Preview</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endforeach; ?>

                <p class="submit">
                    <button type="submit" class="button button-primary button-large">
                        Save All &amp; Create Team Members
                    </button>
                    <a href="<?php echo \esc_url(\admin_url('edit.php?post_type=team_member&page=' . self::MENU_SLUG)); ?>" class="button" style="margin-left:8px;">
                        Cancel
                    </a>
                </p>
            </form>
        </div>

        <!-- Preview modal -->
        <div id="ti-modal-overlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.55);z-index:99998;align-items:center;justify-content:center;">
            <div id="ti-modal" style="background:#fff;border-radius:8px;width:260px;box-shadow:0 8px 32px rgba(0,0,0,.3);overflow:hidden;position:relative;">
                <button id="ti-modal-close" type="button" style="position:absolute;top:8px;right:10px;background:none;border:none;font-size:18px;cursor:pointer;color:#666;line-height:1;" title="Close">&times;</button>
                <img id="ti-modal-img" src="" alt="" style="width:100%;height:180px;object-fit:cover;display:block;">
                <div style="padding:16px 18px 20px;">
                    <div id="ti-modal-name"     style="font-size:15px;font-weight:600;color:#0c1730;margin-bottom:3px;"></div>
                    <div id="ti-modal-position" style="font-size:12px;color:#3a4564;margin-bottom:10px;"></div>
                    <div style="display:flex;gap:6px;flex-wrap:wrap;">
                        <span id="ti-modal-category" style="font-size:11px;background:#eef1f8;color:#3a4564;border-radius:4px;padding:2px 8px;"></span>
                        <span id="ti-modal-region"   style="font-size:11px;background:#d4f0e4;color:#1a6b47;border-radius:4px;padding:2px 8px;"></span>
                    </div>
                </div>
            </div>
        </div>

        <script>
        (function () {
            // Select-all toggles
            document.querySelectorAll('.ti-select-all').forEach(function (master) {
                master.addEventListener('change', function () {
                    var folder = master.dataset.folder;
                    document.querySelectorAll('.ti-member-check[data-folder="' + folder + '"]').forEach(function (cb) {
                        cb.checked = master.checked;
                    });
                });
            });

            // Preview modal
            var overlay  = document.getElementById('ti-modal-overlay');
            var modalImg = document.getElementById('ti-modal-img');
            var modalName     = document.getElementById('ti-modal-name');
            var modalPosition = document.getElementById('ti-modal-position');
            var modalCategory = document.getElementById('ti-modal-category');
            var modalRegion   = document.getElementById('ti-modal-region');

            function openModal(btn) {
                var idx = btn.dataset.idx;
                var row = btn.closest('tr');

                var name     = row.querySelector('input[name="members[' + idx + '][name]"]').value;
                var position = row.querySelector('input[name="members[' + idx + '][position]"]').value;
                var category = row.querySelector('input[name="members[' + idx + '][category]"]').value;
                var region   = row.querySelector('select[name="members[' + idx + '][region]"]').value;
                var imgSrc   = btn.dataset.img;

                modalImg.src           = imgSrc;
                modalImg.style.display = imgSrc ? 'block' : 'none';
                modalName.textContent     = name     || '—';
                modalPosition.textContent = position || '—';
                modalCategory.textContent = category || '—';
                modalRegion.textContent   = region   || '—';

                overlay.style.display = 'flex';
            }

            function closeModal() {
                overlay.style.display = 'none';
            }

            document.querySelectorAll('.ti-preview-btn').forEach(function (btn) {
                btn.addEventListener('click', function () { openModal(btn); });
            });

            document.getElementById('ti-modal-close').addEventListener('click', closeModal);
            overlay.addEventListener('click', function (e) {
                if (e.target === overlay) closeModal();
            });
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') closeModal();
            });
        })();
        </script>
        <?php
    }

    // -------------------------------------------------------------------------
    // Step 3 — bulk create
    // -------------------------------------------------------------------------

    private static function handleBulkCreate(): void
    {
        \check_admin_referer(self::NONCE_ACTION);

        $token = \sanitize_text_field($_POST['token'] ?? '');
        $data  = \get_transient('planetario_team_import_' . $token);

        if (! $data) {
            self::redirectWithError('Import session expired. Please upload the ZIP again.');
            return;
        }

        $submitted = $_POST['members'] ?? [];
        $toCreate  = [];

        // Only include rows where the checkbox was present (checked)
        foreach ($submitted as $idx => $row) {
            if (empty($row['include'])) {
                continue;
            }
            $toCreate[] = [
                'name'       => \sanitize_text_field($row['name']       ?? ''),
                'position'   => \sanitize_text_field($row['position']   ?? ''),
                'category'   => \sanitize_text_field($row['category']   ?? ''),
                'region'     => \sanitize_key($row['region']            ?? 'all'),
                'image_path' => \sanitize_text_field($row['image_path'] ?? ''),
                'order'      => (int) ($row['order']                    ?? 0),
            ];
        }

        $created = TeamImportHandler::bulkCreate($toCreate, $data['temp_dir']);

        \delete_transient('planetario_team_import_' . $token);
        // Clean up the raw extraction root (extract_dir); falls back to temp_dir for old sessions
        TeamImportHandler::cleanupTempDir($data['extract_dir'] ?? $data['temp_dir']);

        \wp_safe_redirect(\add_query_arg([
            'post_type' => 'team_member',
            'imported'  => $created,
        ], \admin_url('edit.php')));
        exit;
    }

    // -------------------------------------------------------------------------
    // Upload form
    // -------------------------------------------------------------------------

    private static function renderUploadForm(): void
    {
        $imported = (int) ($_GET['imported'] ?? 0);
        ?>
        <div class="wrap">
            <h1>Import Team Members from ZIP</h1>

            <?php if ($imported > 0) : ?>
                <div class="notice notice-success is-dismissible">
                    <p>
                        <strong><?php echo $imported; ?> team member(s) created successfully.</strong>
                        <a href="<?php echo \esc_url(\admin_url('edit.php?post_type=team_member')); ?>">View all team members →</a>
                    </p>
                </div>
            <?php endif; ?>

            <?php if (! empty($_GET['import_error'])) : ?>
                <div class="notice notice-error is-dismissible">
                    <p><?php echo \esc_html(\urldecode($_GET['import_error'])); ?></p>
                </div>
            <?php endif; ?>

            <p>Upload a <code>.zip</code> file organised as:</p>
            <pre style="background:#f6f7f7;padding:12px;border-left:4px solid #2271b1;display:inline-block;">
FOLDER_NAME/
  FIRST LAST - JOB TITLE.jpg
  FIRST LAST - JOB TITLE.png
ANOTHER_FOLDER/
  ...</pre>
            <ul style="list-style:disc;padding-left:1.5em;">
                <li>Folder name becomes the <strong>Category</strong> (e.g. <code>BOD</code> → Board of Directors).</li>
                <li>Folders containing "BOHOL" or "CEBU" auto-set the <strong>Region</strong>.</li>
                <li>File stem <code>NAME - POSITION</code> is split on " – " and title-cased.</li>
            </ul>

            <form method="post" enctype="multipart/form-data"
                  action="<?php echo \esc_url(\admin_url('edit.php?post_type=team_member&page=' . self::MENU_SLUG)); ?>">
                <?php \wp_nonce_field(self::NONCE_ACTION); ?>
                <input type="hidden" name="_action" value="upload_zip">

                <table class="form-table" role="presentation">
                    <tr>
                        <th scope="row"><label for="team_zip">ZIP Archive</label></th>
                        <td>
                            <input type="file" id="team_zip" name="team_zip" accept=".zip" required>
                            <p class="description">Maximum upload size: <?php echo \esc_html(size_format(\wp_max_upload_size())); ?></p>
                        </td>
                    </tr>
                </table>

                <p class="submit">
                    <button type="submit" class="button button-primary">Upload &amp; Preview</button>
                </p>
            </form>
        </div>
        <?php
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    private static function redirectWithError(string $message): void
    {
        \wp_safe_redirect(\add_query_arg([
            'page'         => self::MENU_SLUG,
            'import_error' => \urlencode($message),
        ], \admin_url('edit.php?post_type=team_member')));
        exit;
    }
}
