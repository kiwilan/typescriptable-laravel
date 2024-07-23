<?php

use Illuminate\Support\Facades\Artisan;
use Kiwilan\Typescriptable\TypescriptableConfig;

beforeEach(function () {
    deleteFile(outputDir('types-routes.d.ts'));
});

it('can be run', function () {
    Artisan::call('typescriptable:routes', [
        '--list' => true,
        '--output-path' => outputDir(),
    ]);

    $routes = outputDir(TypescriptableConfig::routesFilename());
    expect($routes)->toBeFile();
});
