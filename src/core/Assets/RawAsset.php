<?php

declare(strict_types=1);

namespace Wolat\Assets;

class RawAsset
{
    /**
     * Source name
     *
     * @var string|null
     */
    public ?string $src = null;

    /**
     * Output file name
     *
     * @var string
     */
    public string $file;

    /**
     * Determine if asset was entrypoint
     *
     * @var integer|null
     */
    public ?int $isEntry = null;

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
}
