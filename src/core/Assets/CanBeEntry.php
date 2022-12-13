<?php

declare(strict_types=1);

namespace Wolat\Assets;

trait CanBeEntry
{
    /**
     * @inheritDoc
     */
    public function getSrc(): string
    {
        return $this->asset->getSrc();
    }

    /**
     * Get full absolute path to source file
     *
     * @return string
     */
    public function getAbsoluteSrcPath(): string
    {
        return wp_normalize_path(get_template_directory() . DIRECTORY_SEPARATOR . $this->getSrc());
    }

    /**
     * @inheritDoc
     */
    public function getEntryName(): string
    {
        // Get last part after `/` sign
        // This can help during WordPress script registration
        $lastPart = substr($this->getSrc(), strrpos($this->getSrc(), '/') + 1);
        return str_replace('.', '-', $lastPart);
    }

    /**
     * @inheritDoc
     */
    public function isEntry(): bool
    {
        return $this->asset->isEntry();
    }
}
