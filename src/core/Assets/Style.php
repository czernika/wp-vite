<?php

declare(strict_types=1);

namespace Wolat\Assets;

use Wolat\Assets\Interfaces\AssetInterface;
use Wolat\Assets\Interfaces\CanBeEntryInterface;

class Style implements AssetInterface, CanBeEntryInterface
{
    use Compilable, CanBeEntry;

    public function __construct(protected RawAsset $asset)
    {
    }

    /**
     * @inheritDoc
     */
    public function getHtmlTag(bool $isDev = false): string
    {
        return "<link rel=\"preload\" as=\"style\" href=\"{$this->getDistUrl($isDev)}\" />\n<link href=\"{$this->getDistUrl($isDev)}\" rel=\"stylesheet\" />";
    }
}
