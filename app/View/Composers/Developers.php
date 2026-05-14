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
        return [
            'developers' => $this->all(),
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

    private function normalize(WP_Post $post): array
    {
        $rows = \get_field('developer_locations', $post->ID);
        $locations = is_array($rows)
            ? array_values(array_filter(array_map(static fn ($r) => (string) ($r['location'] ?? ''), $rows)))
            : [];

        $logo = \get_field('developer_logo', $post->ID);

        return [
            'name'        => $post->post_title,
            'sigil'       => (string) (\get_field('developer_sigil', $post->ID) ?: mb_substr($post->post_title, 0, 1)),
            'portfolio'   => (string) \get_field('developer_portfolio', $post->ID),
            'desc'        => (string) \get_field('developer_desc', $post->ID),
            'locations'   => $locations,
            'website'     => (string) \get_field('developer_website', $post->ID),
            'established' => (string) \get_field('developer_established', $post->ID),
            'logo'        => is_array($logo) ? ($logo['url'] ?? '') : '',
        ];
    }

    private function fallback(): array
    {
        return StaticData::developers();
    }
}
