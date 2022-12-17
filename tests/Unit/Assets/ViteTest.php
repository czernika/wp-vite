<?php

use Wolat\Assets\Exceptions\EntryPointNotExistsException;
use Wolat\Assets\Vite;
use Wolat\Assets\Manifest;

beforeEach(function () {
    $this->manifest = Manifest::load(dirname(__DIR__, 2) . '/stubs/theme/dist/');

    $this->loader = new Vite($this->manifest);
});

it('asserts vite has initial data about development url', function () {
    expect($this->loader->getViteDevPort())->toBe(5173);
    expect($this->loader->getViteDevUrl())->toBe('http://127.0.0.1');
    expect($this->loader->getViteFullDevUrl())->toBe('http://127.0.0.1:5173');
});

it('asserts vite has initial data about dist directory', function () {
    expect($this->loader->getDistDir())->toBe('/dist/');
});

it('asserts vite has initial data about hot file', function () {
    expect($this->loader->getHotFileName())->toBe('hot');
});

it('asserts loader accepts correct manifest assets', function () {
    expect(count($this->loader->getAssets()))->toBe(5);
});

it('asserts loader sorts correctly by types', function () {
    expect(count($this->loader->getModules()))->toBe(2);
    expect(count($this->loader->getVendors()))->toBe(1);
    expect(count($this->loader->getScripts()))->toBe(3);
    expect(count($this->loader->getStyles()))->toBe(2);
    expect(count($this->loader->getEntries()))->toBe(3);
});

it('assert script entrypoint object resolved correctly from manifest assets', function ($entry, $isEntry, $src, $name, $dist, $url, $html, $fullHtml) {
    $scripts = $this->loader->getScripts();
    $script = $scripts[$entry];

    expect($script->isEntry())->toBe($isEntry);
    expect($script->getSrc())->toBe($src);
    expect($script->getEntryName())->toBe($name);

    expect($script->getFilePath())->toBe($dist);
    expect($script->getDistUrl())->toBe($url);

    expect($script->getScriptHtmlTag())->toBe($html);
    expect($script->getHtmlTag())->toBe($fullHtml);
})->with([
    ['resources/js/common.js', true, 'resources/js/common.js', 'common-js', 'js/common.9b86745c.js', 'http://example.com/app/themes/wolat/dist/js/common.9b86745c.js', '<script src="http://example.com/app/themes/wolat/dist/js/common.9b86745c.js" crossorigin type="module"></script>', <<<SCRIPT_TAG
    <script src="http://example.com/app/themes/wolat/dist/js/common.9b86745c.js" crossorigin type="module"></script>
    <link href="http://example.com/app/themes/wolat/dist/js/chunks/vendor.641c6ca9.js" rel="modulepreload" />
    <link href="http://example.com/app/themes/wolat/dist/css/common.e82aa1ab.css" rel="stylesheet" />
    SCRIPT_TAG],
    ['resources/js/app.js', true, 'resources/js/app.js', 'app-js', 'js/app.c08c6dc9.js', 'http://example.com/app/themes/wolat/dist/js/app.c08c6dc9.js', '<script src="http://example.com/app/themes/wolat/dist/js/app.c08c6dc9.js" crossorigin type="module"></script>', <<<SCRIPT_TAG
    <script src="http://example.com/app/themes/wolat/dist/js/app.c08c6dc9.js" crossorigin type="module"></script>
    SCRIPT_TAG],
]);

it('asserts tags were injected', function ($entrypoints, $html) {
    $injected = $this->loader->inject(...$entrypoints);

    expect($injected)->toBe($html);
})->with([
    [['resources/js/common.js'], <<<SCRIPT_TAG

    <script src="http://example.com/app/themes/wolat/dist/js/common.9b86745c.js" crossorigin type="module"></script>
    <link href="http://example.com/app/themes/wolat/dist/js/chunks/vendor.641c6ca9.js" rel="modulepreload" />
    <link href="http://example.com/app/themes/wolat/dist/css/common.e82aa1ab.css" rel="stylesheet" />
    SCRIPT_TAG],
    [['resources/js/common.css'], "\n<link href=\"http://example.com/app/themes/wolat/dist/css/common.e82aa1ab.css\" rel=\"stylesheet\" />"],
    [['resources/js/common.js', 'resources/css/app.css'], <<<SCRIPT_TAG

    <script src="http://example.com/app/themes/wolat/dist/js/common.9b86745c.js" crossorigin type="module"></script>
    <link href="http://example.com/app/themes/wolat/dist/js/chunks/vendor.641c6ca9.js" rel="modulepreload" />
    <link href="http://example.com/app/themes/wolat/dist/css/common.e82aa1ab.css" rel="stylesheet" />
    <link href="http://example.com/app/themes/wolat/dist/css/app.98fde70f.css" rel="stylesheet" />
    SCRIPT_TAG],
    [[['resources/js/common.js', 'resources/css/app.css']], <<<SCRIPT_TAG

    <script src="http://example.com/app/themes/wolat/dist/js/common.9b86745c.js" crossorigin type="module"></script>
    <link href="http://example.com/app/themes/wolat/dist/js/chunks/vendor.641c6ca9.js" rel="modulepreload" />
    <link href="http://example.com/app/themes/wolat/dist/css/common.e82aa1ab.css" rel="stylesheet" />
    <link href="http://example.com/app/themes/wolat/dist/css/app.98fde70f.css" rel="stylesheet" />
    SCRIPT_TAG],
]);

it('asserts vite may recognize if entrypoint is not exists', function () {
    $this->loader->inject('not/existing');
})->throws(EntryPointNotExistsException::class, 'Entrypoint "not/existing" not found in manifest file');

it('asserts vite loader gives correct url per entry', function () {
    expect($this->loader->getAssetUrl('resources/js/common.js'))->toBe('http://example.com/app/themes/wolat/dist/js/common.9b86745c.js');
});
