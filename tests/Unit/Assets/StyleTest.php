<?php

use Wolat\Assets\RawAsset;
use Wolat\Assets\Style;

beforeEach(function () {
    $data = new RawAsset();

    $data->viteUrl = 'http://localhost:5173';
    $data->distDir = '/dist/';
    $data->file = 'css/output.css';

    $this->style = new Style($data);
});

it('asserts style compiles correct HTML tag', function () {
    expect($this->style->getHtmlTag())->toBe('<link href="http://example.com/app/themes/wolat/dist/css/output.css" rel="stylesheet" />');
});
