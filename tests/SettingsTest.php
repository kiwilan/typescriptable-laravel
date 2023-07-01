<?php

use Illuminate\Support\Facades\Artisan;

it('can be run', function () {
    $currentDir = getcwd();
    $settingsDir = "{$currentDir}/tests/Data/Settings";
    $outputDir = "{$currentDir}/tests/output";

    Artisan::call('typescriptable:settings', [
        '--settings-path' => $settingsDir,
        '--output-path' => $outputDir,
        '--extends' => 'Kiwilan\Typescriptable\Tests\Data\Settings\Settings',
    ]);
    expect(true)->toBeTrue();
});
