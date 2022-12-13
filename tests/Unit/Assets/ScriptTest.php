<?php

use Wolat\Assets\Manifest;
use Wolat\Assets\RawAsset;
use Wolat\Assets\Script;
use Wolat\Assets\Vite;

beforeEach(function () {
    $asset = new RawAsset();

    $asset->setViteData('http://localhost:5173', '/dist/');
    $asset->setFilePath('js/output.js');

    $this->vite = new Vite(Manifest::load(dirname(__DIR__, 2) . '/stubs/theme/dist/'));

    $this->script = (new Script($asset))->withDependencies($this->vite);
});

it('asserts script compiles correct HTML tag', function () {
    expect($this->script->getHtmlTag())->toBe('<script src="http://example.com/app/themes/wolat/dist/js/output.js" crossorigin type="module"></script>');
    expect($this->script->getScriptHtmlTag())->toBe('<script src="http://example.com/app/themes/wolat/dist/js/output.js" crossorigin type="module"></script>');
});

it('asserts script draws correct HTML tag in development', function () {
    expect($this->script->getHtmlTag(true))->toBe('<script src="http://localhost:5173/dist/js/output.js" crossorigin type="module"></script>');
    expect($this->script->getScriptHtmlTag(true))->toBe('<script src="http://localhost:5173/dist/js/output.js" crossorigin type="module"></script>');
});
