<?php

use Wolat\Assets\RawAsset;
use Wolat\Assets\Style;

beforeEach(function () {
    $asset = new RawAsset();

    $asset->setViteData('http://127.0.0.1:5173', '/dist/', get_template_directory(), get_template_directory_uri());
    $asset->setSrc('resources/css/common.css');
    $asset->setAsEntry(true);

    $this->style = new Style($asset);
});

it('asserts entrypoint resolves correct source', function () {
    expect($this->style->getSrc())->toBe('resources/css/common.css');
});

it('asserts entrypoint resolves correct full source path', function () {
    expect($this->style->getAbsoluteSrcPath())->toBe('path/to/theme/resources/css/common.css');
});

it('asserts entrypoint resolves correct full source entry name', function () {
    expect($this->style->getEntryName())->toBe('common-css');
});

it('asserts entrypoint determines if it is entrypoint or not', function () {
    expect($this->style->isEntry())->toBeTrue();
});
