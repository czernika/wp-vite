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
        return $this->asset->viteUrl;
    }

    /**
     * Get dist directory
     *
     * @return string
     */
    public function getDistDir(): string
    {
        return $this->asset->distDir;
    }

    /**
     * Get compiled file relative path
     *
     * @return string
     */
    public function getFile(): string
    {
        return $this->asset->file;
    }

    /**
     * Get relative path to `dist` folder
     *
     * @return string
     */
    public function getDist(): string
    {
        return $this->getDistDir() . $this->getFile();
    }

    /**
     * Get absolute path to dist file
     *
     * @return string
     */
    public function getAbsoluteDistPath(): string
    {
        return wp_normalize_path(get_template_directory() . $this->getDist());
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
            return $this->getViteFullDevUrl() . $this->getDist();
        }

        return $this->getNonDevUrl() . $this->getDist();
    }
}
