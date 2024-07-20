<?php

use Illuminate\Support\Facades\Artisan;
use Kiwilan\Typescriptable\Commands\TypescriptableEloquentCommand;
use Kiwilan\Typescriptable\Tests\TestCase;
use Kiwilan\Typescriptable\TypescriptableConfig;

beforeEach(function () {
    deleteFile(outputDir('types-eloquent.d.ts'));
});

it('can use command', function () {
    TestCase::setupDatabase('sqlite');
    Artisan::call(TypescriptableEloquentCommand::class, [
        '--models-path' => models(),
        '--output-path' => outputDir(),
        '--php-path' => outputDir().'/php',
        '--parser' => false,
    ]);

    expect(true)->toBeTrue(); // fake assertion to check if the command runs without error
    $eloquent = outputDir(TypescriptableConfig::eloquentFilename());
    expect($eloquent)->toBeFile();
});

it('can use alias command', function () {
    Artisan::call('typescriptable:models', [
        '--models-path' => models(),
        '--output-path' => outputDir(),
        '--php-path' => outputDir().'/php',
        '--parser' => false,
    ]);

    expect(true)->toBeTrue(); // fake assertion to check if the command runs without error
    $eloquent = outputDir(TypescriptableConfig::eloquentFilename());
    expect($eloquent)->toBeFile();
});
