<?php

namespace App\PostTypes;

class Vlog
{
    public const POST_TYPE   = 'vlog';
    public const SEED_OPTION = 'planetario_vlogs_seeded';

    public static function register(): void
    {
        \register_post_type(self::POST_TYPE, [
            'labels' => [
                'name'          => 'Vlogs',
                'singular_name' => 'Vlog',
                'add_new_item'  => 'Add New Vlog',
                'edit_item'     => 'Edit Vlog',
                'menu_name'     => 'Vlogs',
            ],
            'public'        => true,
            'show_ui'       => true,
            'show_in_rest'  => true,
            'has_archive'   => false,
            'rewrite'       => ['slug' => 'vlog-post'],
            'menu_icon'     => 'dashicons-video-alt3',
            'menu_position' => 29,
            'supports'      => ['title', 'thumbnail', 'page-attributes'],
        ]);
    }
}
