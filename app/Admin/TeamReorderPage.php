<?php

namespace App\Admin;

use App\PostTypes\TeamMember as TeamMemberPostType;
use WP_Query;

class TeamReorderPage
{
    private const SLUG = 'planetario-team-reorder';

    private const GROUP_ORDER = [
        'Board of Directors' => 'Board of Directors',
        'broker'             => 'Brokers',
        'Bohol Managers'     => 'Bohol Managers',
        'Cebu Managers'      => 'Cebu Managers',
        'Bohol Staff'        => 'Bohol Staff',
        'Cebu Staff'         => 'Cebu Staff',
    ];

    public static function register(): void
    {
        \add_action('admin_menu', [self::class, 'addMenuPage']);
        \add_action('admin_footer', [self::class, 'injectListButton']);
    }

    public static function addMenuPage(): void
    {
        \add_submenu_page(
            'edit.php?post_type=' . TeamMemberPostType::POST_TYPE,
            'Re-Order Team Members',
            'Re-Order Team',
            'edit_posts',
            self::SLUG,
            [self::class, 'render']
        );
    }

    public static function injectListButton(): void
    {
        $screen = \get_current_screen();
        if (! $screen || $screen->post_type !== TeamMemberPostType::POST_TYPE || $screen->base !== 'edit') {
            return;
        }

        $url = \esc_url_raw(\admin_url('edit.php?post_type=' . TeamMemberPostType::POST_TYPE . '&page=' . self::SLUG));
        ?>
        <script>
        document.addEventListener('DOMContentLoaded', function () {
            var addNew = document.querySelector('.page-title-action');
            if (!addNew) return;
            var btn = document.createElement('a');
            btn.href = <?php echo \wp_json_encode($url); ?>;
            btn.className = 'page-title-action';
            btn.textContent = 'Re-Order Team';
            addNew.parentNode.insertBefore(btn, addNew.nextSibling);
        });
        </script>
        <?php
    }

    public static function render(): void
    {
        if (! \current_user_can('edit_posts')) {
            \wp_die(\esc_html__('Insufficient permissions.', 'sage'));
        }

        $members       = self::fetchMembers();
        $groups        = self::groupByTier($members);
        $orderedGroups = self::buildOrderedGroups($groups);
        $nonce         = \wp_create_nonce('wp_rest');
        $restUrl       = \rest_url('planetario/v1/team-reorder');

        self::renderStyles();
        ?>
        <div class="wrap" id="team-reorder-wrap">
            <h1 class="wp-heading-inline">Re-Order Team Members</h1>
            <p class="description">Drag members within each group to set display order. Click <strong>Save Order</strong> when done.</p>

            <div id="team-reorder-notice" style="display:none;"></div>

            <div class="team-reorder-actions team-reorder-actions--top">
                <button type="button" class="button button-primary" id="save-order-top">Save Order</button>
                <span class="spinner" id="spinner-top"></span>
            </div>

            <?php
            foreach ($orderedGroups as $tierKey => $group) :
            ?>
            <div class="team-reorder-group" data-tier="<?php echo \esc_attr($tierKey); ?>">
                <h2 class="team-reorder-group-title"><?php echo \esc_html($group['label']); ?></h2>
                <ul class="team-reorder-list">
                    <?php
                    foreach ($group['members'] as $member) :
                    ?>
                    <li class="team-reorder-item"
                        data-id="<?php echo \esc_attr((string) $member['id']); ?>"
                        draggable="true">
                        <span class="team-reorder-handle" title="Drag to reorder">&#8942;&#8942;</span>
                        <img
                            class="team-reorder-photo"
                            src="<?php echo \esc_url($member['photo']); ?>"
                            alt="<?php echo \esc_attr($member['name']); ?>"
                            width="48"
                            height="48"
                            loading="lazy">
                        <div class="team-reorder-info">
                            <strong><?php echo \esc_html($member['name']); ?></strong>
                            <span><?php echo \esc_html($member['role']); ?></span>
                        </div>
                    </li>
                    <?php
                    endforeach;
                    ?>
                </ul>
            </div>
            <?php
            endforeach;
            ?>

            <div class="team-reorder-actions team-reorder-actions--bottom">
                <button type="button" class="button button-primary" id="save-order-bottom">Save Order</button>
                <span class="spinner" id="spinner-bottom"></span>
            </div>
        </div>

        <script>
        window.teamReorderAdmin = {
            nonce: <?php echo \wp_json_encode($nonce); ?>,
            restUrl: <?php echo \wp_json_encode($restUrl); ?>
        };
        </script>
        <?php
    }

