<?php

namespace App\Admin;

class TeamImportPage
{
    public const MENU_SLUG     = 'planetario-team-import';
    public const NONCE_ACTION  = 'planetario_team_import';
    public const TRANSIENT_TTL = 3600;

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

        $file = $_FILES['team_zip'];
        $ext  = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
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

        $members      = $data['members'];
        $totalMembers = count($members);
        $uploadBase   = \wp_upload_dir();
        $uploadUrl    = rtrim($uploadBase['baseurl'] . str_replace($uploadBase['basedir'], '', $data['temp_dir']), '/');

        $existingTerms = \get_terms(['taxonomy' => 'team_role', 'hide_empty' => false, 'fields' => 'names']);
        if (\is_wp_error($existingTerms)) {
            $existingTerms = [];
        }

        $byFolder = [];
        foreach ($members as $i => $m) {
            $byFolder[$m['folder']][] = array_merge($m, ['_index' => $i]);
        }
        $folderCount = count($byFolder);
?>
        <style>
            #ti-wrap,
            #ti-wrap * {
                box-sizing: border-box
            }

            #ti-wrap {
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, sans-serif;
                color: #1a202c;
                max-width: 1400px;
                padding-bottom: 100px;
                -webkit-font-smoothing: antialiased
            }

            /* ── Page header ── */
            .ti-ph {
                display: flex;
                align-items: center;
                flex-wrap: wrap;
                gap: 10px;
                margin-bottom: 22px;
                padding: 18px 0 16px;
                border-bottom: 2px solid #eef1f8
            }

            .ti-ph h1 {
                font-size: 19px;
                font-weight: 700;
                color: #0c1730;
                margin: 0;
                padding: 0;
                line-height: 1.2
            }

            .ti-ph-pill {
                background: #eef1f8;
                color: #3a4564;
                border-radius: 20px;
                padding: 3px 11px;
                font-size: 10px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: .6px
            }

            .ti-ph-meta {
                margin-left: auto;
                font-size: 12px;
                color: #94a3b8
            }

            .ti-ph-meta strong {
                color: #3a4564;
                font-size: 13px
            }

            /* ── Folder section card ── */
            .ti-sec {
                background: #fff;
                border: 1px solid #e2e8f0;
                border-radius: 10px;
                margin-bottom: 14px;
                overflow: hidden;
                box-shadow: 0 1px 4px rgba(0, 0, 0, .06)
            }

            .ti-sec-hd {
                display: flex;
                align-items: center;
                gap: 10px;
                background: #0c1730;
                padding: 11px 16px
            }

            .ti-sec-icon {
                font-size: 15px;
                flex-shrink: 0
            }

            .ti-sec-name {
                font-size: 12.5px;
                font-weight: 600;
                letter-spacing: .25px;
                color: #fff;
                flex: 1;
                min-width: 0;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap
            }

            .ti-sec-cnt {
                font-size: 11px;
                color: #88e0b8;
                background: rgba(136, 224, 184, .14);
                border-radius: 10px;
                padding: 2px 9px;
                flex-shrink: 0;
                font-weight: 500
            }

            .ti-sa {
                width: 15px;
                height: 15px;
                cursor: pointer;
                accent-color: #88e0b8;
                flex-shrink: 0
            }

            /* ── Table ── */
            .ti-tbl {
                width: 100%;
                border-collapse: collapse;
                table-layout: fixed
            }

            .ti-tbl thead tr {
                border-bottom: 2px solid #f0f4f8
            }

            .ti-tbl thead th {
                padding: 7px 10px;
                font-size: 9.5px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: .65px;
                color: #b0bec5;
                text-align: left;
                background: #fafbfc;
                white-space: nowrap
            }

            .ti-tbl tbody tr {
                border-bottom: 1px solid #f0f4f8;
                transition: background .1s
            }

            .ti-tbl tbody tr:last-child {
                border-bottom: none
            }

            .ti-tbl tbody tr:hover {
                background: #f8fafc
            }

            .ti-tbl tbody tr.ti-off {
                opacity: .38
            }

            .ti-tbl tbody tr.ti-off td:first-child {
                opacity: 1
            }

            .ti-tbl td {
                padding: 11px 10px;
                vertical-align: top
            }

            .ti-tbl td.ti-mid {
                vertical-align: middle
            }

            /* ── Field + label ── */
            .ti-f {
                display: flex;
                flex-direction: column;
                gap: 3px
            }

            .ti-f+.ti-f {
                margin-top: 7px
            }

            .ti-lbl {
                font-size: 9px;
                font-weight: 700;
                letter-spacing: .65px;
                text-transform: uppercase;
                color: #b0bec5
            }

            .ti-in {
                width: 100%;
                padding: 5px 8px;
                font-size: 12.5px;
                color: #1e293b;
                background: #fff;
                border: 1px solid #e2e8f0;
                border-radius: 5px;
                outline: none;
                transition: border-color .15s, box-shadow .15s;
                font-family: inherit;
                line-height: 1.4
            }

            .ti-in:focus {
                border-color: #0c1730;
                box-shadow: 0 0 0 3px rgba(12, 23, 48, .07)
            }

            .ti-in::placeholder {
                color: #c5ccd8
            }

            .ti-sel {
                -webkit-appearance: none;
                appearance: none;
                background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6' viewBox='0 0 10 6'%3E%3Cpath fill='%2394a3b8' d='M0 0l5 6 5-6z'/%3E%3C/svg%3E");
                background-repeat: no-repeat;
                background-position: right 8px center;
                padding-right: 24px;
                cursor: pointer
            }

            .ti-frow {
                display: flex;
                gap: 6px;
                margin-top: 7px
            }

            .ti-frow .ti-f {
                flex: 1;
                margin-top: 0
            }

            /* ── Avatar ── */
            .ti-av {
                width: 52px;
                height: 52px;
                border-radius: 8px;
                object-fit: contain;
                display: block;
                background: #eef1f8;
                border: 1px solid #e8edf5
            }

            /* ── Checkbox ── */
            .ti-ck {
                width: 15px;
                height: 15px;
                cursor: pointer;
                accent-color: #0c1730;
                display: block;
                margin: 0 auto
            }

            /* ── Preview button ── */
            .ti-pvbtn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 5px;
                padding: 6px 12px;
                font-size: 11.5px;
                font-weight: 500;
                color: #3a4564;
                background: #f0f4ff;
                border: 1px solid #c5d0f0;
                border-radius: 6px;
                cursor: pointer;
                transition: background .12s, border-color .12s;
                white-space: nowrap;
                font-family: inherit;
                width: 100%
            }

            .ti-pvbtn:hover {
                background: #e0e9ff;
                border-color: #8fa3d8
            }

            /* ── Sticky submit bar ── */
            .ti-bar {
                position: sticky;
                bottom: 0;
                z-index: 200;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 16px;
                background: #fff;
                border-top: 1px solid #e2e8f0;
                padding: 13px 20px;
                margin: 20px -20px -10px;
                box-shadow: 0 -4px 24px rgba(0, 0, 0, .09)
            }

            .ti-bar-stat {
                font-size: 12.5px;
                color: #64748b
            }

            .ti-bar-stat strong {
                color: #0c1730;
                font-size: 14px;
                font-weight: 700
            }

            .ti-bar-acts {
                display: flex;
                gap: 8px;
                align-items: center
            }

            .ti-cancel {
                padding: 8px 16px;
                font-size: 12.5px;
                color: #64748b;
                background: none;
                border: 1px solid #dde2ed;
                border-radius: 6px;
                cursor: pointer;
                text-decoration: none;
                transition: border-color .12s, color .12s;
                font-family: inherit;
                display: inline-block;
                line-height: 1.4
            }

            .ti-cancel:hover {
                border-color: #94a3b8;
                color: #3a4564
            }

            .ti-save {
                padding: 9px 22px;
                font-size: 13px;
                font-weight: 600;
                color: #fff;
                background: #0c1730;
                border: none;
                border-radius: 6px;
                cursor: pointer;
                transition: background .15s;
                letter-spacing: .15px;
                font-family: inherit
            }

            .ti-save:hover {
                background: #1e2a4f
            }

            .ti-save:active {
                transform: scale(.98)
            }

            /* ── Modal ── */
            .ti-mo {
                display: none;
                position: fixed;
                inset: 0;
                background: rgba(10, 18, 40, .65);
                z-index: 99998;
                align-items: center;
                justify-content: center;
                backdrop-filter: blur(2px)
            }

            .ti-mo-card {
                background: #fff;
                border-radius: 12px;
                width: 290px;
                max-width: calc(100vw - 40px);
                box-shadow: 0 24px 64px rgba(0, 0, 0, .28);
                overflow: hidden;
                position: relative;
                animation: tiIn .17s ease-out
            }

            @keyframes tiIn {
                from {
                    opacity: 0;
                    transform: translateY(10px) scale(.96)
                }

                to {
                    opacity: 1;
                    transform: none
                }
            }

            .ti-mo-x {
                position: absolute;
                top: 10px;
                right: 10px;
                background: rgba(0, 0, 0, .4);
                border: none;
                width: 26px;
                height: 26px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                color: #fff;
                font-size: 15px;
                line-height: 1;
                transition: background .12s;
                padding: 0;
                font-family: inherit
            }

            .ti-mo-x:hover {
                background: rgba(0, 0, 0, .62)
            }

            .ti-mo-img {
                width: 100%;
                height: 210px;
                object-fit: contain;
                display: block;
                background: #eef1f8
            }

            .ti-mo-body {
                padding: 16px 18px 20px
            }

            .ti-mo-name {
                font-size: 16px;
                font-weight: 700;
                color: #0c1730;
                margin-bottom: 2px;
                line-height: 1.3
            }

            .ti-mo-pos {
                font-size: 12px;
                color: #64748b;
                margin-bottom: 10px;
                min-height: 1em
            }

            .ti-mo-contacts {
                flex-direction: column;
                gap: 5px;
                margin-bottom: 10px
            }

            .ti-mo-row {
                font-size: 11.5px;
                color: #3a4564;
                display: flex;
                align-items: center;
                gap: 7px
            }

            .ti-mo-badges {
                display: flex;
                gap: 6px;
                flex-wrap: wrap;
                margin-top: 2px
            }

            .ti-badge {
                font-size: 10.5px;
                border-radius: 4px;
                padding: 2px 9px;
                font-weight: 500
            }

            .ti-b-cat {
                background: #eef1f8;
                color: #3a4564
            }

            .ti-b-reg {
                background: #d4f0e4;
                color: #1a6b47
            }
        </style>

        <div id="ti-wrap">

            <div class="ti-ph">
                <h1>Import Team Members</h1>
                <span class="ti-ph-pill">Step 2 — Review</span>
                <span class="ti-ph-meta">
                    <strong id="ti-sel-count"><?php echo $totalMembers; ?></strong> / <?php echo $totalMembers; ?> members &nbsp;&middot;&nbsp; <?php echo $folderCount; ?> folder<?php echo $folderCount !== 1 ? 's' : ''; ?>
                </span>
            </div>

            <form method="post" action="<?php echo \esc_url(\admin_url('edit.php?post_type=team_member&page=' . self::MENU_SLUG)); ?>">
                <?php \wp_nonce_field(self::NONCE_ACTION); ?>
                <input type="hidden" name="_action" value="bulk_create">
                <input type="hidden" name="token" value="<?php echo \esc_attr($token); ?>">

                <datalist id="ti-roles">
                    <?php foreach ($existingTerms as $term) : ?>
                        <option value="<?php echo \esc_attr($term); ?>">
                        <?php endforeach; ?>
                </datalist>

                <?php foreach ($byFolder as $folder => $folderMembers) : ?>

                    <div class="ti-sec">
                        <div class="ti-sec-hd">
                            <span class="ti-sec-icon">&#128193;</span>
                            <span class="ti-sec-name"><?php echo \esc_html($folder); ?></span>
                            <span class="ti-sec-cnt">
                                <?php echo count($folderMembers); ?> member<?php echo count($folderMembers) !== 1 ? 's' : ''; ?>
                            </span>
                            <input
                                type="checkbox"
                                class="ti-sa"
                                data-folder="<?php echo \esc_attr($folder); ?>"
                                title="Select / deselect all in this folder"
                                checked>
                        </div>

                        <table class="ti-tbl">
                            <thead>
                                <tr>
                                    <th style="width:40px;text-align:center;"></th>
                                    <th style="width:68px;">Photo</th>
                                    <th>Name &amp; Email</th>
                                    <th>Position &amp; Phone</th>
                                    <th style="width:240px;">Category / Region / Order</th>
                                    <th style="width:96px;"></th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php foreach ($folderMembers as $m) :
                                    $idx      = $m['_index'];
                                    $thumbUrl = $uploadUrl . '/' . $m['image_path'];
                                    $blankSvg = 'data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%2252%22 height=%2252%22%3E%3Crect width=%2252%22 height=%2252%22 fill=%22%23eef1f8%22/%3E%3C/svg%3E';
                                ?>
                                    <tr class="ti-member-row">

                                        <td class="ti-mid" style="text-align:center;">
                                            <input
                                                type="checkbox"
                                                class="ti-ck ti-mc"
                                                name="members[<?php echo $idx; ?>][include]"
                                                value="1"
                                                data-folder="<?php echo \esc_attr($folder); ?>"
                                                checked>
                                        </td>

                                        <td class="ti-mid">
                                            <img
                                                class="ti-av"
                                                src="<?php echo \esc_url($thumbUrl); ?>"
                                                alt=""
                                                onerror="this.src='<?php echo $blankSvg; ?>'">
                                            <input type="hidden" name="members[<?php echo $idx; ?>][image_path]" value="<?php echo \esc_attr($m['image_path']); ?>">
                                            <input type="hidden" name="members[<?php echo $idx; ?>][folder]" value="<?php echo \esc_attr($m['folder']); ?>">
                                        </td>

                                        <td>
                                            <div class="ti-f">
                                                <span class="ti-lbl">Full Name</span>
                                                <input
                                                    type="text"
                                                    class="ti-in"
                                                    name="members[<?php echo $idx; ?>][name]"
                                                    value="<?php echo \esc_attr($m['name']); ?>"
                                                    placeholder="Full name"
                                                    required>
                                            </div>
                                            <div class="ti-f">
                                                <span class="ti-lbl">Email</span>
                                                <input
                                                    type="email"
                                                    class="ti-in"
                                                    name="members[<?php echo $idx; ?>][email]"
                                                    value=""
                                                    placeholder="email@example.com">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="ti-f">
                                                <span class="ti-lbl">Position / Title</span>
                                                <input
                                                    type="text"
                                                    class="ti-in"
                                                    name="members[<?php echo $idx; ?>][position]"
                                                    value="<?php echo \esc_attr($m['position']); ?>"
                                                    placeholder="Job title">
                                            </div>
                                            <div class="ti-f">
                                                <span class="ti-lbl">Phone / Mobile</span>
                                                <input
                                                    type="tel"
                                                    class="ti-in"
                                                    name="members[<?php echo $idx; ?>][phone]"
                                                    value=""
                                                    placeholder="+63 9XX XXX XXXX">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="ti-f">
                                                <span class="ti-lbl">Category</span>
                                                <input
                                                    type="text"
                                                    class="ti-in"
                                                    name="members[<?php echo $idx; ?>][category]"
                                                    value="<?php echo \esc_attr($m['category']); ?>"
                                                    list="ti-roles"
                                                    placeholder="e.g. Board of Directors">
                                            </div>
                                            <div class="ti-frow">
                                                <div class="ti-f">
                                                    <span class="ti-lbl">Region</span>
                                                    <select class="ti-in ti-sel" name="members[<?php echo $idx; ?>][region]">
                                                        <option value="all" <?php \selected($m['region'], 'all');   ?>>All</option>
                                                        <option value="bohol" <?php \selected($m['region'], 'bohol'); ?>>Bohol</option>
                                                        <option value="cebu" <?php \selected($m['region'], 'cebu');  ?>>Cebu</option>
                                                    </select>
                                                </div>
                                                <div class="ti-f">
                                                    <span class="ti-lbl">Order</span>
                                                    <input
                                                        type="number"
                                                        class="ti-in"
                                                        name="members[<?php echo $idx; ?>][order]"
                                                        value="<?php echo \esc_attr($idx); ?>"
                                                        min="0">
                                                </div>
                                            </div>
                                        </td>

                                        <td class="ti-mid">
                                            <button
                                                type="button"
                                                class="ti-pvbtn ti-preview-btn"
                                                data-img="<?php echo \esc_url($thumbUrl); ?>"
                                                data-idx="<?php echo $idx; ?>">
                                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                                    <circle cx="12" cy="12" r="3" />
                                                </svg>
                                                Preview
                                            </button>
                                        </td>

                                    </tr>
                                <?php endforeach; ?>

                            </tbody>
                        </table>
                    </div>

                <?php endforeach; ?>

                <div class="ti-bar">
                    <div class="ti-bar-stat">
                        <strong id="ti-sel-count"><?php echo $totalMembers; ?></strong>
                        <span>/ <?php echo $totalMembers; ?> members selected</span>
                    </div>
                    <div class="ti-bar-acts">
                        <a href="<?php echo \esc_url(\admin_url('edit.php?post_type=team_member&page=' . self::MENU_SLUG)); ?>" class="ti-cancel">
                            Cancel
                        </a>
                        <button type="submit" class="ti-save">
                            Save All &amp; Create Members &#8594;
                        </button>
                    </div>
                </div>

            </form>
        </div>

        <!-- Preview modal -->
        <div class="ti-mo" id="ti-mo">
            <div class="ti-mo-card">
                <button class="ti-mo-x" id="ti-mo-x" type="button">&times;</button>
                <img class="ti-mo-img" id="ti-mo-img" src="" alt="">
                <div class="ti-mo-body">
                    <div class="ti-mo-name" id="ti-mo-name"></div>
                    <div class="ti-mo-pos" id="ti-mo-pos"></div>
                    <div class="ti-mo-contacts" id="ti-mo-contacts" style="display:none;">
                        <div class="ti-mo-row" id="ti-mo-email-row" style="display:none;">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" opacity=".55">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                                <polyline points="22,6 12,13 2,6" />
                            </svg>
                            <span id="ti-mo-email-val"></span>
                        </div>
                        <div class="ti-mo-row" id="ti-mo-phone-row" style="display:none;">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" opacity=".55">
                                <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.79 11.18 19.79 19.79 0 01.72 2.54 2 2 0 012.7.36h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.91 8.09a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z" />
                            </svg>
                            <span id="ti-mo-phone-val"></span>
                        </div>
                    </div>
                    <div class="ti-mo-badges">
                        <span class="ti-badge ti-b-cat" id="ti-mo-cat"></span>
                        <span class="ti-badge ti-b-reg" id="ti-mo-reg"></span>
                    </div>
                </div>
            </div>
        </div>

        <script>
            (function() {
                var total = document.querySelectorAll('.ti-mc').length;

                function updateCount() {
                    var n = document.querySelectorAll('.ti-mc:checked').length;
                    document.getElementById('ti-sel-count').textContent = n;
                }

                document.querySelectorAll('.ti-sa').forEach(function(master) {
                    master.addEventListener('change', function() {
                        var folder = master.dataset.folder;
                        document.querySelectorAll('.ti-mc[data-folder="' + folder + '"]').forEach(function(cb) {
                            cb.checked = master.checked;
                            cb.closest('tr').classList.toggle('ti-off', !cb.checked);
                        });
                        updateCount();
                    });
                });

                document.querySelectorAll('.ti-mc').forEach(function(cb) {
                    cb.addEventListener('change', function() {
                        cb.closest('tr').classList.toggle('ti-off', !cb.checked);
                        updateCount();
                    });
                });

                // Modal
                var mo = document.getElementById('ti-mo');
                var moImg = document.getElementById('ti-mo-img');
                var moName = document.getElementById('ti-mo-name');
                var moPos = document.getElementById('ti-mo-pos');
                var moContacts = document.getElementById('ti-mo-contacts');
                var moEmailRow = document.getElementById('ti-mo-email-row');
                var moEmailVal = document.getElementById('ti-mo-email-val');
                var moPhoneRow = document.getElementById('ti-mo-phone-row');
                var moPhoneVal = document.getElementById('ti-mo-phone-val');
                var moCat = document.getElementById('ti-mo-cat');
                var moReg = document.getElementById('ti-mo-reg');

                function openModal(btn) {
                    var idx = btn.dataset.idx;
                    var row = btn.closest('tr');
                    var q = function(s) {
                        return row.querySelector(s);
                    };

                    var name = q('input[name="members[' + idx + '][name]"]').value;
                    var pos = q('input[name="members[' + idx + '][position]"]').value;
                    var email = q('input[name="members[' + idx + '][email]"]').value;
                    var phone = q('input[name="members[' + idx + '][phone]"]').value;
                    var cat = q('input[name="members[' + idx + '][category]"]').value;
                    var reg = q('select[name="members[' + idx + '][region]"]').value;

                    moImg.src = btn.dataset.img || '';
                    moImg.style.display = btn.dataset.img ? 'block' : 'none';
                    moName.textContent = name || '—';
                    moPos.textContent = pos || '';
                    moEmailVal.textContent = email;
                    moPhoneVal.textContent = phone;
                    moEmailRow.style.display = email ? 'flex' : 'none';
                    moPhoneRow.style.display = phone ? 'flex' : 'none';
                    moContacts.style.display = (email || phone) ? 'flex' : 'none';
                    moCat.textContent = cat || '';
                    moReg.textContent = reg || '';
                    moCat.style.display = cat ? '' : 'none';
                    moReg.style.display = reg ? '' : 'none';

                    mo.style.display = 'flex';
                }

                function closeModal() {
                    mo.style.display = 'none';
                }

                document.querySelectorAll('.ti-preview-btn').forEach(function(btn) {
                    btn.addEventListener('click', function() {
                        openModal(btn);
                    });
                });

                document.getElementById('ti-mo-x').addEventListener('click', closeModal);
                mo.addEventListener('click', function(e) {
                    if (e.target === mo) closeModal();
                });
                document.addEventListener('keydown', function(e) {
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

        foreach ($submitted as $row) {
            if (empty($row['include'])) {
                continue;
            }
            $toCreate[] = [
                'name'       => \sanitize_text_field($row['name']       ?? ''),
                'position'   => \sanitize_text_field($row['position']   ?? ''),
                'category'   => \sanitize_text_field($row['category']   ?? ''),
                'region'     => \sanitize_key($row['region']            ?? 'all'),
                'email'      => \sanitize_email($row['email']           ?? ''),
                'phone'      => \sanitize_text_field($row['phone']      ?? ''),
                'image_path' => \sanitize_text_field($row['image_path'] ?? ''),
                'order'      => (int) ($row['order']                    ?? 0),
            ];
        }

        $created = TeamImportHandler::bulkCreate($toCreate, $data['temp_dir']);

        \delete_transient('planetario_team_import_' . $token);
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
        $formUrl  = \esc_url(\admin_url('edit.php?post_type=team_member&page=' . self::MENU_SLUG));
    ?>
        <style>
            #ti-up {
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
                max-width: 680px;
                -webkit-font-smoothing: antialiased
            }

            #ti-up * {
                box-sizing: border-box
            }

            .ti-up-hd {
                margin-bottom: 22px;
                padding-bottom: 16px;
                border-bottom: 2px solid #eef1f8
            }

            .ti-up-hd h1 {
                font-size: 19px;
                font-weight: 700;
                color: #0c1730;
                margin: 0;
                padding: 0
            }

            .ti-up-hd p {
                margin: 6px 0 0;
                font-size: 13px;
                color: #64748b
            }

            .ti-up-card {
                background: #fff;
                border: 1px solid #e2e8f0;
                border-radius: 10px;
                overflow: hidden;
                box-shadow: 0 1px 4px rgba(0, 0, 0, .06)
            }

            .ti-up-body {
                padding: 28px 28px 24px
            }

            .ti-up-zone {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                gap: 10px;
                border: 2px dashed #d0d9e8;
                border-radius: 8px;
                padding: 32px 24px;
                background: #fafbfc;
                cursor: pointer;
                transition: border-color .2s, background .2s;
                text-align: center
            }

            .ti-up-zone:hover,
            .ti-up-zone.ti-zone-ready {
                border-color: #0c1730;
                background: #f0f4fa;
                border-style: solid
            }

            .ti-up-zone svg {
                opacity: .35;
                transition: opacity .2s
            }

            .ti-up-zone.ti-zone-ready svg {
                opacity: .7
            }

            .ti-up-zone-label {
                font-size: 13.5px;
                font-weight: 600;
                color: #3a4564
            }

            .ti-up-zone-sub {
                font-size: 12px;
                color: #94a3b8
            }

            .ti-file-input {
                position: absolute;
                inset: 0;
                opacity: 0;
                cursor: pointer;
                width: 100%;
                height: 100%
            }

            .ti-up-zone-wrap {
                position: relative;
                width: 100%
            }

            .ti-file-badge {
                display: none;
                align-items: center;
                gap: 8px;
                margin-top: 10px;
                padding: 9px 14px;
                background: #eef6ff;
                border: 1px solid #c5d8f5;
                border-radius: 7px;
                font-size: 12.5px;
                color: #1e3a5f;
                font-weight: 500;
                word-break: break-all
            }

            .ti-file-badge svg {
                flex-shrink: 0;
                color: #2271b1
            }

            .ti-up-info {
                background: #f4f6fb;
                border-radius: 8px;
                padding: 16px 18px;
                margin-bottom: 20px
            }

            .ti-up-info h3 {
                font-size: 11px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: .6px;
                color: #94a3b8;
                margin: 0 0 10px
            }

            .ti-up-info pre {
                margin: 0 0 12px;
                font-size: 12px;
                color: #3a4564;
                background: #fff;
                border: 1px solid #e2e8f0;
                border-radius: 6px;
                padding: 10px 12px;
                line-height: 1.6;
                overflow-x: auto
            }

            .ti-up-info ul {
                margin: 0;
                padding-left: 16px;
                font-size: 12px;
                color: #64748b;
                line-height: 1.8
            }

            .ti-up-info ul li {
                margin: 0
            }

            .ti-up-footer {
                padding: 16px 28px;
                border-top: 1px solid #f0f4f8;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 12px
            }

            .ti-up-size {
                font-size: 11.5px;
                color: #94a3b8
            }

            .ti-up-submit {
                padding: 9px 24px;
                font-size: 13px;
                font-weight: 600;
                color: #fff;
                background: #0c1730;
                border: none;
                border-radius: 6px;
                cursor: pointer;
                letter-spacing: .15px;
                font-family: inherit;
                transition: background .15s
            }

            .ti-up-submit:hover {
                background: #1e2a4f
            }

            .ti-up-submit:disabled {
                opacity: .5;
                cursor: not-allowed
            }

            .ti-fname {
                font-size: 12px;
                color: #3a4564;
                font-weight: 500;
                margin-top: 6px;
                word-break: break-all
            }
        </style>

        <div id="ti-up">

            <div class="ti-up-hd" style="margin-top:20px;">
                <h1>Import Team Members</h1>
                <p>Upload a ZIP archive to bulk-create team member posts from photo files.</p>
            </div>

            <?php if ($imported > 0) : ?>
                <div class="notice notice-success is-dismissible" style="margin-bottom:20px;">
                    <p>
                        <strong><?php echo $imported; ?> team member<?php echo $imported !== 1 ? 's' : ''; ?> created successfully.</strong>
                        &nbsp;
                        <a href="<?php echo \esc_url(\admin_url('edit.php?post_type=team_member')); ?>">View all team members &rarr;</a>
                    </p>
                </div>
            <?php endif; ?>

            <?php if (! empty($_GET['import_error'])) : ?>
                <div class="notice notice-error is-dismissible" style="margin-bottom:20px;">
                    <p><?php echo \esc_html(\urldecode($_GET['import_error'])); ?></p>
                </div>
            <?php endif; ?>

            <div class="ti-up-card">
                <form method="post" enctype="multipart/form-data" action="<?php echo $formUrl; ?>" id="ti-upload-form">
                    <?php \wp_nonce_field(self::NONCE_ACTION); ?>
                    <input type="hidden" name="_action" value="upload_zip">

                    <div class="ti-up-body">

                        <div class="ti-up-zone-wrap" style="margin-bottom: 24px;">
                            <div class="ti-up-zone" id="ti-drop-zone">

                            </div>
                            <input type="file" class="ti-file-input" id="team_zip" name="team_zip" accept=".zip" required>
                        </div>


                        <div class="ti-up-info">
                            <h3>Expected ZIP structure</h3>
                            <pre>FOLDER_NAME/
  FIRST LAST - JOB TITLE.jpg

FOLDER_WITH_SUBFOLDERS/
  SUBFOLDER_NAME/
    FIRST LAST.png</pre>
                            <ul>
                                <li>Folder name &rarr; <strong>Category</strong> &nbsp;(e.g. <code>BOD</code> &rarr; Board of Directors)</li>
                                <li>Folders containing <code>BOHOL</code> or <code>CEBU</code> auto-set the <strong>Region</strong></li>
                                <li>Sub-folder name &rarr; <strong>Position</strong> (e.g. <code>DM</code>, <code>SDM</code>)</li>
                                <li>File stem <code>NAME - POSITION</code> is split on &ldquo;&nbsp;&mdash;&nbsp;&rdquo; and title-cased</li>
                            </ul>
                        </div>

                    </div>

                    <div class="ti-up-footer">
                        <span class="ti-up-size">Max upload size: <?php echo \esc_html(\size_format(\wp_max_upload_size())); ?></span>
                        <button type="submit" class="ti-up-submit" id="ti-up-btn">
                            Upload &amp; Preview &rarr;
                        </button>
                    </div>
                </form>
            </div>

        </div>

        <script>
            (function() {
                var input = document.getElementById('team_zip');
                var zone = document.getElementById('ti-drop-zone');
                var badge = document.getElementById('ti-file-badge');
                var label = document.getElementById('ti-file-name');
                var btn = document.getElementById('ti-up-btn');
                zone.innerHTML = `<svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#0c1730" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4" />
                                    <polyline points="17 8 12 3 7 8" />
                                    <line x1="12" y1="3" x2="12" y2="15" />
                                </svg>
                                <span class="ti-up-zone-label">Click to choose or drag &amp; drop your ZIP</span>
                                <span class="ti-up-zone-sub">Accepted format: <strong>.zip</strong></span>`;
                input.addEventListener('change', function() {
                    if (input.files && input.files[0]) {
                        zone.innerHTML = `Selected File: <b><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M13 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V9z" />
                                <polyline points="13 2 13 9 20 9" />
                            </svg>&nbsp;${input.files[0].name}</b><span style="opacity: 0.4;">Click to change</span>`
                        zone.classList.add('ti-zone-ready');
                    }
                });

                document.getElementById('ti-upload-form').addEventListener('submit', function() {
                    btn.disabled = true;
                    btn.textContent = 'Uploading…';
                });
            })();
        </script>
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
