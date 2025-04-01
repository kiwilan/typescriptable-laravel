<?php

use Kiwilan\Typescriptable\Typed\Schema\SchemaRelation;

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

    // $relation = SchemaRelation::make(STORY_RELATION_CATEGORY);

    // expect($relation->getName())->toBe('category');
    // expect($relation->getLaravelType())->toBe('belongsTo');
    // expect($relation->getRelatedToModel())->toBe('App\Models\Category');
    // expect($relation->getSnakeCaseName())->toBe('category');
    // expect($relation->isInternal())->toBe(true);
    // expect($relation->isMany())->toBe(false);
    // expect($relation->getPhpType())->toBe('App\Models\Category');
    // expect($relation->getTypescriptType())->toBe('Category');
});
