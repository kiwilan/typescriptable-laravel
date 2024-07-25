<?php

use Kiwilan\Typescriptable\Tests\TestCase;
use Kiwilan\Typescriptable\Typed\EloquentType;
use Kiwilan\Typescriptable\TypescriptableConfig;

beforeEach(function () {
    eloquentConfig();
});

it('can be run with artisan', function () {
    TestCase::setupDatabase('mysql');

    EloquentType::make()->execute();

    $eloquent = outputDir(TypescriptableConfig::eloquentFilename());
    expect($eloquent)->toBeFile();
});
