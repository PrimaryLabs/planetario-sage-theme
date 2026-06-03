<?php

namespace App\Fields;

class ContactPage
{
    public const GROUP_KEY = 'group_contact_page';

    public static function register(): void
    {
        if (! function_exists('acf_add_local_field_group')) {
            return;
        }

        $page = \get_page_by_path('contact');

        if (! $page) {
            return;
        }

        \acf_add_local_field_group([
            'key'      => self::GROUP_KEY,
            'title'    => 'Contact Page',
            'location' => [[
                [
                    'param'    => 'page',
                    'operator' => '==',
                    'value'    => (string) $page->ID,
                ],
            ]],
            'position' => 'normal',
            'active'   => true,
            'fields'   => self::fields(),
        ]);
    }

    private static function fields(): array
    {
        return [
            ['key' => 'field_cp_tab_sidebar', 'label' => 'Sidebar', 'type' => 'tab'],
            [
                'key'           => 'field_cp_sidebar_eyebrow',
                'label'         => 'Sidebar eyebrow',
                'name'          => 'contact_sidebar_eyebrow',
                'type'          => 'text',
                'default_value' => 'Reach us directly',
            ],

            ['key' => 'field_cp_tab_map', 'label' => 'Map Section', 'type' => 'tab'],
            [
                'key'           => 'field_cp_map_eyebrow',
                'label'         => 'Map eyebrow',
                'name'          => 'contact_map_eyebrow',
                'type'          => 'text',
                'default_value' => 'Find us on the map',
            ],
            [
                'key'           => 'field_cp_map_heading_lead',
                'label'         => 'Map heading — lead',
                'name'          => 'contact_map_heading_lead',
                'type'          => 'text',
                'default_value' => 'Visit the',
            ],
            [
                'key'           => 'field_cp_map_heading_em',
                'label'         => 'Map heading — emphasis',
                'name'          => 'contact_map_heading_em',
                'type'          => 'text',
                'default_value' => 'Tagbilaran',
            ],
            [
                'key'           => 'field_cp_map_heading_trail',
                'label'         => 'Map heading — trailing text',
                'name'          => 'contact_map_heading_trail',
                'type'          => 'text',
                'default_value' => 'office.',
            ],
        ];
    }
}
