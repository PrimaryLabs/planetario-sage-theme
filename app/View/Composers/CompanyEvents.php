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
                $file = $row['file'] ?? null;
                if (! is_array($file) || empty($file['url'])) continue;

                $mime = (string) ($file['mime_type'] ?? $file['type'] ?? '');
                $kind = str_starts_with($mime, 'video/') ? 'video' : 'image';

                $items[] = [
                    'kind'    => $kind,
                    'url'     => (string) $file['url'],
                    'mime'    => $mime ?: ($kind === 'video' ? 'video/mp4' : 'image/jpeg'),
                    'alt'     => (string) ($file['alt'] ?? $file['title'] ?? ''),
                    'caption' => (string) ($row['caption'] ?? ''),
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
}
