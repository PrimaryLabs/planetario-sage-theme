<?php

namespace App\Admin;

class InlineEditorApi
{
    public static function register(): void
    {
        \register_rest_route('planetario/v1', '/acf-update', [
            'methods'             => \WP_REST_Server::CREATABLE,
            'callback'            => [self::class, 'handle'],
            'permission_callback' => fn () => \current_user_can('edit_posts'),
            'args'                => [
                'post_id'    => ['required' => true,  'type' => 'integer', 'minimum' => 1],
                'field_name' => ['required' => true,  'type' => 'string',  'sanitize_callback' => 'sanitize_key'],
                'field_type' => ['required' => false, 'type' => 'string',  'default' => 'text'],
                'value'      => ['required' => true,  'type' => 'string'],
            ],
        ]);
    }

    public static function handle(\WP_REST_Request $request): \WP_REST_Response
    {
        $post_id    = (int) $request->get_param('post_id');
        $field_name = $request->get_param('field_name');
        $field_type = $request->get_param('field_type');
        $raw        = $request->get_param('value');

        $value = match ($field_type) {
            'wysiwyg'  => \wp_kses_post($raw),
            'url'      => \esc_url_raw($raw),
            'textarea',
            'nl2br'    => \sanitize_textarea_field($raw),
            default    => \sanitize_text_field($raw),
        };

        if (! \function_exists('update_field')) {
            return new \WP_REST_Response(['success' => false, 'message' => 'ACF not active'], 500);
        }

        \update_field($field_name, $value, $post_id);

        // update_field returns false when value is unchanged — still a success.
        return new \WP_REST_Response(['success' => true, 'value' => $value], 200);
    }
}
