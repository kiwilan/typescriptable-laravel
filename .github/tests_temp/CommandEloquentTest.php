<?php

use Illuminate\Support\Facades\Artisan;
use Kiwilan\Typescriptable\Commands\TypescriptableEloquentCommand;
use Kiwilan\Typescriptable\Tests\TestCase;
use Kiwilan\Typescriptable\TypescriptableConfig;

beforeEach(function () {
    deleteFile(outputDir(TypescriptableConfig::eloquentFilename()));
    config()->set('typescriptable.eloquent.directory', getModelsPath());
    config()->set('typescriptable.eloquent.php_path', outputDir('php'));
    config()->set('typescriptable.eloquent.paginate', true);
});

it('can use command', function () {
    config()->set('typescriptable.engine.eloquent', 'artisan');
    TestCase::setupDatabase('sqlite');
    Artisan::call(TypescriptableEloquentCommand::class);

    expect(true)->toBeTrue(); // fake assertion to check if the command runs without error
    $eloquent = outputDir(TypescriptableConfig::eloquentFilename());
    expect($eloquent)->toBeFile();
});

it('can use alias command', function () {
    config()->set('typescriptable.engine.eloquent', 'parser');
    Artisan::call('typescriptable:models');

    expect(true)->toBeTrue(); // fake assertion to check if the command runs without error
    $eloquent = outputDir(TypescriptableConfig::eloquentFilename());
    expect($eloquent)->toBeFile();
});
