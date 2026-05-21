<?php

namespace App\PostTypes;

use App\Data\StaticData;

class BlogPost
{
    public const POST_TYPE   = 'post';
    public const SEED_OPTION = 'planetario_blog_seeded';

    public static function seed(bool $force = false): array
    {
        if (! $force && \get_option(self::SEED_OPTION)) {
            return ['skipped' => true, 'reason' => 'already seeded'];
        }

        $created = 0;
        foreach (StaticData::blog() as $i => $row) {
            $slug = \sanitize_title($row['title']);
            if (\get_page_by_path($slug, OBJECT, self::POST_TYPE)) {
                continue;
            }

            $postId = \wp_insert_post([
                'post_type'    => self::POST_TYPE,
                'post_status'  => 'publish',
                'post_title'   => $row['title'],
                'post_name'    => $slug,
                'post_content' => $row['content']  ?? '',
                'post_excerpt' => $row['excerpt']  ?? '',
                'menu_order'   => $i,
            ]);

            if (\is_wp_error($postId) || ! $postId) continue;

            \update_post_meta($postId, 'post_thumbnail_url', $row['thumbnail'] ?? '');

            $categories = $row['categories'] ?? [];
            if ($categories) {
                $termIds = [];
                foreach ($categories as $name) {
                    $term = \get_term_by('name', $name, 'category');
                    if (! $term) {
                        $inserted = \wp_insert_term($name, 'category');
                        $termIds[] = \is_wp_error($inserted) ? 0 : (int) $inserted['term_id'];
                    } else {
                        $termIds[] = (int) $term->term_id;
                    }
                }
                \wp_set_object_terms($postId, array_filter($termIds), 'category');
            }

            $created++;
        }

        \update_option(self::SEED_OPTION, 1);

        return ['created' => $created];
    }
}
