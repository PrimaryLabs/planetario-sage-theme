<?php

namespace App\PostTypes;

use App\Data\StaticData;

class CompanyEvent
{
    public const POST_TYPE   = 'company_event';
    public const SEED_OPTION = 'planetario_company_events_seeded';

    public static function register(): void
    {
        \register_post_type(self::POST_TYPE, [
            'labels' => [
                'name'          => 'Company Events',
                'singular_name' => 'Company Event',
                'add_new_item'  => 'Add New Company Event',
                'edit_item'     => 'Edit Company Event',
                'menu_name'     => 'Events',
            ],
            'public'        => false,
            'show_ui'       => true,
            'show_in_rest'  => true,
            'menu_icon'     => 'dashicons-calendar-alt',
            'menu_position' => 29,
            'has_archive'   => false,
            'supports'      => ['title', 'editor', 'thumbnail', 'page-attributes'],
        ]);
    }

    public static function seed(bool $force = false): array
    {
        if (! $force && \get_option(self::SEED_OPTION)) {
            return ['skipped' => true, 'reason' => 'already seeded'];
        }

        $created = 0;
        foreach (StaticData::companyEvents() as $i => $row) {
            $slug = \sanitize_title($row['title']);
            if (\get_page_by_path($slug, OBJECT, self::POST_TYPE)) {
                continue;
            }

            $postId = \wp_insert_post([
                'post_type'   => self::POST_TYPE,
                'post_status' => 'publish',
                'post_title'  => $row['title'],
                'post_name'   => $slug,
                'menu_order'  => $i,
            ]);

            if (\is_wp_error($postId) || ! $postId) continue;

            \update_post_meta($postId, 'event_date',     $row['date']     ?? '');
            \update_post_meta($postId, 'event_location', $row['location'] ?? '');
            \update_post_meta($postId, 'event_summary',  $row['summary']  ?? '');
            \update_post_meta($postId, 'event_gallery',  0);
            $created++;
        }

        \update_option(self::SEED_OPTION, 1);

        return ['created' => $created];
    }
}
