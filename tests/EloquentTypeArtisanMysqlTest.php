<?php

use Kiwilan\Typescriptable\Tests\TestCase;
use Kiwilan\Typescriptable\Typed\Eloquent\EloquentConfig;
use Kiwilan\Typescriptable\Typed\EloquentType;
use Kiwilan\Typescriptable\TypescriptableConfig;

beforeEach(function () {
    deleteFile(outputDir('types-eloquent.d.ts'));
});

it('can be run with artisan', function () {
    TestCase::setupDatabase('mysql');

    $type = EloquentType::make(new EloquentConfig(
        modelsPath: models(),
        outputPath: outputDir(),
        phpPath: outputDir().'/php',
        useParser: false,
        skipModels: ['Kiwilan\\Typescriptable\\Tests\\Data\\Models\\SushiTest'],
    ))->execute();

    $eloquent = outputDir(TypescriptableConfig::eloquentFilename());
    expect($eloquent)->toBeFile();
});
