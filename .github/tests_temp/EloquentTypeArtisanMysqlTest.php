<?php

use Kiwilan\Typescriptable\Tests\TestCase;
use Kiwilan\Typescriptable\Eloquent\Eloquent\EloquentType;
use Kiwilan\Typescriptable\TypescriptableConfig;

beforeEach(function () {
    eloquentConfig();
});

it('can be run with artisan', function () {
    TestCase::setupDatabase('mysql');

    EloquentType::make()->execute();

    $eloquent = pathOutput(TypescriptableConfig::eloquentFilename());
    expect($eloquent)->toBeFile();
});
