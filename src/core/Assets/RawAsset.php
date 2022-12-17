<?php

declare(strict_types=1);

namespace Wolat\Assets;

class RawAsset
{
    /**
     * Source file name
     *
     * @var string|null
     */
    protected ?string $src = null;

    /**
     * Output file name
     *
     * @var string
     */
    protected string $file;

    /**
     * Determine if asset is entrypoint
     *
     * @var bool|null
     */
    protected ?bool $isEntry = null;

    /**
     * Vendor dependencies
     *
     * @var array|null
     */
    public ?array $imports = [];

    /**
     * CSS dependencies
     *
     * @var array|null
     */
    public ?array $css = [];

    /**
     * Full Vite development URL
     *
     * @var string
     */
    protected string $url;

    /**
     * Dist directory name (with slashes)
     *
     * @var string
     */
    protected string $dist;

    /**
     * Set extra data about environment
     *
     * @param string $url
     * @param string $dist
     * @return void
     */
    public function setViteData(string $url, string $dist): void
    {
        $this->url = $url;
        $this->dist = $dist;
    }

    /**
     * Get Vite development URL
     *
     * @return string
     */
    public function getViteUrl(): string
    {
        return $this->url;
    }

    /**
     * Get dist directory name
     *
     * @return string
     */
    public function getDistDirName(): string
    {
        return $this->dist;
    }

    /**
     * Get source path
     *
     * @return string|null
     */
    public function getSrc(): ?string
    {
        return $this->src;
    }

    /**
     * Set source path
     *
     * @param string $src
     * @return void
     */
    public function setSrc(string $src): void
    {
        $this->src = $src;
    }

    /**
     * Set asset as entrypoint
     *
     * @param boolean $isEntry
     * @return void
     */
    public function setAsEntry(bool $isEntry = true): void
    {
        $this->isEntry = $isEntry;
    }

    /**
     * Determine is it entry or not
     *
     * @return boolean
     */
    public function isEntry(): bool
    {
        return (bool) $this->isEntry;
    }

    /**
     * Get relative file path
     *
     * @return string
     */
    public function getFilePath(): string
    {
        return $this->file;
    }

    /**
     * Set relative file path
     *
     * @param string $path
     * @return void
     */
    public function setFilePath(string $path): void
    {
        $this->file = $path;
    }

    /**
     * Get list of CSS dependencies
     *
     * @return array
     */
    public function getCssDeps(): array
    {
        return $this->css;
    }

    /**
     * Get list of Vendor dependencies
     *
     * @return array
     */
    public function getVendorDeps(): array
    {
        return $this->imports;
    }
}
