<?php

declare(strict_types=1);

namespace Wolat\Assets;

trait Compilable
{
    /**
     * Get full Vite development URL
     *
     * @return string
     */
    public function getViteFullDevUrl(): string
    {
        return $this->asset->getViteUrl();
    }

    /**
     * Get dist directory name
     *
     * @return string
     */
    public function getDistDir(): string
    {
        return $this->asset->getDistDirName();
    }

    /**
     * Get compiled file relative path to dist directory
     *
     * @return string
     */
    public function getFilePath(): string
    {
        return $this->asset->getFilePath();
    }

    /**
     * Get relative path to theme included dist directory
     *
     * @return string
     */
    public function getDistFilePath(): string
    {
        return $this->getDistDir() . $this->getFilePath();
    }

    /**
     * Get absolute path to output file
     *
     * @return string
     */
    public function getAbsoluteDistFilePath(): string
    {
        return wp_normalize_path(get_template_directory() . $this->getDistFilePath());
    }

    /**
     * Get asset URL when hot file is not present
     *
     * @return string
     */
    protected function getNonDevUrl(): string
    {
        return get_template_directory_uri();
    }

    /**
     * @inheritDoc
     */
    public function getDistUrl(bool $isDev = false): string
    {
        if ($isDev) {
            return $this->getViteFullDevUrl() . $this->getDistFilePath();
        }

        return $this->getNonDevUrl() . $this->getDistFilePath();
    }
}
