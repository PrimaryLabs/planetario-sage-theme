<?php

namespace App\Fields;

use App\PostTypes\Property as PropertyPostType;

class Property
{
    public const GROUP_KEY = 'group_property';

    public static function register(): void
    {
        if (! function_exists('acf_add_local_field_group')) {
            return;
        }

        acf_add_local_field_group([
            'key'      => self::GROUP_KEY,
            'title'    => 'Property Details',
            'location' => [[
                [
                    'param'    => 'post_type',
                    'operator' => '==',
                    'value'    => PropertyPostType::POST_TYPE,
                ],
            ]],
            'position' => 'normal',
            'style'    => 'default',
            'active'   => true,
            'fields'   => self::fields(),
        ]);
    }

    private static function fields(): array
    {
        return [
            ['key' => 'field_prop_tab_pricing', 'label' => 'Pricing & Specs', 'type' => 'tab'],
            [
                'key'           => 'field_prop_price',
                'label'         => 'Price (PHP)',
                'name'          => 'property_price',
                'type'          => 'number',
                'instructions'  => 'Numeric value used for filtering and sorting.',
                'wrapper'       => ['width' => '50'],
            ],
            [
                'key'           => 'field_prop_price_label',
                'label'         => 'Price label',
                'name'          => 'property_price_label',
                'type'          => 'text',
                'instructions'  => 'Display string e.g. "₱38.5M".',
                'wrapper'       => ['width' => '50'],
            ],
            [
                'key'           => 'field_prop_location',
                'label'         => 'Location label',
                'name'          => 'property_location',
                'type'          => 'text',
                'instructions'  => 'e.g. "Panglao, Bohol".',
            ],
            [
                'key'     => 'field_prop_beds',
                'label'   => 'Bedrooms',
                'name'    => 'property_beds',
                'type'    => 'number',
                'min'     => 0,
                'wrapper' => ['width' => '25'],
            ],
            [
                'key'     => 'field_prop_baths',
                'label'   => 'Bathrooms',
                'name'    => 'property_baths',
                'type'    => 'number',
                'min'     => 0,
                'wrapper' => ['width' => '25'],
            ],
            [
                'key'     => 'field_prop_area',
                'label'   => 'Floor area (sqm)',
                'name'    => 'property_area',
                'type'    => 'number',
                'min'     => 0,
                'wrapper' => ['width' => '25'],
            ],
            [
                'key'     => 'field_prop_lot',
                'label'   => 'Lot area (sqm)',
                'name'    => 'property_lot',
                'type'    => 'number',
                'min'     => 0,
                'wrapper' => ['width' => '25'],
            ],
            [
                'key'           => 'field_prop_is_featured',
                'label'         => 'Featured on homepage',
                'name'          => 'property_is_featured',
                'type'          => 'true_false',
                'ui'            => 1,
                'instructions'  => 'Featured properties surface in the homepage grid.',
            ],

            ['key' => 'field_prop_tab_media', 'label' => 'Media & Summary', 'type' => 'tab'],
            [
                'key'           => 'field_prop_image',
                'label'         => 'Hero image',
                'name'          => 'property_image',
                'type'          => 'image',
                'return_format' => 'array',
                'preview_size'  => 'medium',
                'instructions'  => 'Featured image used on cards and the detail page. Leave blank to fall back to the seeded URL.',
            ],
            [
                'key'           => 'field_prop_image_url',
                'label'         => 'Fallback image URL',
                'name'          => 'property_image_url',
                'type'          => 'url',
                'instructions'  => 'Optional external URL used when no Hero image is uploaded.',
            ],
            [
                'key'       => 'field_prop_summary',
                'label'     => 'Summary',
                'name'      => 'property_summary',
                'type'      => 'textarea',
                'rows'      => 4,
                'new_lines' => '',
            ],
            [
                'key'           => 'field_prop_gallery',
                'label'         => 'Gallery',
                'name'          => 'property_gallery',
                'type'          => 'gallery',
                'return_format' => 'array',
                'preview_size'  => 'thumbnail',
                'instructions'  => 'Additional images for the property detail page.',
            ],

            ['key' => 'field_prop_tab_features', 'label' => 'Features', 'type' => 'tab'],
            [
                'key'          => 'field_prop_features',
                'label'        => 'Features',
                'name'         => 'property_features',
                'type'            => 'repeater_field',
                'rf_min'          => 0,
                'rf_layout'       => 'table',
                'rf_button_label' => 'Add feature',
                'rf_sub_fields'   => "feature | Feature | text",
            ],
        ];
    }
}
