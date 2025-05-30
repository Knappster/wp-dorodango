<?php

namespace App\Configs;

class RestAPI implements ConfigInterface
{
    /**
     * Initialise config.
     *
     * @return void
     */
    public static function init(): void
    {
        add_filter('rest_endpoints', function (array $endpoints): array {
            return self::removeUnusedEndpoints($endpoints);
        });
        add_filter('rest_prepare_post', function (
            \WP_REST_Response $response,
            \WP_Post $post,
            \WP_REST_Request $request
        ): \WP_REST_Response {
            return self::removeTitleHTMLEntities($response, $post);
        }, 10, 3);
    }

    /**
     * Remove unused REST API endpoints.
     *
     * @param array<mixed> $endpoints
     * @return array<mixed>
     */
    public static function removeUnusedEndpoints(array $endpoints): array
    {
        $remove_endpoints = [
            '/wp/v2/users',
            '/wp/v2/users/(?P<id>[\d]+)',
            '/wp/v2/settings',
            '/wp/v2/settings/(?P<id>[\d]+)',
            '/wp/v2/statuses',
            '/wp/v2/statuses/(?P<id>[\d]+)',
            '/wp/v2/comments',
            '/wp/v2/comments/(?P<id>[\d]+)',
        ];

        foreach ($remove_endpoints as $endpoint) {
            if (isset($endpoints[$endpoint])) {
                unset($endpoints[$endpoint]);
            }
        }

        return $endpoints;
    }

    /**
     * Remove HTML entities from titles.
     *
     * @param \WP_REST_Response $response
     * @param \WP_Post $post
     * @return \WP_REST_Response
     */
    public static function removeTitleHTMLEntities(\WP_REST_Response $response, \WP_Post $post): \WP_REST_Response
    {
        $response->data['title']['rendered'] = html_entity_decode(
            $response->data['title']['rendered']
        );

        return $response;
    }
}
