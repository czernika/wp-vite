<?php

declare(strict_types=1);

namespace Wolat\Assets;

use Wolat\Assets\Interfaces\AssetInterface;

class Vendor implements AssetInterface
{
    use Compilable;

    public function __construct(protected RawAsset $asset)
    {
    }

    /**
     * @inheritDoc
     */
    public function getHtmlTag(bool $isDev = false): string
    {
        return "<link href=\"{$this->getDistUrl($isDev)}\" rel=\"modulepreload\" />";
    }
}
