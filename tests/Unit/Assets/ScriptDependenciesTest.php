<?php

use Wolat\Assets\Manifest;
use Wolat\Assets\Style;
use Wolat\Assets\Vendor;
use Wolat\Assets\Vite;

beforeEach(function () {
    $this->vite = new Vite(Manifest::load(dirname(__DIR__, 2) . '/stubs/theme', 'dist'));

    $this->vite->inject('resources/js/common.js');

    $this->script = $this->vite->getScripts()['resources/js/common.js'];
});

it('asserts script resolves CSS dependencies', function () {
    expect($this->script->hasCssDeps())->toBeTrue();

    $cssDeps = $this->script->getCssDeps();
    expect(count($cssDeps))->toBe(1);
    expect($cssDeps[0])->toBeInstanceOf(Style::class);
});

it('asserts script resolves vendor dependencies', function () {
    expect($this->script->hasVendorDeps())->toBeTrue();

    $vendorDeps = $this->script->getVendorDeps();
    expect(count($vendorDeps))->toBe(1);
    expect($vendorDeps[0])->toBeInstanceOf(Vendor::class);
});

it('asserts script draws correct full HTML tag including its dependencies', function () {
    expect($this->script->getHtmlTag())->toBe(<<<SCRIPT_TAG
    <link href="http://example.com/app/themes/wolat/dist/js/common.9b86745c.js" rel="modulepreload" />
    <script src="http://example.com/app/themes/wolat/dist/js/common.9b86745c.js" crossorigin type="module"></script>
    <link href="http://example.com/app/themes/wolat/dist/js/chunks/vendor.641c6ca9.js" rel="modulepreload" />
    <link rel="preload" as="style" href="http://example.com/app/themes/wolat/dist/css/common.e82aa1ab.css" />
    <link href="http://example.com/app/themes/wolat/dist/css/common.e82aa1ab.css" rel="stylesheet" />
    SCRIPT_TAG);
});
