<?php

use Illuminate\Support\Facades\Artisan;
use Kiwilan\Typescriptable\TypescriptableConfig;

it('can be run', function () {
    Artisan::call('typescriptable:routes', [
        '--json' => routes(),
        '--list' => true,
        '--output-path' => outputDir(),
    ]);

    $routes = outputDir(TypescriptableConfig::routesFilename());
    expect($routes)->toBeFile();
});
