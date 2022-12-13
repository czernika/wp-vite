<?php

declare(strict_types=1);

namespace Wolat\Assets\Interfaces;

interface AssetInterface
{
    /**
     * Get entry HTML tag
     *
     * @param boolean $isDev
     * @return string
     */
    public function getHtmlTag(bool $isDev = false): string;

    /**
     * Get compiled file URL
     *
     * @param boolean $isDev
     * @return string
     */
    public function getDistUrl(bool $isDev = false): string;
}
