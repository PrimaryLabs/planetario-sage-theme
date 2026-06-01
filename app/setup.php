<?php

/**
 * Theme setup.
 */

namespace App;

use Illuminate\Support\Facades\Vite;

/**
 * Inject styles into the block editor.
 *
 * @return array
 */
add_filter('block_editor_settings_all', function ($settings) {
    $style = Vite::asset('resources/css/editor.css');

    $settings['styles'][] = [
        'css' => "@import url('{$style}')",
    ];

    return $settings;
});

/**
 * Inject scripts into the block editor.
 *
 * @return void
 */
add_action('admin_head', function () {
    if (! get_current_screen()?->is_block_editor()) {
        return;
    }

    if (! Vite::isRunningHot()) {
        $dependencies = json_decode(Vite::content('editor.deps.json'));

        foreach ($dependencies as $dependency) {
            if (! wp_script_is($dependency)) {
                wp_enqueue_script($dependency);
            }
        }
    }
    echo Vite::withEntryPoints([
        'resources/js/editor.js',
    ])->toHtml();
});

/**
 * Use the generated theme.json file.
 *
 * @return string
 */
add_filter('theme_file_path', function ($path, $file) {
    return $file === 'theme.json'
        ? public_path('build/assets/theme.json')
        : $path;
}, 10, 2);

/**
 * Disable on-demand block asset loading.
 *
 * @link https://core.trac.wordpress.org/ticket/61965
 */
add_filter('should_load_separate_core_block_assets', '__return_false');

/**
 * Register the initial theme setup.
 *
 * @return void
 */
add_action('after_setup_theme', function () {
    /**
     * Disable full-site editing support.
     *
     * @link https://wptavern.com/gutenberg-10-5-embeds-pdfs-adds-verse-block-color-options-and-introduces-new-patterns
     */
    remove_theme_support('block-templates');

    /**
     * Register the navigation menus.
     *
     * @link https://developer.wordpress.org/reference/functions/register_nav_menus/
     */
    register_nav_menus([
        'primary_navigation' => __('Primary Navigation', 'sage'),
    ]);

    /**
     * Disable the default block patterns.
     *
     * @link https://developer.wordpress.org/block-editor/developers/themes/theme-support/#disabling-the-default-block-patterns
     */
    remove_theme_support('core-block-patterns');

    /**
     * Enable plugins to manage the document title.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#title-tag
     */
    add_theme_support('title-tag');

    /**
     * Enable post thumbnail support.
     *
     * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
     */
    add_theme_support('post-thumbnails');

    /**
     * Enable custom logo support (managed via Site Settings → Brand).
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#custom-logo
     */
    add_theme_support('custom-logo', [
        'height'      => 80,
        'width'       => 240,
        'flex-height' => true,
        'flex-width'  => true,
    ]);

    /**
     * Enable responsive embed support.
     *
     * @link https://developer.wordpress.org/block-editor/how-to-guides/themes/theme-support/#responsive-embedded-content
     */
    add_theme_support('responsive-embeds');

    /**
     * Enable HTML5 markup support.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#html5
     */
    add_theme_support('html5', [
        'caption',
        'comment-form',
        'comment-list',
        'gallery',
        'search-form',
        'script',
        'style',
    ]);

    /**
     * Enable selective refresh for widgets in customizer.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#customize-selective-refresh-widgets
     */
    add_theme_support('customize-selective-refresh-widgets');
}, 20);

/**
 * Inject Site Settings → Theme Colors as :root CSS variable overrides.
 * Emits both dark and light palettes; OS preference wins by default, and
 * `<html data-theme="dark|light">` forces a specific mode.
 * Matches the alias variables defined in resources/css/app.css @layer base.
 */
add_action('wp_head', function () {
    $colors = \App\View\Composers\SiteSettings::colors();

    $hexToRgb = static function (string $hex): string {
        $hex = ltrim($hex, '#');
        if (strlen($hex) !== 6) {
            return '0, 0, 0';
        }
        return (int) hexdec(substr($hex, 0, 2))
            . ', ' . (int) hexdec(substr($hex, 2, 2))
            . ', ' . (int) hexdec(substr($hex, 4, 2));
    };

    $buildVars = static function (array $c) use ($hexToRgb): string {
        $map = [
            '--bg'       => $c['bg'],
            '--bg-2'     => $c['bg_2'],
            '--bg-3'     => $c['bg_3'],
            '--line'     => $c['line'],
            '--line-2'   => $c['line_2'],
            '--ink'      => $c['ink'],
            '--ink-2'    => $c['ink_2'],
            '--ink-3'    => $c['ink_3'],
            '--accent'   => $c['accent'],
            '--accent-2' => $c['accent_2'],
            '--danger'   => $c['danger'],
        ];
        $lines = [];
        foreach ($map as $var => $val) {
            $lines[] = "{$var}: {$val};";
        }
        $lines[] = '--bg-rgb: ' . $hexToRgb($c['bg']) . ';';
        $lines[] = '--accent-soft: ' . $c['accent'] . '22;';
        return implode('', $lines);
    };

    $dark        = $buildVars($colors['dark']);
    $light       = $buildVars($colors['light']);
    $defaultMode = $colors['defaultMode'] ?? 'auto';

    if ($defaultMode === 'light') {
        $css  = ":root{{$light}}";
        $css .= ":root[data-theme=\"dark\"]{{$dark}}";
        $css .= ":root[data-theme=\"light\"]{{$light}}";
    } elseif ($defaultMode === 'dark') {
        $css  = ":root{{$dark}}";
        $css .= ":root[data-theme=\"dark\"]{{$dark}}";
        $css .= ":root[data-theme=\"light\"]{{$light}}";
    } else {
        $css  = ":root{{$dark}}";
        $css .= "@media (prefers-color-scheme: light){:root{{$light}}}";
        $css .= ":root[data-theme=\"dark\"]{{$dark}}";
        $css .= ":root[data-theme=\"light\"]{{$light}}";
    }

    echo "<style id=\"planetario-theme-colors\">{$css}</style>\n";
}, 20);

