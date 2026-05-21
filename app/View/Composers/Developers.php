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
        $logo = \get_field('developer_logo', $post->ID);

        return [
            'name'    => $post->post_title,
            'website' => (string) \get_field('developer_website', $post->ID),
            'logo'    => is_array($logo) ? ($logo['url'] ?? '') : '',
        ];
    }

    private function fallback(): array
    {
        return StaticData::developers();
    }
}
