<?php

namespace App\Admin;

use App\PostTypes\CompanyEvent as CompanyEventPostType;
use WP_Query;

class CompanyEventReorderPage
{
    private const SLUG = 'planetario-event-reorder';

    public static function register(): void
    {
        \add_action('admin_menu', [self::class, 'addMenuPage']);
        \add_action('admin_footer', [self::class, 'injectListButton']);
    }

    public static function addMenuPage(): void
    {
        \add_submenu_page(
            'edit.php?post_type=' . CompanyEventPostType::POST_TYPE,
            'Re-Order Events',
            'Re-Order Events',
            'edit_posts',
            self::SLUG,
            [self::class, 'render']
        );
    }

    public static function injectListButton(): void
    {
        $screen = \get_current_screen();
        if (! $screen || $screen->post_type !== CompanyEventPostType::POST_TYPE || $screen->base !== 'edit') {
            return;
        }

        $url = \esc_url_raw(\admin_url('edit.php?post_type=' . CompanyEventPostType::POST_TYPE . '&page=' . self::SLUG));
        ?>
        <script>
        document.addEventListener('DOMContentLoaded', function () {
            var addNew = document.querySelector('.page-title-action');
            if (!addNew) return;
            var btn = document.createElement('a');
            btn.href = <?php echo \wp_json_encode($url); ?>;
            btn.className = 'page-title-action';
            btn.textContent = 'Re-Order Events';
            addNew.parentNode.insertBefore(btn, addNew.nextSibling);
        });
        </script>
        <?php
    }

    public static function render(): void
    {
        if (! \current_user_can('edit_posts')) {
            \wp_die(\esc_html__('Insufficient permissions.', 'sage'));
        }

        $events  = self::fetchEvents();
        $nonce   = \wp_create_nonce('wp_rest');
        $restUrl = \rest_url('planetario/v1/event-reorder');

        self::renderStyles();
        ?>
        <div class="wrap" id="event-reorder-wrap">
            <h1 class="wp-heading-inline">Re-Order Company Events</h1>
            <p class="description">Drag events to set display order. Click <strong>Save Order</strong> when done.</p>

            <div id="event-reorder-notice" style="display:none;"></div>

            <div class="event-reorder-actions event-reorder-actions--top">
                <button type="button" class="button button-primary" id="save-order-top">Save Order</button>
                <span class="spinner" id="spinner-top"></span>
            </div>

            <ul class="event-reorder-list">
                <?php
                foreach ($events as $event) :
                ?>
                <li class="event-reorder-item"
                    data-id="<?php echo \esc_attr((string) $event['id']); ?>"
                    draggable="true">
                    <span class="event-reorder-handle" title="Drag to reorder">&#8942;&#8942;</span>
                    <img
                        class="event-reorder-photo"
                        src="<?php echo \esc_url($event['cover']); ?>"
                        alt="<?php echo \esc_attr($event['title']); ?>"
                        width="48"
                        height="48"
                        loading="lazy">
                    <div class="event-reorder-info">
                        <strong><?php echo \esc_html($event['title']); ?></strong>
                        <span><?php echo \esc_html($event['dateLabel']); ?></span>
                    </div>
                </li>
                <?php
                endforeach;
                ?>
            </ul>

            <div class="event-reorder-actions event-reorder-actions--bottom">
                <button type="button" class="button button-primary" id="save-order-bottom">Save Order</button>
                <span class="spinner" id="spinner-bottom"></span>
            </div>
        </div>

        <script>
        window.eventReorderAdmin = {
            nonce: <?php echo \wp_json_encode($nonce); ?>,
            restUrl: <?php echo \wp_json_encode($restUrl); ?>
        };
        </script>
        <?php
    }

    private static function renderStyles(): void
    {
        ?>
        <style>
        #event-reorder-wrap { max-width: 800px; }
        .event-reorder-actions { display: flex; align-items: center; gap: 10px; margin: 16px 0; }
        .event-reorder-actions .spinner { float: none; margin: 0; }
        .event-reorder-list { margin: 0; padding: 0; list-style: none; border: 1px solid #ddd; border-radius: 4px; background: #fff; }
        .event-reorder-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            border-bottom: 1px solid #f0f0f0;
            transition: background .1s;
        }
        .event-reorder-item:last-child { border-bottom: none; }
        .event-reorder-item.drag-over { background: #f0f6ff; border-top: 2px solid #2271b1; }
        .event-reorder-item.dragging { opacity: .35; background: #f6f7f7; }
        .event-reorder-handle {
            cursor: grab;
            font-size: 20px;
            color: #b0b5bb;
            user-select: none;
            padding: 0 4px;
            line-height: 1;
        }
        .event-reorder-handle:active { cursor: grabbing; }
        .event-reorder-photo {
            width: 48px;
            height: 48px;
            border-radius: 4px;
            object-fit: cover;
            flex-shrink: 0;
            background: #eee;
        }
        .event-reorder-info { display: flex; flex-direction: column; gap: 2px; }
        .event-reorder-info strong { font-size: 13px; color: #1d2327; }
        .event-reorder-info span { font-size: 12px; color: #777; }
        #event-reorder-notice {
            padding: 10px 16px;
            border-radius: 3px;
            margin-bottom: 14px;
            font-size: 13px;
        }
        #event-reorder-notice.notice-success { background: #edfaef; border-left: 4px solid #00a32a; color: #1d6e2e; }
        #event-reorder-notice.notice-error   { background: #fcf0f1; border-left: 4px solid #d63638; color: #8a2424; }
        </style>
        <?php
    }

    private static function fetchEvents(): array
    {
        $query = new WP_Query([
            'post_type'      => CompanyEventPostType::POST_TYPE,
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'orderby'        => ['menu_order' => 'ASC', 'date' => 'ASC'],
        ]);

        $events = [];

        foreach ($query->posts as $post) {
            $cover    = \get_field('event_cover', $post->ID);
            $coverUrl = is_array($cover) ? ($cover['url'] ?? '') : '';
            if (! $coverUrl) {
                $coverUrl = (string) \get_the_post_thumbnail_url($post->ID, 'thumbnail');
            }
            if (! $coverUrl) {
                $coverUrl = (string) (\get_field('event_cover_url', $post->ID) ?: '');
            }

            $dateRaw   = (string) \get_field('event_date', $post->ID);
            $dateLabel = $dateRaw ? \date_i18n('F j, Y', strtotime($dateRaw)) : 'No date set';

            $events[] = [
                'id'        => $post->ID,
                'title'     => $post->post_title,
                'cover'     => $coverUrl,
                'dateLabel' => $dateLabel,
                'menu_order' => $post->menu_order,
            ];
        }

        return $events;
    }
}
