<?php

use Kiwilan\Typescriptable\Typed\Eloquent\EloquentConfig;

it('can use default config', function () {
    config(['typescriptable.eloquent.skip' => [
        'Kiwilan\\Typescriptable\\Tests\\Data\\Models\\SushiTest',
    ]]);

    $config = new EloquentConfig();

    expect($config->modelsPath)->toContain('laravel-typescriptable/vendor/orchestra/testbench-core/laravel/app/Models');
    expect($config->outputPath)->toContain('laravel-typescriptable/vendor/orchestra/testbench-core/laravel/resources/js');
    expect($config->phpPath)->toBeNull();
    expect($config->useParser)->toBeFalse();
    expect($config->tsFilename)->toBe('types-eloquent.d.ts');
    expect($config->skipModels)->toBeArray();
    expect(count($config->skipModels))->toBe(1);
    expect($config->skipModels[0])->toBe('Kiwilan\Typescriptable\Tests\Data\Models\SushiTest');

    $config = new EloquentConfig(
        modelsPath: models(),
        outputPath: outputDir(),
        phpPath: outputDir().'/php',
        useParser: false,
        skipModels: ['App\\Models\\SushiTest'],
    );

    expect($config->modelsPath)->toBe(models());
    expect($config->outputPath)->toBe(outputDir());
    expect($config->phpPath)->toBe(outputDir().'/php');
    expect($config->useParser)->toBeFalse();
    expect($config->tsFilename)->toBe('types-eloquent.d.ts');
    expect($config->skipModels)->toBeArray();
    expect(count($config->skipModels))->toBe(1);
    expect($config->skipModels[0])->toBe('App\Models\SushiTest');
});
