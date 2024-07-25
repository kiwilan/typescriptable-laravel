<?php

use Illuminate\Support\Facades\Artisan;
use Kiwilan\Typescriptable\Commands\TypescriptableSettingsCommand;
use Kiwilan\Typescriptable\TypescriptableConfig;

beforeEach(function () {
    deleteFile(outputDir(TypescriptableConfig::settingsFilename()));

    config()->set('typescriptable.settings.filename', 'types-settings.d.ts');
    config()->set('typescriptable.settings.directory', settingsDir());
    config()->set('typescriptable.settings.extends', settingsExtends());
    config()->set('typescriptable.settings.skip', []);
});

it('can be run', function () {
    Artisan::call(TypescriptableSettingsCommand::class);

    $settings = outputDir(TypescriptableConfig::settingsFilename());
    expect($settings)->toBeFile();
});
