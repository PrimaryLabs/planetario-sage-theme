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
            'show_admin_column' => false,
            'hierarchical'      => false,
        ]);

        self::registerAdminColumns();
    }

    public static function registerAdminColumns(): void
    {
        \add_filter('manage_' . self::POST_TYPE . '_posts_columns', [self::class, 'columns']);
        \add_action('manage_' . self::POST_TYPE . '_posts_custom_column', [self::class, 'columnContent'], 10, 2);
        \add_action('admin_head', [self::class, 'columnStyles']);
    }

    public static function columns(array $columns): array
    {
        return [
            'cb'          => $columns['cb'] ?? '<input type="checkbox">',
            'team_photo'  => 'Photo',
            'title'       => 'Name',
            'team_title'  => 'Position / Title',
            'team_region' => 'Region',
            'team_role'   => 'Category',
            'team_email'  => 'Email',
            'team_phone'  => 'Phone',
        ];
    }

    public static function columnContent(string $column, int $postId): void
    {
        switch ($column) {
            case 'team_photo':
                $photo = \get_field('team_photo', $postId);
                $url   = is_array($photo) ? ($photo['sizes']['thumbnail'] ?? $photo['url'] ?? '') : '';
                if (! $url) {
                    $url = 'https://i.pravatar.cc/80?u=' . rawurlencode((string) \get_the_title($postId));
                }
                echo '<img src="' . \esc_url($url) . '" alt="" style="width:40px;height:40px;border-radius:8px;object-fit:cover;display:block;object-fit: contain;padding-top: 4px;">';
                break;

            case 'team_title':
                echo \esc_html((string) \get_field('team_title', $postId));
                break;

            case 'team_region':
                $labels = ['all' => 'All', 'bohol' => 'Bohol', 'cebu' => 'Cebu'];
                $val    = (string) (\get_field('team_region', $postId) ?: 'all');
                echo \esc_html($labels[$val] ?? ucfirst($val));
                break;

            case 'team_role':
                $terms = \get_the_terms($postId, self::TAX_ROLE);
                if (is_array($terms) && ! empty($terms)) {
                    echo \esc_html($terms[0]->name);
                } else {
                    echo '<span style="opacity:.4">—</span>';
                }
                break;

            case 'team_email':
                $email = (string) \get_field('team_email', $postId);
                if ($email) {
                    echo '<a href="mailto:' . \esc_attr($email) . '">' . \esc_html($email) . '</a>';
                } else {
                    echo '<span style="opacity:.4">—</span>';
                }
                break;

            case 'team_phone':
                $phone = (string) \get_field('team_phone', $postId);
                if ($phone) {
                    $tel = preg_replace('/\s+/', '', $phone);
                    echo '<a href="tel:' . \esc_attr($tel) . '">' . \esc_html($phone) . '</a>';
                } else {
                    echo '<span style="opacity:.4">—</span>';
                }
                break;
        }
    }

    public static function columnStyles(): void
    {
        $screen = \get_current_screen();
        if (! $screen || $screen->post_type !== self::POST_TYPE) {
            return;
        }
        echo '<style>
            .column-team_photo  { width: 56px; }
            .column-team_title  { width: 160px; }
            .column-team_region { width: 80px; }
            .column-team_role   { width: 100px; }
            .column-team_email  { width: 200px; word-break: break-all; }
            .column-team_phone  { width: 140px; }
        </style>';
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
