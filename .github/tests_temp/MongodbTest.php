<?php

use Kiwilan\Typescriptable\Tests\TestCase;
use Kiwilan\Typescriptable\Eloquent\Eloquent\EloquentType;
use Kiwilan\Typescriptable\Eloquent\Utils\TypescriptToPhp;
use Kiwilan\Typescriptable\TypescriptableConfig;

it('can be run', function (string $engine) {
    eloquentConfig($engine);
    TestCase::setupDatabase('mongodb');

    $type = EloquentType::make()->execute();

    $app = $type->app();
    $movie = $app->getModel('Kiwilan\Typescriptable\Tests\Data\Models\Movie');

    $app = $type->app();
    expect($app->driver())->toBe('mongodb');

    $models = pathOutput(TypescriptableConfig::eloquentFilename());
    expect($models)->toBeFile();

    $models = pathOutput(TypescriptableConfig::eloquentFilename());
    $ts = TypescriptToPhp::make($models);
    $data = $ts->raw();

    $movie = $data['Movie'];

    $id = $movie['id'];
    expect($id['type'])->toBe('string');

    $title = $movie['title'];
    expect($title['type'])->toBe('string');

    $subtitles = $movie['subtitles'];
    expect($subtitles['type'])->toBe('any[]');

    $homepage = $movie['homepage'];
    expect($homepage['type'])->toBe("'draft' | 'scheduled' | 'published'");

    $budget = $movie['budget'];
    expect($budget['type'])->toBe("'draft' | 'scheduled' | 'published'");

    $revenue = $movie['revenue'];
    expect($revenue['type'])->toBe('number');

    $isMultilingual = $movie['is_multilingual'];
    expect($isMultilingual['type'])->toBe('boolean');

    $addedAt = $movie['added_at'];
    expect($addedAt['type'])->toBe('string');

    $fetchedAt = $movie['fetched_at'];
    expect($fetchedAt['type'])->toBe('string');

    $edition = $movie['edition'];
    expect($edition['type'])->toBe('string');

    $showRoute = $movie['show_route'];
    expect($showRoute['type'])->toBe('string');

    $apiRoute = $movie['api_route'];
    expect($apiRoute['type'])->toBe('string');

    $similars = $movie['similars'];
    expect($similars['type'])->toBe('App.Models.Movie[]');

    $recommendations = $movie['recommendations'];
    expect($recommendations['type'])->toBe('App.Models.Movie[]');

    $members = $movie['members'];
    expect($members['type'])->toBe('App.Models.Member[]');

    $author = $movie['author'];
    expect($author['type'])->toBe('App.Models.NestedAuthor');

    $media = $movie['media'];
    expect($media['type'])->toBe('any[]');

    $members_count = $movie['members_count'];
    expect($members_count['type'])->toBe('number');

    $classes = $ts->onlyModels();
    expect(count($app->getModelsPath()))->toBe(count($classes));
})->with(['artisan', 'parser']);
