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
        add_shortcode('story_engine', [__CLASS__, 'render']);
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
            'demo'     => 'heroes',
            'layout'   => 'fullbleed',
            'theme'    => 'editorial',
            'slug'     => '',
            'data_url' => '',
        ], $atts);

        // Sanitize attributes
        $demo = sanitize_key($atts['demo']);
        if (empty($demo)) {
            $demo = 'heroes';
        }

        $allowed_layouts = ['fullbleed', 'contained'];
        $layout = in_array($atts['layout'], $allowed_layouts) ? $atts['layout'] : 'fullbleed';

        $allowed_themes = ['editorial'];
        $theme = in_array($atts['theme'], $allowed_themes) ? $atts['theme'] : 'editorial';

        $slug = sanitize_key($atts['slug']);
        $data_url = esc_url_raw($atts['data_url']);

        if ($layout === 'fullbleed') {
            self::$has_fullbleed = true;
        }

        // Enqueue all assets
        wp_enqueue_style('dhp-fonts');
        wp_enqueue_style('dhp-tokens');
        wp_enqueue_style('dhp-theme');
        wp_enqueue_style('dhp-app');
        wp_enqueue_style('dhp-page');
        wp_enqueue_script('dhp-app');

        // Load data using resolved dataset
        $data = DHP_Data::load_dataset($demo);
        if (!$data) {
            return '<!-- Story data not available -->';
        }

        // Generate IDs and config
        $instance_id = dhp_instance_id();
        $root_id = 'heroesPazApp-' . $instance_id;
        
        $config = [
            'dataUrl'       => $data_url ?: DHP_Data::get_dataset_url($demo),
            'imagesBaseUrl' => plugins_url('assets/images/', DHP_FILE),
            'layout'        => $layout,
            'theme'         => $theme,
            'instanceId'    => $instance_id,
            'rootId'        => $root_id,
            'slug'          => $slug,
        ];

        $config_json = wp_json_encode($config);

        ob_start();
        include DHP_PATH . 'templates/app-shell.php';
        return ob_get_clean();
    }
}
