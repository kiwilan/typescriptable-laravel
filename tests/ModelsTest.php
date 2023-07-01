<?php

use Illuminate\Support\Facades\Artisan;
use Kiwilan\Typescriptable\Tests\TestCase;

it('can be run', function (string $type) {
    TestCase::setupDatabase($type);

    $currentDir = getcwd();
    $modelsDir = "{$currentDir}/tests/Data/Models";
    $outputDir = "{$currentDir}/tests/output";

    Artisan::call('typescriptable:models', [
        '--models-path' => $modelsDir,
        '--output-path' => $outputDir,
    ]);
    expect(true)->toBeTrue();
})->with(DATABASE_TYPES);
