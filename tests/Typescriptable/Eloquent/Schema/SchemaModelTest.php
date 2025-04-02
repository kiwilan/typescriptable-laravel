<?php

use Kiwilan\Typescriptable\Eloquent\Parser\ParserAccessor;
use Kiwilan\Typescriptable\Eloquent\Schema\SchemaAttribute;
use Kiwilan\Typescriptable\Eloquent\Schema\SchemaClass;
use Kiwilan\Typescriptable\Eloquent\Schema\SchemaModel;
use Kiwilan\Typescriptable\Eloquent\Schema\SchemaRelation;

it('can parse php class', function () {
    $path = getModelPath('Story');
    $spl = new SplFileInfo($path);
    $class = SchemaClass::make($spl, models());

    $model = SchemaModel::make(
        schemaClass: $class,
        driver: 'mysql',
        table: 'ts_stories',
        attributes: [
            STORY_ID,
            STORY_TITLE,
        ],
        relations: [
            STORY_RELATION_CHAPTERS,
            STORY_RELATION_CATEGORY,
        ],
    );

    expect($model)->toBeInstanceOf(SchemaModel::class);
    expect($model->getSchemaClass())->toBeInstanceOf(SchemaClass::class);
    expect($model->getSchemaClass()->getNamespace())->toBe('Kiwilan\Typescriptable\Tests\Data\Models\Story');
    expect($model->getDriver())->toBe('mysql');
    expect($model->getTable())->toBe('ts_stories');
    expect($model->getPolicy())->toBeArray();
    expect($model->getAttributes())->toBeArray();
    expect(count($model->getAttributes()))->toBe(2);
    expect($model->getRelations())->toBeArray();
    expect(count($model->getRelations()))->toBe(2);
    expect($model->getTypescriptModelName())->toBe('Story');

    expect($model->getAttribute('id'))->toBeInstanceOf(SchemaAttribute::class);
    $model->setAttribute(STORY_PUBLISHED_AT);
    expect($model->getAttribute('published_at')->getDatabaseType())->toBe('datetime');
    $model->removeAttribute('published_at');
    expect($model->getAttribute('published_at'))->toBeNull();

    $model->removeAttribute('id');
    $model->removeAttribute('title');
    expect($model->setAttributes([
        STORY_ID,
        STORY_TITLE,
    ])->getAttributes())->toBeArray();
    expect(count($model->getAttributes()))->toBe(2);

    $accessor = new ParserAccessor(
        field: 'title',
        phpType: 'number',
        isLegacy: false,
        isArray: false,
        typescriptType: 'number',
    );
    $model->updateAccessor($accessor);
    expect($model->getAttribute('title')->getPhpType())->toBe('number');
    expect($model->getAttribute('title')->getTypescriptType())->toBe('number');

    expect($model->getRelation('chapters'))->toBeInstanceOf(SchemaRelation::class);

    expect($model->getObservers())->toBeArray();
    expect(count($model->getObservers()))->toBe(0);
});
