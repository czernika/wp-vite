<?php

use Wolat\Assets\Manifest;
use Wolat\Assets\Exceptions\ManifestFileNotFoundException;

it('asserts manifest throws exception if manifest file is not exists', function () {
    Manifest::load('/path/to/manifest', 'dist');
})->throws(ManifestFileNotFoundException::class, 'Manifest file not found at "/path/to/manifest/dist/manifest.json". Check file name and its path');
