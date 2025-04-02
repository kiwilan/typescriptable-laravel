<?php

use Kiwilan\Typescriptable\Eloquent\Settings\SettingsConfig;
use Kiwilan\Typescriptable\Eloquent\Settings\SettingsType;
use Kiwilan\Typescriptable\TypescriptableConfig;

beforeEach(function () {
    deleteFile(outputDir(TypescriptableConfig::settingsFilename()));

    config()->set('typescriptable.settings.filename', TypescriptableConfig::settingsFilename());
    config()->set('typescriptable.settings.directory', settingsDir());
    config()->set('typescriptable.settings.extends', settingsExtends());
    config()->set('typescriptable.settings.skip', []);
});

it('can type settings', function () {
    $type = SettingsType::make();

    $settings = $type->settings();
    expect($settings)->toBeArray();
    expect(count($settings))->toBe(1);

    $homeSetting = $type->setting('HomeSettings');
    expect($homeSetting->name())->toBe('HomeSettings');
    expect($homeSetting->properties())->toBeArray();
    expect($homeSetting->properties())->toHaveKey('hero_title_main');

    $hero_title_main = $homeSetting->properties()['hero_title_main'];
    expect($hero_title_main->name())->toBe('hero_title_main');
    expect($hero_title_main->phpType())->toBe('string');
    expect($hero_title_main->isNullable())->toBeTrue();
    expect($hero_title_main->isBuiltin())->toBeTrue();
    expect($hero_title_main->typescriptType())->toBe('string');

    expect($type->config())->toBeInstanceOf(SettingsConfig::class);
    expect($type->typescript())->toBeString();
});
