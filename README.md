# WordPress Wolat Assets Handler

Allows to inject assets into pages

Works on pair with npm package [wp-wolat-plugin](https://github.com/czernika/wp-vite-plugin)

[![Running Unit Tests](https://github.com/czernika/wp-vite/actions/workflows/tests.yml/badge.svg)](https://github.com/czernika/wp-vite/actions/workflows/tests.yml)

## Installation

> In progress

For now may be resolved as Composer dependency

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

1. Create new `Wolat\Assets\Manifest` instance with path to compiled `manifest.json` file

Typical manifest file should looks like

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

2. Create new `Wolat\Assets\Vite` with `Manifest` as dependency
3. Inject input entrypoint defined in `vite.config.js`

```js
// vite.config.js
import { defineConfig } from 'vite'
import wordPressWolat from 'wordpress-wolat'

// https://vitejs.dev/config/
export default defineConfig({
	plugins: [
		wordPressWolat({
            theme: 'web/app/themes/kawa',
            input: 'resources/js/app.js',
        }),
	]
})
```

```php
// No need to add manifest name at the end like `path/to/manifest.json` - only `path/to`
$manifest = \Wolat\Assets\Manifest::load('absolute/path/where/manifest/file/is');
// $manifest = \Wolat\Assets\Manifest::load(get_template_directory() . DIRECTORY_SEPARATOR . 'dist');

$vite = new \Wolat\Assets\Vite($manifest);

$html = $vite->inject('resources/js/common.js');

echo $html;

// Output (example)
<script src="http://example.com/app/themes/wolat/dist/js/common.9b86745c.js" crossorigin type="module"></script>
<link href="http://example.com/app/themes/wolat/dist/js/chunks/vendor.641c6ca9.js" rel="modulepreload" />
<link href="http://example.com/app/themes/wolat/dist/css/common.e82aa1ab.css" rel="stylesheet" />

// Or multiple entries
// $html = $vite->inject('resources/js/app.js', 'resources/css/app.css');
// $html = $vite->inject(['resources/js/app.js', 'resources/css/app.css']);
```

### Dist directory

You may change `dist` directory with before output. Note - manifest file depends on dist directory, so it should be changed also

```js
// vite.config.js
import { defineConfig } from 'vite'
import wordPressWolat from 'wordpress-wolat'

// https://vitejs.dev/config/
export default defineConfig({
	plugins: [
		wordPressWolat({
            theme: 'web/app/themes/kawa',
            input: 'resources/js/app.js',

            outDir: 'build', // change here
        }),
	]
})
```

```php
$manifest = \Wolat\Assets\Manifest::load(get_template_directory() . DIRECTORY_SEPARATOR . 'new/dist');

$vite = new \Wolat\Assets\Vite($manifest);
$vite->setDistDir('/new/dist/'); // should be wrapped within slashes

$html = $vite->inject('resources/js/common.js');
```

### Hot file

During development it requires special `hot` file in dist directory in order to resolve development and non-development environment

Name of the file can be changed with

```js
// vite.config.js
import { defineConfig } from 'vite'
import wordPressWolat from 'wordpress-wolat'

// https://vitejs.dev/config/
export default defineConfig({
	plugins: [
		wordPressWolat({
            theme: 'web/app/themes/kawa',
            input: 'resources/js/app.js',

            hot: 'newhot', // change here
        }),
	]
})
```

```php
$vite->setHotFileName('newhot');
```

### Manifest file name

If you need to change manifest file name you may pass second argument for `Manifest` object

```js
// vite.config.js
import { defineConfig } from 'vite'
import wordPressWolat from 'wordpress-wolat'

// https://vitejs.dev/config/
export default defineConfig({
	plugins: [
		wordPressWolat({
            theme: 'web/app/themes/kawa',
            input: 'resources/js/app.js',

            manifest: 'assets.json', // change here
        }),
	]
})
```

```php
$manifest = \Wolat\Assets\Manifest::load(get_template_directory() . DIRECTORY_SEPARATOR . 'dist', 'assets.json');
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
            theme: 'web/app/themes/kawa',
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

## License

Open-source under [MIT license](LICENSE.md)