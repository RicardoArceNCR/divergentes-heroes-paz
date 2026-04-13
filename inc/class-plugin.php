<?php
/**
 * Main plugin bootstrap
 */

if (!defined('ABSPATH')) {
    exit;
}

class DHP_Plugin {
    public static function init() {
        add_action('init', [__CLASS__, 'register_components']);
    }

    public static function register_components() {
        DHP_Assets::init();
        DHP_Shortcode::init();
    }
}
