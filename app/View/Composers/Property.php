<?php

namespace App\View\Composers;

use App\Data\StaticData;
use App\PostTypes\Property as PropertyPostType;
use Roots\Acorn\View\Composer;
use WP_Post;
use WP_Query;

class Property extends Composer
{
    protected static $views = [
        'front-page',
        'page-properties',
    ];

    public function with(): array
    {
        $all = $this->properties();

        return [
            'properties'         => $all,
            'featuredProperties' => $this->featured($all),
            'propertyCount'      => count($all),
            'propertyTypes'      => $this->distinct($all, 'type'),
            'propertyTags'       => $this->distinctTags($all),
        ];
    }

    public function properties(): array
    {
        if (! \post_type_exists(PropertyPostType::POST_TYPE)) {
            return $this->fallback();
        }

        $query = new WP_Query([
            'post_type'      => PropertyPostType::POST_TYPE,
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'orderby'        => ['menu_order' => 'ASC', 'date' => 'DESC'],
        ]);

        if (! $query->have_posts()) {
            return $this->fallback();
        }

        return array_map([$this, 'normalize'], $query->posts);
    }

    private function featured(array $all): array
    {
        $featured = array_values(array_filter($all, static fn ($p) => ! empty($p['isFeatured'])));
        if (count($featured) >= 6) {
            return array_slice($featured, 0, 6);
        }

        if (empty($featured)) {
            $byPrice = $all;
            usort($byPrice, static fn ($a, $b) => ($b['price'] ?? 0) <=> ($a['price'] ?? 0));
            return array_slice($byPrice, 0, 6);
        }

        return array_slice($featured, 0, 6);
    }

    private function normalize(WP_Post $post): array
    {
        $imageField  = \get_field('property_image', $post->ID);
        $imageUrl    = is_array($imageField) ? ($imageField['url'] ?? '') : '';
        if (! $imageUrl) {
            $thumb    = \get_the_post_thumbnail_url($post->ID, 'large');
            $imageUrl = $thumb ?: (string) \get_field('property_image_url', $post->ID);
        }

        $features = \get_field('property_features', $post->ID);
        $features = is_array($features)
            ? array_values(array_filter(array_map(static fn ($r) => (string) ($r['feature'] ?? ''), $features)))
            : [];

        return [
            'id'         => $post->post_name,
            'postId'     => $post->ID,
            'name'       => $post->post_title,
            'url'        => \get_permalink($post),
            'location'   => (string) (\get_field('property_location', $post->ID) ?: ''),
            'region'     => $this->firstTermName($post->ID, PropertyPostType::TAX_REGION),
            'type'       => $this->firstTermName($post->ID, PropertyPostType::TAX_TYPE),
            'status'     => $this->firstTermName($post->ID, PropertyPostType::TAX_STATUS) ?: 'For Sale',
            'price'      => (int) (\get_field('property_price', $post->ID) ?: 0),
            'priceLabel' => (string) (\get_field('property_price_label', $post->ID) ?: ''),
            'beds'       => $this->intOrNull(\get_field('property_beds', $post->ID)),
            'baths'      => $this->intOrNull(\get_field('property_baths', $post->ID)),
            'area'       => $this->intOrNull(\get_field('property_area', $post->ID)),
            'lot'        => $this->intOrNull(\get_field('property_lot', $post->ID)),
            'image'      => $imageUrl,
            'summary'    => (string) (\get_field('property_summary', $post->ID) ?: $post->post_excerpt),
            'features'   => $features,
            'tags'       => $this->termNames($post->ID, PropertyPostType::TAX_TAG),
            'isFeatured' => (bool) \get_field('property_is_featured', $post->ID),
        ];
    }

    private function fallback(): array
    {
        return array_map(static function ($row) {
            $row['url']        = \home_url('/properties/' . $row['id']);
            $row['isFeatured'] = false;
            $row['postId']     = 0;
            return $row;
        }, StaticData::properties());
    }

    private function distinct(array $items, string $key): array
    {
        $values = array_unique(array_filter(array_column($items, $key)));
        sort($values);
        return array_values($values);
    }

    private function distinctTags(array $items): array
    {
        $tags = [];
        foreach ($items as $item) {
            foreach ((array) ($item['tags'] ?? []) as $tag) {
                $tags[$tag] = true;
            }
        }
        $tags = array_keys($tags);
        sort($tags);
        return $tags;
    }

    private function intOrNull(mixed $value): ?int
    {
        if ($value === null || $value === '' || $value === false) return null;
        return (int) $value;
    }

    private function firstTermName(int $postId, string $taxonomy): string
    {
        $terms = \get_the_terms($postId, $taxonomy);
        if (! is_array($terms) || empty($terms)) return '';
        return (string) $terms[0]->name;
    }

    private function termNames(int $postId, string $taxonomy): array
    {
        $terms = \get_the_terms($postId, $taxonomy);
        if (! is_array($terms)) return [];
        return array_map(static fn ($t) => (string) $t->name, $terms);
    }
}
