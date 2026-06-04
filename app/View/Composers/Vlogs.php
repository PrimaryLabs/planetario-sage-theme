<?php

namespace App\View\Composers;

use App\PostTypes\Vlog as VlogPostType;
use Roots\Acorn\View\Composer;
use WP_Post;
use WP_Query;

class Vlogs extends Composer
{
    protected static $views = [
        'page-vlog',
    ];

    public function with(): array
    {
        return [
            'vlogs' => $this->all(),
        ];
    }

    public function all(): array
    {
        if (! \post_type_exists(VlogPostType::POST_TYPE)) {
            return [];
        }

        $query = new WP_Query([
            'post_type'      => VlogPostType::POST_TYPE,
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'orderby'        => ['menu_order' => 'ASC', 'date' => 'DESC'],
        ]);

        if (! $query->have_posts()) {
            return [];
        }

        $items = array_map([$this, 'normalize'], $query->posts);
        \wp_reset_postdata();

        return $items;
    }

    public function normalize(WP_Post $post): array
    {
        $thumbnail    = \get_field('vlog_thumbnail', $post->ID);
        $thumbnailUrl = is_array($thumbnail) ? ($thumbnail['url'] ?? '') : '';
        if (! $thumbnailUrl) {
            $thumbnailUrl = (string) \get_the_post_thumbnail_url($post->ID, 'large');
        }

        $mediaType = (string) (\get_field('vlog_media_type', $post->ID) ?: 'youtube');

        $video     = \get_field('vlog_video', $post->ID);
        $videoUrl  = '';
        $videoMime = '';
        if (is_array($video)) {
            $videoUrl  = (string) ($video['url']       ?? '');
            $videoMime = (string) ($video['mime_type'] ?? '');
        }

        $youtubeRaw   = (string) (\get_field('vlog_youtube', $post->ID) ?: '');
        $youtubeEmbed = $youtubeRaw ? $this->youtubeEmbedUrl($youtubeRaw) : '';

        if ($mediaType === 'video' && ! $videoUrl) {
            $mediaType = 'image';
        }
        if ($mediaType === 'youtube' && ! $youtubeEmbed) {
            $mediaType = 'image';
        }

        return [
            'id'          => $post->ID,
            'title'       => $post->post_title,
            'permalink'   => (string) \get_permalink($post->ID),
            'description' => (string) \get_field('vlog_description', $post->ID),
            'location'    => (string) \get_field('vlog_location', $post->ID),
            'mediaType'   => $mediaType,
            'thumbnail'   => $thumbnailUrl,
            'video'       => $videoUrl ? ['url' => $videoUrl, 'mime' => $videoMime ?: 'video/mp4'] : null,
            'youtube'     => $youtubeEmbed ? ['url' => $youtubeRaw, 'embed' => $youtubeEmbed] : null,
        ];
    }

    private function youtubeEmbedUrl(string $url): string
    {
        $url = trim($url);
        if ($url === '') {
            return '';
        }

        if (preg_match('#youtu\.be/([A-Za-z0-9_-]{6,})#', $url, $m)) {
            return 'https://www.youtube.com/embed/' . $m[1];
        }
        if (preg_match('#youtube\.com/(?:watch\?(?:.*&)?v=|embed/|shorts/|v/)([A-Za-z0-9_-]{6,})#', $url, $m)) {
            return 'https://www.youtube.com/embed/' . $m[1];
        }
        if (preg_match('#^[A-Za-z0-9_-]{6,}$#', $url)) {
            return 'https://www.youtube.com/embed/' . $url;
        }

        return '';
    }
}
