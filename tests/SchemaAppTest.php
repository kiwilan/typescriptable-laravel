<?php

use Kiwilan\Typescriptable\Tests\TestCase;
use Kiwilan\Typescriptable\Typed\Eloquent\EloquentConfig;
use Kiwilan\Typescriptable\Typed\EloquentType;

it('can create schema app', function (string $driver) {
    TestCase::setupDatabase($driver);

    $type = EloquentType::make(new EloquentConfig(
        modelsPath: models(),
        outputPath: outputDir(),
        phpPath: outputDir().'/php',
        useParser: false,
        skipModels: ['Kiwilan\\Typescriptable\\Tests\\Data\\Models\\SushiTest'],
    ))->execute();

    $app = $type->app();

    expect($app->modelPath())->toBe(models());
    expect($app->useParser())->toBeFalse();
    expect($app->baseNamespace())->toBe('Kiwilan\Typescriptable\Tests\Data\Models');
    expect($app->models())->toBeArray();
    expect(count($app->models()))->toBe(8);
    expect($app->driver())->toBe($driver);
    expect($app->databaseName())->toBeIn(['testing', ':memory:']);
    expect($app->databasePrefix())->toBe('ts_');

    $config = $type->config();

    expect($config->modelsPath)->toBe(models());
    expect($config->outputPath)->toBe(outputDir());
    expect($config->phpPath)->toBe(outputDir().'/php');
    expect($config->useParser)->toBeFalse();
    expect($config->tsFilename)->toBe('types-eloquent.d.ts');
    expect($config->skipModels)->toBeArray();
    expect(count($config->skipModels))->toBe(1);
    expect($config->skipModels[0])->toBe('Kiwilan\Typescriptable\Tests\Data\Models\SushiTest');
})->with(DatabaseDriverEnums());
