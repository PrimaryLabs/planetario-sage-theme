<?php

namespace App\Admin;

class MetaboxSettingsPage
{
    public const MENU_SLUG    = 'planetario-metabox-settings';
    public const OPT_KEY      = 'planetario_metabox_settings';
    public const CORE_OPT_KEY = 'planetario_page_core_metaboxes';
    public const NONCE        = 'planetario_metabox_settings';

    public const METABOXES = [
        'hero_slides'      => ['Hero Background Slides',               'Custom PHP meta box — front page slide backgrounds.'],
        'front_page_acf'   => ['Front Page Fields (ACF)',              'ACF group bound to the static front page.'],
        'about_page_acf'   => ['About Page Fields (ACF)',              'ACF group bound to the page with slug "about".'],
        'page_intros_acf'  => ['Page Intros / Section Content (ACF)', 'ACF group bound to blog, contact, developers, events, stories, testimonials, properties, and team pages.'],
        'page_admin_links' => ['Page Admin Links (ACF)',               'ACF group that adds quick-edit links to page sidebars.'],
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
        \add_action('add_meta_boxes_page', [self::class, 'removeCoreMetaboxes']);
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

        $saved = [];
        foreach (array_keys(self::METABOXES) as $key) {
            $saved[$key] = isset($_POST['metabox_' . $key]);
        }
        \update_option(self::OPT_KEY, $saved);

        $coreRaw  = isset($_POST['core_metabox']) && is_array($_POST['core_metabox'])
            ? $_POST['core_metabox']
            : [];
        $pages     = \get_pages(['post_status' => 'any']);
        $pageIds   = array_column($pages, 'ID');
        $coreSaved = [];

        foreach ($pageIds as $id) {
            $raw = isset($coreRaw[$id]) && is_array($coreRaw[$id]) ? $coreRaw[$id] : [];
            foreach (array_keys(self::CORE_METABOXES) as $key) {
                $coreSaved[$id][$key] = isset($raw[$key]);
            }
        }

        \update_option(self::CORE_OPT_KEY, $coreSaved);

        \wp_safe_redirect(
            \add_query_arg('updated', '1', \admin_url('edit.php?post_type=page&page=' . self::MENU_SLUG))
        );
        exit;
    }

    public static function isEnabled(string $key): bool
    {
        $settings = (array) \get_option(self::OPT_KEY, []);
        return (bool) ($settings[$key] ?? true);
    }

    public static function isCoreEnabled(int $pageId, string $key): bool
    {
        $settings = (array) \get_option(self::CORE_OPT_KEY, []);
        return (bool) (($settings[$pageId] ?? [])[$key] ?? true);
    }

    public static function removeCoreMetaboxes(\WP_Post $post): void
    {
        $settings = (array) \get_option(self::CORE_OPT_KEY, []);
        $page     = $settings[$post->ID] ?? [];

        if (! ($page['editor'] ?? true)) {
            \remove_meta_box('postdivrich', 'page', 'normal');
            \remove_meta_box('postdiv',     'page', 'normal');
        }

        if (! ($page['page_attributes'] ?? true)) {
            \remove_meta_box('pageparentdiv', 'page', 'side');
        }

        if (! ($page['featured_image'] ?? true)) {
            \remove_meta_box('postimagediv', 'page', 'side');
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

        $settings = (array) \get_option(self::CORE_OPT_KEY, []);
        $enabled  = (bool) (($settings[$post->ID] ?? [])['editor'] ?? true);

        return $enabled ? $useBlockEditor : false;
    }

    public static function render(): void
    {
        $settings     = (array) \get_option(self::OPT_KEY, []);
        $coreSettings = (array) \get_option(self::CORE_OPT_KEY, []);
        $pages        = \get_pages([
            'post_status'  => 'any',
            'sort_column'  => 'menu_order,post_title',
            'hierarchical' => true,
        ]);
        ?>
        <div class="wrap">
            <h1><?php echo \esc_html__('Metabox Settings', 'sage'); ?></h1>

            <?php if (isset($_GET['updated']) && $_GET['updated'] === '1'): ?>
                <div class="notice notice-success is-dismissible">
                    <p><?php echo \esc_html__('Metabox settings saved.', 'sage'); ?></p>
                </div>
            <?php endif; ?>

            <form method="post" action="">
                <?php \wp_nonce_field(self::NONCE, 'planetario_metabox_settings_nonce'); ?>

                <h2 class="title"><?php echo \esc_html__('Custom & ACF Metaboxes', 'sage'); ?></h2>
                <p class="description"><?php echo \esc_html__('Enable or disable theme-registered metaboxes globally across all pages. Disabled metaboxes are not registered at all.', 'sage'); ?></p>

                <table class="form-table" role="presentation">
                    <tbody>
                        <?php foreach (self::METABOXES as $key => [$label, $description]): ?>
                            <tr>
                                <th scope="row"><?php echo \esc_html($label); ?></th>
                                <td>
                                    <label>
                                        <input
                                            type="checkbox"
                                            name="<?php echo \esc_attr('metabox_' . $key); ?>"
                                            value="1"
                                            <?php \checked($settings[$key] ?? true); ?>
                                        >
                                        <?php echo \esc_html__('Enabled', 'sage'); ?>
                                    </label>
                                    <p class="description"><?php echo \esc_html($description); ?></p>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <hr>

                <h2 class="title"><?php echo \esc_html__('Core Metaboxes per Page', 'sage'); ?></h2>
                <p class="description"><?php echo \esc_html__('Control which WordPress core metaboxes appear when editing each individual page. Unchecking "Text Editor" also disables the block editor for that page.', 'sage'); ?></p>

                <?php if (empty($pages)): ?>
                    <p><?php echo \esc_html__('No pages found.', 'sage'); ?></p>
                <?php else: ?>
                    <table class="wp-list-table widefat fixed striped" style="margin-top:12px;">
                        <thead>
                            <tr>
                                <th style="width:40%"><?php echo \esc_html__('Page', 'sage'); ?></th>
                                <?php foreach (self::CORE_METABOXES as $coreLabel): ?>
                                    <th style="width:20%; text-align:center"><?php echo \esc_html($coreLabel); ?></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pages as $page): ?>
                                <?php $pageData = $coreSettings[$page->ID] ?? []; ?>
                                <tr>
                                    <td>
                                        <strong><?php echo \esc_html($page->post_title ?: \__('(no title)', 'sage')); ?></strong>
                                        <span style="color:#646970; margin-left:6px; font-size:12px"><?php echo \esc_html(ucfirst($page->post_status)); ?></span>
                                    </td>
                                    <?php foreach (array_keys(self::CORE_METABOXES) as $coreKey): ?>
                                        <td style="text-align:center; vertical-align:middle">
                                            <input
                                                type="checkbox"
                                                name="<?php echo \esc_attr("core_metabox[{$page->ID}][{$coreKey}]"); ?>"
                                                value="1"
                                                <?php \checked($pageData[$coreKey] ?? true); ?>
                                                aria-label="<?php echo \esc_attr(self::CORE_METABOXES[$coreKey] . ' — ' . $page->post_title); ?>"
                                            >
                                        </td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>

                <p style="margin-top:20px"><?php \submit_button(\__('Save settings', 'sage'), 'primary', 'submit', false); ?></p>
            </form>
        </div>
        <?php
    }
}
