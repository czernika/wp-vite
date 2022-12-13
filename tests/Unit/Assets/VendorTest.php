<?php

use Wolat\Assets\RawAsset;
use Wolat\Assets\Vendor;

beforeEach(function () {
    $data = new RawAsset();

    $data->viteUrl = 'http://localhost:5173';
    $data->distDir = '/dist/';
    $data->file = 'js/chunks/vendor.js';

    $this->vendor = new Vendor($data);
});

it('asserts vendor compiles correct HTML tag', function () {
    expect($this->vendor->getHtmlTag())->toBe('<link href="http://example.com/app/themes/wolat/dist/js/chunks/vendor.js" rel="modulepreload" />');
});
