<?php

use Wolat\Assets\Manifest;
use Wolat\Assets\RawAsset;

beforeEach(function () {
    $this->path = dirname(__DIR__, 2) . '/stubs/theme/dist/';

    $this->manifest = Manifest::load($this->path);
});

it('asserts manifest can change its filename', function () {
    expect($this->manifest->getName())->toBe('manifest.json');

    $this->manifest->setName('assets.json');

    expect($this->manifest->getName())->toBe('assets.json');
    expect($this->manifest->getFullPath())->toBe($this->path . 'assets.json');
});

it('asserts manifest file can configure its file path', function () {
    // Manifest file was previously changed
    expect($this->manifest->getFullPath())->toBe($this->path . 'assets.json');
});

it('asserts manifest loads assets from file and coverts raw data into raw assets instances', function () {
    // There are 5 entires within mock file
    expect(count($this->manifest->getAssets()))->toBe(5);
    expect($this->manifest->getAssets()['resources/js/common.js'])->toBeInstanceOf(RawAsset::class);
});
