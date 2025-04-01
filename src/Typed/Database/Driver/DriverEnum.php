<?php

namespace Kiwilan\Typescriptable\Typed\Database\Driver;

enum DriverEnum: string
{
    case sqlite = 'sqlite';
    case mysql = 'mysql';
    case mariadb = 'mariadb';
    case pgsql = 'pgsql';
    case sqlsrv = 'sqlsrv';
    case mongodb = 'mongodb';

    /**
     * Convert database type to PHP type.
     */
    public function toPhp(?string $databaseType): string
    {
        if (! $databaseType) {
            return 'mixed';
        }

        // Remove everything after parentheses
        $databaseType = strtolower($databaseType);
        if (str_contains($databaseType, '(')) {
            $databaseType = substr($databaseType, 0, strpos($databaseType, '('));
        }

        // Remove some keywords
        $toRemove = ['unsigned', 'varying', 'native', 'big'];
        foreach ($toRemove as $remove) {
            $databaseType = str_replace("{$remove} ", '', $databaseType);
        }
        $databaseType = trim($databaseType);

        // Remove spaces and everything after space
        if (str_contains($databaseType, ' ')) {
            $databaseType = substr($databaseType, 0, strpos($databaseType, ' '));
            $databaseType = trim($databaseType);
        }

        return match ($this) {
            self::sqlite => $this->toSqlite($databaseType),
            self::mysql => $this->toMysql($databaseType),
            self::mariadb => $this->toMariadb($databaseType),
            self::pgsql => $this->toPgsql($databaseType),
            self::sqlsrv => $this->toSqlsrv($databaseType),
            default => 'string',
        };
    }

    private function toMysql(string $type): string
    {
        return match ($type) {
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
            default => 'string',
        };
    }

    private function toMariadb(string $type): string
    {
        return match ($type) {
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
            default => 'string',
        };
    }

    private function toPgsql(string $type): string
    {
        return match ($type) {
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
            'character' => 'string',
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
            default => 'string',
        };
    }

    private function toSqlite(string $type): string
    {
        return match ($type) {
            'int' => 'int',
            'integer' => 'int',
            'tinyint' => 'int',
            'smallint' => 'int',
            'mediumint' => 'int',
            'bigint' => 'int',
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
            default => 'string',
        };
    }

    private function toSqlsrv(string $type): string
    {
        return match ($type) {
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
            default => 'string',
        };
    }
}
