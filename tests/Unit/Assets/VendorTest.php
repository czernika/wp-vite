<?php

use Wolat\Assets\RawAsset;
use Wolat\Assets\Vendor;

beforeEach(function () {
    $asset = new RawAsset();

    $asset->setViteData('http://127.0.0.1:5173', '/dist/');
    $asset->setSrc('resources/js/part.js');
    $asset->setFilePath('js/chunks/vendor.js');

    $this->vendor = new Vendor($asset);
});

it('asserts vendor draws correct HTML tag', function () {
    expect($this->vendor->getHtmlTag())->toBe('<link href="http://example.com/app/themes/wolat/dist/js/chunks/vendor.js" rel="modulepreload" />');
});

it('asserts vendor draws correct HTML tag in development', function () {
    expect($this->vendor->getHtmlTag(true))->toBe('<link href="http://127.0.0.1:5173/resources/js/part.js" rel="modulepreload" />');
});
