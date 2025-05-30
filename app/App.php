<?php

declare(strict_types=1);

namespace App;

use App\Configs\Config;
use App\Configs\ConfigInterface;
use App\Traits\Singleton;
use DI\Container;
use Exception;

class App
{
    use Singleton;

    /**
     * Add all custom config classes to this list.
     *
     * @var array<class-string<Config>|string>
     */
    private array $configs = [
        \App\Configs\Defaults::class,
        \App\Configs\PreventUserEnum::class,
        \App\Configs\Theme::class,
        \App\Configs\Comments::class,
        \App\Configs\RestAPI::class,
        \App\Configs\Menus::class,
        \App\Configs\PostTypes::class,
        \App\Configs\Taxonomies::class,
        \App\Configs\Dashboard::class,
        \App\Configs\Assets::class
    ];

    /**
     * Instance of container.
     *
     * @var Container
     */
    private Container $container;

    /**
     * Initialise all configs.
     *
     * @return void
     */
    public function init(Container $container): void
    {
        $this->container = $container;
    }

    /**
     * Run all Wordpress configuration scripts.
     *
     * @return void
     */
    public function runConfigs(): void
    {
        foreach ($this->configs as $config) {
            $implements = class_implements($config);
            if ($implements !== false && isset($implements[ConfigInterface::class])) {
                $config::init();
            } else {
                throw new Exception("Config missing or doesn't implement Config interface: {$config}!");
            }
        }
    }

    /**
     * Get container instance.
     *
     * @return Container
     */
    public function container(): Container
    {
        return $this->container;
    }

    /**
     * Method for testing Sentry.
     *
     * @return void
     */
    public function functionFailsForSure(): void
    {
        throw new Exception("Testing Sentry!");
    }
}
