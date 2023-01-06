<?php

/**
 * --------------------------------------------------------------------------
 * WordPress core functions mocks
 * --------------------------------------------------------------------------
 */

if (!function_exists('wp_normalize_path')) {
    function wp_normalize_path(string $path): string
    {
        return $path;
    }
}

if (!function_exists('get_template_directory')) {
    function get_template_directory(): string
    {
        return 'path/to/theme';
    }
}

if (!function_exists('get_template_directory_uri')) {
    function get_template_directory_uri(): string
    {
        return 'http://example.com/app/themes/wolat';
    }
}

if (!function_exists('trailingslashit')) {
    function trailingslashit(string $path): string
    {
        return $path . DIRECTORY_SEPARATOR;
    }
}
