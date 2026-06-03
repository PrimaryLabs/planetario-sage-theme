<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class ContactPage extends Composer
{
    protected static $views = [
        'page-contact',
    ];

    public function with(): array
    {
        $page   = \get_page_by_path('contact');
        $pageId = $page ? (int) $page->ID : 0;

        return [
            'contactSections' => [
                'sidebarEyebrow' => $this->field('contact_sidebar_eyebrow', $pageId, 'Reach us directly'),
                'map'            => [
                    'eyebrow'      => $this->field('contact_map_eyebrow',       $pageId, 'Find us on the map'),
                    'headingLead'  => $this->field('contact_map_heading_lead',  $pageId, 'Visit the'),
                    'headingEm'    => $this->field('contact_map_heading_em',    $pageId, 'Tagbilaran'),
                    'headingTrail' => $this->field('contact_map_heading_trail', $pageId, 'office.'),
                ],
            ],
        ];
    }

    private function field(string $name, int $pageId, string $fallback = ''): string
    {
        if (! $pageId || ! function_exists('get_field')) {
            return $fallback;
        }

        $value = \get_field($name, $pageId);

        return ($value === null || $value === '' || $value === false) ? $fallback : (string) $value;
    }
}
