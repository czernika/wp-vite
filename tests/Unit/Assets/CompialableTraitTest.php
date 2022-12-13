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

it('asserts style recieves correct data about dist', function () {
    expect($this->style->getViteFullDevUrl())->toBe('http://localhost:5173');
    expect($this->style->getDistDir())->toBe('/dist/');

    expect($this->style->getFile())->toBe('css/output.css');
    expect($this->style->getDist())->toBe('/dist/css/output.css');
    expect($this->style->getAbsoluteDistPath())->toBe('path/to/theme/dist/css/output.css');

    expect($this->style->getDistUrl(true))->toBe('http://localhost:5173/dist/css/output.css');
    expect($this->style->getDistUrl())->toBe('http://example.com/app/themes/wolat/dist/css/output.css');
});
