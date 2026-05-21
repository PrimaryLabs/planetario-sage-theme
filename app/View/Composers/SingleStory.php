<?php

namespace App\View\Composers;

use App\PostTypes\Story as StoryPostType;
use WP_Post;
use WP_Query;

class SingleStory extends Stories
{
    protected static $views = [
        'single-story',
    ];

    public function with(): array
    {
        $post = \get_post();

        if (! $post instanceof WP_Post) {
            return ['story' => null, 'otherStories' => []];
        }

        return [
            'story'        => $this->normalize($post),
            'otherStories' => $this->others($post->ID),
        ];
    }

    private function others(int $excludeId): array
    {
        if (! \post_type_exists(StoryPostType::POST_TYPE)) {
            return [];
        }

        $query = new WP_Query([
            'post_type'      => StoryPostType::POST_TYPE,
            'post_status'    => 'publish',
            'posts_per_page' => 3,
            'post__not_in'   => [$excludeId],
            'orderby'        => ['menu_order' => 'ASC', 'date' => 'ASC'],
        ]);

        if (! $query->have_posts()) {
            return [];
        }

        return array_map([$this, 'normalize'], $query->posts);
    }
}
