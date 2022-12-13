<?php

declare(strict_types=1);

namespace Wolat\Assets\Interfaces;

interface CanBeEntryInterface
{
    /**
     * Check if asset is entrypoint or not
     *
     * @return boolean
     */
    public function isEntry(): bool;

    /**
     * Get asset entry name
     *
     * @return string
     */
    public function getEntryName(): string;

    /**
     * Get full path to source file
     *
     * @return string
     */
    public function getSrc(): string;
}
