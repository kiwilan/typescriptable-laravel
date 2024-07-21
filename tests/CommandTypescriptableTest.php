<?php

use Illuminate\Support\Facades\Artisan;
use Kiwilan\Typescriptable\Commands\TypescriptableCommand;
use Kiwilan\Typescriptable\Tests\TestCase;

beforeEach(function () {
    deleteFile(outputDir('types-eloquent.d.ts'));
    deleteFile(outputDir('types-routes.d.ts'));
    deleteFile(outputDir('types-settings.d.ts'));
});

it('can use command', function () {
    TestCase::setupDatabase('sqlite');
    Artisan::call(TypescriptableCommand::class, [
        '--eloquent' => true,
        '--routes' => true,
        '--settings' => true,
    ]);

    expect(true)->toBeTrue(); // fake assertion to check if the command runs without error
});
