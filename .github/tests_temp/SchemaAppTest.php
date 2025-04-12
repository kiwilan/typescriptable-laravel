<?php

use Kiwilan\Typescriptable\Tests\TestCase;
use Kiwilan\Typescriptable\Eloquent\Eloquent\EloquentType;

beforeEach(function () {
    eloquentConfig();
});

it('can create schema app', function (string $driver) {
    TestCase::setupDatabase($driver);

    $type = EloquentType::make()->execute();

    $app = $type->app();

    expect($app->modelPath())->toBe(getModelsPath());
    expect($app->useParser())->toBeFalse();
    expect($app->baseNamespace())->toBe('Kiwilan\Typescriptable\Tests\Data\Models');
    expect($app->getModelsPath())->toBeArray();
    expect(count($app->getModelsPath()))->toBe(9);
    expect($app->driver())->toBe($driver);
    expect($app->databaseName())->toBeIn(['testing', ':memory:']);
    expect($app->databasePrefix())->toBe('ts_');

    $config = $type->config();

    expect($config->modelsPath)->toBe(getModelsPath());
    expect($config->phpPath)->toBe(pathOutput().'/php');
    expect($config->useParser)->toBeFalse();
    expect($config->typescriptFilename)->toBe('types-eloquent.d.ts');
    expect($config->skipModels)->toBeArray();
    expect(count($config->skipModels))->toBe(1);
    expect($config->skipModels[0])->toBe('Kiwilan\Typescriptable\Tests\Data\Models\SushiTest');
})->with(DriverEnums());
