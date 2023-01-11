<?php

declare(strict_types=1);

namespace Wolat\Assets;

class Injector
{
    /**
     * List of inject assets
     *
     * @var string[]
     */
    protected static array $assets = [];

    /**
     * Inject assets from keys
     *
     * @param string|array $assets
     * @return string[]
     */
    public static function from(string|array $assets): array
    {
        static::addAssets($assets);

        return static::getAssets();
    }

    /**
     * Get registered assets
     *
     * @return string[]
     */
    public static function getAssets(): array
    {
        return static::$assets;
    }

    /**
     * Set new list of registered assets
     *
     * @param string|string[] $assets
     * @return void
     */
    public static function setAssets(string|array $assets): void
    {
        static::$assets = (array) $assets;
    }

    /**
     * Add new assets to a existing one
     *
     * @param string|string[] $assets
     * @return void
     */
    public static function addAssets(string|array $assets): void
    {
        static::$assets = array_unique(array_merge((array) $assets, static::getAssets()));
    }
}
