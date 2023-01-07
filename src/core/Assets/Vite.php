<?php

declare(strict_types=1);

namespace Wolat\Assets;

use Wolat\Assets\Exceptions\EntryPointNotExistsException;
use Wolat\Assets\Interfaces\AssetInterface;
use Wolat\Assets\Interfaces\CanBeEntryInterface;

class Vite
{
    /**
     * Vite development port
     *
     * @var integer
     */
    protected int $port = 5173;

    /**
     * Vite development url
     *
     * @var string
     */
    protected string $devUrl = 'http://127.0.0.1';

    /**
     * Hot file name
     *
     * @var string
     */
    protected string $hotFile = 'hot';

    /**
     * List of assets
     * All available assets from manifest
     *
     * @var Array<string,AssetInterface>
     */
    protected array $assets = [];

    /**
     * List of entries
     * Entry must have `isEntry = 1`
     *
     * @var Array<string,CanBeEntryInterface>
     */
    protected array $entries = [];

    /**
     * List of scripts
     * Only Javascript files - vendors and entrypoints
     *
     * @var Array<string,Script|Vendor>
     */
    protected array $scripts = [];

    /**
     * List of modules
     * Only Javascript entrypoints
     *
     * @var Array<string,Script>
     */
    protected array $modules = [];

    /**
     * List of vendors
     * Only Javascript imported modules
     *
     * @var Array<string,Vendor>
     */
    protected array $vendors = [];

    /**
     * List of all styles
     *
     * @var Array<string,Style>
     */
    protected array $styles = [];

    public function __construct(protected Manifest $manifest)
    {
    }

    /**
     * Draw HTML tags
     *
     * @param Manifest $manifest
     * @param string|string[] ...$entrypoints
     * @return string
     */
    public static function drawTags(Manifest $manifest, ...$entrypoints): string
    {
        return (new static($manifest))->inject(...$entrypoints);
    }

    /**
     * Get Vite development port
     *
     * @return integer
     */
    public function getViteDevPort(): int
    {
        return $this->port;
    }

    /**
     * Set Vite development port
     *
     * @param integer $port
     * @return void
     */
    public function setViteDevPort(int $port): void
    {
        $this->port = $port;
    }

    /**
     * Get Vite development URL
     *
     * @return string
     */
    public function getViteDevUrl(): string
    {
        return $this->devUrl;
    }

    /**
     * Set Vite development URL
     *
     * @param string $url
     * @return void
     */
    public function setViteDevUrl(string $url): void
    {
        $this->devUrl = $url;
    }

    /**
     * Get full Vite development URL
     *
     * @return string
     */
    public function getViteFullDevUrl(): string
    {
        return "{$this->getViteDevUrl()}:{$this->getViteDevPort()}";
    }

    /**
     * Get dist directory
     *
     * @return string
     */
    public function getDistDir(): string
    {
        return $this->manifest->getDist();
    }

    /**
     * Get hot file name
     *
     * @return string
     */
    public function getHotFileName(): string
    {
        return $this->hotFile;
    }

    /**
     * Set hot file name
     *
     * @param string $hotFile
     * @return void
     */
    public function setHotFileName(string $hotFile): void
    {
        $this->hotFile = $hotFile;
    }

    /**
     * Draw HTML tag
     *
     * @param string|string[] ...$entrypoints
     *
     * @throws EntryPointNotExistsException Invalid entrypoint key
     * @return string
     */
    public function inject(...$entrypoints): string
    {
        $this->resolveAssets();

        $tags = '';
        foreach ($entrypoints as $entrypoint) {
            try {
                // if argument was passed as an array of strings
                // Destructure it into regular string entrypoints
                // `inject(['a', 'b']) ===> inject('a', 'b')`
                if (is_array($entrypoint)) {
                    return $this->inject(...$entrypoint);
                }

                $entry = $this->getAssets()[$entrypoint];
            } catch (\Throwable $th) {
                throw new EntryPointNotExistsException("Entrypoint \"$entrypoint\" not found in manifest file");

                return '';
            }

            $tags .= "\n{$entry->getHtmlTag($this->isDevServerRunning())}";
        }

        return $tags;
    }

    /**
     * Resolve manifest content
     *
     * @return void
     */
    protected function resolveAssets(): void
    {
        $this->setAssets($this->manifest->getAssets());
    }

