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
            'boholManagers'    => $this->sortByManagerOrder($byTier['Bohol Managers'] ?? []),
            'cebuManagers'     => $this->sortByManagerOrder($byTier['Cebu Managers']  ?? []),
            'boholStaffs'      => $this->sortByStaffOrder($byTier['Bohol Staff']      ?? []),
            'cebuStaffs'       => $this->sortByStaffOrder($byTier['Cebu Staff']        ?? []),
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

    private function rankRole(string $role, array $map): int
    {
        $lower = strtolower($role);
        foreach ($map as $keyword => $rank) {
            if (strpos($lower, $keyword) !== false) {
                return $rank;
            }
        }
        return 999;
    }

    private function sortByManagerOrder(array $members): array
    {
        $map = [
            'senior division manager' => 1,
            'division manager'        => 2,
            'sales manager'           => 3,
        ];
        usort($members, fn($a, $b) =>
            $this->rankRole($a['role'], $map) <=> $this->rankRole($b['role'], $map)
        );
        return $members;
    }

    private function sortByStaffOrder(array $members): array
    {
        $map = [
            'general manager'   => 1,
            'operation manager' => 2,
            'accounting'        => 3,
            'marketing head'    => 4,
            'compliance head'   => 5,
            'collection head'   => 6,
            'collection team'   => 7,
            'marketing officer' => 8,
            'admin officer'     => 9,
            'data analyst'      => 10,
        ];
        usort($members, fn($a, $b) =>
            $this->rankRole($a['role'], $map) <=> $this->rankRole($b['role'], $map)
        );
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
