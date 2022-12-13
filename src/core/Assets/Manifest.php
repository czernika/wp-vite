<?php

declare(strict_types=1);

namespace Wolat\Assets;

use JsonMapper;
use stdClass;
use Wolat\Assets\Exceptions\ManifestFileNotFoundException;

class Manifest
{
    /**
     * Manifest instance
     *
     * @var null|static
     */
    protected static ?Manifest $instance = null;

    /**
     * List of manifest raw assets
     *
     * @var Array<string, RawAsset>
     */
    protected array $assets = [];

    /**
     * JSON Mapper object
     *
     * @var JsonMapper
     */
    protected JsonMapper $mapper;

    protected function __construct(protected string $path, protected string $name = 'manifest.json')
    {
        $this->initMapper();
        $this->resolveAssets();
    }

    /**
     * Initialize JSON Mapper object
     *
     * @return void
     */
    protected function initMapper(): void
    {
        $this->mapper = new JsonMapper();
        $this->mapper->bIgnoreVisibility = true;
    }

    /**
     * Instantiate new manifest object
     *
     * @param string $path
     * @param string $name
     * @return static
     */
    public static function load(string $path, string $name = 'manifest.json'): static
    {
        return new static($path, $name);
    }

    /**
     * Load previously created manifest object or new one
     *
     * @param string $path
     * @param string $name
     * @return static
     */
    public static function loadSingleton(string $path, string $name = 'manifest.json'): static
    {
        if (is_null(static::$instance)) {
            static::$instance = static::load($path, $name);
        }

        return static::$instance;
    }

    /**
     * Resolve assets from manifest file
     *
     * @return void
     */
    protected function resolveAssets(): void
    {
        $assets = $this->getAssetsFromManifest();

        $this->assets = array_map(fn (stdClass $asset) => $this->mapper->map($asset, new RawAsset()), $assets);
    }

    /** 
     * Get raw content array from manifest file
     *
     * @throws ManifestFileNotFoundException Manifest file does not exists
     * @return array
     */
    protected function getAssetsFromManifest(): array
    {
        if (!file_exists($manifest = $this->getFullPath())) {
            throw new ManifestFileNotFoundException("Manifest file not found at \"{$manifest}\". Check file name and its path");
        }

        return (array) json_decode(file_get_contents($manifest, true));
    }

    /**
     * Get all manifest assets
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
        return str_ends_with($path, DIRECTORY_SEPARATOR) ? $path : $path . DIRECTORY_SEPARATOR;
    }

    /**
     * Get full manifest file path (name included)
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
}
