<?php
/**
 * Plugin Name: Divergentes — Héroes de la Paz
 * Description: Timeline interactivo embebible via shortcode.
 * Version: 1.0.0
 * Author: Divergentes
 */

if (!defined('ABSPATH')) {
    exit;
}

final class Divergentes_Heroes_Paz_Plugin
{
    const SHORTCODE = 'heroes_paz';
    const SCRIPT_HANDLE = 'divergentes-heroes-paz-app';
    const STYLE_HANDLE = 'divergentes-heroes-paz-app';
    const PAGE_STYLE_HANDLE = 'divergentes-heroes-paz-page';
    protected static $has_fullbleed = false;

    public static function init(): void
    {
        add_shortcode(self::SHORTCODE, [self::class, 'shortcode']);
        add_filter('wp_resource_hints', [self::class, 'resource_hints'], 10, 2);
        add_filter('body_class', [self::class, 'add_body_class']);
    }

    public static function shortcode($atts = []): string
    {
        $atts = shortcode_atts([
            'slug' => 'heroes-de-la-paz',
            'theme' => 'default',
            'layout' => 'contained',
            'max' => '',
            'rail_w' => '',
            'rail_gap' => '',
            'sticky_top' => '',
            'event_photo_w' => '',
            'font_body' => '',
            'font_title' => '',
            'data_url' => '',
            'dot_x' => '',
            'dot_size' => '',
            'dot_top' => '',
            'rail_dot_top' => '',
        ], $atts, self::SHORTCODE);

        $slug = sanitize_title($atts['slug'] ?? 'heroes-de-la-paz');

        $theme = sanitize_key($atts['theme'] ?? 'default');
        if ($theme === '') {
            $theme = 'default';
        }

        $layout = sanitize_key($atts['layout'] ?? 'contained');
        if ($layout !== 'fullbleed') {
            $layout = 'contained';
        }

        $page_id = (int) get_queried_object_id();

        $instance = wp_generate_uuid4();
        $root_id = 'heroesPazApp-' . $instance;

        $data_url_attr = trim((string) ($atts['data_url'] ?? ''));
        if ($data_url_attr === '') {
            $data_url = plugins_url('data/heroes.json', __FILE__);
        } else {
            $data_url = esc_url_raw($data_url_attr);
        }

        $images_base_url = plugins_url('images/', __FILE__);

        self::enqueue_assets($page_id, $layout);

        $config = [
            'instanceId' => $instance,
            'dataUrl' => $data_url,
            'imagesBaseUrl' => $images_base_url,
            'rootId' => $root_id,
            'slug' => $slug,
            'basePath' => home_url('/'),
        ];

        $css_vars = [];
        $vars_to_check = [
            'max' => '--hp-max',
            'rail_w' => '--hp-rail-w',
            'rail_gap' => '--hp-rail-gap',
            'sticky_top' => '--hp-sticky-top',
            'event_photo_w' => '--hp-event-photo-w',
            'font_body' => '--hp-font-body',
            'font_title' => '--hp-font-title',
            'dot_x' => '--hp-dot-x',
            'dot_size' => '--hp-dot-size',
            'dot_top' => '--hp-dot-top',
            'rail_dot_top' => '--hp-rail-dot-top',
        ];

        foreach ($vars_to_check as $attr_key => $var_name) {
            $val = trim((string) ($atts[$attr_key] ?? ''));
            if ($val !== '') {
                // For fonts, we allow strings, for others we validate units
                if (strpos($attr_key, 'font_') === 0 || self::is_valid_css_value($val)) {
                    $css_vars[$var_name] = $val;
                }
            }
        }

        $shell_style_attr = '';
        if (!empty($css_vars)) {
            $pairs = [];
            foreach ($css_vars as $k => $v) {
                $pairs[] = $k . ':' . $v;
            }
            $shell_style_attr = implode(';', $pairs) . ';';
        }

        ob_start();
        $plugin_dir = plugin_dir_path(__FILE__);
        $config_json = wp_json_encode($config, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        if ($layout === 'fullbleed') {
            self::$has_fullbleed = true;
        }

        echo '<div class="hp-wp-wrap ' . ($layout === 'fullbleed' ? 'hp-wp-wrap--fullbleed' : '') . '">';
        echo '<div class="hp-mount ' . ($layout === 'fullbleed' ? 'hp-fullbleed' : '') . '">';
        include $plugin_dir . 'templates/app-shell.php';
        echo '</div>';
        echo '</div>';

        return (string) ob_get_clean();
    }

    private static function enqueue_assets(int $page_id, string $layout): void
    {
        static $enqueued = false;
        if ($enqueued) {
            return;
        }
        $enqueued = true;

        $plugin_dir = plugin_dir_path(__FILE__);
        $plugin_url = plugin_dir_url(__FILE__);

        // Enqueue Google Fonts
        wp_enqueue_style(
            'hp-google-fonts',
            'https://fonts.googleapis.com/css2?family=Lacquer&family=Inter:wght@400;500;600&display=swap',
            [],
            null
        );

        $css_path = $plugin_dir . 'assets/app.css';
        $js_path = $plugin_dir . 'assets/app.js';

        $css_ver = file_exists($css_path) ? (string) filemtime($css_path) : '1.0.0';
        $js_ver = file_exists($js_path) ? (string) filemtime($js_path) : '1.0.0';

        wp_enqueue_style(self::STYLE_HANDLE, $plugin_url . 'assets/app.css', ['hp-google-fonts'], $css_ver);
        wp_enqueue_script(self::SCRIPT_HANDLE, $plugin_url . 'assets/app.js', [], $js_ver, true);

        if ($layout === 'fullbleed') {
            $page_css_path = $plugin_dir . 'assets/page-heroes-paz.css';
            if (file_exists($page_css_path)) {
                $page_css_ver = (string) filemtime($page_css_path);
                wp_enqueue_style(
                    self::PAGE_STYLE_HANDLE,
                    $plugin_url . 'assets/page-heroes-paz.css',
                    [self::STYLE_HANDLE],
                    $page_css_ver
                );
            }
        }
    }

    public static function resource_hints($urls, $relation_type)
    {
        if ($relation_type === 'preconnect') {
            $urls[] = 'https://fonts.googleapis.com';
            $urls[] = [
                'href' => 'https://fonts.gstatic.com',
                'crossorigin' => 'anonymous',
            ];
        }
        return $urls;
    }

    public static function add_body_class($classes)
    {
        if (self::$has_fullbleed) {
            $classes[] = 'hp-has-fullbleed';
        }
        return $classes;
    }

    private static function is_valid_css_value(string $value): bool
    {
        // Simple regex for px, rem, em, vw, vh, %
        return (bool) preg_match('/^\d+(\.\d+)?(px|rem|em|vw|vh|%)$/', $value);
    }
}

Divergentes_Heroes_Paz_Plugin::init();
