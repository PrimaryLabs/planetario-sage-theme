<?php

namespace App\Fields;

use App\PostTypes\TeamMember as TeamMemberPostType;

class TeamMember
{
    public const GROUP_KEY = 'group_team_member';

    public static function register(): void
    {
        if (! function_exists('acf_add_local_field_group')) {
            return;
        }

        acf_add_local_field_group([
            'key'      => self::GROUP_KEY,
            'title'    => 'Team Member',
            'location' => [[
                [
                    'param'    => 'post_type',
                    'operator' => '==',
                    'value'    => TeamMemberPostType::POST_TYPE,
                ],
            ]],
            'position' => 'normal',
            'active'   => true,
            'fields'   => [
                [
                    'key'      => 'field_team_title',
                    'label'    => 'Title / Role',
                    'name'     => 'team_title',
                    'type'     => 'text',
                    'required' => 1,
                ],
                [
                    'key'           => 'field_team_photo',
                    'label'         => 'Photo',
                    'name'          => 'team_photo',
                    'type'          => 'image',
                    'return_format' => 'array',
                    'preview_size'  => 'thumbnail',
                    'instructions'  => 'Leave blank for a generated avatar.',
                ],
                [
                    'key'       => 'field_team_bio',
                    'label'     => 'Bio',
                    'name'      => 'team_bio',
                    'type'      => 'textarea',
                    'rows'      => 4,
                    'new_lines' => '',
                ],
                [
                    'key'           => 'field_team_region',
                    'label'         => 'Region',
                    'name'          => 'team_region',
                    'type'          => 'select',
                    'choices'       => [
                        'all'   => 'All',
                        'bohol' => 'Bohol',
                        'cebu'  => 'Cebu',
                    ],
                    'default_value' => 'all',
                    'return_format' => 'value',
                ],
                [
                    'key'       => 'field_team_email',
                    'label'     => 'Email',
                    'name'      => 'team_email',
                    'type'      => 'email',
                    'wrapper'   => ['width' => '50'],
                ],
                [
                    'key'       => 'field_team_linkedin',
                    'label'     => 'LinkedIn URL',
                    'name'      => 'team_linkedin',
                    'type'      => 'url',
                    'wrapper'   => ['width' => '50'],
                ],
            ],
        ]);
    }
}
