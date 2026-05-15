<?php

namespace App\Admin;

class ThemeColorsPage
{
    public const MENU_SLUG  = 'planetario-theme-colors';
    public const OPT_PREFIX = 'planetario_color_';

    public const DEFAULTS = [
        'accent'   => '#88e0b8',
        'accent_2' => '#6dc69d',
        'bg'       => '#060d1f',
        'bg_2'     => '#0c1730',
        'bg_3'     => '#131e3e',
        'line'     => '#1e2a4f',
        'line_2'   => '#2a3a6a',
        'ink'      => '#ebeef8',
        'ink_2'    => '#b9c1d8',
        'ink_3'    => '#7d869f',
        'danger'   => '#c8553c',
    ];

    private const LABELS = [
        'accent'   => ['Accent (primary)',     'Buttons, links, highlights, emphasis.'],
        'accent_2' => ['Accent (hover/alt)',   'Hover state for primary buttons.'],
        'bg'       => ['Background',           'Page background.'],
        'bg_2'     => ['Background — tint 2',  'Cards, panels.'],
        'bg_3'     => ['Background — tint 3',  'Inner / nested surfaces.'],
        'line'     => ['Border / line',        ''],
        'line_2'   => ['Border / line — 2',    ''],
        'ink'      => ['Text — primary',       ''],
        'ink_2'    => ['Text — secondary',     ''],
        'ink_3'    => ['Text — muted',         ''],
        'danger'   => ['Danger / error',       ''],
    ];

    public static function register(): void
    {
        \add_action('admin_menu', [self::class, 'addMenu']);
        \add_action('admin_init', [self::class, 'handleSave']);
    }

    public static function addMenu(): void
    {
        \add_menu_page(
            'Theme Colors',
            'Theme Colors',
            'manage_options',
            self::MENU_SLUG,
            [self::class, 'render'],
            'dashicons-art',
            4
        );
    }

    public static function get(string $key): string
    {
        $fallback = self::DEFAULTS[$key] ?? '#000000';
        $value    = (string) \get_option(self::OPT_PREFIX . $key, '');

        return self::sanitizeHex($value, $fallback);
    }

    public static function handleSave(): void
    {
        if (! isset($_POST['planetario_theme_colors_nonce'])) {
            return;
        }

        if (! \current_user_can('manage_options')) {
            \wp_die(\esc_html__('Insufficient permissions.', 'sage'));
        }

        if (! \check_admin_referer('planetario_theme_colors', 'planetario_theme_colors_nonce')) {
            return;
        }

        foreach (array_keys(self::DEFAULTS) as $key) {
            $raw   = (string) \wp_unslash($_POST[self::OPT_PREFIX . $key] ?? '');
            $value = self::sanitizeHex($raw, self::DEFAULTS[$key]);
            \update_option(self::OPT_PREFIX . $key, $value);
        }

        \add_settings_error(
            'planetario_theme_colors',
            'saved',
            \__('Theme colors updated.', 'sage'),
            'updated'
        );
        \set_transient('planetario_theme_colors_notices', \get_settings_errors('planetario_theme_colors'), 30);

        \wp_safe_redirect(\add_query_arg('updated', '1', \menu_page_url(self::MENU_SLUG, false)));
        exit;
    }

    public static function render(): void
    {
        $notices = \get_transient('planetario_theme_colors_notices');
        if (is_array($notices)) {
            \delete_transient('planetario_theme_colors_notices');
            foreach ($notices as $n) {
                printf(
                    '<div class="notice notice-%s is-dismissible"><p>%s</p></div>',
                    \esc_attr($n['type']),
                    \esc_html($n['message'])
                );
            }
        }
        ?>
        <div class="wrap planetario-theme-colors">
            <h1><?php echo \esc_html__('Theme Colors', 'sage'); ?></h1>
            <p class="description"><?php echo \esc_html__('Brand palette. Changes apply site-wide via CSS variables. Clear page cache after saving.', 'sage'); ?></p>

            <style>
                .planetario-theme-colors .color-row { display:flex; align-items:center; gap:12px; }
                .planetario-theme-colors input[type=color] { width:54px; height:36px; padding:2px; border:1px solid #dcdcde; background:#fff; cursor:pointer; }
                .planetario-theme-colors input[type=text] { font-family: ui-monospace, SFMono-Regular, Menlo, monospace; width:120px; }
                .planetario-theme-colors .swatch-default { display:inline-block; width:16px; height:16px; border:1px solid #dcdcde; vertical-align:middle; margin-right:6px; border-radius:3px; }
            </style>

            <form method="post" action="">
                <?php \wp_nonce_field('planetario_theme_colors', 'planetario_theme_colors_nonce'); ?>

                <table class="form-table" role="presentation">
                    <?php foreach (self::LABELS as $key => [$label, $help]): ?>
                        <?php
                        $current = self::get($key);
                        $default = self::DEFAULTS[$key];
                        $inputId = self::OPT_PREFIX . $key;
                        ?>
                        <tr>
                            <th scope="row">
                                <label for="<?php echo \esc_attr($inputId); ?>"><?php echo \esc_html($label); ?></label>
                            </th>
                            <td>
                                <div class="color-row">
                                    <input type="color"
                                           id="<?php echo \esc_attr($inputId); ?>"
                                           value="<?php echo \esc_attr($current); ?>"
                                           data-target="<?php echo \esc_attr($inputId . '_hex'); ?>">
                                    <input type="text"
                                           id="<?php echo \esc_attr($inputId . '_hex'); ?>"
                                           name="<?php echo \esc_attr($inputId); ?>"
                                           value="<?php echo \esc_attr($current); ?>"
                                           pattern="^#?[0-9a-fA-F]{6}$"
                                           maxlength="7"
                                           data-source="<?php echo \esc_attr($inputId); ?>"
                                           class="regular-text">
                                </div>
                                <?php if ($help !== ''): ?>
                                    <p class="description"><?php echo \esc_html($help); ?></p>
                                <?php endif; ?>
                                <p class="description">
                                    <span class="swatch-default" style="background: <?php echo \esc_attr($default); ?>;"></span>
                                    <?php
                                    printf(
                                        \esc_html__('Default: %s', 'sage'),
                                        '<code>' . \esc_html($default) . '</code>'
                                    );
                                    ?>
                                </p>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>

                <?php \submit_button(\__('Save changes', 'sage')); ?>
            </form>
        </div>

        <script>
        (function () {
            document.querySelectorAll('input[type=color][data-target]').forEach(function (picker) {
                var hex = document.getElementById(picker.dataset.target);
                if (!hex) return;
                picker.addEventListener('input', function () { hex.value = picker.value; });
                hex.addEventListener('input', function () {
                    var v = hex.value.trim();
                    if (/^#?[0-9a-fA-F]{6}$/.test(v)) {
                        picker.value = v.charAt(0) === '#' ? v : '#' + v;
                    }
                });
            });
        })();
        </script>
        <?php
    }

    private static function sanitizeHex(string $value, string $fallback): string
    {
        $value = trim($value);
        if (preg_match('/^#?([0-9a-f]{3}|[0-9a-f]{6})$/i', $value, $m)) {
            $hex = strtolower($m[1]);
            if (strlen($hex) === 3) {
                $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
            }
            return '#' . $hex;
        }
        return $fallback;
    }
}
