<?php

declare(strict_types=1);

namespace Wolat\Assets;

use JsonMapper;
use stdClass;
use Wolat\Assets\Exceptions\ManifestFileNotFoundException;

class Manifest
{
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

    /**
     * Root path
     *
     * @var string
     */
    protected string $path;

    protected function __construct(
        protected string $root,
        protected string $dist,
        protected string $name = 'manifest.json',
        protected ?string $uri = null,
    ) {
        $this->setPath($this->root . DIRECTORY_SEPARATOR . $this->dist);
        $this->setDist($this->dist);

        $this->initMapper();
        $this->resolveAssets();
    }

    /**
     * Get theme path
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Set theme path
     *
     * @param string $path
     * @return void
     */
    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    /**
     * Get dist directory
     *
     * @return string
     */
    public function getDist(): string
    {
        return $this->dist;
    }

    /**
     * Set dist directory wrapped in slashes
     *
     * @param string $dist
     * @return void
     */
    public function setDist(string $dist): void
    {
        $this->dist = DIRECTORY_SEPARATOR . $dist . DIRECTORY_SEPARATOR;
    }

    /**
     * Get root path
     *
     * @return string
     */
    public function getRootPath(): string
    {
        return $this->root ??= get_template_directory();
    }

    /**
     * Get root uri
     *
     * @return string
     */
    public function getRootUri(): string
    {
        return $this->uri ??= get_template_directory_uri();
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
    public static function load(string $root, string $path, string $name = 'manifest.json', ?string $uri = null): static
    {
        return new static($root, $path, $name, $uri);
    }

    /**
     * Instantiate new manifest object for theme
     *
     * @param string $path
     * @param string $name
     * @return static
     */
    public static function loadAsTheme(string $path, string $name = 'manifest.json'): static
    {
        return (static::load(get_template_directory(), $path, $name, get_template_directory_uri()));
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
        $path = wp_normalize_path($this->getPath());
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
