<?php

use Wolat\Assets\RawAsset;
use Wolat\Assets\Style;

beforeEach(function () {
    $data = new RawAsset();

    $data->src = 'resources/css/common.css';
    $data->isEntry = 1;

    $this->style = new Style($data);
});

it('asserts style recieves correct data about entrypoint', function () {
    expect($this->style->getSrc())->toBe('resources/css/common.css');
    expect($this->style->getAbsoluteSrcPath())->toBe('path/to/theme/resources/css/common.css');
    expect($this->style->getEntryName())->toBe('common-css');
    expect($this->style->isEntry())->toBeTrue();
});
