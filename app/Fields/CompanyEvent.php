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

        acf_add_local_field_group([
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
                    'type'         => 'repeater_field',
                    'min'          => 0,
                    'layout'       => 'block',
                    'button_label' => 'Add media item',
                    'instructions' => 'Add images and videos in any order. Use the caption for context.',
                    'sub_fields'   => [
                        [
                            'key'           => 'field_event_gallery_file',
                            'label'         => 'File',
                            'name'          => 'file',
                            'type'          => 'file',
                            'return_format' => 'array',
                            'mime_types'    => 'jpg,jpeg,png,webp,gif,svg,mp4,webm,mov,m4v',
                            'required'      => 1,
                            'wrapper'       => ['width' => '70'],
                        ],
                        [
                            'key'     => 'field_event_gallery_caption',
                            'label'   => 'Caption',
                            'name'    => 'caption',
                            'type'    => 'text',
                            'wrapper' => ['width' => '30'],
                        ],
                    ],
                ],
            ],
        ]);
    }
}
