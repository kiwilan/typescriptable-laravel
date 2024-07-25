<?php

use Kiwilan\Typescriptable\TypescriptableConfig;

it('config is ok', function () {
    config()->set('typescriptable.engine.eloquent', 'parser');
    config()->set('typescriptable.eloquent.php_path', null);
    deleteDir(TypescriptableConfig::outputPath());

    expect(TypescriptableConfig::engineEloquent())->toBeString();
    expect(TypescriptableConfig::outputPath())->toBeString();
    expect(TypescriptableConfig::setPath())->toBeString();
    expect(TypescriptableConfig::setPath('filename.js'))->toBeString();
    expect(TypescriptableConfig::eloquentFilename())->toBeString();
    expect(TypescriptableConfig::eloquentDirectory())->toBeString();
    expect(TypescriptableConfig::eloquentPhpPath())->toBeNull();
    expect(TypescriptableConfig::eloquentSkip())->toBeArray();
    expect(TypescriptableConfig::eloquentPaginate())->toBeBool();
    expect(TypescriptableConfig::settingsFilename())->toBeString();
    expect(TypescriptableConfig::settingsDirectory())->toBeString();
    expect(TypescriptableConfig::settingsExtends())->toBeString();
    expect(TypescriptableConfig::settingsSkip())->toBeArray();
    expect(TypescriptableConfig::routesFilename())->toBeString();
    expect(TypescriptableConfig::routesFilenameList())->toBeString();
    expect(TypescriptableConfig::routesPrintList())->toBeBool();
    expect(TypescriptableConfig::routesAddToWindow())->toBeBool();
    expect(TypescriptableConfig::routesUsePath())->toBeBool();
    expect(TypescriptableConfig::routesSkipName())->toBeArray();
    expect(TypescriptableConfig::routesSkipPath())->toBeArray();
    expect(TypescriptableConfig::inertiaFilename())->toBeString();
    expect(TypescriptableConfig::inertiaFilenameGlobal())->toBeString();
    expect(TypescriptableConfig::inertiaGlobal())->toBeBool();
    expect(TypescriptableConfig::inertiaPage())->toBeBool();
    expect(TypescriptableConfig::inertiaNpmTypescriptableLaravel())->toBeBool();
});

it('thrown error with engine models', function () {
    config()->set('typescriptable.engine.eloquent', 'engine');

    expect(fn () => TypescriptableConfig::engineEloquent())->toThrow(Exception::class);
});
