<?php
/**
 * Assets management
 */

if (!defined('ABSPATH')) {
    exit;
}

class DHP_Assets
{
    public static function init()
    {
        add_action('wp_enqueue_scripts', [__CLASS__, 'register']);
    }

    public static function register()
    {
        // Local fonts & tokens first
        self::register_style('dhp-tokens', 'assets/css/tokens.css');
        self::register_style('dhp-fonts', 'assets/css/fonts.css', ['dhp-tokens']);

        // Core structural and theme
        self::register_style('dhp-app', 'assets/css/app.css', ['dhp-fonts']);
        self::register_style('dhp-page', 'assets/css/page.css', ['dhp-app']);
        self::register_style('dhp-theme-editorial', 'assets/css/theme-editorial.css', ['dhp-page']);

        // Host compat loaded LAST
        self::register_style('dhp-host-compat-divergentes', 'assets/css/host-compat-divergentes-com.css', ['dhp-theme-editorial']);

        // JS
        self::register_script('dhp-app', 'assets/js/app.js', [], true);
    }

    protected static function register_style($handle, $relative_path, $deps = [])
    {
        $file = DHP_PATH . $relative_path;
        if (file_exists($file)) {
            wp_register_style($handle, DHP_URL . $relative_path, $deps, filemtime($file));
        }
    }

    protected static function register_script($handle, $relative_path, $deps = [], $in_footer = true)
    {
        $file = DHP_PATH . $relative_path;
        if (file_exists($file)) {
            wp_register_script($handle, DHP_URL . $relative_path, $deps, filemtime($file), $in_footer);
        }
    }
}
