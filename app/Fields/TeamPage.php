<?php

namespace App\Fields;

class TeamPage
{
    public const GROUP_KEY = 'group_team_page';
    public const PAGE_SLUG = 'team';

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
            'title'    => 'Team Page',
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
            ['key' => 'field_tp_tab_board', 'label' => 'Board of Directors', 'type' => 'tab'],
            [
                'key'           => 'field_tp_board_eyebrow',
                'label'         => 'Eyebrow',
                'name'          => 'team_board_eyebrow',
                'type'          => 'text',
                'default_value' => 'Leadership',
            ],
            [
                'key'           => 'field_tp_board_headline_lead',
                'label'         => 'Headline — lead',
                'name'          => 'team_board_headline_lead',
                'type'          => 'text',
                'default_value' => 'Board of',
            ],
            [
                'key'           => 'field_tp_board_headline_em',
                'label'         => 'Headline — emphasis',
                'name'          => 'team_board_headline_em',
                'type'          => 'text',
                'default_value' => 'Directors.',
            ],

            ['key' => 'field_tp_tab_brokers', 'label' => 'Brokers', 'type' => 'tab'],
            [
                'key'           => 'field_tp_brokers_eyebrow',
                'label'         => 'Eyebrow',
                'name'          => 'team_brokers_eyebrow',
                'type'          => 'text',
                'default_value' => 'Salesfloor',
            ],
            [
                'key'           => 'field_tp_brokers_headline_em',
                'label'         => 'Headline — emphasis',
                'name'          => 'team_brokers_headline_em',
                'type'          => 'text',
                'default_value' => 'Brokers.',
            ],

            ['key' => 'field_tp_tab_bohol_mgr', 'label' => 'Bohol Managers', 'type' => 'tab'],
            [
                'key'           => 'field_tp_bohol_mgr_eyebrow',
                'label'         => 'Eyebrow',
                'name'          => 'team_bohol_mgr_eyebrow',
                'type'          => 'text',
                'default_value' => 'Bohol',
            ],
            [
                'key'           => 'field_tp_bohol_mgr_lead',
                'label'         => 'Headline — lead',
                'name'          => 'team_bohol_mgr_lead',
                'type'          => 'text',
                'default_value' => 'Bohol',
            ],
            [
                'key'           => 'field_tp_bohol_mgr_headline_em',
                'label'         => 'Headline — emphasis',
                'name'          => 'team_bohol_mgr_headline_em',
                'type'          => 'text',
                'default_value' => 'Managers.',
            ],

            ['key' => 'field_tp_tab_cebu_mgr', 'label' => 'Cebu Managers', 'type' => 'tab'],
            [
                'key'           => 'field_tp_cebu_mgr_eyebrow',
                'label'         => 'Eyebrow',
                'name'          => 'team_cebu_mgr_eyebrow',
                'type'          => 'text',
                'default_value' => 'Cebu',
            ],
            [
                'key'           => 'field_tp_cebu_mgr_lead',
                'label'         => 'Headline — lead',
                'name'          => 'team_cebu_mgr_lead',
                'type'          => 'text',
                'default_value' => 'Cebu',
            ],
            [
                'key'           => 'field_tp_cebu_mgr_headline_em',
                'label'         => 'Headline — emphasis',
                'name'          => 'team_cebu_mgr_headline_em',
                'type'          => 'text',
                'default_value' => 'Managers.',
            ],

            ['key' => 'field_tp_tab_bohol_staff', 'label' => 'Bohol Staff', 'type' => 'tab'],
            [
                'key'           => 'field_tp_bohol_staff_eyebrow',
                'label'         => 'Eyebrow',
                'name'          => 'team_bohol_staff_eyebrow',
                'type'          => 'text',
                'default_value' => 'Bohol',
            ],
            [
                'key'           => 'field_tp_bohol_staff_lead',
                'label'         => 'Headline — lead',
                'name'          => 'team_bohol_staff_lead',
                'type'          => 'text',
                'default_value' => 'Bohol',
            ],
            [
                'key'           => 'field_tp_bohol_staff_headline_em',
                'label'         => 'Headline — emphasis',
                'name'          => 'team_bohol_staff_headline_em',
                'type'          => 'text',
                'default_value' => 'Staff.',
            ],

            ['key' => 'field_tp_tab_cebu_staff', 'label' => 'Cebu Staff', 'type' => 'tab'],
            [
                'key'           => 'field_tp_cebu_staff_eyebrow',
                'label'         => 'Eyebrow',
                'name'          => 'team_cebu_staff_eyebrow',
                'type'          => 'text',
                'default_value' => 'Cebu',
            ],
            [
                'key'           => 'field_tp_cebu_staff_lead',
                'label'         => 'Headline — lead',
                'name'          => 'team_cebu_staff_lead',
                'type'          => 'text',
                'default_value' => 'Cebu',
            ],
            [
                'key'           => 'field_tp_cebu_staff_headline_em',
                'label'         => 'Headline — emphasis',
                'name'          => 'team_cebu_staff_headline_em',
                'type'          => 'text',
                'default_value' => 'Staff.',
            ],
        ];
    }
}
