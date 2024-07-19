<?php

use Illuminate\Support\Facades\Artisan;
use Kiwilan\Typescriptable\TypescriptableConfig;

beforeEach(function () {
    $routes = outputDir('types-routes.d.ts');
    deleteFile($routes);
});

it('can be run', function () {
    Artisan::call('typescriptable:routes', [
        '--json' => routes(),
        '--list' => true,
        '--output-path' => outputDir(),
    ]);

    $routes = outputDir(TypescriptableConfig::routesFilename());
    expect($routes)->toBeFile();
});
