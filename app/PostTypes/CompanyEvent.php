<?php

namespace App\PostTypes;

class CompanyEvent
{
    public const POST_TYPE = 'company_event';

    public static function register(): void
    {
        \register_post_type(self::POST_TYPE, [
            'labels' => [
                'name'          => 'Company Events',
                'singular_name' => 'Company Event',
                'add_new_item'  => 'Add New Company Event',
                'edit_item'     => 'Edit Company Event',
                'menu_name'     => 'Events',
            ],
            'public'        => false,
            'show_ui'       => true,
            'show_in_rest'  => true,
            'menu_icon'     => 'dashicons-calendar-alt',
            'menu_position' => 29,
            'has_archive'   => false,
            'supports'      => ['title', 'editor', 'thumbnail', 'page-attributes'],
        ]);
    }
}
