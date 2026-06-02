<?php

namespace App\Admin;

class MetaboxSettingsPage
{
    public const MENU_SLUG    = 'planetario-metabox-settings';
    public const OPT_KEY      = 'planetario_metabox_settings';
    public const CORE_OPT_KEY = 'planetario_page_core_metaboxes';
    public const PAGE_OPT_KEY = 'planetario_page_metaboxes';
    public const NONCE        = 'planetario_metabox_settings';

    // [label, description, wp_metabox_id|null]
    public const METABOXES = [
        'hero_slides'      => ['Hero Background Slides',               'Custom PHP meta box — front page slide backgrounds.',       'planetario_hero_slides'],
        'front_page_acf'   => ['Front Page Fields (ACF)',              'ACF group bound to the static front page.',                 'acf-group_front_page'],
        'about_page_acf'   => ['About Page Fields (ACF)',              'ACF group bound to the page with slug "about".',            'acf-group_about_page'],
        'page_intros_acf'  => ['Page Intros / Section Content (ACF)', 'ACF group for blog, contact, developers, events, etc.',     'acf-group_page_content'],
        'page_admin_links' => ['Page Admin Links (ACF)',               'ACF group that adds quick-edit links to page sidebars.',    null],
    ];

    public const CORE_METABOXES = [
        'editor'          => 'Text Editor',
        'page_attributes' => 'Page Attributes',
        'featured_image'  => 'Featured Image',
    ];

    public static function register(): void
    {
        \add_action('admin_menu', [self::class, 'addMenu']);
        \add_action('admin_init', [self::class, 'handleSave']);
        \add_action('add_meta_boxes_page', [self::class, 'removeDisabledMetaboxes']);
        \add_filter('use_block_editor_for_post', [self::class, 'maybeDisableBlockEditor'], 10, 2);
    }

    public static function addMenu(): void
    {
        \add_submenu_page(
            'edit.php?post_type=page',
            'Metabox Settings',
            'Metabox Settings',
            'manage_options',
            self::MENU_SLUG,
            [self::class, 'render']
        );
    }

    public static function handleSave(): void
    {
        if (! isset($_POST['planetario_metabox_settings_nonce'])) {
            return;
        }

        if (! \current_user_can('manage_options')) {
            \wp_die(\esc_html__('Insufficient permissions.', 'sage'));
        }

        if (! \check_admin_referer(self::NONCE, 'planetario_metabox_settings_nonce')) {
            return;
        }

        $pageId = (int) ($_POST['page_id'] ?? 0);
        if (! $pageId || ! \get_post($pageId)) {
            return;
        }

        $settings = (array) \get_option(self::PAGE_OPT_KEY, []);

        foreach (array_keys(self::METABOXES) as $key) {
            $settings[$pageId][$key] = isset($_POST['metabox_' . $key]);
        }

        foreach (array_keys(self::CORE_METABOXES) as $key) {
            $settings[$pageId][$key] = isset($_POST['core_metabox_' . $key]);
        }

        \update_option(self::PAGE_OPT_KEY, $settings);

        \wp_safe_redirect(
            \add_query_arg(
                ['updated' => '1', 'page_id' => $pageId],
                \admin_url('edit.php?post_type=page&page=' . self::MENU_SLUG)
            )
        );
        exit;
    }

    public static function isEnabled(string $key): bool
    {
        $settings = (array) \get_option(self::OPT_KEY, []);
        return (bool) ($settings[$key] ?? true);
    }

    public static function isEnabledForPage(int $pageId, string $key): bool
    {
        $settings = (array) \get_option(self::PAGE_OPT_KEY, []);
        return (bool) (($settings[$pageId] ?? [])[$key] ?? true);
    }

    public static function isCoreEnabled(int $pageId, string $key): bool
    {
        $new = (array) \get_option(self::PAGE_OPT_KEY, []);

        if (isset($new[$pageId][$key])) {
            return (bool) $new[$pageId][$key];
        }

        // Backward compat: fall back to old option
        $old = (array) \get_option(self::CORE_OPT_KEY, []);
        return (bool) (($old[$pageId] ?? [])[$key] ?? true);
    }

