<?php

namespace App;

use App\Fields\SiteSettings;

class SiteIdentitySync
{
    public static function register(): void
    {
        \add_action('acf/save_post', [self::class, 'sync'], 20);
        \add_action('wp_head', [self::class, 'outputOgImage'], 5);
    }

    public static function sync(string|int $postId): void
    {
        if ($postId !== 'options') {
            return;
        }

        if (! function_exists('get_field')) {
            return;
        }

        $title = (string) \get_field('brand_site_title', 'option');
        if ($title !== '') {
            \update_option('blogname', $title);
        }

        $tagline = (string) \get_field('brand_site_tagline', 'option');
        if ($tagline !== '') {
            \update_option('blogdescription', $tagline);
        }

        $logoId = (int) \get_field('brand_logo', 'option');
        if ($logoId > 0) {
            \set_theme_mod('custom_logo', $logoId);
        } else {
            \remove_theme_mod('custom_logo');
        }

        $iconId = (int) \get_field('brand_site_icon', 'option');
        if ($iconId > 0) {
            \update_option('site_icon', $iconId);
        } else {
            \delete_option('site_icon');
        }
    }

    public static function outputOgImage(): void
    {
        if (! function_exists('get_field')) {
            return;
        }

        $url = '';

        if (\is_singular() && \has_post_thumbnail()) {
            $src = \wp_get_attachment_image_src(\get_post_thumbnail_id(), 'full');
            if (is_array($src)) {
                $url = (string) $src[0];
            }
        }

        if ($url === '') {
            $id = (int) \get_field('brand_og_image', 'option');
            if ($id > 0) {
                $src = \wp_get_attachment_image_src($id, 'full');
                if (is_array($src)) {
                    $url = (string) $src[0];
                }
            }
        }

        if ($url === '') {
            return;
        }

        $siteName = (string) \get_bloginfo('name');
        $title = \is_singular() ? (string) \get_the_title() : $siteName;

        echo "\n" .
            '<meta property="og:image" content="' . \esc_url($url) . '">' . "\n" .
            '<meta property="og:title" content="' . \esc_attr($title) . '">' . "\n" .
            '<meta property="og:site_name" content="' . \esc_attr($siteName) . '">' . "\n" .
            '<meta name="twitter:card" content="summary_large_image">' . "\n" .
            '<meta name="twitter:image" content="' . \esc_url($url) . '">' . "\n";
    }
}
