<?php

use Kiwilan\Typescriptable\Typed\Utils\Schema\SchemaCollection;

it('is correct from models', function () {
    $collect = SchemaCollection::make(models());
    $schemas = $collect->onlyModels();

    expect($schemas)->toBeArray();
    expect(count($schemas))->toBe(9);

    $movie = $schemas['Movie'];
    expect($movie->basePath())->toContain('laravel-typescriptable/tests/Data/Models');
    expect($movie->path())->toContain('laravel-typescriptable/tests/Data/Models/Movie.php');
    expect($movie->namespace())->toBe('Kiwilan\Typescriptable\Tests\Data\Models\Movie');
    expect($movie->name())->toBe('Movie');
    expect($movie->fullname())->toBe('Movie');
    expect($movie->isModel())->toBeTrue();
    expect($movie->traits())->toBeArray();
    expect($movie->extends())->toBe('Illuminate\Database\Eloquent\Model');
});
