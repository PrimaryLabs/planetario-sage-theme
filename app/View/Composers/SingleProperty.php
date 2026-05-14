<?php

namespace App\View\Composers;

use App\PostTypes\Property as PropertyPostType;
use Roots\Acorn\View\Composer;
use WP_Post;
use WP_Query;

class SingleProperty extends Composer
{
    protected static $views = [
        'single-property',
    ];

    public function with(): array
    {
        $post = \get_post();
        if (! $post instanceof WP_Post) {
            return ['property' => null, 'relatedProperties' => []];
        }

        $normalized = (new Property)->properties();
        $current    = $this->findById($normalized, $post->ID) ?? $this->normalizeFallback($post);

        return [
            'property'           => $current,
            'relatedProperties'  => $this->related($current, $post->ID),
            'gallery'            => $this->gallery($post->ID),
        ];
    }

    private function findById(array $list, int $postId): ?array
    {
        foreach ($list as $item) {
            if (($item['postId'] ?? 0) === $postId) return $item;
        }
        return null;
    }

    private function normalizeFallback(WP_Post $post): array
    {
        return [
            'id'         => $post->post_name,
            'postId'     => $post->ID,
            'name'       => $post->post_title,
            'url'        => \get_permalink($post),
            'location'   => (string) \get_field('property_location', $post->ID),
            'region'     => '',
            'type'       => '',
            'status'     => 'For Sale',
            'price'      => 0,
            'priceLabel' => '',
            'beds'       => null,
            'baths'      => null,
            'area'       => null,
            'lot'        => null,
            'image'      => '',
            'summary'    => (string) \get_field('property_summary', $post->ID),
            'features'   => [],
            'tags'       => [],
            'isFeatured' => false,
        ];
    }

    private function related(array $current, int $excludeId): array
    {
        $region = $current['region'] ?? '';
        $type   = $current['type'] ?? '';

        $taxQuery = ['relation' => 'OR'];
        if ($region) {
            $taxQuery[] = [
                'taxonomy' => PropertyPostType::TAX_REGION,
                'field'    => 'name',
                'terms'    => $region,
            ];
        }
        if ($type) {
            $taxQuery[] = [
                'taxonomy' => PropertyPostType::TAX_TYPE,
                'field'    => 'name',
                'terms'    => $type,
            ];
        }

        $args = [
            'post_type'      => PropertyPostType::POST_TYPE,
            'post_status'    => 'publish',
            'posts_per_page' => 3,
            'post__not_in'   => [$excludeId],
            'orderby'        => 'rand',
        ];

        if (count($taxQuery) > 1) {
            $args['tax_query'] = [$taxQuery];
        }

        $query = new WP_Query($args);
        if (! $query->have_posts()) return [];

        $composer = new Property;
        $reflect  = new \ReflectionClass($composer);
        $method   = $reflect->getMethod('normalize');
        $method->setAccessible(true);

        return array_map(static fn ($post) => $method->invoke($composer, $post), $query->posts);
    }

    private function gallery(int $postId): array
    {
        $items = \get_field('property_gallery', $postId);
        if (! is_array($items)) return [];

        return array_values(array_map(static fn ($item) => [
            'url' => (string) ($item['url'] ?? ''),
            'alt' => (string) ($item['alt'] ?? ''),
        ], $items));
    }
}
