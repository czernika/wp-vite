<?php

use Wolat\Assets\Manifest;
use Wolat\Assets\RawAsset;
use Wolat\Assets\Script;
use Wolat\Assets\Style;
use Wolat\Assets\Vendor;
use Wolat\Assets\Vite;

beforeEach(function () {
    $data = new RawAsset();

    $data->distDir = '/dist/';
    $data->file = 'js/output.js';

    $this->vite = new Vite(Manifest::load(dirname(__DIR__, 2) . '/stubs/theme/dist/'));

    $this->script = new Script($this->vite, $data);
});

it('asserts script compiles correct HTML tag', function () {
    expect($this->script->getHtmlTag())->toBe('<script src="http://example.com/app/themes/wolat/dist/js/output.js" crossorigin type="module"></script>');
});

it('asserts script resolves dependencies', function () {
    $script = $this->vite->getScripts()['resources/js/common.js'];

    $cssDeps = $script->getCssDeps();
    $vendorDeps = $script->getVendorDeps();

    expect(count($cssDeps))->toBe(1);
    expect($cssDeps[0])->toBeInstanceOf(Style::class);
    expect($cssDeps[0]->getDistUrl())->toBe('http://example.com/app/themes/wolat/dist/css/common.e82aa1ab.css');

    expect(count($vendorDeps))->toBe(1);
    expect($vendorDeps[0])->toBeInstanceOf(Vendor::class);
    expect($vendorDeps[0]->getDistUrl())->toBe('http://example.com/app/themes/wolat/dist/js/chunks/vendor.641c6ca9.js');
});
