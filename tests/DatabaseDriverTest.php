<?php

use Kiwilan\Typescriptable\Typed\Database\DatabaseDriverEnum;

it('returns mixed for null or empty database type', function () {
    expect(DatabaseDriverEnum::sqlite->toPhp(null))->toBe('mixed');
    expect(DatabaseDriverEnum::sqlite->toPhp(''))->toBe('mixed');
    expect(DatabaseDriverEnum::mysql->toPhp(null))->toBe('mixed');
    expect(DatabaseDriverEnum::mysql->toPhp(''))->toBe('mixed');
    expect(DatabaseDriverEnum::mariadb->toPhp(null))->toBe('mixed');
    expect(DatabaseDriverEnum::mariadb->toPhp(''))->toBe('mixed');
    expect(DatabaseDriverEnum::pgsql->toPhp(null))->toBe('mixed');
    expect(DatabaseDriverEnum::pgsql->toPhp(''))->toBe('mixed');
    expect(DatabaseDriverEnum::sqlsrv->toPhp(null))->toBe('mixed');
    expect(DatabaseDriverEnum::sqlsrv->toPhp(''))->toBe('mixed');
});

it('removes unwanted keywords and parentheses', function () {
    expect(DatabaseDriverEnum::mysql->toPhp('int unsigned'))->toBe('int');
    expect(DatabaseDriverEnum::mysql->toPhp('varchar(255)'))->toBe('string');
    expect(DatabaseDriverEnum::pgsql->toPhp('character varying(255)'))->toBe('string');
    expect(DatabaseDriverEnum::sqlsrv->toPhp('numeric(10, 2)'))->toBe('string');
    expect(DatabaseDriverEnum::mariadb->toPhp('int unsigned'))->toBe('int');
    expect(DatabaseDriverEnum::sqlite->toPhp('big int'))->toBe('int');
});

it('handles complex database types', function () {
    expect(DatabaseDriverEnum::mysql->toPhp('tinyint(4) unsigned'))->toBe('int');
    expect(DatabaseDriverEnum::pgsql->toPhp('character varying (255)'))->toBe('string');
    expect(DatabaseDriverEnum::sqlsrv->toPhp('decimal (10, 2) unsigned'))->toBe('string');
    expect(DatabaseDriverEnum::mariadb->toPhp('int (11) unsigned'))->toBe('int');
    expect(DatabaseDriverEnum::sqlite->toPhp('big int (20)'))->toBe('int');
});

it('converts MySQL types to PHP types', function () {
    $types = [
        'tinyint' => 'int',
        'smallint' => 'int',
        'mediumint' => 'int',
        'int' => 'int',
        'bigint' => 'int',
        'decimal' => 'string',
        'numeric' => 'string',
        'float' => 'float',
        'double' => 'float',
        'bit' => 'int',
        'char' => 'string',
        'varchar' => 'string',
        'binary' => 'string',
        'varbinary' => 'string',
        'tinyblob' => 'string',
        'blob' => 'string',
        'mediumblob' => 'string',
        'longblob' => 'string',
        'tinytext' => 'string',
        'text' => 'string',
        'mediumtext' => 'string',
        'longtext' => 'string',
        'enum' => 'string',
        'set' => 'string',
        'date' => 'string',
        'datetime' => 'string',
        'timestamp' => 'string',
        'time' => 'string',
        'year' => 'int',
        'json' => 'string',
        'geometry' => 'string',
        'point' => 'string',
        'linestring' => 'string',
        'polygon' => 'string',
        'multipoint' => 'string',
        'multilinestring' => 'string',
        'multipolygon' => 'string',
        'geometrycollection' => 'string',
        'unknown' => 'string',
    ];

    foreach ($types as $dbType => $phpType) {
        expect(DatabaseDriverEnum::mysql->toPhp($dbType))->toBe($phpType);
    }
});

it('converts MariaDB types to PHP types', function () {
    $types = [
        'tinyint' => 'int',
        'smallint' => 'int',
        'mediumint' => 'int',
        'int' => 'int',
        'integer' => 'int',
        'bigint' => 'int',
        'decimal' => 'string',
        'dec' => 'string',
        'numeric' => 'string',
        'fixed' => 'string',
        'float' => 'float',
        'double' => 'float',
        'bit' => 'int',
        'char' => 'string',
        'varchar' => 'string',
        'binary' => 'string',
        'varbinary' => 'string',
        'tinyblob' => 'string',
        'blob' => 'string',
        'mediumblob' => 'string',
        'longblob' => 'string',
        'tinytext' => 'string',
        'text' => 'string',
        'mediumtext' => 'string',
        'longtext' => 'string',
        'enum' => 'string',
        'set' => 'string',
        'json' => 'string',
        'date' => 'string',
        'datetime' => 'string',
        'timestamp' => 'string',
        'time' => 'string',
        'year' => 'int',
        'geometry' => 'string',
        'point' => 'string',
        'linestring' => 'string',
        'polygon' => 'string',
        'multipoint' => 'string',
        'multilinestring' => 'string',
        'multipolygon' => 'string',
        'geometrycollection' => 'string',
        'unknown' => 'string',
    ];

    foreach ($types as $dbType => $phpType) {
        expect(DatabaseDriverEnum::mariadb->toPhp($dbType))->toBe($phpType);
    }
});

