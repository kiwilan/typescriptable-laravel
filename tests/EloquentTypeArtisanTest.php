<?php

use Kiwilan\Typescriptable\Tests\TestCase;
use Kiwilan\Typescriptable\Typed\Eloquent\EloquentType;
use Kiwilan\Typescriptable\Typed\Utils\Schema\SchemaClass;

beforeEach(function () {
    eloquentConfig();
});

it('can be run with artisan', function (string $driver) {
    TestCase::setupDatabase($driver);

    $type = EloquentType::make()->execute();

    $app = $type->app();
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
    expect($movie->getObservers())->toBeArray();
    expect(count($movie->getObservers()))->toBe(6);
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
    expect($id->getDatabaseType())->toBeIn(['char(26)', 'varchar', 'character(26)', 'nchar(52)']);
    expect($id->isIncrements())->toBeFalse();
    expect($id->isNullable())->toBeFalse();
    expect($id->getDefault())->toBeNull();
    expect($id->isUnique())->toBeTrue();
    expect($id->isFillable())->toBeFalse();
    expect($id->isHidden())->toBeFalse();
    expect($id->isAppended())->toBeFalse();
    expect($id->getCast())->toBeNull();
    expect($id->getPhpType())->toBe('string');
    expect($id->getTypescriptType())->toBe('string');

    $title = $movie->getAttribute('title');
    expect($title->getName())->toBe('title');
    expect($title->getDatabaseType())->toBeIn(['varchar(255)', 'varchar', 'character varying(255)', 'nvarchar(510)']);
    expect($title->isIncrements())->toBeFalse();
    expect($title->isNullable())->toBeTrue();
    expect($title->getDefault())->toBeNull();
    expect($title->isUnique())->toBeTrue();
    expect($title->isFillable())->toBeTrue();
    expect($title->isHidden())->toBeFalse();
    expect($title->isAppended())->toBeFalse();
    expect($title->getCast())->toBeNull();
    expect($title->getPhpType())->toBe('string');
    expect($title->getTypescriptType())->toBe('string');

    $subtitles = $movie->getAttribute('subtitles');
    expect($subtitles->getName())->toBe('subtitles');
    expect($subtitles->getDatabaseType())->toBeIn(['json', 'longtext', 'text', 'nvarchar(max)']);
    expect($subtitles->isIncrements())->toBeFalse();
    expect($subtitles->isNullable())->toBeFalse();
    expect($subtitles->getDefault())->toBeNull();
    expect($subtitles->isUnique())->toBeFalse();
    expect($subtitles->isFillable())->toBeTrue();
    expect($subtitles->isHidden())->toBeFalse();
    expect($subtitles->isAppended())->toBeFalse();
    expect($subtitles->getCast())->toBe('array');
    expect($subtitles->getPhpType())->toBe('string');
    expect($subtitles->getTypescriptType())->toBe('any[]');

    $homepage = $movie->getAttribute('homepage');
    expect($homepage->getName())->toBe('homepage');
    expect($homepage->getDatabaseType())->toBeIn(['varchar(255)', 'varchar', 'character varying(255)', 'nvarchar(510)']);
    expect($homepage->isIncrements())->toBeFalse();
    expect($homepage->isNullable())->toBeTrue();
    expect($homepage->getDefault())->toBeNull();
    expect($homepage->isUnique())->toBeFalse();
    expect($homepage->isFillable())->toBeTrue();
    expect($homepage->isHidden())->toBeFalse();
    expect($homepage->isAppended())->toBeFalse();
    expect($homepage->getCast())->toBe('Kiwilan\Typescriptable\Tests\Data\Enums\PublishStatusEnum');
    expect($homepage->getPhpType())->toBe('\Kiwilan\Typescriptable\Tests\Data\Enums\PublishStatusEnum');
    expect($homepage->getTypescriptType())->toBe("'draft' | 'scheduled' | 'published'");

    $budget = $movie->getAttribute('budget');
    expect($budget->getName())->toBe('budget');
    expect($budget->getDatabaseType())->toBeIn(["enum('draft','scheduled','published')", 'varchar', 'character varying(255)', 'nvarchar(510)']);
    expect($budget->isIncrements())->toBeFalse();
    expect($budget->isNullable())->toBeFalse();
    expect($budget->getDefault())->toBeIn(['draft', "'draft'", "'draft'::character varying", "('draft')"]);
    expect($budget->isUnique())->toBeFalse();
    expect($budget->isFillable())->toBeFalse();
    expect($budget->isHidden())->toBeTrue();
    expect($budget->isAppended())->toBeFalse();
    expect($budget->getCast())->toBe('Kiwilan\Typescriptable\Tests\Data\Enums\PublishStatusEnum');
    expect($budget->getPhpType())->toBe('\Kiwilan\Typescriptable\Tests\Data\Enums\PublishStatusEnum');
    expect($budget->getTypescriptType())->toBe("'draft' | 'scheduled' | 'published'");

    $revenue = $movie->getAttribute('revenue');
    expect($revenue->getName())->toBe('revenue');
    expect($revenue->getDatabaseType())->toBeIn(['bigint', 'bigint(20)', 'integer']);
    expect($revenue->isIncrements())->toBeFalse();
    expect($revenue->isNullable())->toBeTrue();
    expect($revenue->getDefault())->toBeNull();
    expect($revenue->isUnique())->toBeFalse();
    expect($revenue->isFillable())->toBeTrue();
    expect($revenue->isHidden())->toBeFalse();
    expect($revenue->isAppended())->toBeFalse();
    expect($revenue->getCast())->toBe('integer');
    expect($revenue->getPhpType())->toBe('int');
    expect($revenue->getTypescriptType())->toBe('number');

    $is_multilingual = $movie->getAttribute('is_multilingual');
    expect($is_multilingual->getName())->toBe('is_multilingual');
    expect($is_multilingual->getDatabaseType())->toBeIn(['tinyint(1)', 'boolean', 'bit']);
    expect($is_multilingual->isIncrements())->toBeFalse();
    expect($is_multilingual->isNullable())->toBeFalse();
    expect($is_multilingual->getDefault())->toBeIn(['0', "'0'", 'false', "('0')"]);
    expect($is_multilingual->isUnique())->toBeFalse();
    expect($is_multilingual->isFillable())->toBeTrue();
    expect($is_multilingual->isHidden())->toBeFalse();
    expect($is_multilingual->isAppended())->toBeFalse();
    expect($is_multilingual->getCast())->toBe('boolean');
    expect($is_multilingual->getPhpType())->toBeIn(['int', 'bool']);
    expect($is_multilingual->getTypescriptType())->toBe('boolean');

    $author_id = $movie->getAttribute('author_id');
    expect($author_id->getName())->toBe('author_id');
    expect($author_id->getDatabaseType())->toBeIn(['bigint unsigned', 'bigint(20) unsigned', 'integer', 'bigint']);
    expect($author_id->isIncrements())->toBeFalse();
    expect($author_id->isNullable())->toBeTrue();
    expect($author_id->getDefault())->toBeNull();
    expect($author_id->isUnique())->toBeFalse();
    expect($author_id->isFillable())->toBeFalse();
    expect($author_id->isHidden())->toBeFalse();
    expect($author_id->isAppended())->toBeFalse();
    expect($author_id->getCast())->toBeNull();
    expect($author_id->getPhpType())->toBe('int');
    expect($author_id->getTypescriptType())->toBe('number');

    $added_at = $movie->getAttribute('added_at');
    expect($added_at->getName())->toBe('added_at');
    expect($added_at->getDatabaseType())->toBeIn(['datetime', 'timestamp(0) without time zone']);
    expect($added_at->isIncrements())->toBeFalse();
    expect($added_at->isNullable())->toBeTrue();
    expect($added_at->getDefault())->toBeNull();
    expect($added_at->isUnique())->toBeFalse();
    expect($added_at->isFillable())->toBeTrue();
    expect($added_at->isHidden())->toBeFalse();
    expect($added_at->isAppended())->toBeFalse();
    expect($added_at->getCast())->toBe('datetime:Y-m-d');
    expect($added_at->getPhpType())->toBe('\DateTime');
    expect($added_at->getTypescriptType())->toBe('string');

    $fetched_at = $movie->getAttribute('fetched_at');
    expect($fetched_at->getName())->toBe('fetched_at');
    expect($fetched_at->getDatabaseType())->toBeIn(['datetime', 'timestamp(0) without time zone']);
    expect($fetched_at->isIncrements())->toBeFalse();
    expect($fetched_at->isNullable())->toBeTrue();
    expect($fetched_at->getDefault())->toBeNull();
    expect($fetched_at->isUnique())->toBeFalse();
    expect($fetched_at->isFillable())->toBeTrue();
    expect($fetched_at->isHidden())->toBeFalse();
    expect($fetched_at->isAppended())->toBeFalse();
    expect($fetched_at->getCast())->toBeNull();
    expect($fetched_at->getPhpType())->toBe('string');
    expect($fetched_at->getTypescriptType())->toBe('string');

    $show_route = $movie->getAttribute('show_route');
    expect($show_route->getName())->toBe('show_route');
    expect($show_route->getDatabaseType())->toBeNull();
    expect($show_route->isIncrements())->toBeFalse();
    expect($show_route->isNullable())->toBeTrue();
    expect($show_route->getDefault())->toBeNull();
    expect($show_route->isUnique())->toBeFalse();
    expect($show_route->isFillable())->toBeFalse();
    expect($show_route->isHidden())->toBeFalse();
    expect($show_route->isAppended())->toBeTrue();
    expect($show_route->getCast())->toBe('accessor');
    expect($show_route->getPhpType())->toBe('string');
    expect($show_route->getTypescriptType())->toBe('string');

    $api_route = $movie->getAttribute('api_route');
    expect($api_route->getName())->toBe('api_route');
    expect($api_route->getDatabaseType())->toBeNull();
    expect($api_route->isIncrements())->toBeFalse();
    expect($api_route->isNullable())->toBeTrue();
    expect($api_route->getDefault())->toBeNull();
    expect($api_route->isUnique())->toBeFalse();
    expect($api_route->isFillable())->toBeFalse();
    expect($api_route->isHidden())->toBeFalse();
    expect($api_route->isAppended())->toBeTrue();
    expect($api_route->getCast())->toBe('attribute');
    expect($api_route->getPhpType())->toBe('string');
    expect($api_route->getTypescriptType())->toBe('string');

    $similars = $movie->getRelation('similars');
    expect($similars->getName())->toBe('similars');
    expect($similars->getLaravelType())->toBe('BelongsToMany');
    expect($similars->getRelatedToModel())->toBe('Kiwilan\Typescriptable\Tests\Data\Models\Movie');
    expect($similars->isInternal())->toBeTrue();
    expect($similars->isMany())->toBeTrue();
    expect($similars->getTypescriptType())->toBe('App.Models.Movie[]');

    $recommendations = $movie->getRelation('recommendations');
    expect($recommendations->getName())->toBe('recommendations');
    expect($recommendations->getLaravelType())->toBe('BelongsToMany');
    expect($recommendations->getRelatedToModel())->toBe('Kiwilan\Typescriptable\Tests\Data\Models\Movie');
    expect($recommendations->isInternal())->toBeTrue();
    expect($recommendations->isMany())->toBeTrue();
    expect($recommendations->getTypescriptType())->toBe('App.Models.Movie[]');

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

    $chapter = $app->getModel('Kiwilan\Typescriptable\Tests\Data\Models\Chapter');
    expect($chapter->getAttribute('creation_date'))->not()->toBeNull();
    expect($chapter->getAttribute('updated_date'))->not()->toBeNull();
    expect($chapter->getAttribute('created_at'))->toBeNull();
    expect($chapter->getAttribute('updated_at'))->toBeNull();

    $category = $app->getModel('Kiwilan\Typescriptable\Tests\Data\Models\Category');
    expect($category->getAttribute('created_at'))->toBeNull();
    expect($category->getAttribute('updated_at'))->toBeNull();
})->with(DatabaseDriverEnums());
