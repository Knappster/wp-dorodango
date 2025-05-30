<?php

declare(strict_types=1);

namespace App\Configs;

class Defaults implements ConfigInterface
{
    /**
     * Initialise config.
     *
     * @return void
     */
    public static function init(): void
    {
        // Disable unused Wordpress features.
        add_filter('pings_open', function (bool $pings_open, int $post_id): bool {
            return false;
        }, 20, 2);
        add_filter('show_admin_bar', function (): bool {
            return false;
        });
        add_filter('the_generator', function (): string {
            return '';
        });
        add_filter('xmlrpc_enabled', function (): bool {
            return false;
        });

        // Remove standard WP header links.
        remove_filter('wp_head', 'rsd_link');
        remove_filter('wp_head', 'wlwmanifest_link');
        remove_filter('wp_head', 'wp_generator');
        add_filter('wpseo_hide_version', function (): bool {
            return true;
        });

        // Admin login styles.
        add_action('login_enqueue_scripts', function (): void {
            self::enqueueLoginStyles();
        });
        add_filter('login_headerurl', function (): string {
            return self::customLoginLogoUrl();
        });

        // Post excerpt settings.
        add_filter('excerpt_length', function (): int {
            return self::excerptLength();
        }, 999);
        add_filter('excerpt_more', function (): string {
            return self::excerptMore();
        }, 999);

        // Check for staging environment.
        add_filter('login_body_class', function (): array {
            return self::addEnvironmentClass();
        });
        add_filter('body_class', function (): array {
            return self::addEnvironmentClass();
        });

        // Add Timber templates path.
        add_filter('timber/locations', function ($paths) {
            $paths[] = [APPROOT . '/templates'];

            return $paths;
        });
    }

    /**
     * Add environment class name to body.
     *
     * @param string[] $classes
     * @return string[]
     */
    public static function addEnvironmentClass($classes = []): array
    {
        $environment = wp_get_environment_type();

        if ($environment !== 'production') {
            $classes[] = 'env-' . $environment;
        }

        return $classes;
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
     * Set excerpt length.
     *
     * @return integer
     */
    public static function excerptLength(): int
    {
        return 20;
    }

    /**
     * Excerpt more.
     *
     * @return string
     */
    public static function excerptMore(): string
    {
        return '&hellip;';
    }
}