/**
 * Preload visitor's theme preference before paint, so [data-theme] is on <html>
 * before the CSS variables resolve. Avoids FOUC when localStorage overrides
 * the admin default.
 */
add_action('wp_head', function () {
    $defaultMode = \App\Admin\ThemeColorsPage::defaultMode();
    $js = "(function(){try{"
        . "var s=localStorage.getItem('planetarioTheme');"
        . "var d=document.currentScript&&document.currentScript.dataset.default;"
        . "var m=(s==='dark'||s==='light')?s:((d==='dark'||d==='light')?d:null);"
        . "if(m)document.documentElement.setAttribute('data-theme',m);"
        . "}catch(e){}})();";
    echo "<script id=\"planetario-theme-preload\" data-default=\"" . \esc_attr($defaultMode) . "\">{$js}</script>\n";
}, 1);

/**
 * Handle contact form submission.
 */
add_action('admin_post_nopriv_planetario_contact', function () {
    if (! check_admin_referer('planetario_contact')) {
        wp_die('Invalid request.');
    }

    $name    = sanitize_text_field(wp_unslash($_POST['name']    ?? ''));
    $email   = sanitize_email(wp_unslash($_POST['email']   ?? ''));
    $phone   = sanitize_text_field(wp_unslash($_POST['phone']   ?? ''));
    $interest = sanitize_text_field(wp_unslash($_POST['interest'] ?? ''));
    $message  = sanitize_textarea_field(wp_unslash($_POST['message'] ?? ''));

    if (! $name || ! is_email($email) || strlen($message) < 12) {
        wp_safe_redirect(add_query_arg('error', '1', wp_get_referer()));
        exit;
    }

    $to      = get_option('admin_email');
    $subject = "[Planetario] Contact from {$name} — {$interest}";
    $body    = "Name: {$name}\nEmail: {$email}\nPhone: {$phone}\nInterest: {$interest}\n\nMessage:\n{$message}";
    $headers = ["From: {$name} <{$email}>", 'Reply-To: ' . $email];

    wp_mail($to, $subject, $body, $headers);

    $contact_page = get_page_by_path('contact');
    $base = $contact_page ? get_permalink($contact_page) : home_url('/contact');
    wp_safe_redirect(add_query_arg('sent', '1', $base));
    exit;
});
add_action('admin_post_planetario_contact', 'admin_post_nopriv_planetario_contact');

/**
 * Register the theme sidebars.
 *
 * @return void
 */
add_action('widgets_init', function () {
    $config = [
        'before_widget' => '<section class="widget %1$s %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ];

    register_sidebar([
        'name' => __('Primary', 'sage'),
        'id' => 'sidebar-primary',
    ] + $config);

    register_sidebar([
        'name' => __('Footer', 'sage'),
        'id' => 'sidebar-footer',
    ] + $config);
});

/**
 * Load inline editor for admin users browsing the front-end.
 * Gated by #wpadminbar in JS, but we also skip loading for non-editors server-side.
 */
add_action('wp_head', function () {
    if (! \is_user_logged_in() || ! \current_user_can('edit_posts') || \is_admin()) {
        return;
    }

    $data = \wp_json_encode([
        'apiUrl'   => \rest_url('planetario/v1/acf-update'),
        'nonce'    => \wp_create_nonce('wp_rest'),
        'postId'   => \get_queried_object_id(),
        'adminUrl' => \admin_url(),
    ]);

    echo "<script>window.planetarioEditor = {$data};</script>\n";
    echo Vite::withEntryPoints(['resources/js/inline-editor.js'])->toHtml();
}, 20);

/**
 * Rename "Posts" → "Blog Posts" in wp-admin via DOM text replacement.
 */
add_action('admin_enqueue_scripts', function () {
    $asset = \get_theme_file_uri('resources/js/admin.js');
    \wp_enqueue_script('planetario-admin', $asset, [], null, true);
});

/**
 * Fix Sage local development CORS errors
 */
add_action('init', function () {
    // Check if the request comes from your local Bud/Vite asset server
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        $origin = $_SERVER['HTTP_ORIGIN'];

        // Dynamically allow localhost ports common to Bud (3000) and Vite (5173)
        if (preg_match('~^https?://localhost:(3000|5173|5174)$~', $origin)) {
            header("Access-Control-Allow-Origin: {$origin}");
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS, HEAD");
            header("Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Requested-With");
            header("Access-Control-Allow-Credentials: true");
        }
    }
});
