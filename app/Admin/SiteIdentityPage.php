<?php

namespace App\Admin;

class SiteIdentityPage
{
    public const MENU_SLUG = 'planetario-site-identity';

    public const OPT_BRAND_NAME    = 'planetario_brand_name';
    public const OPT_LOGO_DARK_ID  = 'planetario_logo_dark_id';
    public const OPT_OG_IMAGE_ID   = 'planetario_og_image_id';

    public static function register(): void
    {
        \add_action('admin_menu', [self::class, 'addMenu']);
        \add_action('admin_init', [self::class, 'handleSave']);
        \add_action('admin_enqueue_scripts', [self::class, 'enqueueAssets']);
    }

    public static function addMenu(): void
    {
        \add_menu_page(
            'Site Identity',
            'Site Identity',
            'manage_options',
            self::MENU_SLUG,
            [self::class, 'render'],
            'dashicons-admin-site-alt3',
            3
        );
    }

    public static function enqueueAssets(string $hook): void
    {
        if ($hook !== 'toplevel_page_' . self::MENU_SLUG) {
            return;
        }

        \wp_enqueue_media();
    }

    public static function handleSave(): void
    {
        if (! isset($_POST['planetario_site_identity_nonce'])) {
            return;
        }

        if (! \current_user_can('manage_options')) {
            \wp_die(\esc_html__('Insufficient permissions.', 'sage'));
        }

        if (! \check_admin_referer('planetario_site_identity', 'planetario_site_identity_nonce')) {
            return;
        }

        $title = \sanitize_text_field(\wp_unslash($_POST['blogname'] ?? ''));
        if ($title !== '') {
            \update_option('blogname', $title);
        }

        $tagline = \sanitize_text_field(\wp_unslash($_POST['blogdescription'] ?? ''));
        \update_option('blogdescription', $tagline);

        $brand = \sanitize_text_field(\wp_unslash($_POST['brand_name'] ?? ''));
        \update_option(self::OPT_BRAND_NAME, $brand);

        $logo = (int) ($_POST['custom_logo'] ?? 0);
        if ($logo > 0) {
            \set_theme_mod('custom_logo', $logo);
        } else {
            \remove_theme_mod('custom_logo');
        }

        $logoDark = (int) ($_POST['logo_dark'] ?? 0);
        if ($logoDark > 0) {
            \update_option(self::OPT_LOGO_DARK_ID, $logoDark);
        } else {
            \delete_option(self::OPT_LOGO_DARK_ID);
        }

        $icon = (int) ($_POST['site_icon'] ?? 0);
        if ($icon > 0) {
            \update_option('site_icon', $icon);
        } else {
            \delete_option('site_icon');
        }

        $og = (int) ($_POST['og_image'] ?? 0);
        if ($og > 0) {
            \update_option(self::OPT_OG_IMAGE_ID, $og);
        } else {
            \delete_option(self::OPT_OG_IMAGE_ID);
        }

        \add_settings_error(
            'planetario_site_identity',
            'saved',
            \__('Site identity updated.', 'sage'),
            'updated'
        );
        \set_transient('planetario_site_identity_notices', \get_settings_errors('planetario_site_identity'), 30);

        \wp_safe_redirect(\add_query_arg('updated', '1', \menu_page_url(self::MENU_SLUG, false)));
        exit;
    }

