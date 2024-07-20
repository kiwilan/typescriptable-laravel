<?php

it('is correct from models', function () {
    config(['typescriptable.models.skip' => [
        'App\\Models\\SushiTest',
    ]]);

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

    // expect(count($type->eloquents()))->toBe(count($classes));

    // foreach ($type->eloquents() as $field => $properties) {
    //     expect(array_key_exists($field, $classes))->toBeTrue();

    //     $tsProperties = $classes[$field]->properties();
    //     if (! array_key_exists('pivot', $properties)) {
    //         expect(count($tsProperties))->toBe(count($properties));
    //     }

    //     expect(array_key_exists($field, $data))->toBeTrue();
    //     foreach ($properties as $key => $property) {
    //         $tsProperty = $tsProperties[$key];

    //         expect(array_key_exists($key, $tsProperties))->toBeTrue();
    //         if (! is_array($property)) {
    //             expect($property->name())->toBe($tsProperty->name());
    //         }
    //         // expect($property->typeTs())->toBe($tsProperty->type());
    //         // expect($property->isNullable())->toBe($tsProperty->isNullable());
    //     }
    // }
});
