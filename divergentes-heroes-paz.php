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

    public static function init(): void
    {
        add_shortcode(self::SHORTCODE, [self::class, 'shortcode']);
    }

    public static function shortcode($atts = []): string
    {
        $atts = shortcode_atts([
            'slug' => 'heroes-de-la-paz',
        ], $atts, self::SHORTCODE);

        $slug = sanitize_title($atts['slug'] ?? 'heroes-de-la-paz');

        $root_id = 'heroesPazApp';

        $data_url = plugins_url('data/heroes.json', __FILE__);

        self::enqueue_assets();

        $config = [
            'dataUrl' => $data_url,
            'rootId' => $root_id,
            'slug' => $slug,
            'basePath' => home_url('/'),
        ];

        ob_start();
        $plugin_dir = plugin_dir_path(__FILE__);
        $config_json = wp_json_encode($config, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        include $plugin_dir . 'templates/app-shell.php';
        return (string) ob_get_clean();
    }

    private static function enqueue_assets(): void
    {
        $plugin_dir = plugin_dir_path(__FILE__);
        $plugin_url = plugin_dir_url(__FILE__);

        $css_path = $plugin_dir . 'assets/app.css';
        $js_path = $plugin_dir . 'assets/app.js';

        $css_ver = file_exists($css_path) ? (string) filemtime($css_path) : '1.0.0';
        $js_ver = file_exists($js_path) ? (string) filemtime($js_path) : '1.0.0';

        wp_enqueue_style(self::STYLE_HANDLE, $plugin_url . 'assets/app.css', [], $css_ver);
        wp_enqueue_script(self::SCRIPT_HANDLE, $plugin_url . 'assets/app.js', [], $js_ver, true);
    }
}

Divergentes_Heroes_Paz_Plugin::init();
