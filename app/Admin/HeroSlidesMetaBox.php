<?php

namespace App\Admin;

class HeroSlidesMetaBox
{
    public const META_KEY = '_planetario_hero_slides';

    public static function register(): void
    {
        \add_meta_box(
            'planetario_hero_slides',
            'Hero Background Slides',
            [self::class, 'render'],
            'page',
            'normal',
            'high'
        );
    }

    public static function render(\WP_Post $post): void
    {
        if ((int) \get_option('page_on_front') !== $post->ID) {
            echo '<p style="color:#999;margin:0">Only available on the front page.</p>';
            return;
        }

        \wp_nonce_field('planetario_hero_slides', '_hero_slides_nonce');

        $raw        = \get_post_meta($post->ID, self::META_KEY, true);
        $slides     = json_decode($raw ?: '[]', true) ?: [];
        $slides_json = \esc_attr((string) json_encode($slides));

        echo '<div id="hero-slides-root" data-slides="' . $slides_json . '"></div>';
        echo '<input type="hidden" id="hero-slides-data" name="hero_slides_data" value="' . $slides_json . '">';
    }

    public static function save(int $post_id, \WP_Post $post): void
    {
        if (
            ! isset($_POST['_hero_slides_nonce']) ||
            ! \wp_verify_nonce(\sanitize_text_field(\wp_unslash($_POST['_hero_slides_nonce'])), 'planetario_hero_slides') ||
            \wp_is_post_revision($post_id) ||
            ! \current_user_can('edit_post', $post_id)
        ) {
            return;
        }

        $raw    = isset($_POST['hero_slides_data']) ? \wp_unslash($_POST['hero_slides_data']) : '[]';
        $slides = json_decode($raw, true);

        if (! is_array($slides)) {
            $slides = [];
        }

        $clean = array_values(array_filter(array_map(function ($s) {
            $id  = (int) ($s['id'] ?? 0);
            $url = \esc_url_raw($s['url'] ?? '');
            if (! $id || ! $url) {
                return null;
            }
            return [
                'id'         => $id,
                'url'        => $url,
                'alt'        => \sanitize_text_field($s['alt'] ?? ''),
                'transition' => in_array($s['transition'] ?? '', ['crossfade', 'slide', 'zoom'], true)
                    ? $s['transition']
                    : 'crossfade',
            ];
        }, $slides)));

        \update_post_meta($post_id, self::META_KEY, wp_json_encode($clean));
    }
}
