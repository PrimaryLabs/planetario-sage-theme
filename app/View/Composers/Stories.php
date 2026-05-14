<?php

namespace App\View\Composers;

use App\Data\StaticData;
use App\PostTypes\Story as StoryPostType;
use Roots\Acorn\View\Composer;
use WP_Post;
use WP_Query;

class Stories extends Composer
{
    protected static $views = [
        'page-stories',
    ];

    public function with(): array
    {
        return [
            'stories' => $this->all(),
        ];
    }

    public function all(): array
    {
        if (! \post_type_exists(StoryPostType::POST_TYPE)) {
            return $this->fallback();
        }

        $query = new WP_Query([
            'post_type'      => StoryPostType::POST_TYPE,
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'orderby'        => ['menu_order' => 'ASC', 'date' => 'ASC'],
        ]);

        if (! $query->have_posts()) return $this->fallback();

        return array_map([$this, 'normalize'], $query->posts);
    }

    private function normalize(WP_Post $post): array
    {
        $image    = \get_field('story_image', $post->ID);
        $imageUrl = is_array($image) ? ($image['url'] ?? '') : '';
        if (! $imageUrl) {
            $imageUrl = (string) (\get_field('story_image_url', $post->ID) ?: \get_the_post_thumbnail_url($post->ID, 'large'));
        }

        $rows  = \get_field('story_stats', $post->ID);
        $stats = is_array($rows)
            ? array_values(array_map(static fn ($r) => [
                'v' => (string) ($r['value'] ?? ''),
                'l' => (string) ($r['label'] ?? ''),
            ], $rows))
            : [];

        $propertyId = (int) (\get_field('story_property', $post->ID) ?: 0);

        return [
            'client'   => $post->post_title,
            'quote'    => (string) \get_field('story_quote', $post->ID),
            'location' => (string) \get_field('story_location', $post->ID),
            'year'     => (string) \get_field('story_year', $post->ID),
            'image'    => $imageUrl,
            'summary'  => (string) \get_field('story_summary', $post->ID),
            'stats'    => $stats,
            'property' => $propertyId ? [
                'id'   => $propertyId,
                'url'  => \get_permalink($propertyId),
                'name' => \get_the_title($propertyId),
            ] : null,
        ];
    }

    private function fallback(): array
    {
        return StaticData::stories();
    }
}
