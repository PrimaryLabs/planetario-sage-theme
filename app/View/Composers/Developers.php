<?php

namespace App\View\Composers;

use App\Data\StaticData;
use App\PostTypes\Developer as DeveloperPostType;
use Roots\Acorn\View\Composer;
use WP_Post;
use WP_Query;

class Developers extends Composer
{
    protected static $views = [
        'page-developers',
    ];

    public function with(): array
    {
        $all = $this->all();

        return [
            'developers' => $all,
            'devGroups'  => $this->groupByRegion($all),
        ];
    }

    public function all(): array
    {
        if (! \post_type_exists(DeveloperPostType::POST_TYPE)) {
            return $this->fallback();
        }

        $query = new WP_Query([
            'post_type'      => DeveloperPostType::POST_TYPE,
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'orderby'        => ['menu_order' => 'ASC', 'date' => 'ASC'],
        ]);

        if (! $query->have_posts()) return $this->fallback();

        return array_map([$this, 'normalize'], $query->posts);
    }

    private function groupByRegion(array $devs): array
    {
        $groups = [];

        foreach ($devs as $d) {
            $label = trim((string) ($d['region'] ?? ''));
            $slug  = $label !== '' ? \sanitize_title($label) : 'other';
            $label = $label !== '' ? $label : 'Other';

            $groups[$slug]['slug']    = $slug;
            $groups[$slug]['label']   = $label;
            $groups[$slug]['items'][] = $d;
        }

        uksort($groups, static function ($a, $b) use ($groups) {
            if ($a === 'other') return 1;
            if ($b === 'other') return -1;
            return strcasecmp($groups[$a]['label'], $groups[$b]['label']);
        });

        return array_values($groups);
    }

    private function normalize(WP_Post $post): array
    {
        $logo = \get_field('developer_logo', $post->ID);

        return [
            'name'    => $post->post_title,
            'region'  => (string) \get_field('developer_region', $post->ID),
            'website' => (string) \get_field('developer_website', $post->ID),
            'logo'    => is_array($logo) ? ($logo['url'] ?? '') : '',
        ];
    }

    private function fallback(): array
    {
        return StaticData::developers();
    }
}
