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
                    'key'           => 'field_story_media_type',
                    'label'         => 'Media type',
                    'name'          => 'story_media_type',
                    'type'          => 'select',
                    'choices'       => [
                        'image'   => 'Image',
                        'video'   => 'Video upload',
                        'youtube' => 'YouTube link',
                    ],
                    'default_value' => 'image',
                    'return_format' => 'value',
                    'ui'            => 1,
                    'allow_null'    => 0,
                ],
                [
                    'key'           => 'field_story_image',
                    'label'         => 'Image',
                    'name'          => 'story_image',
                    'type'          => 'image',
                    'return_format' => 'array',
                    'preview_size'  => 'medium',
                    'instructions'  => 'Used as the story media when type is Image, and as a poster frame for videos.',
                ],
                [
                    'key'   => 'field_story_image_url',
                    'label' => 'Fallback image URL',
                    'name'  => 'story_image_url',
                    'type'  => 'url',
                    'instructions' => 'Used only when no Image is uploaded.',
                ],
                [
                    'key'           => 'field_story_video',
                    'label'         => 'Video file',
                    'name'          => 'story_video',
                    'type'          => 'file',
                    'return_format' => 'array',
                    'mime_types'    => 'mp4,webm,mov,m4v',
                    'instructions'  => 'Upload an mp4/webm/mov. Image (above) is used as the poster frame if set.',
                    'conditional_logic' => [[[
                        'field'    => 'field_story_media_type',
                        'operator' => '==',
                        'value'    => 'video',
                    ]]],
                ],
                [
                    'key'           => 'field_story_youtube',
                    'label'         => 'YouTube URL',
                    'name'          => 'story_youtube',
                    'type'          => 'url',
                    'instructions'  => 'Paste any YouTube URL — youtu.be, watch?v=, /embed/, or /shorts/. The embed URL is derived automatically.',
                    'conditional_logic' => [[[
                        'field'    => 'field_story_media_type',
                        'operator' => '==',
                        'value'    => 'youtube',
                    ]]],
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
