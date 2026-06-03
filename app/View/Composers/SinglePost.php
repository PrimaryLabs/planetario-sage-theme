<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;
use WP_Post;
use WP_Query;

class SinglePost extends Composer
{
    protected static $views = [
        'single',
    ];

    public function with(): array
    {
        $post = \get_post();

        if (! $post instanceof WP_Post) {
            return ['blogPost' => null, 'relatedPosts' => [], 'blogUrl' => $this->blogUrl()];
        }

        return [
            'blogPost'     => $this->normalize($post),
            'relatedPosts' => $this->related($post->ID),
            'blogUrl'      => $this->blogUrl(),
        ];
    }

    private function normalize(WP_Post $post): array
    {
        $categories = \get_the_category($post->ID);
        $cats = array_map(static fn ($c) => [
            'name' => $c->name,
            'url'  => \get_category_link($c->term_id),
        ], $categories ?: []);

        $thumbnail = \get_the_post_thumbnail_url($post->ID, 'full')
            ?: (string) \get_post_meta($post->ID, 'post_thumbnail_url', true);

        $wordCount = str_word_count(strip_tags($post->post_content));
        $readTime  = max(1, (int) ceil($wordCount / 200));

        return [
            'id'            => $post->ID,
            'title'         => \get_the_title($post->ID),
            'permalink'     => \get_permalink($post->ID),
            'excerpt'       => \get_the_excerpt($post->ID),
            'thumbnail'     => $thumbnail ?: null,
            'date'          => $post->post_date,
            'dateFormatted' => \get_the_date('M j, Y', $post->ID),
            'categories'    => $cats,
            'readTime'      => $readTime,
        ];
    }

    private function related(int $excludeId): array
    {
        $query = new WP_Query([
            'post_type'      => 'post',
            'post_status'    => 'publish',
            'posts_per_page' => 3,
            'post__not_in'   => [$excludeId],
            'orderby'        => 'date',
            'order'          => 'DESC',
        ]);

        if (! $query->have_posts()) {
            return [];
        }

        $posts = array_map([$this, 'normalize'], $query->posts);
        \wp_reset_postdata();

        return $posts;
    }

    private function blogUrl(): string
    {
        $pageId = (int) \get_option('page_for_posts');
        if ($pageId) {
            return (string) \get_permalink($pageId);
        }

        $page = \get_page_by_path('blog');
        if ($page instanceof WP_Post) {
            return (string) \get_permalink($page->ID);
        }

        return \home_url('/blog');
    }
}
