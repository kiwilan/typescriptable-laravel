<?php

use Illuminate\Support\Facades\Artisan;
use Kiwilan\Typescriptable\TypescriptableConfig;

beforeEach(function () {
    deleteFile(outputDir(TypescriptableConfig::routesFilename()));
});

it('can be run', function () {
    Artisan::call('typescriptable:routes');

    $routes = outputDir(TypescriptableConfig::routesFilename());
    expect($routes)->toBeFile();
});
