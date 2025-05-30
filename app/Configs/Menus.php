<?php

declare(strict_types=1);

namespace App\Configs;

class Menus implements ConfigInterface
{
    /**
     * Initialise config.
     *
     * @return void
     */
    public static function init(): void
    {
        add_action('init', function (): void {
            self::registerMenus();
        });
    }

    /**
     * Register custom menus.
     *
     * @return void
     */
    public static function registerMenus(): void
    {
        register_nav_menu('primary-menu', 'Primary Menu');
        register_nav_menu('footer-menu', 'Footer Menu');
    }
}
