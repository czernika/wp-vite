<?php

use Wolat\Assets\RawAsset;

beforeEach(function () {
    $this->asset = new RawAsset();

    $this->asset->setViteData('http://127.0.0.1:5173', '/dist/', get_template_directory(), get_template_directory_uri());
    $this->asset->setSrc('src/file.css');
    $this->asset->setAsEntry(true);
    $this->asset->setFilePath('output/file.css');
});

it('asserts raw asset resolves provided vite data', function () {
    expect($this->asset->getViteUrl())->toBe('http://127.0.0.1:5173');
    expect($this->asset->getDistDirName())->toBe('/dist/');
});

it('asserts raw asset may set entrypoint type', function () {
    expect($this->asset->isEntry())->toBeTrue();
});

it('asserts raw asset may resolve output file path', function () {
    expect($this->asset->getFilePath())->toBe('output/file.css');
});
