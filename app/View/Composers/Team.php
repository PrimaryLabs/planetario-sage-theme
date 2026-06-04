<?php

namespace App\View\Composers;

use App\Data\StaticData;
use App\PostTypes\TeamMember as TeamMemberPostType;
use Roots\Acorn\View\Composer;
use WP_Post;
use WP_Query;

class Team extends Composer
{
    protected static $views = [
        'page-team',
        'page-teams',
    ];

    public function with(): array
    {
        $members  = $this->members();
        $byTier   = $this->groupByTier($members);

        return [
            'team'             => $members,
            'boardOfDirectors' => $byTier['Board of Directors'] ?? [],
            'brokers'          => $byTier['broker'] ?? [],
            'boholManagers'    => $this->sortByMenuOrder($byTier['Bohol Managers'] ?? []),
            'cebuManagers'     => $this->sortByMenuOrder($byTier['Cebu Managers']  ?? []),
            'boholStaffs'      => $this->sortByMenuOrder($byTier['Bohol Staff']    ?? []),
            'cebuStaffs'       => $this->sortByMenuOrder($byTier['Cebu Staff']     ?? []),
            'sectionLabels'    => $this->sectionLabels(),
        ];
    }

    private function sectionLabels(): array
    {
        $pid = \get_queried_object_id();
        $g   = fn (string $field, string $default): string =>
            (string) (\get_field($field, $pid) ?: $default);

        return [
            'board'      => [
                'eyebrow' => $g('team_board_eyebrow',       'Leadership'),
                'lead'    => $g('team_board_headline_lead', 'Board of'),
                'em'      => $g('team_board_headline_em',   'Directors.'),
            ],
            'brokers'    => [
                'eyebrow' => $g('team_brokers_eyebrow',      'Salesfloor'),
                'em'      => $g('team_brokers_headline_em',  'Brokers.'),
            ],
            'boholMgr'   => [
                'eyebrow' => $g('team_bohol_mgr_eyebrow',     'Bohol'),
                'lead'    => $g('team_bohol_mgr_lead',        'Bohol'),
                'em'      => $g('team_bohol_mgr_headline_em', 'Managers.'),
            ],
            'cebuMgr'    => [
                'eyebrow' => $g('team_cebu_mgr_eyebrow',     'Cebu'),
                'lead'    => $g('team_cebu_mgr_lead',        'Cebu'),
                'em'      => $g('team_cebu_mgr_headline_em', 'Managers.'),
            ],
            'boholStaff' => [
                'eyebrow' => $g('team_bohol_staff_eyebrow',     'Bohol'),
                'lead'    => $g('team_bohol_staff_lead',        'Bohol'),
                'em'      => $g('team_bohol_staff_headline_em', 'Staff.'),
            ],
            'cebuStaff'  => [
                'eyebrow' => $g('team_cebu_staff_eyebrow',     'Cebu'),
                'lead'    => $g('team_cebu_staff_lead',        'Cebu'),
                'em'      => $g('team_cebu_staff_headline_em', 'Staff.'),
            ],
        ];
    }

    public function members(): array
    {
        if (! \post_type_exists(TeamMemberPostType::POST_TYPE)) {
            return $this->fallback();
        }

        $query = new WP_Query([
            'post_type'      => TeamMemberPostType::POST_TYPE,
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'orderby'        => ['menu_order' => 'ASC', 'date' => 'ASC'],
        ]);

        if (! $query->have_posts()) return $this->fallback();

        return array_map([$this, 'normalize'], $query->posts);
    }

    private function normalize(WP_Post $post): array
    {
        $photo    = \get_field('team_photo', $post->ID);
        $photoUrl = is_array($photo) ? ($photo['url'] ?? '') : '';
        if (! $photoUrl) {
            $photoUrl = 'https://i.pravatar.cc/500?u=' . rawurlencode($post->post_title);
        }

        $terms = \get_the_terms($post->ID, TeamMemberPostType::TAX_ROLE);
        $tier  = is_array($terms) && ! empty($terms) ? (string) $terms[0]->name : '';

        return [
            'id'               => $post->ID,
            'menu_order'       => $post->menu_order,
            'name'             => $post->post_title,
            'role'             => (string) \get_field('team_title', $post->ID),
            'tier'             => $tier ?: 'staff',
            'region'           => (string) (\get_field('team_region', $post->ID) ?: 'all'),
            'bio'              => (string) \get_field('team_bio', $post->ID),
            'photo'            => $photoUrl,
            'email'            => (string) \get_field('team_email', $post->ID),
            'phone'            => (string) \get_field('team_phone', $post->ID),
            'linkedin'         => (string) \get_field('team_linkedin', $post->ID),
            'managing_broker'  => (bool) \get_field('team_managing_broker', $post->ID),
        ];
    }

    private function fallback(): array
    {
        return array_map(static function ($row) {
            return array_merge($row, [
                'photo' => 'https://i.pravatar.cc/500?u=' . rawurlencode($row['name']),
                'email' => '',
                'linkedin' => '',
            ]);
        }, StaticData::team());
    }

    private function sortByMenuOrder(array $members): array
    {
        usort($members, fn ($a, $b) => $a['menu_order'] <=> $b['menu_order']);
        return $members;
    }

    private function groupByTier(array $members): array
    {
        $groups = [];
        foreach ($members as $m) {
            $tier = $m['tier'] ?? 'staff';
            $groups[$tier][] = $m;
        }
        return $groups;
    }
}
