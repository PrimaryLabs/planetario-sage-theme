<?php

namespace App\Fields;

class PageAdminLinks
{
    /**
     * Map of page slug → [post_type, label].
     * Each entry registers a small ACF "message" field group on that page that
     * surfaces a button linking to the matching CPT admin screen.
     */
    private const LINKS = [
        'properties'   => ['post_type' => 'property',     'label' => 'Manage Properties'],
        'team'         => ['post_type' => 'team_member',  'label' => 'Manage Team Members'],
        'stories'      => ['post_type' => 'story',        'label' => 'Manage Success Stories'],
        'testimonials' => ['post_type' => 'testimonial',  'label' => 'Manage Testimonials'],
        'developers'   => ['post_type' => 'developer',    'label' => 'Manage Developers'],
    ];

    public static function register(): void
    {
        if (! function_exists('acf_add_local_field_group')) {
            return;
        }

        foreach (self::LINKS as $slug => $meta) {
            $page = \get_page_by_path($slug);
            if (! $page) continue;

            $editUrl = \admin_url('edit.php?post_type=' . $meta['post_type']);
            $addUrl  = \admin_url('post-new.php?post_type=' . $meta['post_type']);

            \acf_add_local_field_group([
                'key'        => 'group_admin_link_' . $slug,
                'title'      => $meta['label'],
                'location'   => [[
                    [
                        'param'    => 'page',
                        'operator' => '==',
                        'value'    => (string) $page->ID,
                    ],
                ]],
                'position'   => 'normal',
                'style'      => 'default',
                'menu_order' => 5,
                'active'     => true,
                'fields'     => [[
                    'key'           => 'field_admin_link_' . $slug,
                    'label'         => '',
                    'name'          => 'admin_link_' . $slug,
                    'type'          => 'message',
                    'message'       => self::renderMessage($meta['label'], $editUrl, $addUrl, $meta['post_type']),
                    'new_lines'     => '',
                    'esc_html'      => 0,
                ]],
            ]);
        }
    }

    private static function renderMessage(string $label, string $editUrl, string $addUrl, string $postType): string
    {
        $count = \wp_count_posts($postType);
        $published = (int) ($count->publish ?? 0);

        return sprintf(
            '<div style="display:flex;gap:12px;align-items:center;flex-wrap:wrap;padding:4px 0">
                <a href="%s" class="button button-primary">%s &rarr;</a>
                <a href="%s" class="button">+ Add new</a>
                <span style="color:#646970;font-size:13px">%d published</span>
            </div>',
            \esc_url($editUrl),
            \esc_html($label),
            \esc_url($addUrl),
            $published,
        );
    }
}
