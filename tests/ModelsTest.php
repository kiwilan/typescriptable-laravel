<?php

use Illuminate\Support\Facades\Artisan;
use Kiwilan\Typescriptable\Tests\TestCase;
use Kiwilan\Typescriptable\Typed\EloquentType;
use Kiwilan\Typescriptable\Typed\Typescript\TypescriptToPhp;
use Kiwilan\Typescriptable\TypescriptableConfig;

it('can be run', function () {
    foreach (getDatabaseTypes() as $type) {
        TestCase::setupDatabase($type);

        Artisan::call('typescriptable:models', [
            '--models-path' => models(),
            '--output-path' => outputDir(),
            '--php-path' => outputDir().'/php',
        ]);

        $models = outputDir(TypescriptableConfig::modelsFilename());
        expect($models)->toBeFile();
    }
});

it('is correct from models', function () {
    TestCase::setupDatabase('mysql');

    Artisan::call('typescriptable:models', [
        '--models-path' => models(),
        '--output-path' => outputDir(),
        '--php-path' => outputDir().'/php',
    ]);

    $models = outputDir(TypescriptableConfig::modelsFilename());
    $ts = TypescriptToPhp::make($models);
    $data = $ts->raw();
    $classes = $ts->classes();

    foreach ($classes as $key => $value) {
        if (str_contains($key, 'Paginate')) {
            unset($classes[$key]);
        }
    }

    $type = EloquentType::make(models(), outputDir());

    expect(count($type->eloquents()))->toBe(count($classes));

    foreach ($type->eloquents() as $field => $properties) {
        expect(array_key_exists($field, $classes))->toBeTrue();

        $tsProperties = $classes[$field]->properties();
        expect(count($tsProperties))->toBe(count($properties));

        expect(array_key_exists($field, $data))->toBeTrue();
        foreach ($properties as $key => $property) {
            $tsProperty = $tsProperties[$key];

            expect(array_key_exists($key, $tsProperties))->toBeTrue();
            expect($property->name())->toBe($tsProperty->name());
            // expect($property->typeTs())->toBe($tsProperty->type());
            // expect($property->isNullable())->toBe($tsProperty->isNullable());
        }
    }
});
