<?php

namespace App;

use App\Admin\SiteIdentityPage;

class SiteIdentitySync
{
    public static function register(): void
    {
        \add_action('wp_head', [self::class, 'outputOgImage'], 5);
    }

    public static function outputOgImage(): void
    {
        $url = '';

        if (\is_singular() && \has_post_thumbnail()) {
            $src = \wp_get_attachment_image_src(\get_post_thumbnail_id(), 'full');
            if (is_array($src)) {
                $url = (string) $src[0];
            }
        }

        if ($url === '') {
            $id = (int) \get_option(SiteIdentityPage::OPT_OG_IMAGE_ID, 0);
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
