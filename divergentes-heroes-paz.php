<?php
/**
 * Plugin Name: Divergentes - Héroes de la Paz
 * Description: Especial editorial interactivo para WordPress.
 * Version: 1.0.0
 * Author: Divergentes
 */

if (!defined('ABSPATH')) {
    exit;
}

define('DHP_VERSION', '1.0.0');
define('DHP_FILE', __FILE__);
define('DHP_PATH', plugin_dir_path(__FILE__));
define('DHP_URL', plugin_dir_url(__FILE__));

require_once DHP_PATH . 'inc/helpers.php';
require_once DHP_PATH . 'inc/class-assets.php';
require_once DHP_PATH . 'inc/class-data.php';
require_once DHP_PATH . 'inc/class-shortcode.php';
require_once DHP_PATH . 'inc/class-plugin.php';

DHP_Plugin::init();
