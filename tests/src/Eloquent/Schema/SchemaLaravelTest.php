<?php

use Kiwilan\Typescriptable\Eloquent\Database\DriverEnum;
use Kiwilan\Typescriptable\Eloquent\Engine\EngineArtisan;
use Kiwilan\Typescriptable\Tests\TestCase;

beforeEach(function () {
    eloquentConfig();
});

it('can create schema laravel', function (string $driver) {
    TestCase::setupDatabase(DriverEnum::from($driver));

    $type = EngineArtisan::run();

    $laravel = $type->getLaravel();

    expect($laravel->getModelPath())->toBe(pathModels());
    expect($laravel->useParser())->toBeFalse();
    expect($laravel->getBaseNamespace())->toBe('Kiwilan\Typescriptable\Tests\Data\Models');
    expect($laravel->getModels())->toBeArray();
    // expect(count($laravel->getModels()))->toBe(9);
    expect($laravel->getDriver())->toBe(DriverEnum::from($driver));
    expect($laravel->getDatabaseName())->toBeIn(['testing', ':memory:']);
    expect($laravel->getDatabasePrefix())->toBe('ts_');

    $config = $type->getConfig();

    expect($config->modelsPath)->toBe(pathModels());
    expect($config->phpPath)->toBe(pathOutput().'/php');
    expect($config->useParser)->toBeFalse();
    expect($config->typescriptFilename)->toBe('types-eloquent.d.ts');
    expect($config->skipModels)->toBeArray();
    expect(count($config->skipModels))->toBe(1);
    expect($config->skipModels[0])->toBe('Kiwilan\Typescriptable\Tests\Data\Models\SushiTest');
})->with(driverEnums());
