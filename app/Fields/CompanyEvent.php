<?php

namespace App\Fields;

use App\PostTypes\CompanyEvent as CompanyEventPostType;

class CompanyEvent
{
    public const GROUP_KEY = 'group_company_event';

    public static function register(): void
    {
        if (! function_exists('acf_add_local_field_group')) {
            return;
        }

        \acf_add_local_field_group([
            'key'      => self::GROUP_KEY,
            'title'    => 'Company Event',
            'location' => [[
                [
                    'param'    => 'post_type',
                    'operator' => '==',
                    'value'    => CompanyEventPostType::POST_TYPE,
                ],
            ]],
            'position' => 'normal',
            'active'   => true,
            'fields'   => [
                [
                    'key'           => 'field_event_date',
                    'label'         => 'Event date',
                    'name'          => 'event_date',
                    'type'          => 'date_picker',
                    'display_format' => 'F j, Y',
                    'return_format' => 'Y-m-d',
                    'first_day'     => 1,
                    'wrapper'       => ['width' => '50'],
                ],
                [
                    'key'     => 'field_event_location',
                    'label'   => 'Location',
                    'name'    => 'event_location',
                    'type'    => 'text',
                    'wrapper' => ['width' => '50'],
                ],
                [
                    'key'       => 'field_event_summary',
                    'label'     => 'Summary',
                    'name'      => 'event_summary',
                    'type'      => 'textarea',
                    'rows'      => 3,
                    'new_lines' => '',
                ],
                [
                    'key'           => 'field_event_cover',
                    'label'         => 'Cover image',
                    'name'          => 'event_cover',
                    'type'          => 'image',
                    'return_format' => 'array',
                    'preview_size'  => 'medium',
                    'instructions'  => 'Used as the card image. Falls back to the featured image.',
                ],
                [
                    'key'          => 'field_event_gallery',
                    'label'        => 'Gallery (images & videos)',
                    'name'         => 'event_gallery',
                    'type'         => 'repeater',
                    'layout'       => 'block',
                    'min'          => 0,
                    'button_label' => 'Add media item',
                    'instructions' => 'Add images, video uploads or YouTube links in any order. Use the caption for context.',
                    'sub_fields'   => [
                        [
                            'key'           => 'field_event_gal_media_type',
                            'label'         => 'Media type',
                            'name'          => 'media_type',
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
                            'key'           => 'field_event_gal_image',
                            'label'         => 'Image',
                            'name'          => 'image',
                            'type'          => 'image',
                            'return_format' => 'array',
                            'preview_size'  => 'medium',
                            'instructions'  => 'Used as the gallery image, or as a poster frame for videos.',
                        ],
                        [
                            'key'          => 'field_event_gal_image_url',
                            'label'        => 'Fallback image URL',
                            'name'         => 'image_url',
                            'type'         => 'url',
                            'instructions' => 'Used only when no Image is uploaded above.',
                        ],
                        [
                            'key'           => 'field_event_gal_video',
                            'label'         => 'Video file',
                            'name'          => 'video',
                            'type'          => 'file',
                            'return_format' => 'array',
                            'mime_types'    => 'mp4,webm,mov,m4v',
                            'instructions'  => 'Upload an mp4/webm/mov. Image above is used as the poster frame if set.',
                            'conditional_logic' => [[[
                                'field'    => 'field_event_gal_media_type',
                                'operator' => '==',
                                'value'    => 'video',
                            ]]],
                        ],
                        [
                            'key'          => 'field_event_gal_youtube',
                            'label'        => 'YouTube URL',
                            'name'         => 'youtube',
                            'type'         => 'url',
                            'instructions' => 'Paste any YouTube URL — youtu.be, watch?v=, /embed/, or /shorts/.',
                            'conditional_logic' => [[[
                                'field'    => 'field_event_gal_media_type',
                                'operator' => '==',
                                'value'    => 'youtube',
                            ]]],
                        ],
                        [
                            'key'   => 'field_event_gal_caption',
                            'label' => 'Caption',
                            'name'  => 'caption',
                            'type'  => 'text',
                        ],
                    ],
                ],
            ],
        ]);
    }
}
