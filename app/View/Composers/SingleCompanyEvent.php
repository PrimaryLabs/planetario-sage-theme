<?php

namespace App\View\Composers;

use App\PostTypes\CompanyEvent as CompanyEventPostType;
use WP_Post;
use WP_Query;

class SingleCompanyEvent extends CompanyEvents
{
    protected static $views = [
        'single-company_event',
    ];

    public function with(): array
    {
        $post = \get_post();

        if (! $post instanceof WP_Post) {
            return ['event' => null, 'relatedEvents' => []];
        }

        return [
            'event'         => $this->normalize($post),
            'relatedEvents' => $this->related($post->ID),
        ];
    }

    private function related(int $excludeId): array
    {
        if (! \post_type_exists(CompanyEventPostType::POST_TYPE)) {
            return [];
        }

        $query = new WP_Query([
            'post_type'      => CompanyEventPostType::POST_TYPE,
            'post_status'    => 'publish',
            'posts_per_page' => 3,
            'post__not_in'   => [$excludeId],
            'meta_key'       => 'event_date',
            'orderby'        => ['meta_value' => 'DESC', 'date' => 'DESC'],
        ]);

        if (! $query->have_posts()) {
            return [];
        }

        return array_map([$this, 'normalize'], $query->posts);
    }
}
