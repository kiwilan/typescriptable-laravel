<?php

use Kiwilan\Typescriptable\Typed\Schema\SchemaClass;

it('can parse php class', function () {
    $path = getModelPath('Movie');
    $spl = new SplFileInfo($path);
    $class = SchemaClass::make($spl, models());

    expect($class)->toBeInstanceOf(SchemaClass::class);
    expect($class->getBasePath())->toContain('laravel-typescriptable/tests/Data/Models');
    expect(substr($class->getBasePath(), 0, 1))->toBe('/');
    expect($class->getPath())->toBe($path);
    expect($class->getFile())->toBe($spl);
    expect($class->getNamespace())->toBe('Kiwilan\Typescriptable\Tests\Data\Models\Movie');
    expect($class->getName())->toBe('Movie');
    expect($class->getFullname())->toBe('Movie');
    expect($class->getReflect())->toBeInstanceOf(ReflectionClass::class);
    expect($class->getTraits())->toBe([
        "Illuminate\Database\Eloquent\Factories\HasFactory",
        "Illuminate\Database\Eloquent\Concerns\HasUlids",
        "Spatie\MediaLibrary\InteractsWithMedia",
    ]);
    expect($class->isModel())->toBeTrue();
    expect($class->getExtends())->toBe('Illuminate\Database\Eloquent\Model');

    $path = getModelPath('Nested/Author');
    $spl = new SplFileInfo($path);
    $class = SchemaClass::make($spl, models());

    expect($class->getName())->toBe('Author');
    expect($class->getFullname())->toBe('NestedAuthor');

    $path = getModelPath('NotModel');
    $spl = new SplFileInfo($path);
    $class = SchemaClass::make($spl, models());

    expect($class->getName())->toBe('NotModel');
    expect($class->isModel())->toBeFalse();
});

it('cannot parse no php file', function () {
    $path = getModelPath('Chapter', 'ts');
    $spl = new SplFileInfo($path);
    $class = SchemaClass::make($spl, models());

    expect($class)->toBeNull();
});
