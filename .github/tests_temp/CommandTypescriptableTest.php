<?php

use Illuminate\Support\Facades\Artisan;
use Kiwilan\Typescriptable\Commands\TypescriptableCommand;
use Kiwilan\Typescriptable\Tests\TestCase;
use Kiwilan\Typescriptable\TypescriptableConfig;

beforeEach(function () {
    deleteFile(outputDir(TypescriptableConfig::eloquentFilename()));
    deleteFile(outputDir(TypescriptableConfig::routesFilename()));
    deleteFile(outputDir(TypescriptableConfig::settingsFilename()));
});

it('can use command', function () {
    TestCase::setupDatabase('sqlite');
    Artisan::call(TypescriptableCommand::class, [
        '--eloquent' => true,
        '--routes' => true,
        '--settings' => true,
    ]);

    expect(true)->toBeTrue(); // fake assertion to check if the command runs without error
});
