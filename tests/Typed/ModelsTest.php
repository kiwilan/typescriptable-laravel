<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

it('can be run', function () {
    $currentDir = getcwd();
    $modelsDir = "{$currentDir}/tests/Data/Models";
    $outputDir = "{$currentDir}/tests/Print";

    foreach (File::allFiles($outputDir) as $file) {
        $isHiddenFile = $file->getFilename()[0] === '.';
        if ($file->isFile() && ! $isHiddenFile) {
            File::delete($file->getPathname());
        }
    }

    Artisan::call('typescriptable:models', [
        '--models-path' => $modelsDir,
        '--output-path' => $outputDir,
    ]);
    expect(true)->toBeTrue();
});
