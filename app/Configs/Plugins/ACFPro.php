<?php

namespace App\Configs\Plugins;

use App\Configs\ConfigInterface;

class ACFPro implements ConfigInterface
{
    /**
     * Location of ACF JSON config files.
     *
     * @var string
     */
    public static string $config_path = APPROOT . '/config/advanced-custom-fields';

    /**
     * Initialise config.
     *
     * @return void
     */
    public static function init(): void
    {
        add_action('acf/init', [self::class, 'addOptionsPages']);
        add_filter('acf/settings/load_json', [self::class, 'pluginAcfSettingsLoadJson']);
        add_filter('acf/settings/save_json', [self::class, 'pluginAcfSettingsSaveJson']);
    }

    /**
     * Add custom options pages.
     *
     * @return void
     */
    public static function addOptionsPages(): void
    {
        if (
            function_exists('acf_add_options_page') &&
            function_exists('acf_add_options_sub_page')
        ) {
            acf_add_options_page([
                'page_title' => 'Theme Settings',
                'menu_title' => 'Theme Settings',
                'menu_slug' => 'theme-general-settings',
                'capability' => 'edit_posts',
                'redirect' => true
            ]);

            acf_add_options_sub_page([
                'page_title' => 'Theme Settings',
                'menu_title' => 'General',
                'parent_slug' => 'theme-general-settings',
                'capability' => 'edit_posts',
                'redirect' => true
            ]);

            acf_add_options_sub_page([
                'page_title' => 'Theme Header Settings',
                'menu_title' => 'Header',
                'parent_slug' => 'theme-general-settings',
                'capability' => 'edit_posts',
                'redirect' => true
            ]);

            acf_add_options_sub_page([
                'page_title' => 'Theme Footer Settings',
                'menu_title' => 'Footer',
                'parent_slug' => 'theme-general-settings',
                'capability' => 'edit_posts',
                'redirect' => true
            ]);
        }
    }

    /**
     * Set custom path to load JSON configs.
     *
     * @param array<string> $paths
     * @return array<string>
     */
    public static function pluginAcfSettingsLoadJson(array $paths): array
    {
        unset($paths[0]);
        $paths[] = self::$config_path;
        return $paths;
    }

    /**
     * Set custom path to save JSON configs.
     *
     * @param string $path
     * @return string
     */
    public static function pluginAcfSettingsSaveJson(string $path): string
    {
        $path = self::$config_path;
        return $path;
    }
}
