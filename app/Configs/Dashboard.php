<?php

declare(strict_types=1);

namespace App\Configs;

class Dashboard implements ConfigInterface
{
    /**
     * Initialise config.
     *
     * @return void
     */
    public static function init(): void
    {
        add_action('admin_init', [self::class, 'removeNews']);
    }

    /**
     * Remove news panel.
     *
     * @return void
     */
    public static function removeNews(): void
    {
        remove_meta_box('dashboard_activity', 'dashboard', 'normal');
        remove_meta_box('dashboard_site_health', 'dashboard', 'normal');
        remove_meta_box('dashboard_right_now', 'dashboard', 'normal');
        remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
        remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal');
        remove_meta_box('dashboard_plugins', 'dashboard', 'normal');
        remove_meta_box('dashboard_quick_press', 'dashboard', 'side');
        remove_meta_box('dashboard_recent_drafts', 'dashboard', 'side');
        // WordPress blog
        remove_meta_box('dashboard_primary', 'dashboard', 'side');
        // Other WordPress News
        remove_meta_box('dashboard_secondary', 'dashboard', 'side');
    }
}
