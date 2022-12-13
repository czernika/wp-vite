<?php

use Wolat\Assets\RawAsset;
use Wolat\Assets\Style;

beforeEach(function () {
    $asset = new RawAsset();

    $asset->setViteData('http://localhost:5173', '/dist/');
    $asset->setFilePath('css/output.css');

    $this->style = new Style($asset);
});

it('asserts entrypoint resolves correct full development url', function () {
    expect($this->style->getViteFullDevUrl())->toBe('http://localhost:5173');
});

it('asserts entrypoint resolves correct dist directory name', function () {
    expect($this->style->getDistDir())->toBe('/dist/');
});

it('asserts entrypoint resolves correct relative output file path', function () {
    expect($this->style->getFilePath())->toBe('css/output.css');
});

it('asserts entrypoint resolves correct relative output file path from dist directory', function () {
    expect($this->style->getDistFilePath())->toBe('/dist/css/output.css');
    expect($this->style->getAbsoluteDistFilePath())->toBe('path/to/theme/dist/css/output.css');
});

it('asserts entrypoint resolves correct dist URL data depends on environment', function () {
    // Development
    expect($this->style->getDistUrl(true))->toBe('http://localhost:5173/dist/css/output.css');

    // Production
    expect($this->style->getDistUrl())->toBe('http://example.com/app/themes/wolat/dist/css/output.css');
});
