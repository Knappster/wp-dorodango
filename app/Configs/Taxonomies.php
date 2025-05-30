<?php

declare(strict_types=1);

namespace App\Configs;

class Taxonomies implements ConfigInterface
{
    /**
     * Initialise config.
     *
     * @return void
     */
    public static function init(): void
    {
        add_action('init', function (): void {
            self::registerTaxonomies();
        });
    }

    /**
     * Register custom taxonomies.
     *
     * @return void
     */
    public static function registerTaxonomies(): void {}
}
