<?php

use Wolat\Assets\RawAsset;
use Wolat\Assets\Vendor;

beforeEach(function () {
    $asset = new RawAsset();

    $asset->setViteData('http://localhost:5173', '/dist/');
    $asset->setFilePath('js/chunks/vendor.js');

    $this->vendor = new Vendor($asset);
});

it('asserts vendor draws correct HTML tag', function () {
    expect($this->vendor->getHtmlTag())->toBe('<link href="http://example.com/app/themes/wolat/dist/js/chunks/vendor.js" rel="modulepreload" />');
});

it('asserts vendor draws correct HTML tag in development', function () {
    expect($this->vendor->getHtmlTag(true))->toBe('<link href="http://localhost:5173/dist/js/chunks/vendor.js" rel="modulepreload" />');
});
