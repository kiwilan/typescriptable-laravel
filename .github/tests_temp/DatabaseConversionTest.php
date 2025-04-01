<?php

use Kiwilan\Typescriptable\Tests\TestCase;
use Kiwilan\Typescriptable\Typed\Database\DatabaseConverter;
use Kiwilan\Typescriptable\Typed\Database\DriverEnum;

it('can convert database type', function () {
    TestCase::setupDatabase('mysql');

    $db = DatabaseConverter::make('mysql', 'int', null);

    $enum = DriverEnum::tryFrom('mysql');
    expect($db->databaseDriver())->toBe($enum);
    expect($db->databaseType())->toBe('int');
    expect($db->phpType())->toBe('int');
    expect($db->castType())->toBeNull();
    expect($db->typescriptType())->toBe('number');

    $db = DatabaseConverter::make('mysql', 'integer', 'int');
    expect($db->databaseType())->toBe('integer');
    expect($db->phpType())->toBe('string');
    expect($db->castType())->toBe('int');
    expect($db->typescriptType())->toBe('number');
});

it('can convert types', function (string $cast) {
    TestCase::setupDatabase('mysql');

    $db = DatabaseConverter::make('mysql', 'string', $cast);
    expect($db->castType())->toBe($cast);
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
