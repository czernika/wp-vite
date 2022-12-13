<?php

declare(strict_types=1);

namespace Wolat\Assets;

use JsonMapper;
use stdClass;

class Manifest
{
    /**
     * Manifest filename
     *
     * @var string
     */
    protected string $name = 'manifest.json';

    /**
     * Manifest instance
     *
     * @var null|static
     */
    protected static ?Manifest $instance = null;

    /**
     * List of manifest entries and assets
     *
     * @var array
     */
    protected array $assets = [];

    protected function __construct(protected string $path)
    {
        $this->resolveAssets();
    }

    /**
     * Instantiate manifest object
     *
     * @param string $path
     * @return static
     */
    public static function load(string $path): static
    {
        if (is_null(static::$instance)) {
            static::$instance = new static($path);
        }

        return static::$instance;
    }

    /**
     * Resolve manifest file assets
     *
     * @return void
     */
    protected function resolveAssets(): void
    {
        $mapper = new JsonMapper();
        $assets = (array) json_decode(file_get_contents($this->getFullPath(), true));

        $this->assets = array_map(fn (stdClass $asset) => $mapper->map($asset, new RawAsset()), $assets);
    }

    /**
     * Get manifest assets
     *
     * @return array
     */
    public function getAssets(): array
    {
        return $this->assets;
    }

    /**
     * Normalize provided manifest path
     *
     * @return string
     */
    protected function getNormalizedPath(): string
    {
        $path = wp_normalize_path($this->path);
        return str_ends_with($path, '/') ? $path : $path . '/';
    }

    /**
     * Get full manifest file path
     *
     * @return string
     */
    public function getFullPath(): string
    {
        return $this->getNormalizedPath() . $this->getName();
    }

    /**
     * Get manifest file name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set manifest file name
     *
     * @param string $name
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
