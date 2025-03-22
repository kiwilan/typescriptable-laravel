<?php

use Kiwilan\Typescriptable\Tests\TestCase;
use Kiwilan\Typescriptable\Typed\Eloquent\EloquentType;

beforeEach(function () {
    eloquentConfig();
});

it('can create schema app', function (string $driver) {
    TestCase::setupDatabase($driver);

    $type = EloquentType::make()->execute();

    $app = $type->app();

    expect($app->getModelPath())->toBe(models());
    expect($app->useParser())->toBeFalse();
    expect($app->getBaseNamespace())->toBe('Kiwilan\Typescriptable\Tests\Data\Models');
    expect($app->getModels())->toBeArray();
    expect(count($app->getModels()))->toBe(9);
    expect($app->getDriver())->toBe($driver);
    expect($app->getDatabaseName())->toBeIn(['testing', ':memory:']);
    expect($app->getDatabasePrefix())->toBe('ts_');

    $config = $type->config();

    expect($config->modelsPath)->toBe(models());
    expect($config->phpPath)->toBe(outputDir().'/php');
    expect($config->useParser)->toBeFalse();
    expect($config->typescriptFilename)->toBe('types-eloquent.d.ts');
    expect($config->skipModels)->toBeArray();
    expect(count($config->skipModels))->toBe(1);
    expect($config->skipModels[0])->toBe('Kiwilan\Typescriptable\Tests\Data\Models\SushiTest');
})->with(DatabaseDriverEnums());
