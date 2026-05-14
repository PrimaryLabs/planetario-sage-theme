<?php

namespace App\PostTypes;

use App\Data\StaticData;

class Property
{
    public const POST_TYPE     = 'property';
    public const TAX_REGION    = 'region';
    public const TAX_TYPE      = 'property_type';
    public const TAX_STATUS    = 'property_status';
    public const TAX_TAG       = 'property_tag';
    public const SEED_OPTION   = 'planetario_properties_seeded';

    public static function register(): void
    {
        self::registerPostType();
        self::registerTaxonomies();
    }

    private static function registerPostType(): void
    {
        \register_post_type(self::POST_TYPE, [
            'labels' => [
                'name'               => 'Properties',
                'singular_name'      => 'Property',
                'add_new_item'       => 'Add New Property',
                'edit_item'          => 'Edit Property',
                'new_item'           => 'New Property',
                'view_item'          => 'View Property',
                'search_items'       => 'Search Properties',
                'not_found'          => 'No properties found',
                'not_found_in_trash' => 'No properties found in trash',
                'menu_name'          => 'Properties',
            ],
            'public'        => true,
            'has_archive'   => false,
            'show_in_rest'  => true,
            'menu_icon'     => 'dashicons-building',
            'menu_position' => 20,
            'rewrite'       => ['slug' => 'property', 'with_front' => false],
            'supports'      => ['title', 'editor', 'thumbnail', 'page-attributes', 'excerpt'],
            'taxonomies'    => [self::TAX_REGION, self::TAX_TYPE, self::TAX_STATUS, self::TAX_TAG],
        ]);
    }

    private static function registerTaxonomies(): void
    {
        $shared = [
            'public'            => true,
            'show_in_rest'      => true,
            'show_admin_column' => true,
            'hierarchical'      => false,
        ];

        \register_taxonomy(self::TAX_REGION, [self::POST_TYPE], array_merge($shared, [
            'labels'  => ['name' => 'Regions', 'singular_name' => 'Region'],
            'rewrite' => ['slug' => 'region'],
        ]));

        \register_taxonomy(self::TAX_TYPE, [self::POST_TYPE], array_merge($shared, [
            'labels'  => ['name' => 'Property Types', 'singular_name' => 'Property Type'],
            'rewrite' => ['slug' => 'property-type'],
        ]));

        \register_taxonomy(self::TAX_STATUS, [self::POST_TYPE], array_merge($shared, [
            'labels'  => ['name' => 'Statuses', 'singular_name' => 'Status'],
            'rewrite' => ['slug' => 'property-status'],
        ]));

        \register_taxonomy(self::TAX_TAG, [self::POST_TYPE], array_merge($shared, [
            'labels'  => ['name' => 'Property Tags', 'singular_name' => 'Property Tag'],
            'rewrite' => ['slug' => 'property-tag'],
        ]));
    }

    public static function seed(bool $force = false): array
    {
        if (! $force && \get_option(self::SEED_OPTION)) {
            return ['skipped' => true, 'reason' => 'already seeded'];
        }

        $created = 0;
        $skipped = 0;
        foreach (StaticData::properties() as $row) {
            $existing = \get_page_by_path($row['id'], OBJECT, self::POST_TYPE);
            if ($existing) {
                $skipped++;
                continue;
            }

            $postId = \wp_insert_post([
                'post_type'    => self::POST_TYPE,
                'post_status'  => 'publish',
                'post_title'   => $row['name'],
                'post_name'    => $row['id'],
                'post_content' => '',
                'post_excerpt' => $row['summary'] ?? '',
            ]);

            if (\is_wp_error($postId) || ! $postId) {
                continue;
            }

            if (! empty($row['region'])) \wp_set_object_terms($postId, $row['region'], self::TAX_REGION);
            if (! empty($row['type']))   \wp_set_object_terms($postId, $row['type'], self::TAX_TYPE);
            if (! empty($row['status'])) \wp_set_object_terms($postId, $row['status'], self::TAX_STATUS);
            if (! empty($row['tags']))   \wp_set_object_terms($postId, $row['tags'], self::TAX_TAG);

            foreach ([
                'property_price'         => $row['price'] ?? null,
                'property_price_label'   => $row['priceLabel'] ?? '',
                'property_beds'          => $row['beds'] ?? null,
                'property_baths'         => $row['baths'] ?? null,
                'property_area'          => $row['area'] ?? null,
                'property_lot'           => $row['lot'] ?? null,
                'property_location'      => $row['location'] ?? '',
                'property_summary'       => $row['summary'] ?? '',
                'property_image_url'     => $row['image'] ?? '',
                'property_is_featured'   => in_array(($row['id'] ?? ''), ['panglao-villa-alon', 'tagbilaran-skyline-residences', 'cebu-it-park-penthouse', 'loboc-river-house', 'mactan-bayfront-lot', 'talisay-hillside'], true) ? 1 : 0,
            ] as $key => $value) {
                if ($value !== null && $value !== '') {
                    \update_post_meta($postId, $key, $value);
                }
            }

            if (! empty($row['features'])) {
                \update_post_meta($postId, 'property_features', count($row['features']));
                foreach (array_values($row['features']) as $i => $feature) {
                    \update_post_meta($postId, "property_features_{$i}_feature", $feature);
                }
            }

            $created++;
        }

        \update_option(self::SEED_OPTION, 1);

        return ['created' => $created, 'skipped' => $skipped];
    }
}
