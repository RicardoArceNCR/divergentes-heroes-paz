<?php
/**
 * Data management and validation
 */

if (!defined('ABSPATH')) {
    exit;
}

class DHP_Data {
    public static function get_default_data_path() {
        return DHP_PATH . 'data/heroes.json';
    }

    public static function get_dataset_path($slug = 'heroes') {
        $slug = sanitize_key($slug);
        if (empty($slug)) {
            $slug = 'heroes';
        }
        
        $path = DHP_PATH . "data/{$slug}.json";
        
        // Fallback to heroes.json if requested file doesn't exist
        if (!file_exists($path)) {
            $path = self::get_default_data_path();
        }
        
        return $path;
    }

    public static function get_dataset_url($slug = 'heroes') {
        $slug = sanitize_key($slug);
        if (empty($slug)) {
            $slug = 'heroes';
        }
        
        $path = self::get_dataset_path($slug);
        $filename = basename($path);
        
        return plugins_url("data/{$filename}", DHP_FILE);
    }

    public static function load_dataset($slug = 'heroes') {
        $path = self::get_dataset_path($slug);
        return self::load_data($path);
    }

    public static function load_data($path = '') {
        $path = $path ?: self::get_default_data_path();

        if (!file_exists($path)) {
            return null;
        }

        $raw = file_get_contents($path);
        if (!$raw) {
            return null;
        }

        $data = json_decode($raw, true);
        if (!is_array($data)) {
            return null;
        }

        return self::normalize($data);
    }

    protected static function normalize(array $data) {
        $data['meta'] = isset($data['meta']) && is_array($data['meta']) ? $data['meta'] : [];
        $data['intro'] = isset($data['intro']) && is_array($data['intro']) ? $data['intro'] : [];
        $data['months'] = isset($data['months']) && is_array($data['months']) ? $data['months'] : [];
        $data['events'] = isset($data['events']) && is_array($data['events']) ? $data['events'] : [];

        return $data;
    }

    public static function validate($data) {
        $errors = [];

        if (!is_array($data)) {
            $errors[] = 'Data must be an array';
            return $errors;
        }

        if (!isset($data['months']) || !is_array($data['months'])) {
            $errors[] = 'months must be an array';
        }

        if (!isset($data['events']) || !is_array($data['events'])) {
            $errors[] = 'events must be an array';
        }

        return $errors;
    }
}
