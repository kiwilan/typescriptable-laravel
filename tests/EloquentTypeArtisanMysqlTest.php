<?php

use Kiwilan\Typescriptable\Tests\TestCase;
use Kiwilan\Typescriptable\Typed\Eloquent\EloquentConfig;
use Kiwilan\Typescriptable\Typed\EloquentType;
use Kiwilan\Typescriptable\TypescriptableConfig;

beforeEach(function () {
    deleteFile(outputDir(TypescriptableConfig::eloquentFilename()));
    config()->set('typescriptable.output_path', outputDir());
    config()->set('typescriptable.engine.eloquent', 'artisan');
    config()->set('typescriptable.eloquent.directory', models());
    config()->set('typescriptable.eloquent.php_path', outputDir('php'));
    config()->set('typescriptable.eloquent.paginate', true);
    config()->set('typescriptable.eloquent.skip', [
        'Kiwilan\\Typescriptable\\Tests\\Data\\Models\\SushiTest'
    ]);
});

it('can be run with artisan', function () {
    TestCase::setupDatabase('mysql');

    $type = EloquentType::make(new EloquentConfig(
        modelsPath: models(),
        outputPath: ,
        phpPath: outputDir().'/php',
        useParser: false,
        skipModels: [],
    ))->execute();

    $eloquent = outputDir(TypescriptableConfig::eloquentFilename());
    expect($eloquent)->toBeFile();
});
