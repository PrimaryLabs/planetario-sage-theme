<?php

namespace App\View\Composers;

use App\PostTypes\CompanyEvent as CompanyEventPostType;
use Roots\Acorn\View\Composer;
use WP_Post;
use WP_Query;

class CompanyEvents extends Composer
{
    protected static $views = [
        'page-stories',
    ];

    public function with(): array
    {
        return [
            'companyEvents' => $this->all(),
        ];
    }

    public function all(): array
    {
        if (! \post_type_exists(CompanyEventPostType::POST_TYPE)) {
            return [];
        }

        $query = new WP_Query([
            'post_type'      => CompanyEventPostType::POST_TYPE,
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'meta_key'       => 'event_date',
            'orderby'        => ['meta_value' => 'DESC', 'menu_order' => 'ASC', 'date' => 'DESC'],
        ]);

        if (! $query->have_posts()) {
            return [];
        }

        return array_map([$this, 'normalize'], $query->posts);
    }

    private function normalize(WP_Post $post): array
    {
        $cover    = \get_field('event_cover', $post->ID);
        $coverUrl = is_array($cover) ? ($cover['url'] ?? '') : '';
        if (! $coverUrl) {
            $coverUrl = (string) \get_the_post_thumbnail_url($post->ID, 'large');
        }

        $dateRaw = (string) \get_field('event_date', $post->ID);
        $dateLabel = $dateRaw ? \date_i18n('F j, Y', strtotime($dateRaw)) : '';

        $gallery = \get_field('event_gallery', $post->ID);
        $items   = [];
        if (is_array($gallery)) {
            foreach ($gallery as $row) {
                $mediaType = (string) ($row['media_type'] ?? 'image') ?: 'image';
                $caption   = (string) ($row['caption'] ?? '');

                $image    = $row['image'] ?? null;
                $imageUrl = is_array($image) ? (string) ($image['url'] ?? '') : '';
                if ($imageUrl === '') {
                    $imageUrl = (string) ($row['image_url'] ?? '');
                }

                $video     = $row['video'] ?? null;
                $videoUrl  = is_array($video) ? (string) ($video['url'] ?? '') : '';
                $videoMime = is_array($video) ? (string) ($video['mime_type'] ?? '') : '';

                $youtubeEmbed = $this->youtubeEmbedUrl((string) ($row['youtube'] ?? ''));

                if ($mediaType === 'video' && $videoUrl === '') $mediaType = 'image';
                if ($mediaType === 'youtube' && $youtubeEmbed === '') $mediaType = 'image';

                if ($mediaType === 'youtube') {
                    $items[] = [
                        'kind'    => 'youtube',
                        'url'     => '',
                        'embed'   => $youtubeEmbed,
                        'mime'    => '',
                        'poster'  => $imageUrl,
                        'alt'     => $caption,
                        'caption' => $caption,
                    ];
                    continue;
                }

                if ($mediaType === 'video') {
                    $items[] = [
                        'kind'    => 'video',
                        'url'     => $videoUrl,
                        'embed'   => '',
                        'mime'    => $videoMime ?: $this->videoMime($videoUrl),
                        'poster'  => $imageUrl,
                        'alt'     => $caption,
                        'caption' => $caption,
                    ];
                    continue;
                }

                if ($imageUrl === '') continue;

                $items[] = [
                    'kind'    => 'image',
                    'url'     => $imageUrl,
                    'embed'   => '',
                    'mime'    => is_array($image) ? (string) ($image['mime_type'] ?? 'image/jpeg') : 'image/jpeg',
                    'poster'  => '',
                    'alt'     => is_array($image) ? (string) ($image['alt'] ?? $caption) : $caption,
                    'caption' => $caption,
                ];
            }
        }

        return [
            'id'        => $post->ID,
            'title'     => $post->post_title,
            'date'      => $dateRaw,
            'dateLabel' => $dateLabel,
            'location'  => (string) \get_field('event_location', $post->ID),
            'summary'   => (string) \get_field('event_summary', $post->ID),
            'cover'     => $coverUrl,
            'gallery'   => $items,
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

    private function videoMime(string $url): string
    {
        $ext = strtolower(pathinfo(parse_url($url, PHP_URL_PATH) ?: '', PATHINFO_EXTENSION));

        return match ($ext) {
            'webm'        => 'video/webm',
            'mov', 'm4v'  => 'video/quicktime',
            default       => 'video/mp4',
        };
    }
}
