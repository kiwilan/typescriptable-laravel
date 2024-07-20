<?php

use Kiwilan\Typescriptable\Tests\TestCase;
use Kiwilan\Typescriptable\Typed\Eloquent\EloquentConfig;
use Kiwilan\Typescriptable\Typed\Utils\Schema\SchemaClass;
use Kiwilan\Typescriptable\Typescriptable;

it('can be run with parser', function (string $driver) {
    TestCase::setupDatabase($driver);

    $type = Typescriptable::models(new EloquentConfig(
        modelsPath: models(),
        outputPath: outputDir(),
        phpPath: outputDir().'/php',
        useParser: true,
        skipModels: ['Kiwilan\\Typescriptable\\Tests\\Data\\Models\\SushiTest'],
    ));

    $app = $type->app();
    ray($app);

    expect($app->modelPath())->toBe(models());
    expect($app->useParser())->toBeTrue();
    expect($app->baseNamespace())->toBe('Kiwilan\Typescriptable\Tests\Data\Models');
    expect($app->models())->toBeArray();
    expect(count($app->models()))->toBe(8);
    expect($app->driver())->toBe($driver);
    expect($app->databaseName())->toBeIn(['testing', ':memory:']);
    expect($app->databasePrefix())->toBe('ts_');

    $config = $type->config();

    expect($config->modelsPath)->toBe(models());
    expect($config->outputPath)->toBe(outputDir());
    expect($config->phpPath)->toBe(outputDir().'/php');
    expect($config->useParser)->toBeTrue();
    expect($config->tsFilename)->toBe('types-eloquent.d.ts');
    expect($config->skipModels)->toBeArray();
    expect(count($config->skipModels))->toBe(1);
    expect($config->skipModels[0])->toBe('Kiwilan\Typescriptable\Tests\Data\Models\SushiTest');

    $movie = $app->getModel('Kiwilan\Typescriptable\Tests\Data\Models\Movie');

    expect($movie->schemaClass())->toBeInstanceOf(SchemaClass::class);
    expect($movie->namespace())->toBe('Kiwilan\Typescriptable\Tests\Data\Models\Movie');
    expect($movie->driver())->toBe($driver);
    expect($movie->table())->toBe('ts_movies');
    expect($movie->policy())->toBeNull();
    expect($movie->attributes())->toBeArray();
    expect(count($movie->attributes()))->toBe(39);
    expect($movie->relations())->toBeArray();
    expect(count($movie->relations()))->toBe(5);
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
    expect($id->databaseType())->toBeIn(['char(26)', 'varchar', 'character', 'nchar']);
    expect($id->nullable())->toBeFalse();
    expect($id->default())->toBeNull();
    expect($id->fillable())->toBeFalse();
    expect($id->hidden())->toBeFalse();
    expect($id->appended())->toBeFalse();
    expect($id->cast())->toBeNull();
    expect($id->phpType())->toBe('string');
    expect($id->typescriptType())->toBe('string');

    $title = $movie->getAttribute('title');
    expect($title->name())->toBe('title');
    expect($title->databaseType())->toBeIn(['varchar(255)', 'varchar', 'character varying', 'nvarchar']);
    expect($title->nullable())->toBeTrue();
    expect($title->default())->toBeNull();
    expect($title->fillable())->toBeTrue();
    expect($title->hidden())->toBeFalse();
    expect($title->appended())->toBeFalse();
    expect($title->cast())->toBeNull();
    expect($title->phpType())->toBe('string');
    expect($title->typescriptType())->toBe('string');

    $budget = $movie->getAttribute('budget');
    expect($budget->name())->toBe('budget');
    expect($budget->databaseType())->toBeIn(["enum('draft','scheduled','published')", 'varchar', 'character varying', 'nvarchar']);
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

    $members = $movie->getRelation('members');
    expect($members->name())->toBe('members');
    expect($members->laravelType())->toBe('MorphToMany');
    expect($members->relatedToModel())->toBe('Kiwilan\Typescriptable\Tests\Data\Models\Member');
    expect($members->isInternal())->toBeTrue();
    expect($members->isPlural())->toBeTrue();
    expect($members->typescriptType())->toBe('App.Models.Member[]');

    $author = $movie->getRelation('author');
    expect($author->name())->toBe('author');
    expect($author->laravelType())->toBe('BelongsTo');
    expect($author->relatedToModel())->toBe('Kiwilan\Typescriptable\Tests\Data\Models\Nested\Author');
    expect($author->isInternal())->toBeTrue();
    expect($author->isPlural())->toBeFalse();
    expect($author->typescriptType())->toBe('App.Models.NestedAuthor');

    $media = $movie->getRelation('media');
    expect($media->name())->toBe('media');
    expect($media->laravelType())->toBe('MorphMany');
    expect($media->relatedToModel())->toBe('Spatie\MediaLibrary\MediaCollections\Models\Media');
    expect($media->isInternal())->toBeFalse();
    expect($media->isPlural())->toBeTrue();
    expect($media->typescriptType())->toBe('any[]');
})->with(databaseDrivers());
