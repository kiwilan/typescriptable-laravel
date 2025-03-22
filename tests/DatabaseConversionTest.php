<?php

use Kiwilan\Typescriptable\Tests\TestCase;
use Kiwilan\Typescriptable\Typed\Database\DatabaseConversion;
use Kiwilan\Typescriptable\Typed\Database\DatabaseDriverEnum;

it('can convert database type', function () {
    TestCase::setupDatabase('mysql');

    $db = DatabaseConversion::make('mysql', 'int', null);

    $enum = DatabaseDriverEnum::tryFrom('mysql');
    expect($db->getDatabaseDriver())->toBe($enum);
    expect($db->getDatabaseType())->toBe('int');
    expect($db->getPhpType())->toBe('int');
    expect($db->getCastType())->toBeNull();
    expect($db->getTypescriptType())->toBe('number');

    $db = DatabaseConversion::make('mysql', 'integer', 'int');
    expect($db->getDatabaseType())->toBe('integer');
    expect($db->getPhpType())->toBe('string');
    expect($db->getCastType())->toBe('int');
    expect($db->getTypescriptType())->toBe('number');
});

it('can convert types', function (string $cast) {
    TestCase::setupDatabase('mysql');

    $db = DatabaseConversion::make('mysql', 'string', $cast);
    expect($db->getCastType())->toBe($cast);
})->with([
    'collection',
    'encrypted:array',
    'encrypted:collection',
    'encrypted:object',
    'object',
    'date',
    'immutable_date',
    'immutable_datetime',
    'timestamp',
    'decimal',
    'double',
    'encrypted',
    'hashed',
    'real',
    'string',
]);
