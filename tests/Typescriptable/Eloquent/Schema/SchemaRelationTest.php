<?php

use Kiwilan\Typescriptable\Eloquent\Schema\SchemaRelation;

it('can parse relation', function () {
    $relation = SchemaRelation::make(STORY_RELATION_CHAPTERS);

    expect($relation->getName())->toBe('chapters');
    expect($relation->getLaravelType())->toBe('HasMany');
    expect($relation->getRelatedToModel())->toBe('Kiwilan\Typescriptable\Tests\Data\Models\Chapter');
    expect($relation->getSnakeCaseName())->toBe('chapters');
    expect($relation->isInternal())->toBe(true);
    expect($relation->isMany())->toBe(true);
    expect($relation->getPhpType())->toBe('Kiwilan\Typescriptable\Tests\Data\Models\Chapter[]');

    // fix namespace here
    $relation->setTypescriptType('Chapter', 'Kiwilan\Typescriptable\Tests\Data\Models');
    expect($relation->getTypescriptType())->toBe('App.Models.Chapter[]');
});

it('can set php type', function () {
    $relation = SchemaRelation::make(STORY_RELATION_CHAPTERS);
    expect($relation->getPhpType())->toBe('Kiwilan\Typescriptable\Tests\Data\Models\Chapter[]');

    $relation->setPhpType('App\Models\Chapter');
    expect($relation->getPhpType())->toBe('App\Models\Chapter[]');

    $relation->setPhpType('App\Models\Chapter[]');
    expect($relation->getPhpType())->toBe('App\Models\Chapter[]');
});

it('can set typescript type', function () {
    $relation = SchemaRelation::make(STORY_RELATION_CHAPTERS);

    $relation->setTypescriptType('any', 'App.Models');
    expect($relation->getTypescriptType())->toBe('any[]');
});
