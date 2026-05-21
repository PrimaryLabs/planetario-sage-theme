<?php

namespace App\Fields;

use App\PostTypes\Developer as DeveloperPostType;
use App\PostTypes\Property as PropertyPostType;

class Developer
{
    public const GROUP_KEY          = 'group_developer';
    public const PROPERTY_LINK_KEY  = 'group_property_developer_link';

    public static function register(): void
    {
        if (! function_exists('acf_add_local_field_group')) {
            return;
        }

        acf_add_local_field_group([
            'key'      => self::GROUP_KEY,
            'title'    => 'Developer Details',
            'location' => [[
                [
                    'param'    => 'post_type',
                    'operator' => '==',
                    'value'    => DeveloperPostType::POST_TYPE,
                ],
            ]],
            'position' => 'normal',
            'active'   => true,
            'fields'   => [
                [
                    'key'           => 'field_developer_logo',
                    'label'         => 'Logo',
                    'name'          => 'developer_logo',
                    'type'          => 'image',
                    'return_format' => 'array',
                    'preview_size'  => 'thumbnail',
                ],
                [
                    'key'          => 'field_developer_website',
                    'label'        => 'Website',
                    'name'         => 'developer_website',
                    'type'         => 'url',
                    'instructions' => 'Optional.',
                    'required'     => 0,
                ],
            ],
        ]);

        acf_add_local_field_group([
            'key'      => self::PROPERTY_LINK_KEY,
            'title'    => 'Developer',
            'location' => [[
                [
                    'param'    => 'post_type',
                    'operator' => '==',
                    'value'    => PropertyPostType::POST_TYPE,
                ],
            ]],
            'position' => 'side',
            'active'   => true,
            'fields'   => [[
                'key'           => 'field_property_developer',
                'label'         => 'Developer',
                'name'          => 'property_developer',
                'type'          => 'post_object',
                'post_type'     => [DeveloperPostType::POST_TYPE],
                'return_format' => 'id',
                'allow_null'    => 1,
                'ui'            => 1,
            ]],
        ]);
    }
}