    public static function render(): void
    {
        $notices = \get_transient('planetario_site_identity_notices');
        if (is_array($notices)) {
            \delete_transient('planetario_site_identity_notices');
            foreach ($notices as $n) {
                printf(
                    '<div class="notice notice-%s is-dismissible"><p>%s</p></div>',
                    \esc_attr($n['type']),
                    \esc_html($n['message'])
                );
            }
        }

        $title    = \get_option('blogname', '');
        $tagline  = \get_option('blogdescription', '');
        $brand    = \get_option(self::OPT_BRAND_NAME, '');
        $logoId   = (int) \get_theme_mod('custom_logo', 0);
        $logoDark = (int) \get_option(self::OPT_LOGO_DARK_ID, 0);
        $iconId   = (int) \get_option('site_icon', 0);
        $ogId     = (int) \get_option(self::OPT_OG_IMAGE_ID, 0);
        ?>
        <div class="wrap planetario-site-identity">
            <h1><?php echo \esc_html__('Site Identity', 'sage'); ?></h1>
            <p class="description"><?php echo \esc_html__('Manage your site title, logo, favicon, and social preview image. Changes apply site-wide.', 'sage'); ?></p>

            <style>
                .planetario-media-field img { display:block; max-width:240px; max-height:120px; border:1px solid #dcdcde; background:#f6f7f7; padding:6px; margin-bottom:8px; }
                .planetario-media-field .button-link { color:#b32d2e; text-decoration:underline; margin-left:8px; }
            </style>

            <form method="post" action="">
                <?php \wp_nonce_field('planetario_site_identity', 'planetario_site_identity_nonce'); ?>

                <h2 class="title"><?php echo \esc_html__('Names', 'sage'); ?></h2>
                <table class="form-table" role="presentation">
                    <tr>
                        <th scope="row"><label for="blogname"><?php echo \esc_html__('Site title', 'sage'); ?></label></th>
                        <td>
                            <input name="blogname" id="blogname" type="text" value="<?php echo \esc_attr($title); ?>" class="regular-text">
                            <p class="description"><?php echo \esc_html__('Shown in browser tabs and search results. Synced to Settings → General.', 'sage'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="blogdescription"><?php echo \esc_html__('Tagline', 'sage'); ?></label></th>
                        <td>
                            <input name="blogdescription" id="blogdescription" type="text" value="<?php echo \esc_attr($tagline); ?>" class="regular-text">
                            <p class="description"><?php echo \esc_html__('Short description. Used by themes and search engines.', 'sage'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="brand_name"><?php echo \esc_html__('Brand display name', 'sage'); ?></label></th>
                        <td>
                            <input name="brand_name" id="brand_name" type="text" value="<?php echo \esc_attr($brand); ?>" class="regular-text" placeholder="<?php echo \esc_attr($title); ?>">
                            <p class="description"><?php echo \esc_html__('Optional. Custom display name for theme copy. Defaults to the site title if blank.', 'sage'); ?></p>
                        </td>
                    </tr>
                </table>

                <h2 class="title"><?php echo \esc_html__('Images', 'sage'); ?></h2>
                <table class="form-table" role="presentation">
                    <?php self::renderMediaRow('custom_logo', \__('Logo', 'sage'), $logoId, \__('Primary site logo. Used in the site header.', 'sage')); ?>
                    <?php self::renderMediaRow('logo_dark', \__('Logo — dark variant', 'sage'), $logoDark, \__('Optional. Used on dark backgrounds (e.g. footer).', 'sage')); ?>
                    <?php self::renderMediaRow('site_icon', \__('Site Icon (Favicon)', 'sage'), $iconId, \__('Square, at least 512×512px. Used as browser favicon and app touch icon.', 'sage')); ?>
                    <?php self::renderMediaRow('og_image', \__('Default social share image', 'sage'), $ogId, \__('Recommended 1200×630px. Used for Open Graph / Twitter previews when a page has no featured image.', 'sage')); ?>
                </table>

                <?php \submit_button(\__('Save changes', 'sage')); ?>
            </form>
        </div>

        <script>
        (function ($) {
            $(document).on('click', '.planetario-media-pick', function (e) {
                e.preventDefault();
                var $btn = $(this);
                var fieldKey = $btn.data('field');
                var $input = $('#planetario_field_' + fieldKey);
                var $preview = $('#planetario_preview_' + fieldKey);
                var $remove = $('#planetario_remove_' + fieldKey);

                var frame = wp.media({
                    title: $btn.data('title') || 'Select image',
                    button: { text: 'Use this image' },
                    library: { type: 'image' },
                    multiple: false
                });

                frame.on('select', function () {
                    var att = frame.state().get('selection').first().toJSON();
                    var src = (att.sizes && att.sizes.medium && att.sizes.medium.url) || att.url;
                    $input.val(att.id);
                    $preview.attr('src', src).show();
                    $remove.show();
                });

                frame.open();
            });

            $(document).on('click', '.planetario-media-remove', function (e) {
                e.preventDefault();
                var fieldKey = $(this).data('field');
                $('#planetario_field_' + fieldKey).val('');
                $('#planetario_preview_' + fieldKey).attr('src', '').hide();
                $(this).hide();
            });
        })(jQuery);
        </script>
        <?php
    }

    private static function renderMediaRow(string $key, string $label, int $attachmentId, string $description): void
    {
        $src = '';
        if ($attachmentId > 0) {
            $img = \wp_get_attachment_image_src($attachmentId, 'medium');
            if (is_array($img)) {
                $src = (string) $img[0];
            }
        }
        $hasImage = $src !== '';
        ?>
        <tr>
            <th scope="row"><label><?php echo \esc_html($label); ?></label></th>
            <td>
                <div class="planetario-media-field">
                    <img id="planetario_preview_<?php echo \esc_attr($key); ?>"
                         src="<?php echo \esc_url($src); ?>"
                         alt=""
                         style="<?php echo $hasImage ? '' : 'display:none;'; ?>">
                    <input type="hidden"
                           id="planetario_field_<?php echo \esc_attr($key); ?>"
                           name="<?php echo \esc_attr($key); ?>"
                           value="<?php echo \esc_attr((string) $attachmentId); ?>">
                    <p>
                        <button type="button"
                                class="button planetario-media-pick"
                                data-field="<?php echo \esc_attr($key); ?>"
                                data-title="<?php echo \esc_attr($label); ?>">
                            <?php echo $hasImage ? \esc_html__('Replace image', 'sage') : \esc_html__('Select image', 'sage'); ?>
                        </button>
                        <button type="button"
                                id="planetario_remove_<?php echo \esc_attr($key); ?>"
                                class="button-link planetario-media-remove"
                                data-field="<?php echo \esc_attr($key); ?>"
                                style="<?php echo $hasImage ? '' : 'display:none;'; ?>">
                            <?php echo \esc_html__('Remove', 'sage'); ?>
                        </button>
                    </p>
                    <p class="description"><?php echo \esc_html($description); ?></p>
                </div>
            </td>
        </tr>
        <?php
    }
}
