<?php

use Wolat\Assets\Manifest;
use Wolat\Assets\RawAsset;
use Wolat\Assets\Script;
use Wolat\Assets\Vite;

beforeEach(function () {
    $asset = new RawAsset();

    $asset->setViteData('http://127.0.0.1:5173', '/dist/', get_template_directory(), get_template_directory_uri());
    $asset->setSrc('resources/js/app.js');
    $asset->setFilePath('js/output.js');

    $this->vite = new Vite(Manifest::load(dirname(__DIR__, 2) . '/stubs/theme', 'dist'));

    $this->script = (new Script($asset))->withDependencies($this->vite);
});

it('asserts script compiles correct HTML tag', function () {
    expect($this->script->getHtmlTag())->toBe(<<<SCRIPT_TAG
    <link href="http://example.com/app/themes/wolat/dist/js/output.js" rel="modulepreload" />
    <script src="http://example.com/app/themes/wolat/dist/js/output.js" crossorigin type="module"></script>
    SCRIPT_TAG);
    expect($this->script->getScriptHtmlTag())->toBe(<<<SCRIPT_TAG
    <link href="http://example.com/app/themes/wolat/dist/js/output.js" rel="modulepreload" />
    <script src="http://example.com/app/themes/wolat/dist/js/output.js" crossorigin type="module"></script>
    SCRIPT_TAG);
});

it('asserts script draws correct HTML tag in development', function () {
    expect($this->script->getHtmlTag(true))->toBe(<<<SCRIPT_TAG
    <script src="http://127.0.0.1:5173/resources/js/app.js" crossorigin type="module"></script>
    SCRIPT_TAG);
    expect($this->script->getScriptHtmlTag(true))->toBe(<<<SCRIPT_TAG
    <script src="http://127.0.0.1:5173/resources/js/app.js" crossorigin type="module"></script>
    SCRIPT_TAG);
});
