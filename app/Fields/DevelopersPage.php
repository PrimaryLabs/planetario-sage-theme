<?php

namespace App\Fields;

class DevelopersPage
{
    public const GROUP_KEY = 'group_developers_page';
    public const PAGE_SLUG = 'developers';

    public static function register(): void
    {
        if (! function_exists('acf_add_local_field_group')) {
            return;
        }

        $page = \get_page_by_path(self::PAGE_SLUG);
        if (! $page) {
            return;
        }

        \acf_add_local_field_group([
            'key'      => self::GROUP_KEY,
            'title'    => 'Developers Page — How We Work',
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
            [
                'key'   => 'field_devs_how_tab',
                'label' => 'How We Work',
                'type'  => 'tab',
            ],
            [
                'key'           => 'field_devs_how_eyebrow',
                'label'         => 'Eyebrow',
                'name'          => 'devs_how_eyebrow',
                'type'          => 'text',
                'default_value' => 'How we work with developers',
            ],
            [
                'key'           => 'field_devs_how_headline_lead',
                'label'         => 'Headline — lead',
                'name'          => 'devs_how_headline_lead',
                'type'          => 'text',
                'default_value' => 'A small list.',
            ],
            [
                'key'           => 'field_devs_how_headline_em',
                'label'         => 'Headline — emphasis',
                'name'          => 'devs_how_headline_em',
                'type'          => 'text',
                'default_value' => 'Carefully kept.',
            ],
            [
                'key'           => 'field_devs_how_lead',
                'label'         => 'Lead paragraph',
                'name'          => 'devs_how_lead',
                'type'          => 'textarea',
                'rows'          => 3,
                'new_lines'     => '',
                'default_value' => 'Our partnership is a stake on both sides. Here is what working with Planetario typically looks like — for the builder, and for the buyer.',
            ],
            [
                'key'             => 'field_devs_how_items',
                'label'           => 'Feature cards',
                'name'            => 'devs_how_items',
                'type'            => 'repeater_field',
                'rf_min'          => 0,
                'rf_layout'       => 'block',
                'rf_button_label' => 'Add card',
                'rf_sub_fields'   => "num | Number | text\ntitle | Title | text\ndesc | Description | textarea",
            ],
        ];
    }
}
