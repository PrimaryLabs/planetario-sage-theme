<?php

namespace App\PostTypes;

use App\Data\StaticData;

class Story
{
    public const POST_TYPE   = 'story';
    public const SEED_OPTION = 'planetario_stories_seeded';

    public static function register(): void
    {
        \register_post_type(self::POST_TYPE, [
            'labels' => [
                'name'          => 'Success Stories',
                'singular_name' => 'Success Story',
                'add_new_item'  => 'Add New Success Story',
                'edit_item'     => 'Edit Success Story',
                'menu_name'     => 'Stories',
            ],
            'public'        => true,
            'show_ui'       => true,
            'show_in_rest'  => true,
            'has_archive'   => false,
            'rewrite'       => ['slug' => 'story'],
            'menu_icon'     => 'dashicons-format-status',
            'menu_position' => 28,
            'supports'      => ['title', 'thumbnail', 'page-attributes'],
        ]);
    }

    public static function seed(bool $force = false): array
    {
        if (! $force && \get_option(self::SEED_OPTION)) {
            return ['skipped' => true, 'reason' => 'already seeded'];
        }

        $created = 0;
        foreach (StaticData::stories() as $i => $row) {
            $slug = \sanitize_title($row['client']);
            if (\get_page_by_path($slug, OBJECT, self::POST_TYPE)) continue;

            $postId = \wp_insert_post([
                'post_type'   => self::POST_TYPE,
                'post_status' => 'publish',
                'post_title'  => $row['client'],
                'post_name'   => $slug,
                'menu_order'  => $i,
            ]);
            if (\is_wp_error($postId) || ! $postId) continue;

            \update_post_meta($postId, 'story_quote',     $row['quote']    ?? '');
            \update_post_meta($postId, 'story_location',  $row['location'] ?? '');
            \update_post_meta($postId, 'story_summary',   $row['summary']  ?? '');
            \update_post_meta($postId, 'story_image_url', $row['image']    ?? '');

            $stats = $row['stats'] ?? [];
            \update_post_meta($postId, 'story_stats', count($stats));
            foreach (array_values($stats) as $idx => $st) {
                \update_post_meta($postId, "story_stats_{$idx}_value", $st['v'] ?? '');
                \update_post_meta($postId, "story_stats_{$idx}_label", $st['l'] ?? '');
            }
            $created++;
        }

        \update_option(self::SEED_OPTION, 1);

        return ['created' => $created];
    }
}
