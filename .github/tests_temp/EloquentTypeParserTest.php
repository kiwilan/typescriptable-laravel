<?php

use Kiwilan\Typescriptable\Tests\TestCase;
use Kiwilan\Typescriptable\Typed\Eloquent\EloquentType;
use Kiwilan\Typescriptable\Typed\Utils\Schema\SchemaClass;

beforeEach(function () {
    eloquentConfig('parser');
});

it('can be run with parser', function (string $driver) {
    TestCase::setupDatabase($driver);

    $type = EloquentType::make()->execute();

    $app = $type->getApp();
    $movie = $app->getModel('Kiwilan\Typescriptable\Tests\Data\Models\Movie');

    expect($movie->getSchemaClass())->toBeInstanceOf(SchemaClass::class);
    expect($movie->getNamespace())->toBe('Kiwilan\Typescriptable\Tests\Data\Models\Movie');
    expect($movie->getDriver())->toBe($driver);
    expect($movie->getTable())->toBe('ts_movies');
    expect($movie->getPolicy())->toBeNull();
    expect($movie->getAttributes())->toBeArray();
    expect(count($movie->getAttributes()))->toBe(43);
    expect($movie->getRelations())->toBeArray();
    expect(count($movie->getRelations()))->toBe(5);
    expect($movie->getTypescriptModelName())->toBe('Movie');

    $schemaClass = $movie->getSchemaClass();

    expect($schemaClass->getBasePath())->toBe(models());
    expect($schemaClass->getPath())->toBe(models().'/Movie.php');
    expect($schemaClass->getNamespace())->toBe('Kiwilan\Typescriptable\Tests\Data\Models\Movie');
    expect($schemaClass->getName())->toBe('Movie');
    expect($schemaClass->getFullname())->toBe('Movie');
    expect($schemaClass->isModel())->toBeTrue();
    expect($schemaClass->getTraits())->toBeArray();
    expect(count($schemaClass->getTraits()))->toBe(3);
    expect($schemaClass->getExtends())->toBe('Illuminate\Database\Eloquent\Model');

    $id = $movie->getAttribute('id');
    expect($id->getName())->toBe('id');
    expect($id->getDatabaseType())->toBeIn(['char(26)', 'character(26)', 'varchar', 'character', 'nchar', 'nchar(52)']);
    expect($id->isNullable())->toBeFalse();
    expect($id->getDefault())->toBeNull();
    expect($id->isFillable())->toBeFalse();
    expect($id->isHidden())->toBeFalse();
    expect($id->isAppended())->toBeFalse();
    expect($id->getCast())->toBeNull();
    expect($id->getPhpType())->toBe('string');
    expect($id->getTypescriptType())->toBe('string');

    $title = $movie->getAttribute('title');
    expect($title->getName())->toBe('title');
    expect($title->getDatabaseType())->toBeIn(['varchar(255)', 'character varying(255)', 'nvarchar(510)', 'varchar', 'character varying', 'nvarchar']);
    expect($title->isNullable())->toBeTrue();
    expect($title->getDefault())->toBeNull();
    expect($title->isFillable())->toBeTrue();
    expect($title->isHidden())->toBeFalse();
    expect($title->isAppended())->toBeFalse();
    expect($title->getCast())->toBeNull();
    expect($title->getPhpType())->toBe('string');
    expect($title->getTypescriptType())->toBe('string');

    $budget = $movie->getAttribute('budget');
    expect($budget->getName())->toBe('budget');
    expect($budget->getDatabaseType())->toBeIn(["enum('draft','scheduled','published')", 'varchar', 'character varying(255)', 'nvarchar(510)', 'character varying', 'nvarchar']);
    expect($budget->isIncremental())->toBeFalse();
    expect($budget->isNullable())->toBeFalse();
    expect($budget->getDefault())->toBeIn(['draft', "'draft'", "'draft'::character varying", "('draft')"]);
    expect($budget->isUnique())->toBeFalse();
    expect($budget->isFillable())->toBeFalse();
    expect($budget->isHidden())->toBeTrue();
    expect($budget->isAppended())->toBeFalse();
    expect($budget->getCast())->toBe('Kiwilan\Typescriptable\Tests\Data\Enums\PublishStatusEnum');
    expect($budget->getPhpType())->toBe('\Kiwilan\Typescriptable\Tests\Data\Enums\PublishStatusEnum');
    expect($budget->getTypescriptType())->toBe("'draft' | 'scheduled' | 'published'");

    $show_route = $movie->getAttribute('show_route');
    expect($show_route->getName())->toBe('show_route');
    expect($show_route->getDatabaseType())->toBeNull();
    expect($show_route->isIncremental())->toBeFalse();
    expect($show_route->isNullable())->toBeTrue();
    expect($show_route->getDefault())->toBeNull();
    expect($show_route->isUnique())->toBeFalse();
    expect($show_route->isFillable())->toBeFalse();
    expect($show_route->isHidden())->toBeFalse();
    expect($show_route->isAppended())->toBeTrue();
    expect($show_route->getCast())->toBe('accessor');
    expect($show_route->getPhpType())->toBe('string');
    expect($show_route->getTypescriptType())->toBe('string');

    $members = $movie->getRelation('members');
    expect($members->getName())->toBe('members');
    expect($members->getLaravelType())->toBe('MorphToMany');
    expect($members->getRelatedToModel())->toBe('Kiwilan\Typescriptable\Tests\Data\Models\Member');
    expect($members->isInternal())->toBeTrue();
    expect($members->isMany())->toBeTrue();
    expect($members->getTypescriptType())->toBe('App.Models.Member[]');

    $author = $movie->getRelation('author');
    expect($author->getName())->toBe('author');
    expect($author->getLaravelType())->toBe('BelongsTo');
    expect($author->getRelatedToModel())->toBe('Kiwilan\Typescriptable\Tests\Data\Models\Nested\Author');
    expect($author->isInternal())->toBeTrue();
    expect($author->isMany())->toBeFalse();
    expect($author->getTypescriptType())->toBe('App.Models.NestedAuthor');

    $media = $movie->getRelation('media');
    expect($media->getName())->toBe('media');
    expect($media->getLaravelType())->toBe('MorphMany');
    expect($media->getRelatedToModel())->toBe('Spatie\MediaLibrary\MediaCollections\Models\Media');
    expect($media->isInternal())->toBeFalse();
    expect($media->isMany())->toBeTrue();
    expect($media->getTypescriptType())->toBe('any[]');

    $members_count = $movie->getAttribute('members_count');
    expect($members_count->getName())->toBe('members_count');
    expect($members_count->getPhpType())->toBe('int');
    expect($members_count->getTypescriptType())->toBe('number');
})->with(DatabaseDriverEnums());
