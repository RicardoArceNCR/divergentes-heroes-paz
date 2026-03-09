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
    return isset($array[$key]) ? $array[$key] : $default;
}
