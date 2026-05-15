<?php

namespace App\Admin;

/**
 * Admin-only UI tweaks for ACF metaboxes:
 *  - Force-open all ACF postboxes on page load (respects manual collapse for the current session only).
 *  - Apply a more visible card style with an accent border and stronger header.
 */
class AcfMetaboxUi
{
    public static function register(): void
    {
        \add_action('admin_print_styles', [self::class, 'printStyles']);
        \add_action('admin_print_footer_scripts', [self::class, 'printScripts']);
    }

    public static function printStyles(): void
    {
        if (! self::isEditScreen()) return;
        ?>
<style id="planetario-acf-ui">
    /* Container */
    .acf-postbox {
        border: 1px solid #d0d4dc !important;
        border-left: 3px solid #88e0b8 !important;
        border-radius: 6px !important;
        box-shadow: 0 1px 2px rgba(8, 17, 38, .06) !important;
        margin-bottom: 18px !important;
        background: #fff !important;
    }

    .acf-postbox > .postbox-header,
    .acf-postbox > .hndle {
        background: linear-gradient(180deg, #f6f8fb 0%, #eef1f7 100%) !important;
        border-bottom: 1px solid #e3e6ec !important;
        padding: 6px 4px !important;
    }

    .acf-postbox > .postbox-header .hndle,
    .acf-postbox .hndle {
        font-size: 14px !important;
        font-weight: 600 !important;
        color: #1f2330 !important;
        padding: 10px 14px !important;
        letter-spacing: .01em !important;
    }

    /* Inner spacing */
    .acf-postbox .inside {
        padding: 14px 18px 18px !important;
    }

    /* Tabs */
    .acf-tab-wrap {
        background: #f6f8fb !important;
        border-bottom: 1px solid #e3e6ec !important;
    }
    .acf-tab-group li a {
        font-weight: 500 !important;
        color: #1f2330 !important;
    }
    .acf-tab-group li.active a {
        border-bottom-color: #88e0b8 !important;
        background: #fff !important;
    }

    /* Field labels */
    .acf-field > .acf-label > label {
        font-weight: 600 !important;
        color: #1f2330 !important;
        font-size: 13.5px !important;
    }
    .acf-field > .acf-label .description {
        color: #4a5468 !important;
        font-size: 12.5px !important;
        margin-top: 2px !important;
    }

    /* Repeater rows */
    .acf-repeater .acf-row {
        background: #fafbfd !important;
        transition: background .12s ease;
    }
    .acf-repeater .acf-row:hover {
        background: #f1f4f9 !important;
    }
    .acf-repeater .acf-row.-collapsed {
        background: #fff !important;
    }

    /* Hide the "closed" caret affordance — we keep them open */
    .acf-postbox.closed .inside {
        display: block !important;
    }
</style>
        <?php
    }

    public static function printScripts(): void
    {
        if (! self::isEditScreen()) return;
        ?>
<script>
(function () {
    function openAll() {
        document.querySelectorAll('.postbox.acf-postbox.closed').forEach(function (box) {
            box.classList.remove('closed');
        });
    }
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', openAll);
    } else {
        openAll();
    }
})();
</script>
        <?php
    }

    private static function isEditScreen(): bool
    {
        if (! function_exists('get_current_screen')) return false;
        $screen = \get_current_screen();
        if (! $screen) return false;

        // Apply on post-edit screens (block editor + classic) and ACF options pages.
        if (in_array($screen->base, ['post', 'post-new'], true)) return true;
        if (str_contains((string) $screen->id, 'acf-options')) return true;
        if (str_contains((string) $screen->id, 'site-settings')) return true;
        return false;
    }
}
