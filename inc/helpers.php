<?php
/**
 * Helpers utility functions
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Escape attribute with fallback
 */
function dhp_esc_attr($value) {
    return esc_attr($value ?? '');
}

/**
 * Escape HTML with fallback
 */
function dhp_esc_html($value) {
    return esc_html($value ?? '');
}

/**
 * Generate unique instance ID
 */
function dhp_instance_id($prefix = 'hp') {
    return $prefix . '-' . uniqid();
}

/**
 * Get asset image URL
 */
function dhp_asset_image_url($filename = '') {
    if (!$filename) {
        return '';
    }
    return plugins_url('assets/images/' . ltrim($filename, '/'), DHP_FILE);
}

/**
 * Safe array get with fallback
 */
function dhp_array_get($array, $key, $default = '') {
    if (!is_array($array)) {
        return $default;
    }

    return array_key_exists($key, $array) ? $array[$key] : $default;
}

/**
 * Safe array get for strings with trimming
 */
function dhp_array_get_string($array, $key, $default = '') {
    $value = dhp_array_get($array, $key, $default);
    return is_string($value) ? trim($value) : $default;
}

/**
 * Safe array get for arrays
 */
function dhp_array_get_array($array, $key, $default = []) {
    $value = dhp_array_get($array, $key, $default);
    return is_array($value) ? $value : $default;
}
