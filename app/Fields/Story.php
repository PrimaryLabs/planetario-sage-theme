<?php

namespace App\Fields;

use App\PostTypes\Property as PropertyPostType;
use App\PostTypes\Story as StoryPostType;

class Story
{
    public const GROUP_KEY = 'group_story';

    public static function register(): void
    {
        if (! function_exists('acf_add_local_field_group')) {
            return;
        }

        acf_add_local_field_group([
            'key'      => self::GROUP_KEY,
            'title'    => 'Success Story',
            'location' => [[
                [
                    'param'    => 'post_type',
                    'operator' => '==',
                    'value'    => StoryPostType::POST_TYPE,
                ],
            ]],
            'position' => 'normal',
            'active'   => true,
            'fields'   => [
                [
                    'key'      => 'field_story_quote',
                    'label'    => 'Client quote',
                    'name'     => 'story_quote',
                    'type'     => 'textarea',
                    'rows'     => 3,
                    'required' => 1,
                ],
                [
                    'key'     => 'field_story_location',
                    'label'   => 'Location',
                    'name'    => 'story_location',
                    'type'    => 'text',
                    'wrapper' => ['width' => '50'],
                ],
                [
                    'key'     => 'field_story_year',
                    'label'   => 'Year',
                    'name'    => 'story_year',
                    'type'    => 'text',
                    'wrapper' => ['width' => '50'],
                ],
                [
                    'key'       => 'field_story_summary',
                    'label'     => 'Summary',
                    'name'      => 'story_summary',
                    'type'      => 'textarea',
                    'rows'      => 4,
                    'new_lines' => '',
                ],
                [
                    'key'           => 'field_story_image',
                    'label'         => 'Image',
                    'name'          => 'story_image',
                    'type'          => 'image',
                    'return_format' => 'array',
                    'preview_size'  => 'medium',
                ],
                [
                    'key'   => 'field_story_image_url',
                    'label' => 'Fallback image URL',
                    'name'  => 'story_image_url',
                    'type'  => 'url',
                    'instructions' => 'Used only when no Image is uploaded.',
                ],
                [
                    'key'          => 'field_story_stats',
                    'label'        => 'Stats',
                    'name'         => 'story_stats',
                    'type'         => 'repeater',
                    'min'          => 0,
                    'max'          => 6,
                    'layout'       => 'table',
                    'button_label' => 'Add stat',
                    'sub_fields'   => [
                        [
                            'key'      => 'field_story_stat_value',
                            'label'    => 'Value',
                            'name'     => 'value',
                            'type'     => 'text',
                            'required' => 1,
                        ],
                        [
                            'key'      => 'field_story_stat_label',
                            'label'    => 'Label',
                            'name'     => 'label',
                            'type'     => 'text',
                            'required' => 1,
                        ],
                    ],
                ],
                [
                    'key'           => 'field_story_property',
                    'label'         => 'Related Property',
                    'name'          => 'story_property',
                    'type'          => 'post_object',
                    'post_type'     => [PropertyPostType::POST_TYPE],
                    'return_format' => 'id',
                    'allow_null'    => 1,
                    'ui'            => 1,
                ],
            ],
        ]);
    }
}