    /**
     * Determine if dev server is running
     *
     * @return boolean
     */
    public function isDevServerRunning(): bool
    {
        // TODO check test environment
        return file_exists($this->manifest->getRootPath() . $this->getDistDir() . $this->getHotFileName());
    }

    /**
     * Set list of assets
     *
     * @param array $assets
     * @return void
     */
    public function setAssets(array $assets): void
    {
        $this->assets = $assets;

        array_walk($this->assets, function (RawAsset &$asset, string $entrypoint) {
            $asset = $this->resolveEntryType($asset, $entrypoint);
        });
    }

    /**
     * Get list of assets
     *
     * @return Array<string,AssetInterface>
     */
    public function getAssets(): array
    {
        return $this->assets;
    }

    /**
     * Get list of script entrypoints
     *
     * @return Array<string,Script>
     */
    public function getModules(): array
    {
        return $this->modules = array_filter($this->getAssets(), function (AssetInterface $data) {
            return $data instanceof Script;
        });
    }

    /**
     * Get list of vendors
     *
     * @return Array<string,Vendor>
     */
    public function getVendors(): array
    {
        return $this->modules = array_filter($this->getAssets(), function (AssetInterface $data) {
            return $data instanceof Vendor;
        });
    }

    /**
     * Get list of scripts
     *
     * @return Array<string,Script>
     */
    public function getScripts(): array
    {
        return $this->modules = array_merge($this->getModules(), $this->getVendors());
    }

    /**
     * Get list of styles
     *
     * @return Array<string,Style>
     */
    public function getStyles(): array
    {
        return $this->modules = array_filter($this->getAssets(), function (AssetInterface $data) {
            return $data instanceof Style;
        });
    }

    /**
     * Get list of entries
     *
     * @return Array<string,CanBeEntryInterface>
     */
    public function getEntries(): array
    {
        return $this->entries = array_filter(
            $this->getAssets(),
            fn (AssetInterface $asset) => $asset instanceof CanBeEntryInterface && $asset->isEntry()
        );
    }

    /**
     * Add extra data to assets
     *
     * @param object $data
     * @return RawAsset
     */
    protected function addDevServerDataToAsset(RawAsset $data): RawAsset
    {
        $data->setViteData(
            $this->getViteFullDevUrl(),
            $this->getDistDir(),
            $this->manifest->getRootPath(),
            $this->manifest->getRootUri(),
        );

        return $data;
    }

    /**
     * Get asset entry
     *
     * @param string $key
     * @return AssetInterface|null
     */
    public function getAsset(string $key): ?AssetInterface
    {
        if (!isset($this->getAssets()[$key])) {
            return null;
        }

        return $this->getAssets()[$key];
    }

    /**
     * Get asset URL by key name
     *
     * @param string $key
     * @return string|null
     */
    public function getAssetUrl(string $key): ?string
    {
        if (is_null($asset = $this->getAsset($key))) {
            return null;
        }

        return $asset->getDistUrl();
    }

    /**
     * Resolve entry type by its data
     *
     * @param RawAsset $entry
     * @param string $key
     * @return AssetInterface
     */
    protected function resolveEntryType(RawAsset $entry, string $key): AssetInterface
    {
        if ($this->isStyle($key)) {
            return new Style($this->addDevServerDataToAsset($entry));
        }

        if ($this->isVendor($key)) {
            return new Vendor($this->addDevServerDataToAsset($entry));
        }

        // By default we're assuming it's a Script
        return (new Script($this->addDevServerDataToAsset($entry)))->withDependencies($this);
    }

    /**
     * Determine does asset vendor or not
     *
     * @param string $asset
     * @return boolean
     */
    public function isVendor(string $asset): bool
    {
        return $this->isScript($asset) && str_starts_with($asset, '_');
    }

    /**
     * Determine does this asset script or not
     *
     * @param string $asset
     * @return boolean
     */
    public function isScript(string $asset): bool
    {
        return (bool) preg_match('/.js$/i', $asset);
    }

    /**
     * Determine does asset module or not
     *
     * @param string $asset
     * @return boolean
     */
    public function isModule(string $asset): bool
    {
        return $this->isScript($asset) && !$this->isVendor($asset);
    }

    /**
     * Determine does asset style or not
     *
     * @param string $asset
     * @return boolean
     */
    public function isStyle(string $asset): bool
    {
        return (bool) preg_match('/.css$/i', $asset);
    }
}
