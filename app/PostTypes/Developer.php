<?php

namespace App\PostTypes;

use App\Data\StaticData;

class Developer
{
    public const POST_TYPE   = 'developer';
    public const SEED_OPTION = 'planetario_developers_seeded';

    public static function register(): void
    {
        \register_post_type(self::POST_TYPE, [
            'labels' => [
                'name'          => 'Developers',
                'singular_name' => 'Developer',
                'add_new_item'  => 'Add New Developer',
                'edit_item'     => 'Edit Developer',
                'menu_name'     => 'Developers',
            ],
            'public'        => false,
            'show_ui'       => true,
            'show_in_rest'  => true,
            'menu_icon'     => 'dashicons-businessman',
            'menu_position' => 26,
            'has_archive'   => false,
            'supports'      => ['title', 'thumbnail', 'page-attributes'],
        ]);

        \add_filter('manage_' . self::POST_TYPE . '_posts_columns', [self::class, 'addColumns']);
        \add_action('manage_' . self::POST_TYPE . '_posts_custom_column', [self::class, 'renderColumn'], 10, 2);
        \add_filter('manage_edit-' . self::POST_TYPE . '_sortable_columns', [self::class, 'sortableColumns']);
    }

    public static function addColumns(array $columns): array
    {
        $reordered = [];
        foreach ($columns as $key => $label) {
            $reordered[$key] = $label;
            if ($key === 'title') {
                $reordered['developer_image']  = 'Image';
                $reordered['developer_region'] = 'Region';
            }
        }
        return $reordered;
    }

    public static function renderColumn(string $column, int $postId): void
    {
        if ($column === 'developer_image') {
            $logo = \get_field('developer_logo', $postId);
            if (! empty($logo['url'])) {
                $url = \esc_url($logo['url']);
                $alt = \esc_attr($logo['alt'] ?? '');
                echo '<img src="' . $url . '" alt="' . $alt . '" style="height:40px;width:auto;object-fit:contain;">';
            } else {
                echo '—';
            }
        }

        if ($column === 'developer_region') {
            $region = \get_field('developer_region', $postId);
            echo $region ? \esc_html($region) : '—';
        }
    }

    public static function sortableColumns(array $columns): array
    {
        $columns['developer_region'] = 'developer_region';
        return $columns;
    }

    public static function seed(bool $force = false): array
    {
        if (! $force && \get_option(self::SEED_OPTION)) {
            return ['skipped' => true, 'reason' => 'already seeded'];
        }

        $created = 0;
        foreach (StaticData::developers() as $i => $row) {
            $slug = \sanitize_title($row['name']);
            if (\get_page_by_path($slug, OBJECT, self::POST_TYPE)) continue;

            $postId = \wp_insert_post([
                'post_type'   => self::POST_TYPE,
                'post_status' => 'publish',
                'post_title'  => $row['name'],
                'post_name'   => $slug,
                'menu_order'  => $i,
            ]);
            if (\is_wp_error($postId) || ! $postId) continue;

            \update_post_meta($postId, 'developer_sigil',     $row['sigil']     ?? '');
            \update_post_meta($postId, 'developer_portfolio', $row['portfolio'] ?? '');
            \update_post_meta($postId, 'developer_desc',      $row['desc']      ?? '');
            \update_post_meta($postId, 'developer_locations', count($row['locations'] ?? []));
            foreach (array_values($row['locations'] ?? []) as $idx => $loc) {
                \update_post_meta($postId, "developer_locations_{$idx}_location", $loc);
            }
            $created++;
        }

        \update_option(self::SEED_OPTION, 1);

        return ['created' => $created];
    }
}
