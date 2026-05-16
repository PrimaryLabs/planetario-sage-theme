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
                    'key'             => 'field_event_gallery',
                    'label'           => 'Gallery (images & videos)',
                    'name'            => 'event_gallery',
                    'type'            => 'repeater_field',
                    'rf_layout'       => 'block',
                    'rf_min'          => 0,
                    'rf_button_label' => 'Add media item',
                    'rf_empty_label'  => 'No media added yet.',
                    'instructions'    => 'Add images, video uploads or YouTube links in any order — each row works exactly like a Success Story media block. Use the caption for context.',
                    'rf_sub_fields'   => implode("\n", [
                        'media_type | Media type | select | image:Image,video:Video upload,youtube:YouTube link',
                        'image | Image | image',
                        'image_url | Fallback image URL | url',
                        'video | Video file | file |  | showif:media_type=video',
                        'youtube | YouTube URL | url |  | showif:media_type=youtube',
                        'caption | Caption | text',
                    ]),
                ],
            ],
        ]);
    }
}
