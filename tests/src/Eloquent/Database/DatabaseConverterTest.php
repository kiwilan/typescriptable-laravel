<?php

use Kiwilan\Typescriptable\Eloquent\Database\DatabaseConverter;
use Kiwilan\Typescriptable\Eloquent\Database\DriverEnum;
use Kiwilan\Typescriptable\Tests\TestCase;

it('can convert database type', function () {
    TestCase::setupDatabase(DriverEnum::mysql);

    $db = DatabaseConverter::make(DriverEnum::mysql, 'int', null);

    expect($db->getDatabaseDriver())->toBe(DriverEnum::mysql);
    expect($db->getDatabaseType())->toBe('int');
    expect($db->getPhpType())->toBe('int');
    expect($db->getCastType())->toBeNull();
    expect($db->getTypescriptType())->toBe('number');

    $db = DatabaseConverter::make(DriverEnum::mysql, 'integer', 'int');
    expect($db->getDatabaseType())->toBe('integer');
    expect($db->getPhpType())->toBe('string');
    expect($db->getCastType())->toBe('int');
    expect($db->getTypescriptType())->toBe('number');
});

it('can convert types', function (string $cast) {
    TestCase::setupDatabase(DriverEnum::mysql);

    $db = DatabaseConverter::make(DriverEnum::mysql, 'string', $cast);
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
