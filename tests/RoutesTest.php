<?php

use Illuminate\Support\Facades\Artisan;

it('can be run', function () {
    $currentDir = getcwd();
    $routeList = "{$currentDir}/tests/Data/routes.json";
    $outputDir = "{$currentDir}/tests/output";

    Artisan::call('typescriptable:routes', [
        '--route-list' => $routeList,
        '--output-path' => $outputDir,
    ]);
    expect(true)->toBeTrue();
});
