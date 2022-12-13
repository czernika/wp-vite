<?php

declare(strict_types=1);

namespace Wolat\Assets;

use Wolat\Assets\Interfaces\AssetInterface;
use Wolat\Assets\Interfaces\CanBeEntryInterface;

class Script implements AssetInterface, CanBeEntryInterface
{
    use Compilable, CanBeEntry;

    /**
     * Vite assets
     *
     * @var Vite
     */
    protected Vite $vite;

    public function __construct(protected RawAsset $asset)
    {
    }

    /**
     * Set Script dependencies with Vite
     *
     * @param Vite $vite
     * @return static
     */
    public function withDependencies(Vite $vite): static
    {
        $this->vite = $vite;
        return $this;
    }

    /**
     * Get HTML tag for script file itself
     *
     * @param boolean $isDev
     * @return string
     */
    public function getScriptHtmlTag(bool $isDev = false): string
    {
        return "<script src=\"{$this->getDistUrl($isDev)}\" crossorigin type=\"module\"></script>";
    }

    /**
     * Determine if script has CSS dependencies
     *
     * @return boolean
     */
    public function hasCssDeps(): bool
    {
        return !empty($this->asset->getCssDeps());
    }

    /**
     * Determine if script has vendor dependencies
     *
     * @return boolean
     */
    public function hasVendorDeps(): bool
    {
        return !empty($this->asset->getVendorDeps());
    }

    /**
     * Get raw list of entrypoints
     *
     * @return array
     */
    public function getRawCssDeps(): array
    {
        if (!$this->hasCssDeps()) {
            return [];
        }

        return $this->asset->getCssDeps();
    }

    /**
     * Get list of CSS dependencies
     *
     * @return Array<string, Style>
     */
    public function getCssDeps(): array
    {
        if (!$this->hasCssDeps()) {
            return [];
        }

        return array_map(function (string $key) {
            $assets = array_filter($this->vite->getStyles(), fn (Style $css) => $key === $css->getFilePath());

            // We know there can be only one entry
            $entrypoint = array_keys($assets)[0];

            return $assets[$entrypoint];
        }, $this->getRawCssDeps());
    }

    /**
     * Get raw list of entrypoints
     *
     * @return array
     */
    public function getRawVendorDeps(): array
    {
        if (!$this->hasVendorDeps() || !isset($this->vite)) {
            return [];
        }

        return $this->asset->getVendorDeps();
    }

    /**
     * Get list of vendor dependencies
     *
     * @return Array<string, Vendor>
     */
    public function getVendorDeps(): array
    {
        if (!$this->hasVendorDeps() || !isset($this->vite)) {
            return [];
        }

        return array_map(function (string $key) {
            return $this->vite->getVendors()[$key];
        }, $this->getRawVendorDeps());
    }

    /**
     * @inheritDoc
     */
    public function getHtmlTag(bool $isDev = false): string
    {
        $html = $this->getScriptHtmlTag($isDev);

        foreach ($this->getVendorDeps() as $vendor) {
            $html .= "\n{$vendor->getHtmlTag($isDev)}";
        }

        foreach ($this->getCssDeps() as $css) {
            $html .= "\n{$css->getHtmlTag($isDev)}";
        }

        return $html;
    }
}
