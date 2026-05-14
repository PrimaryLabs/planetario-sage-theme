<?php

namespace App\PostTypes;

use App\Data\StaticData;

class Testimonial
{
    public const POST_TYPE   = 'testimonial';
    public const SEED_OPTION = 'planetario_testimonials_seeded';

    public static function register(): void
    {
        \register_post_type(self::POST_TYPE, [
            'labels' => [
                'name'          => 'Testimonials',
                'singular_name' => 'Testimonial',
                'add_new_item'  => 'Add New Testimonial',
                'edit_item'     => 'Edit Testimonial',
                'menu_name'     => 'Testimonials',
            ],
            'public'        => false,
            'show_ui'       => true,
            'show_in_rest'  => true,
            'menu_icon'     => 'dashicons-format-quote',
            'menu_position' => 22,
            'has_archive'   => false,
            'supports'      => ['title', 'page-attributes'],
        ]);
    }

    public static function seed(bool $force = false): array
    {
        if (! $force && \get_option(self::SEED_OPTION)) {
            return ['skipped' => true, 'reason' => 'already seeded'];
        }

        $created = 0;
        foreach (StaticData::testimonials() as $i => $row) {
            $slug = \sanitize_title($row['name']);
            if (\get_page_by_path($slug, OBJECT, self::POST_TYPE)) {
                continue;
            }

            $postId = \wp_insert_post([
                'post_type'    => self::POST_TYPE,
                'post_status'  => 'publish',
                'post_title'   => $row['name'],
                'post_name'    => $slug,
                'menu_order'   => $i,
            ]);

            if (\is_wp_error($postId) || ! $postId) continue;

            \update_post_meta($postId, 'testimonial_quote',     $row['quote'] ?? '');
            \update_post_meta($postId, 'testimonial_name',      $row['name'] ?? '');
            \update_post_meta($postId, 'testimonial_role',      $row['role'] ?? '');
            \update_post_meta($postId, 'testimonial_highlight', $i < 2 ? 1 : 0);
            $created++;
        }

        \update_option(self::SEED_OPTION, 1);

        return ['created' => $created];
    }
}
