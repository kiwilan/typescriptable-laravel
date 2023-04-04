<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

it('can be run', function () {
    $currentDir = getcwd();
    $routeList = "{$currentDir}/tests/Data/routes.json";
    $outputDir = "{$currentDir}/tests/Print";

    foreach (File::allFiles($outputDir) as $file) {
        $isHiddenFile = $file->getFilename()[0] === '.';
        if ($file->isFile() && ! $isHiddenFile) {
            File::delete($file->getPathname());
        }
    }

    Artisan::call('typescriptable:routes', [
        '--route-list' => $routeList,
        '--output-path' => $outputDir,
    ]);
    expect(true)->toBeTrue();
});
