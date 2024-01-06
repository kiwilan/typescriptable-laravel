<?php

namespace Kiwilan\Typescriptable\Typed\Database;

use Doctrine\DBAL\Types\Types;

class PostgreColumn implements IColumn
{
    public const TYPE = 'pgsql';

    public const TABLE_NAME = 'tablename';

    public const TABLE_TYPE = 'qualifiedname';

    protected function __construct(
        public ?string $column_name = null,
        public ?string $data_type = null,
    ) {
    }

    public static function make(array|object $data, string $table, string $driver): Column
    {
        $data = Column::toArray($data);

        $self = new self(
            $data['column_name'] ?? null,
            $data['data_type'] ?? null,
        );

        return Column::convert(
            $driver,
            $table,
            $self->column_name,
            $self->data_type,
            true,
            false,
        );
    }

    public static function typeToPhp(string $formatType): string
    {
        return match ($formatType) {
            'bigint' => Types::BIGINT,
            'bigserial' => Types::BIGINT,
            'bit' => Types::STRING,
            'bit varying' => Types::STRING,
            'boolean' => Types::BOOLEAN,
            'box' => Types::STRING,
            'bytea' => Types::BINARY,
            'character' => Types::STRING,
            'character varying' => Types::STRING,
            'cidr' => Types::STRING,
            'circle' => Types::STRING,
            'date' => Types::DATE_MUTABLE,
            'double precision' => Types::FLOAT,
            'inet' => Types::STRING,
            'integer' => 'int',
            'interval' => Types::STRING,
            'json' => Types::JSON,
            'jsonb' => Types::JSON,
            'line' => Types::STRING,
            'lseg' => Types::STRING,
            'macaddr' => Types::STRING,
            'money' => Types::STRING,
            'numeric' => Types::FLOAT,
            'path' => Types::STRING,
            'point' => Types::STRING,
            'polygon' => Types::STRING,
            'real' => Types::FLOAT,
            'smallint' => Types::SMALLINT,
            'smallserial' => Types::SMALLINT,
            'serial' => 'int',
            'text' => Types::TEXT,
            'time' => Types::TIME_MUTABLE,
            'time without time zone' => Types::TIME_MUTABLE,
            'time with time zone' => Types::TIME_MUTABLE,
            'timestamp' => Types::DATETIME_MUTABLE,
            'timestamp without time zone' => Types::DATETIME_MUTABLE,
            'timestamp with time zone' => Types::DATETIME_MUTABLE,
            'tsquery' => Types::STRING,
            'tsvector' => Types::STRING,
            'txid_snapshot' => Types::STRING,
            'uuid' => Types::GUID,
            'xml' => Types::STRING,
            default => Types::STRING,
        };
    }
}
