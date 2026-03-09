<?php
/**
 * Shortcode handler
 */

if (!defined('ABSPATH')) {
    exit;
}

class DHP_Shortcode {
    protected static $has_fullbleed = false;

    public static function init() {
        add_shortcode('heroes_paz', [__CLASS__, 'render']);
        add_filter('body_class', [__CLASS__, 'body_class']);
    }

    public static function body_class($classes) {
        if (self::$has_fullbleed) {
            $classes[] = 'hp-has-fullbleed';
        }
        return $classes;
    }

    public static function render($atts = []) {
        $atts = shortcode_atts([
            'layout'   => 'fullbleed',
            'theme'    => 'editorial',
            'data_url' => '',
            'slug'     => 'heroes-paz',
        ], $atts, 'heroes_paz');

        if (($atts['layout'] ?? '') === 'fullbleed') {
            self::$has_fullbleed = true;
        }

        // Enqueue all assets
        wp_enqueue_style('dhp-fonts');
        wp_enqueue_style('dhp-tokens');
        wp_enqueue_style('dhp-theme');
        wp_enqueue_style('dhp-app');
        wp_enqueue_style('dhp-page');
        wp_enqueue_script('dhp-app');

        // Load data
        $data = DHP_Data::load_data();
        if (!$data) {
            return '<!-- Heroes data not available -->';
        }

        // Generate IDs and config
        $instance_id = dhp_instance_id();
        $root_id = 'heroesPazApp-' . $instance_id;
        
        $config = [
            'dataUrl'       => $atts['data_url'] ?: plugins_url('data/heroes.json', DHP_FILE),
            'imagesBaseUrl' => plugins_url('assets/images/', DHP_FILE),
            'layout'        => $atts['layout'],
            'theme'         => $atts['theme'],
            'instanceId'    => $instance_id,
            'rootId'        => $root_id,
            'slug'          => $atts['slug'],
        ];

        $config_json = wp_json_encode($config);

        ob_start();
        include DHP_PATH . 'templates/app-shell.php';
        return ob_get_clean();
    }
}