    private static function renderStyles(): void
    {
        ?>
        <style>
        #team-reorder-wrap { max-width: 800px; }
        .team-reorder-actions { display: flex; align-items: center; gap: 10px; margin: 16px 0; }
        .team-reorder-actions .spinner { float: none; margin: 0; }
        .team-reorder-group { margin-bottom: 32px; }
        .team-reorder-group-title {
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: #50575e;
            margin-bottom: 8px;
            padding-bottom: 6px;
            border-bottom: 1px solid #ddd;
        }
        .team-reorder-list { margin: 0; padding: 0; list-style: none; border: 1px solid #ddd; border-radius: 4px; background: #fff; }
        .team-reorder-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            border-bottom: 1px solid #f0f0f0;
            transition: background .1s;
        }
        .team-reorder-item:last-child { border-bottom: none; }
        .team-reorder-item.drag-over { background: #f0f6ff; border-top: 2px solid #2271b1; }
        .team-reorder-item.dragging { opacity: .35; background: #f6f7f7; }
        .team-reorder-handle {
            cursor: grab;
            font-size: 20px;
            color: #b0b5bb;
            user-select: none;
            padding: 0 4px;
            line-height: 1;
        }
        .team-reorder-handle:active { cursor: grabbing; }
        .team-reorder-photo {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            object-fit: cover;
            flex-shrink: 0;
            background: #eee;
        }
        .team-reorder-info { display: flex; flex-direction: column; gap: 2px; }
        .team-reorder-info strong { font-size: 13px; color: #1d2327; }
        .team-reorder-info span { font-size: 12px; color: #777; }
        #team-reorder-notice {
            padding: 10px 16px;
            border-radius: 3px;
            margin-bottom: 14px;
            font-size: 13px;
        }
        #team-reorder-notice.notice-success { background: #edfaef; border-left: 4px solid #00a32a; color: #1d6e2e; }
        #team-reorder-notice.notice-error   { background: #fcf0f1; border-left: 4px solid #d63638; color: #8a2424; }
        </style>
        <?php
    }

    private static function fetchMembers(): array
    {
        $query = new WP_Query([
            'post_type'      => TeamMemberPostType::POST_TYPE,
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'orderby'        => ['menu_order' => 'ASC', 'date' => 'ASC'],
        ]);

        $members = [];

        foreach ($query->posts as $post) {
            $photo    = \get_field('team_photo', $post->ID);
            $photoUrl = is_array($photo) ? ($photo['url'] ?? '') : '';

            if (! $photoUrl) {
                $photoUrl = 'https://i.pravatar.cc/500?u=' . rawurlencode($post->post_title);
            }

            $terms = \get_the_terms($post->ID, TeamMemberPostType::TAX_ROLE);
            $tier  = is_array($terms) && ! empty($terms) ? (string) $terms[0]->name : 'Other';

            $members[] = [
                'id'         => $post->ID,
                'name'       => $post->post_title,
                'role'       => (string) \get_field('team_title', $post->ID),
                'tier'       => $tier,
                'photo'      => $photoUrl,
                'menu_order' => $post->menu_order,
            ];
        }

        return $members;
    }

    private static function groupByTier(array $members): array
    {
        $groups = [];

        foreach ($members as $m) {
            $groups[$m['tier']][] = $m;
        }

        return $groups;
    }

    private static function buildOrderedGroups(array $groups): array
    {
        $order = self::GROUP_ORDER;

        // Append any tiers not in the predefined order
        foreach ($groups as $tierName => $_) {
            if (! isset($order[$tierName])) {
                $order[$tierName] = $tierName;
            }
        }

        $result = [];
        foreach ($order as $tierKey => $tierLabel) {
            if (isset($groups[$tierKey])) {
                $result[$tierKey] = [
                    'label'   => $tierLabel,
                    'members' => $groups[$tierKey],
                ];
            }
        }

        return $result;
    }
}
