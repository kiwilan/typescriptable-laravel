<?php

use Illuminate\Support\Facades\Artisan;
use Kiwilan\Typescriptable\TypescriptableConfig;

it('can be run', function () {
    Artisan::call('typescriptable:routes', [
        '--route-list' => routes(),
        '--output-path' => outputDir(),
    ]);

    $routes = outputDir(TypescriptableConfig::routesFilename());
    expect($routes)->toBeFile();
});
