<?php

use Illuminate\Support\Facades\Artisan;
use Kiwilan\Typescriptable\TypescriptableConfig;

beforeEach(function () {
    deleteFile(pathOutput(TypescriptableConfig::routesFilename()));
});

it('can be run', function () {
    Artisan::call('typescriptable:routes');

    $routes = pathOutput(TypescriptableConfig::routesFilename());
    expect($routes)->toBeFile();
});
