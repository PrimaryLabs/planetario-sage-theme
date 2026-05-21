<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;
use WP_Query;

class Blog extends Composer
{
    protected static $views = [
        'page-blog',
    ];

    public function with(): array
    {
        $paged = max(1, (int) (\get_query_var('paged') ?: \get_query_var('page') ?: 1));

        $query = new WP_Query([
            'post_type'      => 'post',
            'post_status'    => 'publish',
            'posts_per_page' => 9,
            'paged'          => $paged,
            'orderby'        => 'date',
            'order'          => 'DESC',
        ]);

        $posts = array_map([$this, 'normalize'], $query->posts);

        $pagination = paginate_links([
            'total'     => $query->max_num_pages,
            'current'   => $paged,
            'prev_text' => '&larr;',
            'next_text' => '&rarr;',
            'type'      => 'array',
        ]);

        \wp_reset_postdata();

        return [
            'blogPosts'      => $posts,
            'blogPagination' => $pagination ?: [],
            'blogHasMore'    => $query->max_num_pages > 1,
        ];
    }

    private function normalize(\WP_Post $post): array
    {
        $categories = \get_the_category($post->ID);
        $cats = array_map(static fn ($c) => [
            'name' => $c->name,
            'url'  => \get_category_link($c->term_id),
        ], $categories ?: []);

        $thumbnail = \get_the_post_thumbnail_url($post->ID, 'large')
            ?: (string) \get_post_meta($post->ID, 'post_thumbnail_url', true);

        $wordCount = str_word_count(strip_tags($post->post_content));
        $readTime  = max(1, (int) ceil($wordCount / 200));

        return [
            'id'           => $post->ID,
            'title'        => \get_the_title($post->ID),
            'permalink'    => \get_permalink($post->ID),
            'excerpt'      => \get_the_excerpt($post->ID),
            'bodyPreview'  => \wp_trim_words(strip_tags($post->post_content), 50, '…'),
            'thumbnail'    => $thumbnail,
            'date'         => $post->post_date,
            'dateFormatted'=> \get_the_date('M j, Y', $post->ID),
            'categories'   => $cats,
            'readTime'     => $readTime,
        ];
    }
}
