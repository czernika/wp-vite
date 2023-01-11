<?php

declare(strict_types=1);

namespace Wolat\Support;

use Wolat\Assets\Injector;

class Helper
{
    /**
     * Load assets no matter where being called
     *
     * @param string|array $assets
     * @return array
     */
    public static function vite(string|array $assets): array
    {
        return Injector::from($assets);
    }
}
