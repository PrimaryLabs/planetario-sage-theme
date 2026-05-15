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
            'team'         => $members,
            'founders'     => $byTier['founder'] ?? [],
            'managers'     => $byTier['manager'] ?? [],
            'brokers'      => $byTier['broker'] ?? [],
            'staffs'       => $byTier['staff'] ?? [],
            'teamGroups'   => [
                ['key' => 'managers', 'label' => 'Managers', 'eyebrow' => 'Leadership', 'members' => $byTier['manager'] ?? []],
                ['key' => 'brokers',  'label' => 'Brokers',  'eyebrow' => 'Salesfloor', 'members' => $byTier['broker'] ?? []],
                ['key' => 'staffs',   'label' => 'Staff',    'eyebrow' => 'Support',    'members' => $byTier['staff'] ?? []],
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
            'name'             => $post->post_title,
            'role'             => (string) \get_field('team_title', $post->ID),
            'tier'             => $tier ?: 'staff',
            'region'           => (string) (\get_field('team_region', $post->ID) ?: 'all'),
            'bio'              => (string) \get_field('team_bio', $post->ID),
            'photo'            => $photoUrl,
            'email'            => (string) \get_field('team_email', $post->ID),
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
