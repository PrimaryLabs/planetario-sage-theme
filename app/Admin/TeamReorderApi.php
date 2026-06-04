<?php

namespace App\Admin;

class TeamReorderApi
{
    public static function register(): void
    {
        \register_rest_route('planetario/v1', '/team-reorder', [
            'methods'             => \WP_REST_Server::CREATABLE,
            'callback'            => [self::class, 'handle'],
            'permission_callback' => fn () => \current_user_can('edit_posts'),
            'args'                => [
                'order' => [
                    'required' => true,
                    'type'     => 'array',
                    'items'    => [
                        'type'       => 'object',
                        'properties' => [
                            'id'         => ['type' => 'integer'],
                            'menu_order' => ['type' => 'integer'],
                        ],
                    ],
                ],
            ],
        ]);
    }

    public static function handle(\WP_REST_Request $request): \WP_REST_Response
    {
        $order = $request->get_param('order');

        if (! is_array($order) || empty($order)) {
            return new \WP_REST_Response(['success' => false, 'message' => 'Empty order payload'], 400);
        }

        $updated = 0;
        foreach ($order as $item) {
            $id         = (int) ($item['id'] ?? 0);
            $menu_order = (int) ($item['menu_order'] ?? 0);

            if ($id <= 0) {
                continue;
            }

            \wp_update_post([
                'ID'         => $id,
                'menu_order' => $menu_order,
            ]);

            $updated++;
        }

        return new \WP_REST_Response(['success' => true, 'updated' => $updated], 200);
    }
}
