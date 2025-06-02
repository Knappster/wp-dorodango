<?php

declare(strict_types=1);

namespace App\Configs;

class Assets implements ConfigInterface
{
    /**
     * Assets map.
     *
     * @var array<mixed>
     */
    private static array $assets_map;

    /**
     * Initialise config.
     *
     * @return void
     */
    public static function init(): void
    {
        self::$assets_map = self::getAssetsMap();

        // Frontend assets.
        add_action('wp_enqueue_scripts', [self::class, 'enqueueStyles']);
        add_action('wp_enqueue_scripts', [self::class, 'enqueueScripts']);

        // Login assets.
        add_action('login_enqueue_scripts', function (): void {
            self::enqueueLoginStyles();
        });
        add_filter('login_headerurl', function (): string {
            return self::customLoginLogoUrl();
        });
    }

    /**
     * Get assets map.
     *
     * @return array<mixed>
     */
    private static function getAssetsMap(): array
    {
        $assets_map_path = get_stylesheet_directory() . '/dist/.vite/manifest.json';

        if (
            file_exists($assets_map_path) &&
            $contents = file_get_contents($assets_map_path)
        ) {
            return json_decode($contents, true);
        }

        return [];
    }

    /**
     * Load custom JS files.
     *
     * @return void
     */
    public static function enqueueScripts(): void
    {
        if (!self::isViteHMRAvailable()) {
            if (array_key_exists('assets/main.js', self::$assets_map)) {
                wp_enqueue_script(
                    'custom-script',
                    get_stylesheet_directory_uri() . '/dist/' . self::$assets_map['assets/main.js']["file"],
                    [],
                    null,
                    []
                );
                self::loadJSScriptAsESModule('custom-script');
            }
        } else {
            $theme_path = parse_url(get_stylesheet_directory_uri(), PHP_URL_PATH);

            wp_enqueue_script(
                'vite-client',
                self::getViteDevServerAddress() . $theme_path . '/dist/@vite/client',
                [],
                null,
                []
            );
            self::loadJSScriptAsESModule('vite-client');

            wp_enqueue_script(
                'vite-script',
                self::getViteDevServerAddress() . $theme_path . '/dist/assets/main.js',
                [],
                null,
                []
            );
            self::loadJSScriptAsESModule('vite-script');
        }
    }

    /**
     * Load custom styles.
     *
     * @return void
     */
    public static function enqueueStyles(): void
    {
        wp_dequeue_style('wp-block-library');

        if (
            !self::isViteHMRAvailable() &&
            array_key_exists('assets/main.js', self::$assets_map) &&
            array_key_exists('css', self::$assets_map['assets/main.js'])
        ) {
            foreach (self::$assets_map['assets/main.js']["css"] as $style_path) {
                wp_enqueue_style(
                    'core',
                    get_stylesheet_directory_uri() . '/dist/' . $style_path,
                    [],
                    null,
                    'all'
                );
            }
        }
    }

    /**
     * Set login styles.
     *
     * @return void
     */
    public static function enqueueLoginStyles(): void
    {
        wp_enqueue_style(
            'admin-styles',
            get_stylesheet_directory_uri() . '/login.css',
            [],
            false,
            'all'
        );
    }

    /**
     * Set the login logo link URL.
     *
     * @return string
     */
    public static function customLoginLogoUrl(): string
    {
        return site_url();
    }

    /**
     * Ensure scripts are loaded as modules.
     *
     * @param string $script_handle
     * @return void
     */
    public static function loadJSScriptAsESModule(string $script_handle): void
    {
        add_filter(
            'script_loader_tag',
            function ($tag, $handle, $src) use ($script_handle) {
                if ($script_handle === $handle) {
                    return sprintf(
                        '<script type="module" src="%s"></script>',
                        esc_url($src)
                    );
                }
                return $tag;
            },
            10,
            3
        );
    }

    /**
     * Get Vite dev server URL.
     *
     * @return string
     */
    public static function getViteDevServerAddress(): string
    {
        if (defined('VITE_DEV_SERVER_URL')) {
            return VITE_DEV_SERVER_URL;
        }

        return '';
    }

    /**
     * Check if Vite dev server is supposed to be running.
     *
     * @return boolean
     */
    public static function isViteHMRAvailable(): bool
    {
        return !empty(self::getViteDevServerAddress()) &&
            defined('WP_ENVIRONMENT_TYPE') &&
            // @phpstan-ignore identical.alwaysTrue 
            WP_ENVIRONMENT_TYPE === 'local';
    }
}
