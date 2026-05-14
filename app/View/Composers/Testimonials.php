<?php

namespace App\View\Composers;

use App\Data\StaticData;
use App\PostTypes\Testimonial as TestimonialPostType;
use Roots\Acorn\View\Composer;
use WP_Post;
use WP_Query;

class Testimonials extends Composer
{
    protected static $views = [
        'front-page',
        'page-testimonials',
    ];

    public function with(): array
    {
        $all = $this->all();

        return [
            'testimonials'           => $all,
            'testimonialsHighlights' => $this->highlights($all),
        ];
    }

    public function all(): array
    {
        if (! \post_type_exists(TestimonialPostType::POST_TYPE)) {
            return $this->fallback();
        }

        $query = new WP_Query([
            'post_type'      => TestimonialPostType::POST_TYPE,
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'orderby'        => ['menu_order' => 'ASC', 'date' => 'DESC'],
        ]);

        if (! $query->have_posts()) return $this->fallback();

        return array_map([$this, 'normalize'], $query->posts);
    }

    private function highlights(array $all): array
    {
        $picked = array_values(array_filter($all, static fn ($t) => ! empty($t['highlight'])));
        if (count($picked) >= 2) return array_slice($picked, 0, 2);
        return array_slice($all, 0, 2);
    }

    private function normalize(WP_Post $post): array
    {
        $avatar = \get_field('testimonial_avatar', $post->ID);
        $avatarUrl = is_array($avatar) ? ($avatar['url'] ?? '') : '';

        $name = (string) (\get_field('testimonial_name', $post->ID) ?: $post->post_title);
        if (! $avatarUrl) {
            $avatarUrl = 'https://i.pravatar.cc/96?u=' . rawurlencode($name);
        }

        return [
            'id'        => $post->post_name,
            'quote'     => (string) \get_field('testimonial_quote', $post->ID),
            'name'      => $name,
            'role'      => (string) \get_field('testimonial_role', $post->ID),
            'avatar'    => $avatarUrl,
            'highlight' => (bool) \get_field('testimonial_highlight', $post->ID),
        ];
    }

    private function fallback(): array
    {
        return array_map(static function ($row, $i) {
            return [
                'id'        => \sanitize_title($row['name']),
                'quote'     => $row['quote'],
                'name'      => $row['name'],
                'role'      => $row['role'],
                'avatar'    => 'https://i.pravatar.cc/96?u=' . rawurlencode($row['name']),
                'highlight' => $i < 2,
            ];
        }, StaticData::testimonials(), array_keys(StaticData::testimonials()));
    }
}
