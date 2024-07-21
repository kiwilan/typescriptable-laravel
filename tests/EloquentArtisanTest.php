<?php

use Kiwilan\Typescriptable\Tests\TestCase;
use Kiwilan\Typescriptable\Typed\Eloquent\EloquentConfig;
use Kiwilan\Typescriptable\Typed\EloquentType;
use Kiwilan\Typescriptable\Typed\Utils\Schema\SchemaClass;

it('can be run with artisan', function (string $driver) {
    TestCase::setupDatabase($driver);

    $type = EloquentType::make(new EloquentConfig(
        modelsPath: models(),
        outputPath: outputDir(),
        phpPath: outputDir().'/php',
        useParser: false,
        skipModels: ['Kiwilan\\Typescriptable\\Tests\\Data\\Models\\SushiTest'],
    ))->execute();

    $app = $type->app();
    $movie = $app->getModel('Kiwilan\Typescriptable\Tests\Data\Models\Movie');

    expect($movie->schemaClass())->toBeInstanceOf(SchemaClass::class);
    expect($movie->namespace())->toBe('Kiwilan\Typescriptable\Tests\Data\Models\Movie');
    expect($movie->driver())->toBe($driver);
    expect($movie->table())->toBe('ts_movies');
    expect($movie->policy())->toBeNull();
    expect($movie->attributes())->toBeArray();
    expect(count($movie->attributes()))->toBe(43);
    expect($movie->relations())->toBeArray();
    expect(count($movie->relations()))->toBe(5);
    expect($movie->observers())->toBeArray();
    expect(count($movie->observers()))->toBe(1);
    expect($movie->typescriptModelName())->toBe('Movie');

    $schemaClass = $movie->schemaClass();

    expect($schemaClass->basePath())->toBe(models());
    expect($schemaClass->path())->toBe(models().'/Movie.php');
    expect($schemaClass->namespace())->toBe('Kiwilan\Typescriptable\Tests\Data\Models\Movie');
    expect($schemaClass->name())->toBe('Movie');
    expect($schemaClass->fullname())->toBe('Movie');
    expect($schemaClass->isModel())->toBeTrue();
    expect($schemaClass->traits())->toBeArray();
    expect(count($schemaClass->traits()))->toBe(3);
    expect($schemaClass->extends())->toBe('Illuminate\Database\Eloquent\Model');

    $id = $movie->getAttribute('id');
    expect($id->name())->toBe('id');
    expect($id->databaseType())->toBeIn(['char(26)', 'varchar', 'character(26)', 'nchar(52)']);
    expect($id->increments())->toBeFalse();
    expect($id->nullable())->toBeFalse();
    expect($id->default())->toBeNull();
    expect($id->unique())->toBeTrue();
    expect($id->fillable())->toBeFalse();
    expect($id->hidden())->toBeFalse();
    expect($id->appended())->toBeFalse();
    expect($id->cast())->toBeNull();
    expect($id->phpType())->toBe('string');
    expect($id->typescriptType())->toBe('string');

    $title = $movie->getAttribute('title');
    expect($title->name())->toBe('title');
    expect($title->databaseType())->toBeIn(['varchar(255)', 'varchar', 'character varying(255)', 'nvarchar(510)']);
    expect($title->increments())->toBeFalse();
    expect($title->nullable())->toBeTrue();
    expect($title->default())->toBeNull();
    expect($title->unique())->toBeTrue();
    expect($title->fillable())->toBeTrue();
    expect($title->hidden())->toBeFalse();
    expect($title->appended())->toBeFalse();
    expect($title->cast())->toBeNull();
    expect($title->phpType())->toBe('string');
    expect($title->typescriptType())->toBe('string');

    $subtitles = $movie->getAttribute('subtitles');
    expect($subtitles->name())->toBe('subtitles');
    expect($subtitles->databaseType())->toBeIn(['json', 'longtext', 'text', 'nvarchar(max)']);
    expect($subtitles->increments())->toBeFalse();
    expect($subtitles->nullable())->toBeFalse();
    expect($subtitles->default())->toBeNull();
    expect($subtitles->unique())->toBeFalse();
    expect($subtitles->fillable())->toBeTrue();
    expect($subtitles->hidden())->toBeFalse();
    expect($subtitles->appended())->toBeFalse();
    expect($subtitles->cast())->toBe('array');
    expect($subtitles->phpType())->toBe('string');
    expect($subtitles->typescriptType())->toBe('any[]');

    $homepage = $movie->getAttribute('homepage');
    expect($homepage->name())->toBe('homepage');
    expect($homepage->databaseType())->toBeIn(['varchar(255)', 'varchar', 'character varying(255)', 'nvarchar(510)']);
    expect($homepage->increments())->toBeFalse();
    expect($homepage->nullable())->toBeTrue();
    expect($homepage->default())->toBeNull();
    expect($homepage->unique())->toBeFalse();
    expect($homepage->fillable())->toBeTrue();
    expect($homepage->hidden())->toBeFalse();
    expect($homepage->appended())->toBeFalse();
    expect($homepage->cast())->toBe('Kiwilan\Typescriptable\Tests\Data\Enums\PublishStatusEnum');
    expect($homepage->phpType())->toBe('\Kiwilan\Typescriptable\Tests\Data\Enums\PublishStatusEnum');
    expect($homepage->typescriptType())->toBe("'draft' | 'scheduled' | 'published'");

    $budget = $movie->getAttribute('budget');
    expect($budget->name())->toBe('budget');
    expect($budget->databaseType())->toBeIn(["enum('draft','scheduled','published')", 'varchar', 'character varying(255)', 'nvarchar(510)']);
    expect($budget->increments())->toBeFalse();
    expect($budget->nullable())->toBeFalse();
    expect($budget->default())->toBeIn(['draft', "'draft'", "'draft'::character varying", "('draft')"]);
    expect($budget->unique())->toBeFalse();
    expect($budget->fillable())->toBeTrue();
    expect($budget->hidden())->toBeTrue();
    expect($budget->appended())->toBeFalse();
    expect($budget->cast())->toBe('Kiwilan\Typescriptable\Tests\Data\Enums\PublishStatusEnum');
    expect($budget->phpType())->toBe('\Kiwilan\Typescriptable\Tests\Data\Enums\PublishStatusEnum');
    expect($budget->typescriptType())->toBe("'draft' | 'scheduled' | 'published'");

    $revenue = $movie->getAttribute('revenue');
    expect($revenue->name())->toBe('revenue');
    expect($revenue->databaseType())->toBeIn(['bigint', 'bigint(20)', 'integer']);
    expect($revenue->increments())->toBeFalse();
    expect($revenue->nullable())->toBeTrue();
    expect($revenue->default())->toBeNull();
    expect($revenue->unique())->toBeFalse();
    expect($revenue->fillable())->toBeTrue();
    expect($revenue->hidden())->toBeFalse();
    expect($revenue->appended())->toBeFalse();
    expect($revenue->cast())->toBe('integer');
    expect($revenue->phpType())->toBe('int');
    expect($revenue->typescriptType())->toBe('number');

    $is_multilingual = $movie->getAttribute('is_multilingual');
    expect($is_multilingual->name())->toBe('is_multilingual');
    expect($is_multilingual->databaseType())->toBeIn(['tinyint(1)', 'boolean', 'bit']);
    expect($is_multilingual->increments())->toBeFalse();
    expect($is_multilingual->nullable())->toBeFalse();
    expect($is_multilingual->default())->toBeIn(['0', "'0'", 'false', "('0')"]);
    expect($is_multilingual->unique())->toBeFalse();
    expect($is_multilingual->fillable())->toBeTrue();
    expect($is_multilingual->hidden())->toBeFalse();
    expect($is_multilingual->appended())->toBeFalse();
    expect($is_multilingual->cast())->toBe('boolean');
    expect($is_multilingual->phpType())->toBeIn(['int', 'bool']);
    expect($is_multilingual->typescriptType())->toBe('boolean');

    $author_id = $movie->getAttribute('author_id');
    expect($author_id->name())->toBe('author_id');
    expect($author_id->databaseType())->toBeIn(['bigint unsigned', 'bigint(20) unsigned', 'integer', 'bigint']);
    expect($author_id->increments())->toBeFalse();
    expect($author_id->nullable())->toBeTrue();
    expect($author_id->default())->toBeNull();
    expect($author_id->unique())->toBeFalse();
    expect($author_id->fillable())->toBeFalse();
    expect($author_id->hidden())->toBeFalse();
    expect($author_id->appended())->toBeFalse();
    expect($author_id->cast())->toBeNull();
    expect($author_id->phpType())->toBe('int');
    expect($author_id->typescriptType())->toBe('number');

    $added_at = $movie->getAttribute('added_at');
    expect($added_at->name())->toBe('added_at');
    expect($added_at->databaseType())->toBeIn(['datetime', 'timestamp(0) without time zone']);
    expect($added_at->increments())->toBeFalse();
    expect($added_at->nullable())->toBeTrue();
    expect($added_at->default())->toBeNull();
    expect($added_at->unique())->toBeFalse();
    expect($added_at->fillable())->toBeTrue();
    expect($added_at->hidden())->toBeFalse();
    expect($added_at->appended())->toBeFalse();
    expect($added_at->cast())->toBe('datetime:Y-m-d');
    expect($added_at->phpType())->toBe('\DateTime');
    expect($added_at->typescriptType())->toBe('string');

    $fetched_at = $movie->getAttribute('fetched_at');
    expect($fetched_at->name())->toBe('fetched_at');
    expect($fetched_at->databaseType())->toBeIn(['datetime', 'timestamp(0) without time zone']);
    expect($fetched_at->increments())->toBeFalse();
    expect($fetched_at->nullable())->toBeTrue();
    expect($fetched_at->default())->toBeNull();
    expect($fetched_at->unique())->toBeFalse();
    expect($fetched_at->fillable())->toBeTrue();
    expect($fetched_at->hidden())->toBeFalse();
    expect($fetched_at->appended())->toBeFalse();
    expect($fetched_at->cast())->toBeNull();
    expect($fetched_at->phpType())->toBe('string');
    expect($fetched_at->typescriptType())->toBe('string');

    $show_route = $movie->getAttribute('show_route');
    expect($show_route->name())->toBe('show_route');
    expect($show_route->databaseType())->toBeNull();
    expect($show_route->increments())->toBeFalse();
    expect($show_route->nullable())->toBeTrue();
    expect($show_route->default())->toBeNull();
    expect($show_route->unique())->toBeFalse();
    expect($show_route->fillable())->toBeFalse();
    expect($show_route->hidden())->toBeFalse();
    expect($show_route->appended())->toBeTrue();
    expect($show_route->cast())->toBe('accessor');
    expect($show_route->phpType())->toBe('string');
    expect($show_route->typescriptType())->toBe('string');

    $api_route = $movie->getAttribute('api_route');
    expect($api_route->name())->toBe('api_route');
    expect($api_route->databaseType())->toBeNull();
    expect($api_route->increments())->toBeFalse();
    expect($api_route->nullable())->toBeTrue();
    expect($api_route->default())->toBeNull();
    expect($api_route->unique())->toBeFalse();
    expect($api_route->fillable())->toBeFalse();
    expect($api_route->hidden())->toBeFalse();
    expect($api_route->appended())->toBeTrue();
    expect($api_route->cast())->toBe('attribute');
    expect($api_route->phpType())->toBe('string');
    expect($api_route->typescriptType())->toBe('string');

    $similars = $movie->getRelation('similars');
    expect($similars->name())->toBe('similars');
    expect($similars->laravelType())->toBe('BelongsToMany');
    expect($similars->relatedToModel())->toBe('Kiwilan\Typescriptable\Tests\Data\Models\Movie');
    expect($similars->isInternal())->toBeTrue();
    expect($similars->isMany())->toBeTrue();
    expect($similars->typescriptType())->toBe('App.Models.Movie[]');

    $recommendations = $movie->getRelation('recommendations');
    expect($recommendations->name())->toBe('recommendations');
    expect($recommendations->laravelType())->toBe('BelongsToMany');
    expect($recommendations->relatedToModel())->toBe('Kiwilan\Typescriptable\Tests\Data\Models\Movie');
    expect($recommendations->isInternal())->toBeTrue();
    expect($recommendations->isMany())->toBeTrue();
    expect($recommendations->typescriptType())->toBe('App.Models.Movie[]');

    $members = $movie->getRelation('members');
    expect($members->name())->toBe('members');
    expect($members->laravelType())->toBe('MorphToMany');
    expect($members->relatedToModel())->toBe('Kiwilan\Typescriptable\Tests\Data\Models\Member');
    expect($members->isInternal())->toBeTrue();
    expect($members->isMany())->toBeTrue();
    expect($members->typescriptType())->toBe('App.Models.Member[]');

    $author = $movie->getRelation('author');
    expect($author->name())->toBe('author');
    expect($author->laravelType())->toBe('BelongsTo');
    expect($author->relatedToModel())->toBe('Kiwilan\Typescriptable\Tests\Data\Models\Nested\Author');
    expect($author->isInternal())->toBeTrue();
    expect($author->isMany())->toBeFalse();
    expect($author->typescriptType())->toBe('App.Models.NestedAuthor');

    $media = $movie->getRelation('media');
    expect($media->name())->toBe('media');
    expect($media->laravelType())->toBe('MorphMany');
    expect($media->relatedToModel())->toBe('Spatie\MediaLibrary\MediaCollections\Models\Media');
    expect($media->isInternal())->toBeFalse();
    expect($media->isMany())->toBeTrue();
    expect($media->typescriptType())->toBe('any[]');

    $members_count = $movie->getAttribute('members_count');
    expect($members_count->name())->toBe('members_count');
    expect($members_count->phpType())->toBe('int');
    expect($members_count->typescriptType())->toBe('number');
})->with(DatabaseDriverEnums());
