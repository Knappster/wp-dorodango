<?php

declare(strict_types=1);

namespace App\Configs;

class PostTypes implements ConfigInterface
{
    /**
     * Initialise config.
     *
     * @return void
     */
    public static function init(): void
    {
        add_action('init', function (): void {
            self::registerPostTypes();
        });
    }

    /**
     * Register custom post types.
     *
     * @return void
     */
    public static function registerPostTypes(): void {}
}
