<?php

declare(strict_types=1);

use Wolat\Assets\Manifest;
use Wolat\Assets\Vite;

if (!function_exists('wl_vite')) {

    /**
     * Inject assets from injector
     *
     * @param string|array $assets
     * @return void
     */
    function wl_vite(string|array $assets): void
    {
        echo (new Vite(
            Manifest::loadAsTheme()
        ))->inject($assets);
    }
}
