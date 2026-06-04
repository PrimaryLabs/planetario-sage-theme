<?php

namespace App\Fields;

use App\PostTypes\Vlog as VlogPostType;

class Vlog
{
    public const GROUP_KEY = 'group_vlog';

    public static function register(): void
    {
        if (! function_exists('acf_add_local_field_group')) {
            return;
        }

        \acf_add_local_field_group([
            'key'      => self::GROUP_KEY,
            'title'    => 'Vlog',
            'location' => [[
                [
                    'param'    => 'post_type',
                    'operator' => '==',
                    'value'    => VlogPostType::POST_TYPE,
                ],
            ]],
            'position' => 'normal',
            'active'   => true,
            'fields'   => [
                [
                    'key'          => 'field_vlog_description',
                    'label'        => 'Description',
                    'name'         => 'vlog_description',
                    'type'         => 'wysiwyg',
                    'tabs'         => 'all',
                    'toolbar'      => 'basic',
                    'media_upload' => 0,
                    'delay'        => 0,
                ],
                [
                    'key'     => 'field_vlog_location',
                    'label'   => 'Location',
                    'name'    => 'vlog_location',
                    'type'    => 'text',
                    'wrapper' => ['width' => '50'],
                ],
                [
                    'key'           => 'field_vlog_media_type',
                    'label'         => 'Media type',
                    'name'          => 'vlog_media_type',
                    'type'          => 'select',
                    'choices'       => [
                        'youtube' => 'YouTube link',
                        'video'   => 'Video upload',
                        'image'   => 'Thumbnail only',
                    ],
                    'default_value' => 'youtube',
                    'return_format' => 'value',
                    'ui'            => 1,
                    'allow_null'    => 0,
                ],
                [
                    'key'           => 'field_vlog_thumbnail',
                    'label'         => 'Thumbnail / Poster frame',
                    'name'          => 'vlog_thumbnail',
                    'type'          => 'image',
                    'return_format' => 'array',
                    'preview_size'  => 'medium',
                    'instructions'  => 'Used as the video poster frame and fallback image.',
                ],
                [
                    'key'           => 'field_vlog_youtube',
                    'label'         => 'YouTube URL',
                    'name'          => 'vlog_youtube',
                    'type'          => 'url',
                    'instructions'  => 'Paste any YouTube URL — youtu.be, watch?v=, /embed/, or /shorts/. The embed URL is derived automatically.',
                    'conditional_logic' => [[[
                        'field'    => 'field_vlog_media_type',
                        'operator' => '==',
                        'value'    => 'youtube',
                    ]]],
                ],
                [
                    'key'           => 'field_vlog_video',
                    'label'         => 'Video file',
                    'name'          => 'vlog_video',
                    'type'          => 'file',
                    'return_format' => 'array',
                    'mime_types'    => 'mp4,webm,mov,m4v',
                    'instructions'  => 'Upload an mp4/webm/mov. Thumbnail (above) is used as the poster frame if set.',
                    'conditional_logic' => [[[
                        'field'    => 'field_vlog_media_type',
                        'operator' => '==',
                        'value'    => 'video',
                    ]]],
                ],
            ],
        ]);
    }
}
