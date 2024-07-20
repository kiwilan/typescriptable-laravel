<?php

use Illuminate\Support\Facades\Artisan;
use Kiwilan\Typescriptable\Commands\TypescriptableEloquentCommand;
use Kiwilan\Typescriptable\Tests\TestCase;
use Kiwilan\Typescriptable\Typescriptable;
use Kiwilan\Typescriptable\TypescriptableConfig;

beforeEach(function () {
    $models = outputDir('types-eloquent.d.ts');
    deleteFile($models);
});

it('can be run', function () {
    foreach (databaseDrivers() as $type) {
        ray('Database type: '.$type);
        TestCase::setupDatabase($type);

        config(['typescriptable.eloquent.skip' => [
            'Kiwilan\\Typescriptable\\Tests\\Data\\Models\\SushiTest',
        ]]);

        Artisan::call(TypescriptableEloquentCommand::class, [
            '--models-path' => models(),
            '--output-path' => outputDir(),
            '--php-path' => outputDir().'/php',
            '--legacy' => true,
        ]);

        $models = outputDir(TypescriptableConfig::eloquentFilename());
        expect($models)->toBeFile();
    }
});

// it('is correct from models', function () {
//     config(['typescriptable.eloquent.skip' => [
//         'App\\Models\\SushiTest',
//     ]]);

//     TestCase::setupDatabase('mysql');

//     Artisan::call(TypescriptableEloquentCommand::class, [
//         '--models-path' => models(),
//         '--output-path' => outputDir(),
//         '--php-path' => outputDir().'/php',
//     ]);

//     $models = outputDir(TypescriptableConfig::modelsFilename());
//     $ts = TypescriptToPhp::make($models);
//     $data = $ts->raw();
//     $classes = $ts->classes();

//     foreach ($classes as $key => $value) {
//         if (str_contains($key, 'Paginate')) {
//             unset($classes[$key]);
//         }
//     }

//     $type = EloquentType::make(models(), outputDir());

//     // expect(count($type->eloquents()))->toBe(count($classes));

//     // foreach ($type->eloquents() as $field => $properties) {
//     //     expect(array_key_exists($field, $classes))->toBeTrue();

//     //     $tsProperties = $classes[$field]->properties();
//     //     if (! array_key_exists('pivot', $properties)) {
//     //         expect(count($tsProperties))->toBe(count($properties));
//     //     }

//     //     expect(array_key_exists($field, $data))->toBeTrue();
//     //     foreach ($properties as $key => $property) {
//     //         $tsProperty = $tsProperties[$key];

//     //         expect(array_key_exists($key, $tsProperties))->toBeTrue();
//     //         if (! is_array($property)) {
//     //             expect($property->name())->toBe($tsProperty->name());
//     //         }
//     //         // expect($property->typeTs())->toBe($tsProperty->type());
//     //         // expect($property->isNullable())->toBe($tsProperty->isNullable());
//     //     }
//     // }
// });

// it('can list models', function () {
//     $list = ModelList::make(models());

//     expect($list->models())->toBeArray();
//     expect($list->path())->toBe(models());
//     expect(count($list->models()))->toBe(10);

//     Artisan::call('model:list', [
//         'modelPath' => models(),
//     ]);

//     $output = Artisan::output();
//     expect($output)->toContain('Name');
//     expect($output)->toContain('Namespace');
//     expect($output)->toContain('Path');
// });
