<?php

declare(strict_types=1);

namespace App\Configs;

class Comments implements ConfigInterface
{
    public static function init(): void
    {
        add_action('admin_init', function (string $context): void {
            self::removeAdminComments($context);
        });
        add_action('admin_menu', function (): void {
            self::removeAdminEditCommentsMenu();
        });
        add_filter('comments_array', function (array $comments, int $post_id): array {
            return [];
        }, 10, 2);
        add_filter('comments_open', function (bool $comments_open, int $post_id): bool {
            return false;
        }, 20, 2);
    }

    /**
     * Remove comments support from admin.
     *
     * @return void
     */
    public static function removeAdminComments(string $context): void
    {
        // Redirect any user trying to access comments page.
        global $pagenow;

        if ($pagenow === 'edit-comments.php') {
            wp_redirect(admin_url());
            exit;
        }

        // Remove comments metabox from dashboard.
        remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');

        // Remove comments links from admin bar.
        if (is_admin_bar_showing()) {
            remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
        }

        // Prevent subscribers viewing dashboard profile.
        if (is_admin() && !defined('DOING_AJAX') && (current_user_can('subscriber'))) {
            wp_redirect(home_url());
            exit;
        }

        // Disable support for comments and trackbacks in post types.
        foreach (get_post_types() as $post_type) {
            if (post_type_supports($post_type, 'comments')) {
                remove_post_type_support($post_type, 'comments');
                remove_post_type_support($post_type, 'trackbacks');
            }
        }
    }

    /**
     * Remove comments menu.
     *
     * @return void
     */
    public static function removeAdminEditCommentsMenu(): void
    {
        remove_menu_page('edit-comments.php');
    }
}
