<?php

use Kiwilan\Typescriptable\Eloquent\Utils\Schema\SchemaCollection;

it('is correct from models', function () {
    $collect = SchemaCollection::make(models());
    $schemas = $collect->onlyModels();

    expect($schemas)->toBeArray();
    expect(count($schemas))->toBe(10);

    $movie = $schemas['Movie'];
    expect($movie->basePath())->toContain('tests/Data/Models');
    expect($movie->path())->toContain('tests/Data/Models/Movie.php');
    expect($movie->namespace())->toBe('Kiwilan\Typescriptable\Tests\Data\Models\Movie');
    expect($movie->name())->toBe('Movie');
    expect($movie->fullname())->toBe('Movie');
    expect($movie->isModel())->toBeTrue();
    expect($movie->traits())->toBeArray();
    expect($movie->extends())->toBe('Illuminate\Database\Eloquent\Model');
});
