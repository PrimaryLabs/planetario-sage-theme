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

        $mediaType = (string) (\get_field('story_media_type', $post->ID) ?: 'image');

        $video    = \get_field('story_video', $post->ID);
        $videoUrl = '';
        $videoMime = '';
        if (is_array($video)) {
            $videoUrl  = (string) ($video['url']      ?? '');
            $videoMime = (string) ($video['mime_type'] ?? '');
        }

        $youtubeRaw = (string) (\get_field('story_youtube', $post->ID) ?: '');
        $youtubeEmbed = $youtubeRaw ? $this->youtubeEmbedUrl($youtubeRaw) : '';

        if ($mediaType === 'video' && ! $videoUrl) $mediaType = 'image';
        if ($mediaType === 'youtube' && ! $youtubeEmbed) $mediaType = 'image';

        $rows  = \get_field('story_stats', $post->ID);
        $stats = is_array($rows)
            ? array_values(array_map(static fn ($r) => [
                'v' => (string) ($r['value'] ?? ''),
                'l' => (string) ($r['label'] ?? ''),
            ], $rows))
            : [];

        $propertyId = (int) (\get_field('story_property', $post->ID) ?: 0);

        return [
            'client'    => $post->post_title,
            'quote'     => (string) \get_field('story_quote', $post->ID),
            'location'  => (string) \get_field('story_location', $post->ID),
            'year'      => (string) \get_field('story_year', $post->ID),
            'mediaType' => $mediaType,
            'image'     => $imageUrl,
            'video'     => $videoUrl ? ['url' => $videoUrl, 'mime' => $videoMime ?: 'video/mp4'] : null,
            'youtube'   => $youtubeEmbed ? ['url' => $youtubeRaw, 'embed' => $youtubeEmbed] : null,
            'summary'   => (string) \get_field('story_summary', $post->ID),
            'stats'     => $stats,
            'property'  => $propertyId ? [
                'id'   => $propertyId,
                'url'  => \get_permalink($propertyId),
                'name' => \get_the_title($propertyId),
            ] : null,
        ];
    }

    private function youtubeEmbedUrl(string $url): string
    {
        $url = trim($url);
        if ($url === '') return '';

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

    private function fallback(): array
    {
        return StaticData::stories();
    }
}