    public static function removeDisabledMetaboxes(\WP_Post $post): void
    {
        // Core metaboxes
        if (! self::isCoreEnabled($post->ID, 'editor')) {
            \remove_meta_box('postdivrich', 'page', 'normal');
            \remove_meta_box('postdiv',     'page', 'normal');
        }

        if (! self::isCoreEnabled($post->ID, 'page_attributes')) {
            \remove_meta_box('pageparentdiv', 'page', 'side');
        }

        if (! self::isCoreEnabled($post->ID, 'featured_image')) {
            \remove_meta_box('postimagediv', 'page', 'side');
        }

        // Custom / ACF metaboxes per-page
        foreach (self::METABOXES as $key => $meta) {
            if (self::isEnabledForPage($post->ID, $key)) {
                continue;
            }

            $wpId = $meta[2];

            if ($wpId !== null) {
                \remove_meta_box($wpId, 'page', 'normal');
                \remove_meta_box($wpId, 'page', 'side');
            } elseif ($key === 'page_admin_links') {
                \remove_meta_box('acf-group_admin_link_' . $post->post_name, 'page', 'normal');
            }
        }
    }

    /**
     * Disables the block editor for pages where the editor metabox is turned off.
     * Falls back to the classic editor so the page is still editable via custom metaboxes.
     */
    public static function maybeDisableBlockEditor(bool $useBlockEditor, \WP_Post $post): bool
    {
        if ($post->post_type !== 'page') {
            return $useBlockEditor;
        }

        return self::isCoreEnabled($post->ID, 'editor') ? $useBlockEditor : false;
    }

