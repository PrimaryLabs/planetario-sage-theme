<?php

namespace App\View\Composers;

use App\PostTypes\Vlog as VlogPostType;
use WP_Post;
use WP_Query;

class SingleVlog extends Vlogs
{
    protected static $views = [
        'single-vlog',
    ];

    public function with(): array
    {
        $post = \get_post();

        if (! $post instanceof WP_Post) {
            return ['vlog' => null, 'otherVlogs' => []];
        }

        return [
            'vlog'       => $this->normalize($post),
            'otherVlogs' => $this->others($post->ID),
        ];
    }

    private function others(int $excludeId): array
    {
        if (! \post_type_exists(VlogPostType::POST_TYPE)) {
            return [];
        }

        $query = new WP_Query([
            'post_type'      => VlogPostType::POST_TYPE,
            'post_status'    => 'publish',
            'posts_per_page' => 3,
            'post__not_in'   => [$excludeId],
            'orderby'        => ['menu_order' => 'ASC', 'date' => 'DESC'],
        ]);

        if (! $query->have_posts()) {
            return [];
        }

        $items = array_map([$this, 'normalize'], $query->posts);
        \wp_reset_postdata();

        return $items;
    }
}
