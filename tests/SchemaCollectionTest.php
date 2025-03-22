<?php

use Kiwilan\Typescriptable\Typed\Utils\Schema\SchemaCollection;

it('is correct from models', function () {
    $collect = SchemaCollection::make(models());
    $schemas = $collect->getItems(only_models: true);

    expect($schemas)->toBeArray();
    expect(count($schemas))->toBe(10);

    $movie = $schemas['Movie'];
    expect($movie->getBasePath())->toContain('tests/Data/Models');
    expect($movie->getPath())->toContain('tests/Data/Models/Movie.php');
    expect($movie->getNamespace())->toBe('Kiwilan\Typescriptable\Tests\Data\Models\Movie');
    expect($movie->getName())->toBe('Movie');
    expect($movie->getFullname())->toBe('Movie');
    expect($movie->isModel())->toBeTrue();
    expect($movie->getTraits())->toBeArray();
    expect($movie->getExtends())->toBe('Illuminate\Database\Eloquent\Model');
});
