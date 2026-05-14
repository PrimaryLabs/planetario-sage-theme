<?php

namespace App\PostTypes;

use App\Data\StaticData;

class TeamMember
{
    public const POST_TYPE   = 'team_member';
    public const TAX_ROLE    = 'team_role';
    public const SEED_OPTION = 'planetario_team_seeded';

    public static function register(): void
    {
        \register_post_type(self::POST_TYPE, [
            'labels' => [
                'name'          => 'Team Members',
                'singular_name' => 'Team Member',
                'add_new_item'  => 'Add New Team Member',
                'edit_item'     => 'Edit Team Member',
                'menu_name'     => 'Team',
            ],
            'public'        => false,
            'show_ui'       => true,
            'show_in_rest'  => true,
            'menu_icon'     => 'dashicons-groups',
            'menu_position' => 24,
            'has_archive'   => false,
            'supports'      => ['title', 'page-attributes'],
            'taxonomies'    => [self::TAX_ROLE],
        ]);

        \register_taxonomy(self::TAX_ROLE, [self::POST_TYPE], [
            'labels' => [
                'name'          => 'Team Roles',
                'singular_name' => 'Team Role',
            ],
            'public'            => false,
            'show_ui'           => true,
            'show_in_rest'      => true,
            'show_admin_column' => true,
            'hierarchical'      => false,
        ]);
    }

    public static function seed(bool $force = false): array
    {
        if (! $force && \get_option(self::SEED_OPTION)) {
            return ['skipped' => true, 'reason' => 'already seeded'];
        }

        $created = 0;
        foreach (StaticData::team() as $i => $row) {
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

            \wp_set_object_terms($postId, $row['tier'], self::TAX_ROLE);

            \update_post_meta($postId, 'team_title',  $row['role']   ?? '');
            \update_post_meta($postId, 'team_bio',    $row['bio']    ?? '');
            \update_post_meta($postId, 'team_region', $row['region'] ?? 'all');
            $created++;
        }

        \update_option(self::SEED_OPTION, 1);

        return ['created' => $created];
    }
}
