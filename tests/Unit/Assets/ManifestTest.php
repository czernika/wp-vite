<?php

use Wolat\Assets\Manifest;
use Wolat\Assets\RawAsset;

beforeEach(function () {
    $this->path = dirname(__DIR__, 2) . '/stubs/theme/dist/';

    $this->manifest = Manifest::load(dirname(__DIR__, 2) . '/stubs/theme', 'dist', 'assets.json');
});

it('asserts manifest can get its filename if it was passed', function () {
    expect($this->manifest->getName())->toBe('assets.json');
});

it('asserts manifest file can configure its file path', function () {
    expect($this->manifest->getFullPath())->toBe($this->path . 'assets.json');
});

it('asserts manifest loads assets from file and coverts raw data into raw assets instances', function () {
    // There are 5 entires within mock file
    expect(count($this->manifest->getAssets()))->toBe(1);
    expect($this->manifest->getAssets()['resources/js/app.js'])->toBeInstanceOf(RawAsset::class);
});
