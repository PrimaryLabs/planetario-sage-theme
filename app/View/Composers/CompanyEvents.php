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
        'page-events',
    ];

    public function with(): array
    {
        $all = $this->all();

        return [
            'companyEvents'  => $all,
            'featuredEvents' => array_slice($all, 0, 3),
            'allEvents'      => $all,
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

    protected function normalize(WP_Post $post): array
    {
        $cover    = \get_field('event_cover', $post->ID);
        $coverUrl = is_array($cover) ? ($cover['url'] ?? '') : '';
        if (! $coverUrl) {
            $coverUrl = (string) \get_the_post_thumbnail_url($post->ID, 'large');
        }
        if (! $coverUrl) {
            $coverUrl = (string) (\get_field('event_cover_url', $post->ID) ?: '');
        }

        $dateRaw = (string) \get_field('event_date', $post->ID);
        $dateLabel = $dateRaw ? \date_i18n('F j, Y', strtotime($dateRaw)) : '';

        $gallery = \get_field('event_gallery', $post->ID);
        $items   = [];
        if (is_array($gallery)) {
            foreach ($gallery as $row) {
                $mediaType   = (string) ($row['media_type'] ?? 'image') ?: 'image';
                $title       = (string) ($row['title'] ?? '');
                $description = (string) ($row['description'] ?? '');

                $image    = $row['image'] ?? null;
                $imageUrl = is_array($image) ? (string) ($image['url'] ?? '') : '';

                $poster    = $row['poster'] ?? null;
                $posterUrl = is_array($poster) ? (string) ($poster['url'] ?? '') : '';

                $video     = $row['video'] ?? null;
                $videoUrl  = is_array($video) ? (string) ($video['url'] ?? '') : '';
                $videoMime = is_array($video) ? (string) ($video['mime_type'] ?? '') : '';

                $youtubeId    = $this->youtubeVideoId((string) ($row['youtube'] ?? ''));
                $youtubeEmbed = $youtubeId ? 'https://www.youtube.com/embed/' . $youtubeId : '';

                if ($mediaType === 'video' && $videoUrl === '') $mediaType = 'image';
                if ($mediaType === 'youtube' && $youtubeEmbed === '') $mediaType = 'image';

                if ($mediaType === 'youtube') {
                    $items[] = [
                        'kind'        => 'youtube',
                        'url'         => '',
                        'embed'       => $youtubeEmbed,
                        'mime'        => '',
                        'poster'      => $posterUrl ?: 'https://img.youtube.com/vi/' . $youtubeId . '/hqdefault.jpg',
                        'alt'         => $title,
                        'title'       => $title,
                        'description' => $description,
                    ];
                    continue;
                }

                if ($mediaType === 'video') {
                    $items[] = [
                        'kind'        => 'video',
                        'url'         => $videoUrl,
                        'embed'       => '',
                        'mime'        => $videoMime ?: $this->videoMime($videoUrl),
                        'poster'      => $posterUrl,
                        'alt'         => $title,
                        'title'       => $title,
                        'description' => $description,
                    ];
                    continue;
                }

                if ($imageUrl === '') continue;

                $items[] = [
                    'kind'        => 'image',
                    'url'         => $imageUrl,
                    'embed'       => '',
                    'mime'        => is_array($image) ? (string) ($image['mime_type'] ?? 'image/jpeg') : 'image/jpeg',
                    'poster'      => '',
                    'alt'         => is_array($image) ? (string) ($image['alt'] ?? $title) : $title,
                    'title'       => $title,
                    'description' => $description,
                ];
            }
        }

        return [
            'id'        => $post->ID,
            'title'     => $post->post_title,
            'permalink' => (string) \get_permalink($post->ID),
            'date'      => $dateRaw,
            'dateLabel' => $dateLabel,
            'location'  => (string) \get_field('event_location', $post->ID),
            'summary'   => (string) \get_field('event_summary', $post->ID),
            'cover'     => $coverUrl,
            'gallery'   => $items,
        ];
    }

    protected function youtubeVideoId(string $url): string
    {
        $url = trim($url);
        if ($url === '') return '';

        if (preg_match('#youtu\.be/([A-Za-z0-9_-]{6,})#', $url, $m)) {
            return $m[1];
        }
        if (preg_match('#youtube\.com/(?:watch\?(?:.*&)?v=|embed/|shorts/|v/)([A-Za-z0-9_-]{6,})#', $url, $m)) {
            return $m[1];
        }
        if (preg_match('#^[A-Za-z0-9_-]{6,}$#', $url)) {
            return $url;
        }
        return '';
    }

    protected function videoMime(string $url): string
    {
        $ext = strtolower(pathinfo(parse_url($url, PHP_URL_PATH) ?: '', PATHINFO_EXTENSION));

        return match ($ext) {
            'webm'        => 'video/webm',
            'mov', 'm4v'  => 'video/quicktime',
            default       => 'video/mp4',
        };
    }
}
