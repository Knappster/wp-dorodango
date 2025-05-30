<?php

declare(strict_types=1);

namespace App\Configs;

class Theme implements ConfigInterface
{
    /**
     * Initialise config.
     *
     * @return void
     */
    public static function init(): void
    {
        add_action('admin_menu', [self::class, 'removeAdminMenuPages'], 1000);
        add_action('wp_before_admin_bar_render', [self::class, 'removeAdminBarItems']);
        add_action('current_screen', [self::class, 'preventAdminPageAccess']);
    }

    /**
     * Remove admin menus for theme related items.
     *
     * @return void
     */
    public static function removeAdminMenuPages(): void
    {
        /**
         * Global submenu data.
         * 
         * @var array<mixed>
         */
        global $submenu;

        // Remove Appearance > Themes.
        remove_submenu_page('themes.php', 'themes.php');
        // Remove Appearance > Theme Editor.
        remove_submenu_page('themes.php', 'theme-editor.php');
        // Remove Appearance > Editor.
        remove_submenu_page('themes.php', 'site-editor.php');
        // Remove Appearance > Widgets.
        remove_submenu_page('themes.php', 'widgets.php');
        // Remove Appearance > Patterns.
        remove_submenu_page('themes.php', 'site-editor.php?p=/pattern');

        /**
         * Remove features that require the Customizer.
         * Loop through the themes.php submenu to remove features in Customizer
         * since there's no direct way to remove them via the submenu_slug.
         * Targeting 'hide-if-no-customize' removes all the features that
         * rely on the Customizer. Otherwise you must target each one
         * specifically by name (which might be a good idea?).
         */
        if (isset($submenu['themes.php'])) {
            foreach ($submenu['themes.php'] as $key => $item) {
                if (in_array('hide-if-no-customize', $item, true)) {
                    unset($submenu['themes.php'][$key]);
                }
            }
        }
    }

    /**
     * Remove theme items from the admin menu bar.
     *
     * @return void
     */
    public static function removeAdminBarItems(): void
    {
        /**
         * WP Admin Bar global
         *
         * @var \WP_Admin_Bar $wp_admin_bar
         */
        global $wp_admin_bar;

        $wp_admin_bar->remove_menu('customize');
        $wp_admin_bar->remove_node('themes');
        $wp_admin_bar->remove_node('site-editor');
        $wp_admin_bar->remove_node('widgets');
    }

    /**
     * Prevent access to unused admin pages.
     *
     * @return void
     */
    public static function preventAdminPageAccess(): void
    {
        $disabled_screens = [
            'sites-editor',
            'themes'
        ];

        $screen = get_current_screen();

        if (is_object($screen) && in_array($screen->id, $disabled_screens, true)) {
            wp_safe_redirect(admin_url());
            exit;
        }
    }
}
