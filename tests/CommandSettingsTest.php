<?php

use Illuminate\Support\Facades\Artisan;
use Kiwilan\Typescriptable\Commands\TypescriptableSettingsCommand;
use Kiwilan\Typescriptable\TypescriptableConfig;

beforeEach(function () {
    deleteFile(outputDir('types-settings.d.ts'));

    deleteFile(outputDir(TypescriptableConfig::settingsFilename()));
    config()->set('typescriptable.settings.filename', settingsDir());
    config()->set('typescriptable.settings.directory', setttingsOutputDir());
    config()->set('typescriptable.settings.extends', settingsExtends());
    config()->set('typescriptable.settings.skip', []);
});

it('can be run', function () {
    Artisan::call(TypescriptableSettingsCommand::class);

    $settings = outputDir(TypescriptableConfig::settingsFilename());
    expect($settings)->toBeFile();
});
