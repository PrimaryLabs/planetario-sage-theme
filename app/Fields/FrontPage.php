<?php

namespace App\Fields;

class FrontPage
{
    public const GROUP_KEY = 'group_front_page';

    public static function register(): void
    {
        if (! function_exists('acf_add_local_field_group')) {
            return;
        }

        acf_add_local_field_group([
            'key'      => self::GROUP_KEY,
            'title'    => 'Front Page',
            'location' => [[
                [
                    'param'    => 'page_type',
                    'operator' => '==',
                    'value'    => 'front_page',
                ],
            ]],
            'position'              => 'normal',
            'style'                 => 'default',
            'label_placement'       => 'top',
            'instruction_placement' => 'label',
            'menu_order'            => 0,
            'active'                => true,
            'fields'                => self::fields(),
        ]);
    }

    private static function fields(): array
    {
        return array_merge(
            [[
                'key'   => 'field_fp_tab_hero',
                'label' => 'Hero',
                'type'  => 'tab',
            ]],
            self::heroFields(),
        );
    }

    private static function heroFields(): array
    {
        return [
            [
                'key'           => 'field_fp_hero_eyebrow',
                'label'         => 'Eyebrow',
                'name'          => 'hero_eyebrow',
                'type'          => 'text',
                'default_value' => 'Bohol · Cebu · Visayas',
            ],
            [
                'key'           => 'field_fp_hero_headline_lead',
                'label'         => 'Headline — lead',
                'name'          => 'hero_headline_lead',
                'type'          => 'text',
                'instructions'  => 'The plain part of the headline before the emphasis.',
                'default_value' => 'A home is the long answer to a',
                'required'      => 1,
            ],
            [
                'key'           => 'field_fp_hero_headline_emphasis',
                'label'         => 'Headline — emphasis',
                'name'          => 'hero_headline_emphasis',
                'type'          => 'text',
                'instructions'  => 'Rendered in italic accent style.',
                'default_value' => 'short prayer.',
            ],
            [
                'key'           => 'field_fp_hero_sub',
                'label'         => 'Subheading',
                'name'          => 'hero_sub',
                'type'          => 'textarea',
                'rows'          => 4,
                'new_lines'     => '',
                'default_value' => "Planetario Realty & Brokerage Services Inc. has guided Boholano families, OFW investors, and first-time buyers across the Visayas for nearly a decade with patience, with paperwork done right, and with the kind of honesty that keeps clients coming back.",
            ],
            [
                'key'           => 'field_fp_hero_background',
                'label'         => 'Background image',
                'name'          => 'hero_background_image',
                'type'          => 'image',
                'return_format' => 'array',
                'preview_size'  => 'medium',
                'instructions'  => 'Leave empty to use the default Unsplash image.',
            ],
            [
                'key'           => 'field_fp_hero_primary_cta_label',
                'label'         => 'Primary CTA — label',
                'name'          => 'hero_primary_cta_label',
                'type'          => 'text',
                'default_value' => 'Browse properties',
                'wrapper'       => ['width' => '50'],
            ],
            [
                'key'           => 'field_fp_hero_primary_cta_url',
                'label'         => 'Primary CTA — URL',
                'name'          => 'hero_primary_cta_url',
                'type'          => 'url',
                'default_value' => '/properties',
                'wrapper'       => ['width' => '50'],
            ],
            [
                'key'           => 'field_fp_hero_secondary_cta_label',
                'label'         => 'Secondary CTA — label',
                'name'          => 'hero_secondary_cta_label',
                'type'          => 'text',
                'default_value' => 'Talk to a broker',
                'wrapper'       => ['width' => '50'],
            ],
            [
                'key'           => 'field_fp_hero_secondary_cta_url',
                'label'         => 'Secondary CTA — URL',
                'name'          => 'hero_secondary_cta_url',
                'type'          => 'url',
                'default_value' => '/contact',
                'wrapper'       => ['width' => '50'],
            ],
            [
                'key'          => 'field_fp_hero_stats',
                'label'        => 'Stats',
                'name'         => 'hero_stats',
                'type'         => 'repeater',
                'min'          => 0,
                'max'          => 6,
                'layout'       => 'table',
                'button_label' => 'Add stat',
                'sub_fields'   => [
                    [
                        'key'     => 'field_fp_hero_stat_prefix',
                        'label'   => 'Prefix',
                        'name'    => 'prefix',
                        'type'    => 'text',
                        'wrapper' => ['width' => '10'],
                    ],
                    [
                        'key'      => 'field_fp_hero_stat_value',
                        'label'    => 'Value',
                        'name'     => 'value',
                        'type'     => 'text',
                        'required' => 1,
                        'wrapper'  => ['width' => '20'],
                    ],
                    [
                        'key'           => 'field_fp_hero_stat_decimals',
                        'label'         => 'Decimals',
                        'name'          => 'decimals',
                        'type'          => 'number',
                        'default_value' => 0,
                        'min'           => 0,
                        'max'           => 4,
                        'wrapper'       => ['width' => '15'],
                    ],
                    [
                        'key'     => 'field_fp_hero_stat_suffix',
                        'label'   => 'Suffix',
                        'name'    => 'suffix',
                        'type'    => 'text',
                        'wrapper' => ['width' => '15'],
                    ],
                    [
                        'key'      => 'field_fp_hero_stat_label',
                        'label'    => 'Label',
                        'name'     => 'label',
                        'type'     => 'text',
                        'required' => 1,
                        'wrapper'  => ['width' => '40'],
                    ],
                ],
            ],
        ];
    }
}
