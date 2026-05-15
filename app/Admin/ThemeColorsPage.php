<?php

namespace App\Admin;

class ThemeColorsPage
{
    public const MENU_SLUG       = 'planetario-theme-colors';
    public const OPT_PREFIX      = 'planetario_color_';
    public const OPT_DEFAULT_MODE = 'planetario_color_default_mode';
    public const MODES = ['dark', 'light', 'auto'];

    public const KEYS = [
        'accent', 'accent_2',
        'bg', 'bg_2', 'bg_3',
        'line', 'line_2',
        'ink', 'ink_2', 'ink_3',
        'danger',
    ];

    public const DEFAULTS_DARK = [
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

    public const DEFAULTS_LIGHT = [
        'accent'   => '#3aae84',
        'accent_2' => '#2d8e6c',
        'bg'       => '#f7f8fc',
        'bg_2'     => '#ffffff',
        'bg_3'     => '#eef1f8',
        'line'     => '#dde2ed',
        'line_2'   => '#c5cdde',
        'ink'      => '#0c1730',
        'ink_2'    => '#3a4564',
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

    public static function get(string $key, string $mode = 'dark'): string
    {
        $mode     = $mode === 'light' ? 'light' : 'dark';
        $fallback = ($mode === 'light' ? self::DEFAULTS_LIGHT : self::DEFAULTS_DARK)[$key] ?? '#000000';
        $value    = (string) \get_option(self::OPT_PREFIX . $mode . '_' . $key, '');

        return self::sanitizeHex($value, $fallback);
    }

    public static function defaultMode(): string
    {
        $stored = (string) \get_option(self::OPT_DEFAULT_MODE, 'auto');
        return in_array($stored, self::MODES, true) ? $stored : 'auto';
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

        foreach (['dark', 'light'] as $mode) {
            $defaults = $mode === 'light' ? self::DEFAULTS_LIGHT : self::DEFAULTS_DARK;
            foreach (self::KEYS as $key) {
                $name  = self::OPT_PREFIX . $mode . '_' . $key;
                $raw   = (string) \wp_unslash($_POST[$name] ?? '');
                $value = self::sanitizeHex($raw, $defaults[$key]);
                \update_option($name, $value);
            }
        }

        $rawMode = (string) \wp_unslash($_POST[self::OPT_DEFAULT_MODE] ?? 'auto');
        $mode    = in_array($rawMode, self::MODES, true) ? $rawMode : 'auto';
        \update_option(self::OPT_DEFAULT_MODE, $mode);

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

    /**
     * Curated palette pairs (dark + light variants per preset).
     */
    public static function presets(): array
    {
        return [
            'sapphire_mint' => [
                'label' => 'Deep Sapphire + Mint',
                'dark'  => self::DEFAULTS_DARK,
                'light' => self::DEFAULTS_LIGHT,
            ],
            'plum_gold' => [
                'label' => 'Midnight Plum + Gold',
                'dark' => [
                    'accent' => '#e8b04b', 'accent_2' => '#caa341',
                    'bg' => '#15091e', 'bg_2' => '#1f0e2b', 'bg_3' => '#2a1538',
                    'line' => '#3a2150', 'line_2' => '#4d2d68',
                    'ink' => '#f5ecf8', 'ink_2' => '#d4c3df', 'ink_3' => '#9b88a8',
                    'danger' => '#d4533f',
                ],
                'light' => [
                    'accent' => '#b8862c', 'accent_2' => '#946922',
                    'bg' => '#faf6fb', 'bg_2' => '#ffffff', 'bg_3' => '#f3ebf5',
                    'line' => '#e3d4e9', 'line_2' => '#c7afd2',
                    'ink' => '#2a1538', 'ink_2' => '#5a3d6c', 'ink_3' => '#9b88a8',
                    'danger' => '#c8553c',
                ],
            ],
            'forest_amber' => [
                'label' => 'Forest + Amber',
                'dark' => [
                    'accent' => '#e6b450', 'accent_2' => '#c89a40',
                    'bg' => '#0a1612', 'bg_2' => '#0f2018', 'bg_3' => '#172d22',
                    'line' => '#21402f', 'line_2' => '#2e5640',
                    'ink' => '#eaf2ed', 'ink_2' => '#b9cdc1', 'ink_3' => '#7a9183',
                    'danger' => '#cd5c5c',
                ],
                'light' => [
                    'accent' => '#b07f1f', 'accent_2' => '#8e651a',
                    'bg' => '#f5f8f5', 'bg_2' => '#ffffff', 'bg_3' => '#eaf2ec',
                    'line' => '#d2e0d6', 'line_2' => '#a8bfb0',
                    'ink' => '#172d22', 'ink_2' => '#3d5648', 'ink_3' => '#7a9183',
                    'danger' => '#c0392b',
                ],
            ],
            'onyx_coral' => [
                'label' => 'Onyx + Coral',
                'dark' => [
                    'accent' => '#f47461', 'accent_2' => '#d76352',
                    'bg' => '#0a0a0c', 'bg_2' => '#141416', 'bg_3' => '#1d1d20',
                    'line' => '#2a2a2e', 'line_2' => '#3a3a40',
                    'ink' => '#f0f0f2', 'ink_2' => '#bfbfc4', 'ink_3' => '#7e7e87',
                    'danger' => '#c8553c',
                ],
                'light' => [
                    'accent' => '#d4503c', 'accent_2' => '#b03e2c',
                    'bg' => '#fafafa', 'bg_2' => '#ffffff', 'bg_3' => '#f2f2f4',
                    'line' => '#e3e3e6', 'line_2' => '#c4c4c9',
                    'ink' => '#1d1d20', 'ink_2' => '#4a4a52', 'ink_3' => '#7e7e87',
                    'danger' => '#a8392a',
                ],
            ],
            'slate_sky' => [
                'label' => 'Slate + Sky',
                'dark' => [
                    'accent' => '#5cbaf1', 'accent_2' => '#4aa1d4',
                    'bg' => '#0b1018', 'bg_2' => '#131a26', 'bg_3' => '#1c2535',
                    'line' => '#283248', 'line_2' => '#37445e',
                    'ink' => '#ecf0f7', 'ink_2' => '#b6c0d2', 'ink_3' => '#7c8699',
                    'danger' => '#e0735c',
                ],
                'light' => [
                    'accent' => '#1f87c1', 'accent_2' => '#136ba0',
                    'bg' => '#f6f8fb', 'bg_2' => '#ffffff', 'bg_3' => '#edf1f7',
                    'line' => '#dce2ec', 'line_2' => '#bcc5d4',
                    'ink' => '#1c2535', 'ink_2' => '#4a5366', 'ink_3' => '#7c8699',
                    'danger' => '#c0533e',
                ],
            ],
            'charcoal_lime' => [
                'label' => 'Charcoal + Lime',
                'dark' => [
                    'accent' => '#c4e057', 'accent_2' => '#a7c046',
                    'bg' => '#121211', 'bg_2' => '#1c1c1a', 'bg_3' => '#262624',
                    'line' => '#33332f', 'line_2' => '#454540',
                    'ink' => '#f1f1ee', 'ink_2' => '#bdbdb6', 'ink_3' => '#7d7d76',
                    'danger' => '#d4634a',
                ],
                'light' => [
                    'accent' => '#789c1d', 'accent_2' => '#5d7a15',
                    'bg' => '#f9f9f7', 'bg_2' => '#ffffff', 'bg_3' => '#f0f0ec',
                    'line' => '#dededa', 'line_2' => '#bdbdb6',
                    'ink' => '#262624', 'ink_2' => '#4d4d49', 'ink_3' => '#7d7d76',
                    'danger' => '#c0533e',
                ],
            ],
            'wine_champagne' => [
                'label' => 'Wine + Champagne',
                'dark' => [
                    'accent' => '#dfc89a', 'accent_2' => '#bfa97a',
                    'bg' => '#1a0c10', 'bg_2' => '#26121a', 'bg_3' => '#321a23',
                    'line' => '#48272f', 'line_2' => '#5d3540',
                    'ink' => '#f5ecf0', 'ink_2' => '#d6c0c8', 'ink_3' => '#9a8088',
                    'danger' => '#d05c3c',
                ],
                'light' => [
                    'accent' => '#a07e3a', 'accent_2' => '#7e6128',
                    'bg' => '#fbf6f7', 'bg_2' => '#ffffff', 'bg_3' => '#f3eaec',
                    'line' => '#e4d3d7', 'line_2' => '#c8a9b1',
                    'ink' => '#321a23', 'ink_2' => '#5a3a44', 'ink_3' => '#9a8088',
                    'danger' => '#c0533e',
                ],
            ],
            'espresso_rose' => [
                'label' => 'Espresso + Rose',
                'dark' => [
                    'accent' => '#e58faa', 'accent_2' => '#c97894',
                    'bg' => '#160f0c', 'bg_2' => '#211915', 'bg_3' => '#2d2421',
                    'line' => '#3e342f', 'line_2' => '#544740',
                    'ink' => '#f4ece6', 'ink_2' => '#cfbeb2', 'ink_3' => '#8d7f74',
                    'danger' => '#d4634a',
                ],
                'light' => [
                    'accent' => '#b8527a', 'accent_2' => '#923d5c',
                    'bg' => '#faf6f3', 'bg_2' => '#ffffff', 'bg_3' => '#f1eae5',
                    'line' => '#e0d5cc', 'line_2' => '#bda99c',
                    'ink' => '#2d2421', 'ink_2' => '#574a43', 'ink_3' => '#8d7f74',
                    'danger' => '#c0533e',
                ],
            ],
        ];
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

        $presets     = self::presets();
        $presetsJs   = \wp_json_encode($presets);
        $defaultMode = self::defaultMode();
        ?>
        <div class="wrap planetario-theme-colors">
            <h1><?php echo \esc_html__('Theme Colors', 'sage'); ?></h1>
            <p class="description"><?php echo \esc_html__('Brand palette. Changes apply site-wide via CSS variables. Clear page cache after saving.', 'sage'); ?></p>

            <style>
                .planetario-theme-colors .ptc-presets { margin-top: 18px; padding: 16px 18px; background:#fff; border:1px solid #dcdcde; border-radius:6px; display:flex; flex-wrap:wrap; align-items:center; gap:12px; }
                .planetario-theme-colors .ptc-presets label { font-weight:600; margin-right:6px; }
                .planetario-theme-colors .ptc-presets select { min-width: 240px; }
                .planetario-theme-colors .ptc-presets .ptc-swatches { display:inline-flex; gap:4px; margin-left: 4px; }
                .planetario-theme-colors .ptc-presets .ptc-swatch { width:18px; height:18px; border-radius:50%; border:1px solid rgba(0,0,0,0.1); }
                .planetario-theme-colors .ptc-presets .description { flex-basis:100%; margin:0; }

                .planetario-theme-colors .ptc-default-mode { margin-top: 18px; padding: 14px 18px; background:#fff; border:1px solid #dcdcde; border-radius:6px; }
                .planetario-theme-colors .ptc-default-mode legend { font-weight:600; padding:0 6px; }
                .planetario-theme-colors .ptc-default-mode label { display:inline-flex; align-items:center; gap:6px; margin-right:18px; cursor:pointer; }

                .planetario-theme-colors .ptc-mode-tabs { margin-top: 22px; display:inline-flex; padding:4px; background:#f0f0f1; border-radius:999px; border:1px solid #dcdcde; }
                .planetario-theme-colors .ptc-mode-tab { padding:6px 18px; border:0; background:transparent; cursor:pointer; border-radius:999px; font-size:13px; font-weight:500; color:#50575e; }
                .planetario-theme-colors .ptc-mode-tab.is-active { background:#fff; color:#1d2327; box-shadow:0 1px 2px rgba(0,0,0,0.08); }

                .planetario-theme-colors .ptc-mode-panel { display:none; }
                .planetario-theme-colors .ptc-mode-panel.is-active { display:block; }

                .planetario-theme-colors .color-row { display:flex; align-items:center; gap:12px; }
                .planetario-theme-colors input[type=color] { width:54px; height:36px; padding:2px; border:1px solid #dcdcde; background:#fff; cursor:pointer; }
                .planetario-theme-colors input[type=text].ptc-hex { font-family: ui-monospace, SFMono-Regular, Menlo, monospace; width:120px; }
                .planetario-theme-colors .swatch-default { display:inline-block; width:14px; height:14px; border:1px solid #dcdcde; vertical-align:middle; margin-right:6px; border-radius:3px; }
            </style>

            <div class="ptc-presets">
                <label for="ptc-preset-select"><?php echo \esc_html__('Preset palette', 'sage'); ?></label>
                <select id="ptc-preset-select">
                    <option value=""><?php echo \esc_html__('— select a preset —', 'sage'); ?></option>
                    <?php foreach ($presets as $id => $p): ?>
                        <option value="<?php echo \esc_attr($id); ?>">
                            <?php echo \esc_html($p['label']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <span class="ptc-swatches" id="ptc-preset-swatches" aria-hidden="true"></span>
                <button type="button" class="button" id="ptc-preset-apply"><?php echo \esc_html__('Apply preset', 'sage'); ?></button>
                <p class="description"><?php echo \esc_html__('Loads both Dark and Light values into the form. Click "Save changes" below to commit.', 'sage'); ?></p>
            </div>

            <form method="post" action="">
                <?php \wp_nonce_field('planetario_theme_colors', 'planetario_theme_colors_nonce'); ?>

                <fieldset class="ptc-default-mode">
                    <legend><?php echo \esc_html__('Default mode for visitors', 'sage'); ?></legend>
                    <label>
                        <input type="radio" name="<?php echo \esc_attr(self::OPT_DEFAULT_MODE); ?>" value="dark" <?php \checked($defaultMode, 'dark'); ?>>
                        <?php echo \esc_html__('Dark', 'sage'); ?>
                    </label>
                    <label>
                        <input type="radio" name="<?php echo \esc_attr(self::OPT_DEFAULT_MODE); ?>" value="light" <?php \checked($defaultMode, 'light'); ?>>
                        <?php echo \esc_html__('Light', 'sage'); ?>
                    </label>
                    <label>
                        <input type="radio" name="<?php echo \esc_attr(self::OPT_DEFAULT_MODE); ?>" value="auto" <?php \checked($defaultMode, 'auto'); ?>>
                        <?php echo \esc_html__('Follow OS preference', 'sage'); ?>
                    </label>
                    <p class="description"><?php echo \esc_html__('What new visitors see. Each visitor can override with the header toggle (saved in their browser).', 'sage'); ?></p>
                </fieldset>

                <div class="ptc-mode-tabs" role="tablist" aria-label="<?php echo \esc_attr__('Color mode', 'sage'); ?>">
                    <button type="button" class="ptc-mode-tab is-active" data-mode="dark" role="tab" aria-selected="true"><?php echo \esc_html__('Dark mode', 'sage'); ?></button>
                    <button type="button" class="ptc-mode-tab" data-mode="light" role="tab" aria-selected="false"><?php echo \esc_html__('Light mode', 'sage'); ?></button>
                </div>

                <?php foreach (['dark', 'light'] as $mode): ?>
                    <?php $defaults = $mode === 'light' ? self::DEFAULTS_LIGHT : self::DEFAULTS_DARK; ?>
                    <div class="ptc-mode-panel <?php echo $mode === 'dark' ? 'is-active' : ''; ?>" data-mode="<?php echo \esc_attr($mode); ?>">
                        <table class="form-table" role="presentation">
                            <?php foreach (self::LABELS as $key => [$label, $help]): ?>
                                <?php
                                $current = self::get($key, $mode);
                                $default = $defaults[$key];
                                $name    = self::OPT_PREFIX . $mode . '_' . $key;
                                $hexId   = $name . '_hex';
                                ?>
                                <tr>
                                    <th scope="row">
                                        <label for="<?php echo \esc_attr($name); ?>"><?php echo \esc_html($label); ?></label>
                                    </th>
                                    <td>
                                        <div class="color-row">
                                            <input type="color"
                                                   id="<?php echo \esc_attr($name); ?>"
                                                   value="<?php echo \esc_attr($current); ?>"
                                                   data-target="<?php echo \esc_attr($hexId); ?>"
                                                   data-key="<?php echo \esc_attr($key); ?>"
                                                   data-mode="<?php echo \esc_attr($mode); ?>">
                                            <input type="text"
                                                   id="<?php echo \esc_attr($hexId); ?>"
                                                   name="<?php echo \esc_attr($name); ?>"
                                                   value="<?php echo \esc_attr($current); ?>"
                                                   pattern="^#?[0-9a-fA-F]{6}$"
                                                   maxlength="7"
                                                   data-source="<?php echo \esc_attr($name); ?>"
                                                   class="regular-text ptc-hex">
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
                    </div>
                <?php endforeach; ?>

                <?php \submit_button(\__('Save changes', 'sage')); ?>
            </form>
        </div>

        <script>
        (function () {
            var PRESETS = <?php echo $presetsJs; ?>;

            // Color picker <-> hex input sync
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

            // Dark / Light mode tabs
            var tabs   = document.querySelectorAll('.ptc-mode-tab');
            var panels = document.querySelectorAll('.ptc-mode-panel');
            tabs.forEach(function (tab) {
                tab.addEventListener('click', function () {
                    var mode = tab.dataset.mode;
                    tabs.forEach(function (t) {
                        var active = t.dataset.mode === mode;
                        t.classList.toggle('is-active', active);
                        t.setAttribute('aria-selected', active ? 'true' : 'false');
                    });
                    panels.forEach(function (p) {
                        p.classList.toggle('is-active', p.dataset.mode === mode);
                    });
                });
            });

            // Preset preview swatches + apply
            var presetSelect   = document.getElementById('ptc-preset-select');
            var presetSwatches = document.getElementById('ptc-preset-swatches');
            var presetApply    = document.getElementById('ptc-preset-apply');

            function renderSwatches(id) {
                presetSwatches.innerHTML = '';
                var p = PRESETS[id];
                if (!p) return;
                // Show accent + bg + ink for both dark and light = 6 dots
                ['dark', 'light'].forEach(function (mode) {
                    ['accent', 'bg', 'ink'].forEach(function (key) {
                        var dot = document.createElement('span');
                        dot.className = 'ptc-swatch';
                        dot.style.background = p[mode][key];
                        dot.title = p.label + ' — ' + mode + ' / ' + key;
                        presetSwatches.appendChild(dot);
                    });
                });
            }

            presetSelect.addEventListener('change', function () {
                renderSwatches(presetSelect.value);
            });

            presetApply.addEventListener('click', function () {
                var id = presetSelect.value;
                var p  = PRESETS[id];
                if (!p) {
                    window.alert('<?php echo \esc_js(\__('Select a preset first.', 'sage')); ?>');
                    return;
                }
                ['dark', 'light'].forEach(function (mode) {
                    Object.keys(p[mode]).forEach(function (key) {
                        var name   = '<?php echo self::OPT_PREFIX; ?>' + mode + '_' + key;
                        var picker = document.getElementById(name);
                        var hex    = document.getElementById(name + '_hex');
                        var val    = p[mode][key];
                        if (picker) picker.value = val;
                        if (hex) hex.value = val;
                    });
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
