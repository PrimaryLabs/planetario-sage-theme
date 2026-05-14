<?php

namespace App\Fields;

class PageIntros
{
    public const GROUP_KEY = 'group_page_content';

    /** Pages that get the shared Intro + Closing CTA group. */
    public const PAGE_SLUGS = [
        'contact',
        'developers',
        'stories',
        'testimonials',
        'properties',
        'team',
    ];

    public static function register(): void
    {
        if (! function_exists('acf_add_local_field_group')) {
            return;
        }

        $locations = [];
        foreach (self::PAGE_SLUGS as $slug) {
            $page = \get_page_by_path($slug);
            if (! $page) continue;
            $locations[] = [[
                'param'    => 'page',
                'operator' => '==',
                'value'    => (string) $page->ID,
            ]];
        }

        if (empty($locations)) {
            return;
        }

        acf_add_local_field_group([
            'key'      => self::GROUP_KEY,
            'title'    => 'Page Content',
            'location' => $locations,
            'position' => 'normal',
            'active'   => true,
            'fields'   => self::fields(),
        ]);
    }

    private static function fields(): array
    {
        return [
            ['key' => 'field_pc_tab_intro', 'label' => 'Intro', 'type' => 'tab'],
            [
                'key'   => 'field_pc_intro_eyebrow',
                'label' => 'Eyebrow',
                'name'  => 'page_intro_eyebrow',
                'type'  => 'text',
            ],
            [
                'key'   => 'field_pc_intro_headline_lead',
                'label' => 'Headline — lead',
                'name'  => 'page_intro_headline_lead',
                'type'  => 'text',
            ],
            [
                'key'   => 'field_pc_intro_headline_em',
                'label' => 'Headline — emphasis',
                'name'  => 'page_intro_headline_emphasis',
                'type'  => 'text',
            ],
            [
                'key'   => 'field_pc_intro_headline_trail',
                'label' => 'Headline — trailing text',
                'name'  => 'page_intro_headline_trail',
                'type'  => 'text',
                'instructions' => 'Optional text after the emphasis.',
            ],
            [
                'key'       => 'field_pc_intro_lead',
                'label'     => 'Lead paragraph',
                'name'      => 'page_intro_lead',
                'type'      => 'textarea',
                'rows'      => 4,
                'new_lines' => '',
            ],

            ['key' => 'field_pc_tab_closing', 'label' => 'Closing CTA', 'type' => 'tab'],
            [
                'key'   => 'field_pc_closing_eyebrow',
                'label' => 'Eyebrow',
                'name'  => 'page_closing_eyebrow',
                'type'  => 'text',
            ],
            [
                'key'   => 'field_pc_closing_headline_lead',
                'label' => 'Headline — lead',
                'name'  => 'page_closing_headline_lead',
                'type'  => 'text',
            ],
            [
                'key'   => 'field_pc_closing_headline_em',
                'label' => 'Headline — emphasis',
                'name'  => 'page_closing_headline_emphasis',
                'type'  => 'text',
            ],
            [
                'key'       => 'field_pc_closing_body',
                'label'     => 'Body',
                'name'      => 'page_closing_body',
                'type'      => 'textarea',
                'rows'      => 4,
                'new_lines' => '',
            ],
            [
                'key'     => 'field_pc_closing_primary_label',
                'label'   => 'Primary CTA — label',
                'name'    => 'page_closing_primary_label',
                'type'    => 'text',
                'wrapper' => ['width' => '50'],
            ],
            [
                'key'     => 'field_pc_closing_primary_url',
                'label'   => 'Primary CTA — URL',
                'name'    => 'page_closing_primary_url',
                'type'    => 'url',
                'wrapper' => ['width' => '50'],
            ],
            [
                'key'     => 'field_pc_closing_secondary_label',
                'label'   => 'Secondary CTA — label',
                'name'    => 'page_closing_secondary_label',
                'type'    => 'text',
                'wrapper' => ['width' => '50'],
            ],
            [
                'key'     => 'field_pc_closing_secondary_url',
                'label'   => 'Secondary CTA — URL',
                'name'    => 'page_closing_secondary_url',
                'type'    => 'url',
                'wrapper' => ['width' => '50'],
            ],
        ];
    }
}
