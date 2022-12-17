<?php

use Wolat\Assets\RawAsset;
use Wolat\Assets\Style;

beforeEach(function () {

    $asset = new RawAsset();

    $asset->setViteData('http://127.0.0.1:5173', '/dist/');
    $asset->setSrc('resources/css/app.css');
    $asset->setFilePath('css/output.css');

    $this->style = new Style($asset);
});

it('asserts style draws correct HTML tag', function () {
    expect($this->style->getHtmlTag())->toBe('<link href="http://example.com/app/themes/wolat/dist/css/output.css" rel="stylesheet" />');
});

it('asserts style draws correct HTML tag in development', function () {
    expect($this->style->getHtmlTag(true))->toBe('<link href="http://127.0.0.1:5173/resources/css/app.css" rel="stylesheet" />');
});
