<?php

use Kiwilan\Typescriptable\Eloquent\Database\DriverEnum;
use Kiwilan\Typescriptable\Eloquent\Schema\SchemaLaravel;

it('can parse app', function () {
    $app = SchemaLaravel::make(
        modelPath: getModelsPath(),
        phpPath: getPhpPath(),
    );
    ray($app);

    expect($app->getModelPath())->toBe(getModelsPath());
    expect($app->getPhpPath())->toBe(getPhpPath());
    expect($app->useParser())->toBeFalse();
    expect($app->getBaseNamespace())->toBeNull();
    expect($app->getModels())->toBeArray();
    expect($app->getDriver())->toBe(DriverEnum::sqlite);
    expect($app->getDatabaseName())->toBe(':memory:');
    expect($app->getDatabasePrefix())->toBeNull();

    $app->setPhpPath('output');
    expect($app->getPhpPath())->toBe('output');

    $app->enableParser();
    expect($app->useParser())->toBeTrue();

    $app->setModels([

    ]);

    // getModel
    // setModels
    // parseBaseNamespace
    // improveRelations
});
