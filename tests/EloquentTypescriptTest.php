<?php

use Kiwilan\Typescriptable\Tests\TestCase;
use Kiwilan\Typescriptable\Typed\Eloquent\EloquentConfig;
use Kiwilan\Typescriptable\Typed\Utils\TypescriptToPhp;
use Kiwilan\Typescriptable\Typescriptable;
use Kiwilan\Typescriptable\TypescriptableConfig;

beforeEach(function () {
    deleteFile(outputDir('types-eloquent.d.ts'));
    deleteDir(outputDir('php'));
});

it('can be run', function (string $driver) {
    TestCase::setupDatabase($driver);

    $type = Typescriptable::models(new EloquentConfig(
        modelsPath: models(),
        outputPath: outputDir(),
        phpPath: outputDir().'/php',
        useParser: false,
        skipModels: ['Kiwilan\\Typescriptable\\Tests\\Data\\Models\\SushiTest'],
    ));

    $app = $type->app();
    expect($app->driver())->toBe($driver);

    $models = outputDir(TypescriptableConfig::eloquentFilename());
    expect($models)->toBeFile();

    $models = outputDir(TypescriptableConfig::eloquentFilename());
    $ts = TypescriptToPhp::make($models);
    $data = $ts->raw();

    $movie = $data['Movie'];

    $id = $movie['id'];
    expect($id['type'])->toBe('string');
    expect($id['nullable'])->toBeFalse();

    $title = $movie['title'];
    expect($title['type'])->toBe('string');
    expect($title['nullable'])->toBeTrue();

    $subtitles = $movie['subtitles'];
    expect($subtitles['type'])->toBe('any[]');
    expect($subtitles['nullable'])->toBeFalse();

    $homepage = $movie['homepage'];
    expect($homepage['type'])->toBe("'draft' | 'scheduled' | 'published'");
    expect($homepage['nullable'])->toBeTrue();

    $budget = $movie['budget'];
    expect($budget['type'])->toBe("'draft' | 'scheduled' | 'published'");
    expect($budget['nullable'])->toBeFalse();

    $revenue = $movie['revenue'];
    expect($revenue['type'])->toBe('number');
    expect($revenue['nullable'])->toBeTrue();

    $isMultilingual = $movie['is_multilingual'];
    expect($isMultilingual['type'])->toBe('boolean');
    expect($isMultilingual['nullable'])->toBeFalse();

    $authorId = $movie['author_id'];
    expect($authorId['type'])->toBe('number');
    expect($authorId['nullable'])->toBeTrue();

    $addedAt = $movie['added_at'];
    expect($addedAt['type'])->toBe('string');
    expect($addedAt['nullable'])->toBeTrue();

    $fetchedAt = $movie['fetched_at'];
    expect($fetchedAt['type'])->toBe('string');
    expect($fetchedAt['nullable'])->toBeTrue();

    $showRoute = $movie['show_route'];
    expect($showRoute['type'])->toBe('string');
    expect($showRoute['nullable'])->toBeTrue();

    $apiRoute = $movie['api_route'];
    expect($apiRoute['type'])->toBe('string');
    expect($apiRoute['nullable'])->toBeTrue();

    $similars = $movie['similars'];
    expect($similars['type'])->toBe('App.Models.Movie[]');
    expect($similars['nullable'])->toBeTrue();

    $recommendations = $movie['recommendations'];
    expect($recommendations['type'])->toBe('App.Models.Movie[]');
    expect($recommendations['nullable'])->toBeTrue();

    $members = $movie['members'];
    expect($members['type'])->toBe('App.Models.Member[]');
    expect($members['nullable'])->toBeTrue();

    $author = $movie['author'];
    expect($author['type'])->toBe('App.Models.NestedAuthor');
    expect($author['nullable'])->toBeTrue();

    $media = $movie['media'];
    expect($media['type'])->toBe('any[]');
    expect($media['nullable'])->toBeTrue();

    $members_count = $movie['members_count'];
    expect($members_count['type'])->toBe('number');
    expect($members_count['nullable'])->toBeTrue();

    $classes = $ts->onlyModels();
    expect(count($app->models()))->toBe(count($classes));

    // foreach ($app->models() as $namespace => $model) {
    //     expect(array_key_exists($namespace, $classes))->toBeTrue();

    //     $tsProperties = $classes[$namespace]->properties();
    //     if (! array_key_exists('pivot', $model)) {
    //         expect(count($tsProperties))->toBe(count($model));
    //     }

    //     expect(array_key_exists($namespace, $data))->toBeTrue();
    //     foreach ($model as $key => $property) {
    //         $tsProperty = $tsProperties[$key];

    //         expect(array_key_exists($key, $tsProperties))->toBeTrue();
    //         if (! is_array($property)) {
    //             expect($property->name())->toBe($tsProperty->name());
    //         }
    //         // expect($property->typeTs())->toBe($tsProperty->type());
    //         // expect($property->isNullable())->toBe($tsProperty->isNullable());
    //     }
    // }
})->with(DatabaseDriverEnums());
