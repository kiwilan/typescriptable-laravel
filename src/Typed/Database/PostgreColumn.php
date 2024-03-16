<?php

namespace Kiwilan\Typescriptable\Typed\Database;

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
            'bigint' => 'bigint',
            'bigserial' => 'bigint',
            'bit' => 'string',
            'bit varying' => 'string',
            'boolean' => 'boolean',
            'box' => 'string',
            'bytea' => 'binary',
            'character' => 'string',
            'character varying' => 'string',
            'cidr' => 'string',
            'circle' => 'string',
            'date' => 'string',
            'double precision' => 'float',
            'inet' => 'string',
            'integer' => 'int',
            'interval' => 'string',
            'json' => 'json',
            'jsonb' => 'json',
            'line' => 'string',
            'lseg' => 'string',
            'macaddr' => 'string',
            'money' => 'string',
            'numeric' => 'float',
            'path' => 'string',
            'point' => 'string',
            'polygon' => 'string',
            'real' => 'float',
            'smallint' => 'smallint',
            'smallserial' => 'smallint',
            'serial' => 'int',
            'text' => 'text',
            'time' => 'string',
            'time without time zone' => 'string',
            'time with time zone' => 'string',
            'timestamp' => 'string',
            'timestamp without time zone' => 'string',
            'timestamp with time zone' => 'string',
            'tsquery' => 'string',
            'tsvector' => 'string',
            'txid_snapshot' => 'string',
            'uuid' => 'guid',
            'xml' => 'string',
            default => 'string',
        };
    }
}