    public static function render(): void
    {
        $pages = \get_posts([
            'post_type'      => 'page',
            'post_status'    => ['publish', 'draft', 'private', 'pending', 'future'],
            'posts_per_page' => -1,
            'orderby'        => 'menu_order title',
            'order'          => 'ASC',
            'no_found_rows'  => true,
        ]);

        $pageId     = isset($_GET['page_id']) ? (int) $_GET['page_id'] : 0;
        $activePage = $pageId ? \get_post($pageId) : null;

        if ($pageId && ! $activePage) {
            $pageId = 0;
        }
        ?>
        <div class="wrap">
            <h1><?php echo \esc_html__('Metabox Settings', 'sage'); ?></h1>

            <?php
            if (isset($_GET['updated']) && $_GET['updated'] === '1' && $activePage):
            ?>
                <div class="notice notice-success is-dismissible">
                    <p>
                        <?php
                        printf(
                            \esc_html__('Metabox settings saved for "%s".', 'sage'),
                            \esc_html($activePage->post_title ?: \__('(no title)', 'sage'))
                        );
                        ?>
                    </p>
                </div>
            <?php
            endif;
            ?>

            <form method="get" action="" style="margin-top:20px; display:flex; align-items:center; gap:10px;">
                <input type="hidden" name="post_type" value="page">
                <input type="hidden" name="page" value="<?php echo \esc_attr(self::MENU_SLUG); ?>">

                <label for="metabox-page-select" style="font-weight:600;">
                    <?php echo \esc_html__('Configure page:', 'sage'); ?>
                </label>

                <select id="metabox-page-select" name="page_id" style="min-width:260px;">
                    <option value=""><?php echo \esc_html__('— Select a page —', 'sage'); ?></option>
                    <?php
                    foreach ($pages as $p):
                        $title = $p->post_title ?: \__('(no title)', 'sage');
                    ?>
                        <option value="<?php echo \esc_attr($p->ID); ?>" <?php \selected($pageId, $p->ID); ?>>
                            <?php echo \esc_html($title); ?>
                            <?php
                            if ($p->post_status !== 'publish'):
                            ?>
                                (<?php echo \esc_html(ucfirst($p->post_status)); ?>)
                            <?php
                            endif;
                            ?>
                        </option>
                    <?php
                    endforeach;
                    ?>
                </select>

                <?php \submit_button(\__('Load Settings', 'sage'), 'secondary', '', false); ?>
            </form>

            <?php
            if (! $activePage):
            ?>
                <p style="margin-top:24px; color:#646970;">
                    <?php echo \esc_html__('Select a page above to view and configure its metabox visibility.', 'sage'); ?>
                </p>
            <?php
            else:
            ?>
                <hr style="margin:24px 0;">

                <h2 style="margin-bottom:4px;">
                    <?php echo \esc_html($activePage->post_title ?: \__('(no title)', 'sage')); ?>
                </h2>
                <p style="color:#646970; margin-top:0;">
                    <?php
                    printf(
                        \esc_html__('Status: %s', 'sage'),
                        \esc_html(ucfirst($activePage->post_status))
                    );
                    ?>
                    &nbsp;&mdash;&nbsp;
                    <a href="<?php echo \esc_url(\get_edit_post_link($activePage->ID)); ?>">
                        <?php echo \esc_html__('Edit page', 'sage'); ?>
                    </a>
                </p>

                <form method="post" action="">
                    <?php \wp_nonce_field(self::NONCE, 'planetario_metabox_settings_nonce'); ?>
                    <input type="hidden" name="page_id" value="<?php echo \esc_attr($pageId); ?>">

                    <h2 class="title"><?php echo \esc_html__('Custom & ACF Metaboxes', 'sage'); ?></h2>
                    <p class="description">
                        <?php echo \esc_html__('Show or hide theme-registered metaboxes on this page. Note: hiding a metabox here does not remove the ACF field group — data is preserved.', 'sage'); ?>
                    </p>

                    <table class="form-table" role="presentation">
                        <tbody>
                            <?php
                            foreach (self::METABOXES as $key => $meta):
                                [$label, $description] = $meta;
                                $enabled = self::isEnabledForPage($pageId, $key);
                            ?>
                                <tr>
                                    <th scope="row"><?php echo \esc_html($label); ?></th>
                                    <td>
                                        <label>
                                            <input
                                                type="checkbox"
                                                name="<?php echo \esc_attr('metabox_' . $key); ?>"
                                                value="1"
                                                <?php \checked($enabled); ?>
                                            >
                                            <?php echo \esc_html__('Visible', 'sage'); ?>
                                        </label>
                                        <p class="description"><?php echo \esc_html($description); ?></p>
                                    </td>
                                </tr>
                            <?php
                            endforeach;
                            ?>
                        </tbody>
                    </table>

                    <hr>

                    <h2 class="title"><?php echo \esc_html__('Core Metaboxes', 'sage'); ?></h2>
                    <p class="description">
                        <?php echo \esc_html__('Control which WordPress core metaboxes appear when editing this page. Hiding "Text Editor" also disables the block editor for this page.', 'sage'); ?>
                    </p>

                    <table class="form-table" role="presentation">
                        <tbody>
                            <?php
                            foreach (self::CORE_METABOXES as $coreKey => $coreLabel):
                                $enabled = self::isCoreEnabled($pageId, $coreKey);
                            ?>
                                <tr>
                                    <th scope="row"><?php echo \esc_html($coreLabel); ?></th>
                                    <td>
                                        <label>
                                            <input
                                                type="checkbox"
                                                name="<?php echo \esc_attr('core_metabox_' . $coreKey); ?>"
                                                value="1"
                                                <?php \checked($enabled); ?>
                                            >
                                            <?php echo \esc_html__('Visible', 'sage'); ?>
                                        </label>
                                    </td>
                                </tr>
                            <?php
                            endforeach;
                            ?>
                        </tbody>
                    </table>

                    <p style="margin-top:20px;"><?php \submit_button(\__('Save settings', 'sage'), 'primary', 'submit', false); ?></p>
                </form>
            <?php
            endif;
            ?>
        </div>
        <?php
    }
}
