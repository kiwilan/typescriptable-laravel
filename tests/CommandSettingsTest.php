<?php

use Illuminate\Support\Facades\Artisan;
use Kiwilan\Typescriptable\Commands\TypescriptableSettingsCommand;
use Kiwilan\Typescriptable\TypescriptableConfig;

beforeEach(function () {
    deleteFile(outputDir('types-settings.d.ts'));
});

it('can be run', function () {
    Artisan::call(TypescriptableSettingsCommand::class, [
        '--settings-path' => settingsDir(),
        '--output-path' => setttingsOutputDir(),
        '--extends' => settingsExtends(),
    ]);

    $settings = outputDir(TypescriptableConfig::settingsFilename());
    expect($settings)->toBeFile();
});
