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
            [['key' => 'field_fp_tab_hero', 'label' => 'Hero', 'type' => 'tab']],
            self::heroFields(),
            [['key' => 'field_fp_tab_commitment', 'label' => 'Commitment', 'type' => 'tab']],
            self::commitmentFields(),
            [['key' => 'field_fp_tab_vm', 'label' => 'Vision & Mission', 'type' => 'tab']],
            self::visionMissionFields(),
            [['key' => 'field_fp_tab_locations', 'label' => 'Locations', 'type' => 'tab']],
            self::locationsFields(),
            [['key' => 'field_fp_tab_cta', 'label' => 'CTA Banner', 'type' => 'tab']],
            self::ctaBannerFields(),
        );
    }

    private static function commitmentFields(): array
    {
        return [
            [
                'key'           => 'field_fp_com_eyebrow',
                'label'         => 'Eyebrow',
                'name'          => 'commitment_eyebrow',
                'type'          => 'text',
                'default_value' => 'Our commitment',
            ],
            [
                'key'           => 'field_fp_com_headline_lead',
                'label'         => 'Headline — lead',
                'name'          => 'commitment_headline_lead',
                'type'          => 'text',
                'default_value' => 'We walk with you from',
            ],
            [
                'key'           => 'field_fp_com_headline_em',
                'label'         => 'Headline — emphasis',
                'name'          => 'commitment_headline_emphasis',
                'type'          => 'text',
                'default_value' => 'first viewing',
            ],
            [
                'key'           => 'field_fp_com_headline_trail',
                'label'         => 'Headline — trailing text',
                'name'          => 'commitment_headline_trail',
                'type'          => 'text',
                'instructions'  => 'Text after the emphasis. Leave blank to omit.',
                'default_value' => 'to final signature.',
            ],
            [
                'key'           => 'field_fp_com_body_1',
                'label'         => 'Paragraph 1',
                'name'          => 'commitment_paragraph_1',
                'type'          => 'textarea',
                'rows'          => 4,
                'new_lines'     => '',
                'default_value' => "You don't have to worry about documentation, coordination, or negotiation. We handle it all and we'll tell you to wait if the unit isn't right. That kind of honesty is rarer than a clean title.",
            ],
            [
                'key'           => 'field_fp_com_body_2',
                'label'         => 'Paragraph 2',
                'name'          => 'commitment_paragraph_2',
                'type'          => 'textarea',
                'rows'          => 3,
                'new_lines'     => '',
                'default_value' => "Most of our clients are referrals from clients. That's a number we work quietly to keep true.",
            ],
            [
                'key'           => 'field_fp_com_cta_label',
                'label'         => 'CTA — label',
                'name'          => 'commitment_cta_label',
                'type'          => 'text',
                'default_value' => 'Read our story',
                'wrapper'       => ['width' => '50'],
            ],
            [
                'key'           => 'field_fp_com_cta_url',
                'label'         => 'CTA — URL',
                'name'          => 'commitment_cta_url',
                'type'          => 'url',
                'default_value' => '/about',
                'wrapper'       => ['width' => '50'],
            ],
        ];
    }

    private static function visionMissionFields(): array
    {
        return [
            [
                'key'           => 'field_fp_vm_eyebrow',
                'label'         => 'Eyebrow',
                'name'          => 'vm_eyebrow',
                'type'          => 'text',
                'default_value' => 'Who we are',
            ],
            [
                'key'           => 'field_fp_vm_headline_lead',
                'label'         => 'Headline — lead',
                'name'          => 'vm_headline_lead',
                'type'          => 'text',
                'default_value' => 'A',
            ],
            [
                'key'           => 'field_fp_vm_headline_em',
                'label'         => 'Headline — emphasis',
                'name'          => 'vm_headline_emphasis',
                'type'          => 'text',
                'default_value' => 'steady house',
            ],
            [
                'key'           => 'field_fp_vm_headline_trail',
                'label'         => 'Headline — trailing text',
                'name'          => 'vm_headline_trail',
                'type'          => 'text',
                'default_value' => 'with a planet-sized horizon.',
            ],
            [
                'key'           => 'field_fp_vm_intro',
                'label'         => 'Intro lead',
                'name'          => 'vm_intro',
                'type'          => 'textarea',
                'rows'          => 3,
                'new_lines'     => '',
                'default_value' => 'Two quiet commitments anchor everything we do the company we want to be, and the work we promise our clients every day.',
            ],
            [
                'key'           => 'field_fp_vm_vision',
                'label'         => 'Vision',
                'name'          => 'vm_vision',
                'type'          => 'wysiwyg',
                'tabs'          => 'visual',
                'toolbar'       => 'basic',
                'media_upload'  => 0,
                'instructions'  => 'Use italic to highlight accent words (e.g. "world-class").',
                'default_value' => 'To be a <em>world-class</em> real-estate company delivering exceptional service to clients, salespeople, business partners, and team members transforming lives by creating opportunities for growth, empowering communities, and fostering progress, all while contributing to a sustainable future for our planet.',
            ],
            [
                'key'           => 'field_fp_vm_mission',
                'label'         => 'Mission',
                'name'          => 'vm_mission',
                'type'          => 'wysiwyg',
                'tabs'          => 'visual',
                'toolbar'       => 'basic',
                'media_upload'  => 0,
                'default_value' => "To deliver <em>world-class</em> services in the realty industry ensuring our clients' happiness and complete satisfaction. We continuously enhance our competitive edge through innovation, motivation, and training, while fostering long-term relationships built on trust and excellence.",
            ],
            [
                'key'           => 'field_fp_vm_cta_label',
                'label'         => 'CTA — label',
                'name'          => 'vm_cta_label',
                'type'          => 'text',
                'default_value' => 'Read the full story',
                'wrapper'       => ['width' => '50'],
            ],
            [
                'key'           => 'field_fp_vm_cta_url',
                'label'         => 'CTA — URL',
                'name'          => 'vm_cta_url',
                'type'          => 'url',
                'default_value' => '/about',
                'wrapper'       => ['width' => '50'],
            ],
        ];
    }

    private static function locationsFields(): array
    {
        return [
            [
                'key'           => 'field_fp_loc_eyebrow',
                'label'         => 'Eyebrow',
                'name'          => 'locations_eyebrow',
                'type'          => 'text',
                'default_value' => 'Where we work',
            ],
            [
                'key'           => 'field_fp_loc_headline_lead',
                'label'         => 'Headline — lead',
                'name'          => 'locations_headline_lead',
                'type'          => 'text',
                'default_value' => 'Two islands.',
            ],
            [
                'key'           => 'field_fp_loc_headline_em',
                'label'         => 'Headline — emphasis',
                'name'          => 'locations_headline_emphasis',
                'type'          => 'text',
                'default_value' => 'One quiet sea between them.',
            ],
            [
                'key'           => 'field_fp_loc_intro',
                'label'         => 'Intro lead',
                'name'          => 'locations_intro',
                'type'          => 'textarea',
                'rows'          => 3,
                'new_lines'     => '',
                'default_value' => 'Planetario operates from Tagbilaran, with active brokerage across Bohol\'s coastal towns and a senior team in Cebu City.',
            ],
            [
                'key'          => 'field_fp_loc_items',
                'label'        => 'Location cards',
                'name'         => 'locations_items',
                'type'         => 'repeater',
                'min'          => 0,
                'max'          => 4,
                'layout'       => 'block',
                'button_label' => 'Add location card',
                'sub_fields'   => [
                    [
                        'key'     => 'field_fp_loc_item_eyebrow',
                        'label'   => 'Eyebrow',
                        'name'    => 'eyebrow',
                        'type'    => 'text',
                        'wrapper' => ['width' => '40'],
                    ],
                    [
                        'key'      => 'field_fp_loc_item_title',
                        'label'    => 'Title',
                        'name'     => 'title',
                        'type'     => 'text',
                        'required' => 1,
                        'wrapper'  => ['width' => '60'],
                    ],
                    [
                        'key'       => 'field_fp_loc_item_desc',
                        'label'     => 'Description',
                        'name'      => 'description',
                        'type'      => 'textarea',
                        'rows'      => 2,
                        'new_lines' => '',
                    ],
                    [
                        'key'           => 'field_fp_loc_item_image',
                        'label'         => 'Image',
                        'name'          => 'image',
                        'type'          => 'image',
                        'return_format' => 'array',
                        'preview_size'  => 'medium',
                    ],
                ],
            ],
        ];
    }

    private static function ctaBannerFields(): array
    {
        return [
            [
                'key'           => 'field_fp_cta_lead',
                'label'         => 'Quote — lead',
                'name'          => 'cta_quote_lead',
                'type'          => 'text',
                'default_value' => 'Tell us what your next',
            ],
            [
                'key'           => 'field_fp_cta_em',
                'label'         => 'Quote — emphasis',
                'name'          => 'cta_quote_emphasis',
                'type'          => 'text',
                'default_value' => 'five years',
            ],
            [
                'key'           => 'field_fp_cta_trail',
                'label'         => 'Quote — trailing line',
                'name'          => 'cta_quote_trail',
                'type'          => 'text',
                'instructions'  => 'Rendered on a new line below the lead.',
                'default_value' => "should look like. We'll find the address.",
            ],
            [
                'key'           => 'field_fp_cta_primary_label',
                'label'         => 'Primary CTA — label',
                'name'          => 'cta_primary_label',
                'type'          => 'text',
                'default_value' => 'Book a tripping',
                'wrapper'       => ['width' => '50'],
            ],
            [
                'key'           => 'field_fp_cta_primary_url',
                'label'         => 'Primary CTA — URL',
                'name'          => 'cta_primary_url',
                'type'          => 'url',
                'default_value' => '/contact',
                'wrapper'       => ['width' => '50'],
            ],
            [
                'key'           => 'field_fp_cta_secondary_label',
                'label'         => 'Secondary CTA — label',
                'name'          => 'cta_secondary_label',
                'type'          => 'text',
                'default_value' => 'Browse listings',
                'wrapper'       => ['width' => '50'],
            ],
            [
                'key'           => 'field_fp_cta_secondary_url',
                'label'         => 'Secondary CTA — URL',
                'name'          => 'cta_secondary_url',
                'type'          => 'url',
                'default_value' => '/properties',
                'wrapper'       => ['width' => '50'],
            ],
        ];
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
