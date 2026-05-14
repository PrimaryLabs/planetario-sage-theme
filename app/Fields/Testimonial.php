<?php

namespace App\Fields;

use App\PostTypes\Testimonial as TestimonialPostType;

class Testimonial
{
    public const GROUP_KEY = 'group_testimonial';

    public static function register(): void
    {
        if (! function_exists('acf_add_local_field_group')) {
            return;
        }

        acf_add_local_field_group([
            'key'      => self::GROUP_KEY,
            'title'    => 'Testimonial',
            'location' => [[
                [
                    'param'    => 'post_type',
                    'operator' => '==',
                    'value'    => TestimonialPostType::POST_TYPE,
                ],
            ]],
            'position' => 'normal',
            'active'   => true,
            'fields'   => [
                [
                    'key'      => 'field_testimonial_quote',
                    'label'    => 'Quote',
                    'name'     => 'testimonial_quote',
                    'type'     => 'textarea',
                    'rows'     => 5,
                    'required' => 1,
                ],
                [
                    'key'      => 'field_testimonial_name',
                    'label'    => 'Name',
                    'name'     => 'testimonial_name',
                    'type'     => 'text',
                    'required' => 1,
                    'wrapper'  => ['width' => '50'],
                ],
                [
                    'key'     => 'field_testimonial_role',
                    'label'   => 'Role / context',
                    'name'    => 'testimonial_role',
                    'type'    => 'text',
                    'wrapper' => ['width' => '50'],
                    'instructions' => 'e.g. "Engineer · Dubai → Tagbilaran".',
                ],
                [
                    'key'           => 'field_testimonial_avatar',
                    'label'         => 'Avatar',
                    'name'          => 'testimonial_avatar',
                    'type'          => 'image',
                    'return_format' => 'array',
                    'preview_size'  => 'thumbnail',
                    'instructions'  => 'Leave blank to use a generated avatar based on name.',
                ],
                [
                    'key'   => 'field_testimonial_highlight',
                    'label' => 'Highlight on homepage',
                    'name'  => 'testimonial_highlight',
                    'type'  => 'true_false',
                    'ui'    => 1,
                ],
            ],
        ]);
    }
}
