<?php

declare(strict_types=1);

namespace App\Configs;

use WP_Error;
use WP_Sitemaps_Provider;

class PreventUserEnum implements ConfigInterface
{
    /**
     * Initialise config.
     *
     * @return void
     */
    public static function init(): void
    {
        add_filter('login_errors', function () {
            return self::modifyLoginErrors();
        });
        add_action('init', function () {
            self::preventAuthorRequests();
        });
        add_filter('rest_authentication_errors', function (WP_Error|null|true $access): WP_Error|null|true {
            return self::onlyAllowLoggedInRestAccessToUsers($access);
        });
        add_filter('wp_sitemaps_add_provider', function (WP_Sitemaps_Provider $provider, string $name): WP_Sitemaps_Provider|false {
            return self::removeAuthorsFromSitemap($provider, $name);
        }, 10, 2);
        add_filter('oembed_response_data', function (array $data): array {
            return self::removeAuthorFromOembed($data);
        });
        add_action('template_redirect', function (): void {
            self::redirectAuthorArchives();
        });
        add_filter('the_author_posts_link', function (string $link): string {
            return self::modifyTheAuthorPostsLink($link);
        });
    }

    /**
     * Return a less helpful error message to prevent info gathering.
     *
     * @return string
     */
    public static function modifyLoginErrors(): string
    {
        return 'An error occurred. Try again or if you are a bot, please don\'t.';
    }

    /**
     * Prevent Author enumeration.
     *
     * @return void
     */
    public static function preventAuthorRequests(): void
    {
        if (
            isset($_REQUEST['author'])
            && self::stringContainsNumbers($_REQUEST['author'])
            && ! is_user_logged_in()
        ) {
            wp_die('forbidden - number in author name not allowed = ' . esc_html($_REQUEST['author']));
        }
    }

    /**
     * Pass error back to unauthenticated Users REST API calls.
     *
     * @param WP_Error|null|true $access
     * @return WP_Error|null|true
     */
    public static function onlyAllowLoggedInRestAccessToUsers(WP_Error|null|true $access): WP_Error|null|true
    {
        if (is_user_logged_in()) {
            return $access;
        }

        if ((preg_match('/users/i', $_SERVER['REQUEST_URI']) !== 0)
            || (isset($_REQUEST['rest_route']) && (preg_match('/users/i', $_REQUEST['rest_route']) !== 0))
        ) {
            return new WP_Error(
                'rest_cannot_access',
                'Only authenticated users can access the User endpoint REST API.',
                [
                    'status' => rest_authorization_required_code()
                ]
            );
        }

        return $access;
    }

    /**
     * Check if string contains numbers.
     *
     * @param string $string
     * @return boolean
     */
    private static function stringContainsNumbers(string $string): bool
    {
        return preg_match('/\\d/', $string) > 0;
    }

    /**
     * Remove authors from sitemap.
     *
     * @param WP_Sitemaps_Provider $provider
     * @param string $name
     * @return WP_Sitemaps_Provider|false
     */
    public static function removeAuthorsFromSitemap(WP_Sitemaps_Provider $provider, string $name): WP_Sitemaps_Provider|false
    {
        if ('users' === $name) {
            return false;
        }

        return $provider;
    }

    /**
     * Remove author from oembed data.
     *
     * @param string[] $data
     * @return string[]
     */
    public static function removeAuthorFromOembed(array $data): array
    {
        unset($data['author_url']);
        unset($data['author_name']);

        return $data;
    }

    /**
     * Redirect any traffic from authors archive to home.
     *
     * @return void
     */
    public static function redirectAuthorArchives(): void
    {
        if (is_author() || isset($_GET['author'])) {
            wp_safe_redirect(esc_url(home_url('/')), 301);
        }
    }

    /**
     * Amend any author page links.
     *
     * @param string $link
     * @return string
     */
    public static function modifyTheAuthorPostsLink(string $link): string
    {
        if (! is_admin()) {
            return get_the_author();
        }
        return $link;
    }
}
