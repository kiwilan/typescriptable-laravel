<?php

use Kiwilan\Typescriptable\Tests\TestCase;
use Kiwilan\Typescriptable\Typed\Database\DriverEnum;
use Kiwilan\Typescriptable\Typed\Database\Table;
use Kiwilan\Typescriptable\Typed\Schema\SchemaAttribute;

beforeEach(function () {
    eloquentConfig();
});

it('can parse database', function (DriverEnum $driver) {
    TestCase::setupDatabase($driver->value);
    $table = Table::make('ts_movies', $driver);

    expect($table->getName())->toBe('ts_movies');
    expect($table->getDriver())->toBe($driver);
    expect($table->getAttributes())->toBeArray();
    expect($table->getAttributes())->toHaveCount(37);
    expect($table->getColumns())->toBeArray();
    expect($table->getColumns())->toHaveCount(37);

    $table->addAttribute(STORY_PUBLISHED_AT);
    expect($table->getAttribute('published_at'))->toBeInstanceOf(SchemaAttribute::class);

    match ($driver) {
        DriverEnum::mysql => expect($table->getSelect())->toBe('SHOW COLUMNS FROM ts_movies'),
        DriverEnum::mariadb => expect($table->getSelect())->toBe('SHOW COLUMNS FROM ts_movies'),
        DriverEnum::pgsql => expect($table->getSelect())->toBe("SELECT column_name, data_type, is_nullable, column_default FROM information_schema.columns WHERE table_name = 'ts_movies'"),
        DriverEnum::sqlite => expect($table->getSelect())->toBe('PRAGMA table_info(ts_movies)'),
        DriverEnum::sqlsrv => expect($table->getSelect())->toBe("SELECT COLUMN_NAME, DATA_TYPE, IS_NULLABLE, COLUMN_DEFAULT FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'ts_movies'"),
        default => expect($table->getSelect())->toBeNull(),
    };
})->with(DriverEnum::getRelationalDrivers());

it('fail on mongodb (because mongodb use manual parser)', function () {
    TestCase::setupDatabase('mongodb');
    expect(fn () => Table::make('ts_movies', DriverEnum::mongodb))->toThrow(Exception::class);
});
