<?php

use Kiwilan\Typescriptable\Typed\Schema\SchemaClass;

it('can parse php class', function () {
    $path = getModelPath('Movie');
    $spl = new SplFileInfo($path);
    $schemaClass = SchemaClass::make($spl, models());

    expect($schemaClass)->toBeInstanceOf(SchemaClass::class);
    expect($schemaClass->getBasePath())->toContain('laravel-typescriptable/tests/Data/Models');
    expect(substr($schemaClass->getBasePath(), 0, 1))->toBe('/');
    expect($schemaClass->getPath())->toBe($path);
    expect($schemaClass->getFile())->toBe($spl);
    expect($schemaClass->getNamespace())->toBe('Kiwilan\Typescriptable\Tests\Data\Models\Movie');
    expect($schemaClass->getName())->toBe('Movie');
    expect($schemaClass->getFullname())->toBe('Movie');
    expect($schemaClass->getReflect())->toBeInstanceOf(ReflectionClass::class);
    expect($schemaClass->getTraits())->toBe([
        "Illuminate\Database\Eloquent\Factories\HasFactory",
        "Illuminate\Database\Eloquent\Concerns\HasUlids",
        "Spatie\MediaLibrary\InteractsWithMedia",
    ]);
    expect($schemaClass->isModel())->toBeTrue();
    expect($schemaClass->getExtends())->toBe('Illuminate\Database\Eloquent\Model');

    $path = getModelPath('Nested/Author');
    $spl = new SplFileInfo($path);
    $schemaClass = SchemaClass::make($spl, models());

    expect($schemaClass->getName())->toBe('Author');
    expect($schemaClass->getFullname())->toBe('NestedAuthor');

    $path = getModelPath('NotModel');
    $spl = new SplFileInfo($path);
    $schemaClass = SchemaClass::make($spl, models());

    expect($schemaClass->getName())->toBe('NotModel');
    expect($schemaClass->isModel())->toBeFalse();
});
