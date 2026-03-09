<?php
/**
 * Assets management
 */

if (!defined('ABSPATH')) {
    exit;
}

class DHP_Assets {
    public static function init() {
        add_action('wp_enqueue_scripts', [__CLASS__, 'register']);
    }

    public static function register() {
        // Google Fonts
        wp_register_style(
            'dhp-fonts',
            'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Staatliches&display=swap',
            [],
            null
        );

        // CSS tokens and theme
        self::register_style('dhp-tokens', 'assets/css/tokens.css', ['dhp-fonts']);
        self::register_style('dhp-theme', 'assets/css/theme-editorial.css', ['dhp-tokens']);
        self::register_style('dhp-app', 'assets/css/app.css', ['dhp-theme']);
        self::register_style('dhp-page', 'assets/css/page.css', ['dhp-app']);

        // JS
        self::register_script('dhp-app', 'assets/js/app.js', [], true);
    }

    protected static function register_style($handle, $relative_path, $deps = []) {
        $file = DHP_PATH . $relative_path;
        if (file_exists($file)) {
            wp_register_style($handle, DHP_URL . $relative_path, $deps, filemtime($file));
        }
    }

    protected static function register_script($handle, $relative_path, $deps = [], $in_footer = true) {
        $file = DHP_PATH . $relative_path;
        if (file_exists($file)) {
            wp_register_script($handle, DHP_URL . $relative_path, $deps, filemtime($file), $in_footer);
        }
    }
}
