# WordPress Wolat Assets Handler

Allows to inject assets into pages - works great with Vite setup

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

```php
// No need to add manifest name at the end like `path/to/manifest.json` - only `path/to`
$manifest = \Wolat\Assets\Manifest::load('absolute/path/where/manifest/file/is');

$vite = new \Wolat\Assets\Vite($manifest);

$html = $vite->inject('resources/js/common.js');

// Output (example)
<script src="http://example.com/app/themes/wolat/dist/js/common.9b86745c.js" crossorigin type="module"></script>
<link href="http://example.com/app/themes/wolat/dist/js/chunks/vendor.641c6ca9.js" rel="modulepreload" />
<link href="http://example.com/app/themes/wolat/dist/css/common.e82aa1ab.css" rel="stylesheet" />

// Or multiple entries
// $html = $vite->inject('resources/js/app.js', 'resources/css/app.css');
// $html = $vite->inject(['resources/js/app.js', 'resources/css/app.css']);
```

### Dist directory

You may change `dist` directory with

```php
$vite->setDistDir('/new/dist/');
```

### Hot file

During development it requires special `hot` file in dist directory in order to resolve development and non-development environment

Name of the file can be changed with

```php
$vite->setHotFileName('newhot');
```

## Vite config file example

> Bedrock configuration for theme named `wolat`

```js
import { defineConfig, splitVendorChunkPlugin } from 'vite'

export default defineConfig({
    root: 'web/app/themes/wolat',

    build: {
        outDir: 'dist',

        emptyOutDir: true,

        manifest: true, // or `filename.json`

        rollupOptions: {
            input: [
                'web/app/themes/wolat/resources/js/app.js',
                'web/app/themes/wolat/resources/js/common.js',
                'web/app/themes/wolat/resources/css/app.css',
            ],
            output: {
                assetFileNames: (assetInfo) => {
                    let extType = assetInfo.name.split('.')[0]
                    if (/png|jpe?g|gif|tiff|bmp|ico/i.test(extType)) {
                        extType = 'images'
                    }

                    if (/svg/i.test(extType)) {
                        extType = 'icons'
                    }

                    return `${extType}/[name].[hash][extname]`
                },
                chunkFileNames: 'js/chunks/[name].[hash].js',
                entryFileNames: 'js/[name].[hash].js'
            }
        }
    },

    server: {
        strictPort: true,
        port: 5173,
    },

    plugins: [
        splitVendorChunkPlugin(),
    ],
})
```
