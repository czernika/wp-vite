<?php

use Wolat\Assets\Manifest;

beforeEach(function () {
    $this->path = dirname(__DIR__, 2) . '/stubs/theme/dist/';
    $this->manifest = Manifest::load($this->path);
});

it('asserts manifest file can configure its file path', function () {
    expect($this->manifest->getFullPath())->toBe($this->path . 'manifest.json');
});

it('asserts manifest can change its filename', function () {
    $this->manifest->setName('assets.json');

    expect($this->manifest->getName())->toBe('assets.json');
    expect($this->manifest->getFullPath())->toBe($this->path . 'assets.json');
});

it('asserts manifest loads correct assets', function () {
    // There are 5 entires within mock file
    expect(count($this->manifest->getAssets()))->toBe(5);
});
