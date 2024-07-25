<?php

use Kiwilan\Typescriptable\Typed\Eloquent\EloquentConfig;

it('can use default config', function () {
    config()->set('typescriptable.eloquent.php_path', null);
    config()->set('typescriptable.eloquent.skip', [
        'Kiwilan\\Typescriptable\\Tests\\Data\\Models\\SushiTest',
    ]);

    $config = new EloquentConfig();

    expect($config->modelsPath)->toContain('tests/Data/Models');
    expect($config->phpPath)->toBeNull();
    expect($config->useParser)->toBeFalse();
    expect($config->typescriptFilename)->toBe('types-eloquent.d.ts');
    expect($config->skipModels)->toBeArray();
    expect(count($config->skipModels))->toBe(1);
    expect($config->skipModels[0])->toBe('Kiwilan\Typescriptable\Tests\Data\Models\SushiTest');

    $config = new EloquentConfig(
        modelsPath: models(),
        phpPath: outputDir('php'),
        useParser: false,
        skipModels: ['App\\Models\\SushiTest'],
        typescriptFilename: 'eloquent.d.ts',
    );
    ray($config);

    expect($config->modelsPath)->toBe(models());
    expect($config->phpPath)->toBe(outputDir('php'));
    expect($config->useParser)->toBeFalse();
    expect($config->typescriptFilename)->toBe('eloquent.d.ts');
    expect($config->skipModels)->toBeArray();
    expect(count($config->skipModels))->toBe(1);
    expect($config->skipModels[0])->toBe('App\Models\SushiTest');
});
