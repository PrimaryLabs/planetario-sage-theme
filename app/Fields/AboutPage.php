<?php

namespace App\Fields;

class AboutPage
{
    public const GROUP_KEY = 'group_about_page';
    public const PAGE_SLUG = 'about';

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
            'title'    => 'About Page',
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
        return array_merge(
            [['key' => 'field_about_tab_intro', 'label' => 'Intro', 'type' => 'tab']],
            self::introFields(),
            [['key' => 'field_about_tab_board', 'label' => 'Board of Directors', 'type' => 'tab']],
            self::boardFields(),
            [['key' => 'field_about_tab_vm', 'label' => 'Vision & Mission', 'type' => 'tab']],
            self::vmFields(),
            [['key' => 'field_about_tab_values', 'label' => 'Core Values', 'type' => 'tab']],
            self::valuesFields(),
            [['key' => 'field_about_tab_why', 'label' => 'Why Choose Us', 'type' => 'tab']],
            self::whyFields(),
            [['key' => 'field_about_tab_office', 'label' => 'Office Photos', 'type' => 'tab']],
            self::officeFields(),
            [['key' => 'field_about_tab_closing', 'label' => 'Closing Quote', 'type' => 'tab']],
            self::closingFields(),
        );
    }

    private static function introFields(): array
    {
        return [
            [
                'key'           => 'field_about_intro_eyebrow',
                'label'         => 'Eyebrow',
                'name'          => 'about_intro_eyebrow',
                'type'          => 'text',
                'default_value' => 'About Planetario',
            ],
            [
                'key'           => 'field_about_intro_headline_lead',
                'label'         => 'Headline — lead',
                'name'          => 'about_intro_headline_lead',
                'type'          => 'text',
                'default_value' => 'A realty house built on',
            ],
            [
                'key'           => 'field_about_intro_headline_em',
                'label'         => 'Headline — emphasis',
                'name'          => 'about_intro_headline_emphasis',
                'type'          => 'text',
                'default_value' => 'relationships,',
            ],
            [
                'key'           => 'field_about_intro_headline_trail',
                'label'         => 'Headline — trailing text',
                'name'          => 'about_intro_headline_trail',
                'type'          => 'text',
                'default_value' => 'not transactions.',
            ],
            [
                'key'           => 'field_about_intro_lead',
                'label'         => 'Lead paragraph',
                'name'          => 'about_intro_lead',
                'type'          => 'textarea',
                'rows'          => 4,
                'new_lines'     => '',
                'default_value' => 'Planetario Realty & Brokerage Services Inc. is a trusted Bohol-rooted realty company dedicated to professional, reliable, and client-focused property solutions. We specialize in property sales, brokerage, and real-estate services across the Visayas committed to helping clients find the right investments while ensuring smooth and transparent transactions.',
            ],
            [
                'key'           => 'field_about_intro_image',
                'label'         => 'Landscape image',
                'name'          => 'about_intro_image',
                'type'          => 'image',
                'return_format' => 'array',
                'preview_size'  => 'medium',
                'instructions'  => 'Wide hero shown below the intro. Leave blank to use the default.',
            ],
        ];
    }

    private static function boardFields(): array
    {
        return [
            [
                'key'           => 'field_about_board_eyebrow',
                'label'         => 'Eyebrow',
                'name'          => 'about_board_eyebrow',
                'type'          => 'text',
                'default_value' => 'Leadership',
            ],
            [
                'key'           => 'field_about_board_headline_lead',
                'label'         => 'Headline — lead',
                'name'          => 'about_board_headline_lead',
                'type'          => 'text',
                'default_value' => 'Board of',
            ],
            [
                'key'           => 'field_about_board_headline_em',
                'label'         => 'Headline — emphasis',
                'name'          => 'about_board_headline_em',
                'type'          => 'text',
                'default_value' => 'Directors.',
            ],
        ];
    }

    private static function vmFields(): array
    {
        return [
            [
                'key'           => 'field_about_vm_vision',
                'label'         => 'Vision',
                'name'          => 'about_vm_vision',
                'type'          => 'wysiwyg',
                'tabs'          => 'visual',
                'toolbar'       => 'basic',
                'media_upload'  => 0,
                'default_value' => 'To be a <em>world-class</em> real-estate company delivering exceptional service to clients, salespeople, business partners, and team members transforming lives by creating opportunities for growth, empowering communities, and fostering progress, all while contributing to a sustainable future for our planet.',
            ],
            [
                'key'           => 'field_about_vm_mission',
                'label'         => 'Mission',
                'name'          => 'about_vm_mission',
                'type'          => 'wysiwyg',
                'tabs'          => 'visual',
                'toolbar'       => 'basic',
                'media_upload'  => 0,
                'default_value' => "To deliver <em>world-class</em> services in the realty industry ensuring our clients' happiness and complete satisfaction. We continuously enhance our competitive edge through innovation, motivation, and training, while fostering long-term relationships built on trust and excellence.",
            ],
        ];
    }

    private static function valuesFields(): array
    {
        return [
            [
                'key'           => 'field_about_values_eyebrow',
                'label'         => 'Eyebrow',
                'name'          => 'about_values_eyebrow',
                'type'          => 'text',
                'default_value' => 'Core values',
            ],
            [
                'key'           => 'field_about_values_headline_lead',
                'label'         => 'Headline — lead',
                'name'          => 'about_values_headline_lead',
                'type'          => 'text',
                'default_value' => 'Five quiet commitments.',
            ],
            [
                'key'           => 'field_about_values_headline_em',
                'label'         => 'Headline — emphasis',
                'name'          => 'about_values_headline_emphasis',
                'type'          => 'text',
                'default_value' => 'One steady house.',
            ],
            [
                'key'           => 'field_about_values_intro',
                'label'         => 'Intro paragraph',
                'name'          => 'about_values_intro',
                'type'          => 'textarea',
                'rows'          => 3,
                'new_lines'     => '',
                'default_value' => "These aren't poster words. They show up in the smallest decisions: which call we return first, what we tell a buyer when a listing isn't right, who we hire and who we don't.",
            ],
            [
                'key'             => 'field_about_values_items',
                'label'           => 'Values',
                'name'            => 'about_values_items',
                'type'            => 'repeater_field',
                'rf_min'          => 0,
                'rf_layout'       => 'block',
                'rf_button_label' => 'Add value',
                'rf_sub_fields'   => "number | Number | text\ntitle | Title | text\ndescription | Description | textarea",
            ],
        ];
    }

    private static function whyFields(): array
    {
        return [
            [
                'key'           => 'field_about_why_eyebrow',
                'label'         => 'Eyebrow',
                'name'          => 'about_why_eyebrow',
                'type'          => 'text',
                'default_value' => 'Why choose Planetario',
            ],
            [
                'key'           => 'field_about_why_headline_lead',
                'label'         => 'Headline — lead',
                'name'          => 'about_why_headline_lead',
                'type'          => 'text',
                'default_value' => 'Five practical reasons.',
            ],
            [
                'key'           => 'field_about_why_headline_em',
                'label'         => 'Headline — emphasis',
                'name'          => 'about_why_headline_emphasis',
                'type'          => 'text',
                'default_value' => 'Felt, not advertised.',
            ],
            [
                'key'             => 'field_about_why_items',
                'label'           => 'Reasons',
                'name'            => 'about_why_items',
                'type'            => 'repeater_field',
                'rf_min'          => 0,
                'rf_layout'       => 'block',
                'rf_button_label' => 'Add reason',
                'rf_sub_fields'   => "number | Number | text\ntitle | Title | text\ndescription | Description | textarea",
            ],
        ];
    }

    private static function officeFields(): array
    {
        return [
            [
                'key'           => 'field_about_office_eyebrow',
                'label'         => 'Eyebrow',
                'name'          => 'about_office_eyebrow',
                'type'          => 'text',
                'default_value' => 'Our workspace',
            ],
            [
                'key'           => 'field_about_office_headline',
                'label'         => 'Headline',
                'name'          => 'about_office_headline',
                'type'          => 'text',
                'default_value' => 'Where we work',
            ],
            [
                'key'             => 'field_about_office_gallery',
                'label'           => 'Office photos',
                'name'            => 'about_office_gallery',
                'type'            => 'repeater_field',
                'instructions'    => 'Add up to 12 office photos. The first photo is displayed larger.',
                'rf_sub_fields'   => 'photo | Photo | image',
                'rf_min'          => 0,
                'rf_max'          => 12,
                'rf_layout'       => 'block',
                'rf_button_label' => 'Add photo',
                'rf_empty_label'  => 'No photos yet.',
            ],
        ];
    }

    private static function closingFields(): array
    {
        return [
            [
                'key'           => 'field_about_closing_quote',
                'label'         => 'Closing quote',
                'name'          => 'about_closing_quote',
                'type'          => 'wysiwyg',
                'tabs'          => 'visual',
                'toolbar'       => 'basic',
                'media_upload'  => 0,
                'default_value' => '"We work with you every step of the way from property selection to closing ensuring a <em>hassle-free</em> and <em>secure</em> transaction."',
            ],
            [
                'key'           => 'field_about_closing_attribution',
                'label'         => 'Attribution',
                'name'          => 'about_closing_attribution',
                'type'          => 'text',
                'default_value' => 'A word from our founders',
            ],
            [
                'key'           => 'field_about_closing_primary_label',
                'label'         => 'Primary CTA — label',
                'name'          => 'about_closing_primary_label',
                'type'          => 'text',
                'default_value' => 'Meet the team',
                'wrapper'       => ['width' => '50'],
            ],
            [
                'key'           => 'field_about_closing_primary_url',
                'label'         => 'Primary CTA — URL',
                'name'          => 'about_closing_primary_url',
                'type'          => 'url',
                'default_value' => '/team',
                'wrapper'       => ['width' => '50'],
            ],
            [
                'key'           => 'field_about_closing_secondary_label',
                'label'         => 'Secondary CTA — label',
                'name'          => 'about_closing_secondary_label',
                'type'          => 'text',
                'default_value' => 'Get in touch',
                'wrapper'       => ['width' => '50'],
            ],
            [
                'key'           => 'field_about_closing_secondary_url',
                'label'         => 'Secondary CTA — URL',
                'name'          => 'about_closing_secondary_url',
                'type'          => 'url',
                'default_value' => '/contact',
                'wrapper'       => ['width' => '50'],
            ],
        ];
    }
}
