# WordPress Vite Assets Loader

Allows to inject assets tags into pages using [Vite](https://vitejs.dev/) tool.

Works in pair with [Vite plugin](https://github.com/czernika/wp-vite-plugin)

[![Running Unit Tests](https://github.com/czernika/wp-vite/actions/workflows/tests.yml/badge.svg)](https://github.com/czernika/wp-vite/actions/workflows/tests.yml) [![Latest Tag](https://img.shields.io/github/v/tag/czernika/wp-vite)](https://github.com/czernika/wp-vite/releases)

## Installation

> In progress

For now may be resolved as [Composer](https://getcomposer.org/) dependency within `composer.json`

```json
"repositories": [
    {
        "type": "vcs",
        "url":  "git@github.com:czernika/wp-vite.git"
    }
],
"require": {
    "czernika/wp-vite": "dev-master"
},
```

## Usage

You should pre-install [WordPress Vite plugin](https://github.com/czernika/wp-vite-plugin) and configure `vite.config.js`

1. Create new `Wolat\Assets\Manifest` instance with path to compiled `manifest.json` file

> NOTE: manifest file SHOULD exists. Run `npm run build` in order to generate it

Typical generated manifest file with all dependencies should look like

```json
{
    "resources/js/common.css": {
        "file": "css/common.e82aa1ab.css",
        "src": "resources/js/common.css"
    },
    "resources/css/app.css": {
        "file": "css/app.98fde70f.css",
        "src": "resources/css/app.css",
        "isEntry": true
    },
    "resources/js/app.js": {
        "file": "js/app.c08c6dc9.js",
        "src": "resources/js/app.js",
        "isEntry": true
    },
    "resources/js/common.js": {
        "file": "js/common.9b86745c.js",
        "src": "resources/js/common.js",
        "isEntry": true,
        "imports": [
            "_vendor.641c6ca9.js"
        ],
        "css": [
            "css/common.e82aa1ab.css"
        ]
    },
    "_vendor.641c6ca9.js": {
        "file": "js/chunks/vendor.641c6ca9.js"
    }
}
```

1. Create new `Wolat\Assets\Vite` with `Manifest` dependency
2. Inject input entrypoints defined in `vite.config.js`

### Example

```js
// vite.config.js
import { defineConfig } from 'vite'
import wordPressWolat from 'wordpress-wolat'

export default defineConfig({
	plugins: [
		wordPressWolat({
            theme: 'web/app/themes/my-theme',
            input: 'resources/js/app.js',
        }),
	]
})
```

```php
use Wolat\Assets\Manifest;
use Wolat\Assets\Vite;

// No need to add manifest name at the end like `path/to/manifest.json` - only `path/to`
$manifest = Manifest::load(get_template_directory() . DIRECTORY_SEPARATOR . 'dist');

$vite = new Vite($manifest);

echo $vite->inject('resources/js/common.js');
```

Inject method will resolve required assets and all its dependencies depends on environment type and inject appropriate tags into HTML (where inject method being called)

### Dist directory

You may change `dist` directory with before output. Note - manifest file depends on dist directory, so it should be changed also

```js
// vite.config.js
plugins: [
    wordPressWolat({
        theme: 'web/app/themes/my-theme',
        input: 'resources/js/app.js',

        outDir: 'build', // here
    }),
]
```

```php
// Manifest dir should be changed also
$manifest = Manifest::load(get_template_directory() . DIRECTORY_SEPARATOR . 'new/dist');

$vite = new Vite($manifest);
$vite->setDistDir('/new/dist/'); // new placement should be wrapped within slashes

$html = $vite->inject('resources/js/common.js');
```

### Hot file

During development it requires special `hot` file in dist directory in order to resolve development and non-development environment. Name of this file can be changed with

```js
// vite.config.js
plugins: [
    wordPressWolat({
        theme: 'web/app/themes/my-theme',
        input: 'resources/js/app.js',

        hot: 'newhot', // here
    }),
]
```

```php
$vite->setHotFileName('newhot');
```

### Manifest file

If you need to change manifest file name you may pass second argument for `Manifest` object

```js
// vite.config.js
plugins: [
    wordPressWolat({
        theme: 'web/app/themes/my-theme',
        input: 'resources/js/app.js',

        manifest: 'assets.json', // here
    }),
]
```

```php
$manifest = Manifest::load(get_template_directory() . DIRECTORY_SEPARATOR . 'dist', 'assets.json');
```

### Changing dev server url and port

If you need to change port and url in your `vite.config.js`

```js
// vite.config.js
import { defineConfig } from 'vite'
import wordPressWolat from 'wordpress-wolat'

// https://vitejs.dev/config/
export default defineConfig({

    server: {
        port: 5555,
        host: 'some.new.host',
    },

	plugins: [
		wordPressWolat({
            theme: 'web/app/themes/my-theme',
            input: 'resources/js/app.js',
        }),
	]
})
```

You should change `Vite` settings for that

```php
$vite->setViteDevPort(5555);
$vite->setViteDevUrl('some.new.host');
```

## TODO

- [ ] - Remove slashes wrapper fro new dist
- [ ] - Do NOT include dist directory in the manifest loader (setting being duplicated)

## License

Open-source under [MIT license](LICENSE.md)