it('converts PostgreSQL types to PHP types', function () {
    $types = [
        'bigint' => 'int',
        'int8' => 'int',
        'bigserial' => 'int',
        'serial8' => 'int',
        'bit' => 'string',
        'varbit' => 'string',
        'boolean' => 'bool',
        'bool' => 'bool',
        'box' => 'string',
        'bytea' => 'string',
        'character' => 'string',
        'char' => 'string',
        'character varying' => 'string',
        'varchar' => 'string',
        'cidr' => 'string',
        'circle' => 'string',
        'date' => 'string',
        'double' => 'float',
        'float8' => 'float',
        'inet' => 'string',
        'integer' => 'int',
        'int' => 'int',
        'int4' => 'int',
        'interval' => 'string',
        'json' => 'string',
        'jsonb' => 'string',
        'line' => 'string',
        'lseg' => 'string',
        'macaddr' => 'string',
        'macaddr8' => 'string',
        'money' => 'string',
        'numeric' => 'string',
        'decimal' => 'string',
        'path' => 'string',
        'pg_lsn' => 'string',
        'point' => 'string',
        'polygon' => 'string',
        'real' => 'float',
        'float4' => 'float',
        'smallint' => 'int',
        'int2' => 'int',
        'smallserial' => 'int',
        'serial2' => 'int',
        'serial' => 'int',
        'serial4' => 'int',
        'text' => 'string',
        'time' => 'string',
        'timetz' => 'string',
        'timestamp' => 'string',
        'timestamptz' => 'string',
        'tsquery' => 'string',
        'tsvector' => 'string',
        'txid_snapshot' => 'string',
        'uuid' => 'string',
        'xml' => 'string',
        'unknown' => 'string',
    ];

    foreach ($types as $dbType => $phpType) {
        expect(DatabaseDriverEnum::pgsql->toPhp($dbType))->toBe($phpType);
    }
});

it('converts SQLite types to PHP types', function () {
    $types = [
        'int' => 'int',
        'integer' => 'int',
        'tinyint' => 'int',
        'smallint' => 'int',
        'mediumint' => 'int',
        'bigint' => 'int',
        'big int' => 'int',
        'int2' => 'int',
        'int8' => 'int',
        'character' => 'string',
        'varchar' => 'string',
        'nchar' => 'string',
        'nvarchar' => 'string',
        'text' => 'string',
        'clob' => 'string',
        'blob' => 'string',
        'real' => 'float',
        'double' => 'float',
        'float' => 'float',
        'numeric' => 'string',
        'decimal' => 'string',
        'boolean' => 'bool',
        'date' => 'string',
        'datetime' => 'string',
        'unknown' => 'string',
    ];

    foreach ($types as $dbType => $phpType) {
        expect(DatabaseDriverEnum::sqlite->toPhp($dbType))->toBe($phpType);
    }
});

it('converts SQL Server types to PHP types', function () {
    $types = [
        'bigint' => 'int',
        'binary' => 'string',
        'bit' => 'bool',
        'char' => 'string',
        'date' => 'string',
        'datetime' => 'string',
        'datetime2' => 'string',
        'datetimeoffset' => 'string',
        'decimal' => 'string',
        'float' => 'float',
        'geography' => 'string',
        'geometry' => 'string',
        'hierarchyid' => 'string',
        'image' => 'string',
        'int' => 'int',
        'money' => 'string',
        'nchar' => 'string',
        'ntext' => 'string',
        'numeric' => 'string',
        'nvarchar' => 'string',
        'real' => 'float',
        'smalldatetime' => 'string',
        'smallint' => 'int',
        'smallmoney' => 'string',
        'sql_variant' => 'string',
        'text' => 'string',
        'time' => 'string',
        'timestamp' => 'string',
        'tinyint' => 'int',
        'uniqueidentifier' => 'string',
        'varbinary' => 'string',
        'varchar' => 'string',
        'xml' => 'string',
        'unknown' => 'string',
    ];

    foreach ($types as $dbType => $phpType) {
        expect(DatabaseDriverEnum::sqlsrv->toPhp($dbType))->toBe($phpType);
    }
});
