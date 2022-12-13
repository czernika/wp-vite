<?php

declare(strict_types=1);

namespace Wolat\Assets;

use Wolat\Assets\Interfaces\AssetInterface;
use Wolat\Assets\Interfaces\CanBeEntryInterface;

class Script implements AssetInterface, CanBeEntryInterface
{
    use Compilable, CanBeEntry;

    public function __construct(protected Vite $vite, protected RawAsset $asset)
    {
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
        return isset($this->asset->css);
    }

    /**
     * Determine if script has vendor dependencies
     *
     * @return boolean
     */
    public function hasVednorDeps(): bool
    {
        return isset($this->asset->imports);
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

        return $this->asset->css;
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
            $assets = array_filter($this->vite->getStyles(), fn (Style $css) => $key === $css->getFile());

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
        if (!$this->hasVednorDeps()) {
            return [];
        }

        return $this->asset->imports;
    }

    /**
     * Get list of vendor dependencies
     *
     * @return Array<string, Vendor>
     */
    public function getVendorDeps(): array
    {
        if (!$this->hasVednorDeps()) {
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
