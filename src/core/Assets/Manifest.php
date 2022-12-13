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
     * @var Array<string, RawAsset>
     */
    protected array $assets = [];

    /**
     * JSON Mapper
     *
     * @var JsonMapper
     */
    protected JsonMapper $mapper;

    protected function __construct(protected string $path)
    {
        $this->mapper = new JsonMapper();
        $this->mapper->bIgnoreVisibility = true;

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
        $assets = $this->getAssetsFromManifest();

        $this->assets = array_map(fn (stdClass $asset) => $this->mapper->map($asset, new RawAsset()), $assets);
    }

    /** 
     * Get assets from manifest file path
     *
     * @return array
     */
    protected function getAssetsFromManifest(): array
    {
        return (array) json_decode(file_get_contents($this->getFullPath(), true));
    }

    /**
     * Get manifest assets
     *
     * @return Array<string, RawAsset>
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
