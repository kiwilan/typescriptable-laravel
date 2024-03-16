<?php

namespace Kiwilan\Typescriptable\Typed\Database;

class SqliteColumn implements IColumn
{
    public const TYPE = 'sqlite';

    public const TABLE_NAME = 'name';

    public const TABLE_TYPE = 'type';

    protected function __construct(
        public ?int $cid = null,
        public ?string $name = null,
        public string $type = 'YES',
        public ?int $notnull = null,
        public ?string $dflt_value = null,
        public ?int $pk = null,
    ) {
    }

    public static function make(array|object $data, string $table, string $driver): Column
    {
        $data = Column::toArray($data);

        $self = new self(
            $data['cid'] ?? null,
            $data['name'] ?? null,
            $data['type'] ?? 'YES',
            $data['notnull'] ?? null,
            $data['dflt_value'] ?? null,
            $data['pk'] ?? null,
        );

        return Column::convert(
            $driver,
            $table,
            $self->name,
            $self->type,
            $self->notnull === 0,
            $self->pk === 1,
        );
    }

    public static function typeToPhp(string $formatType): string
    {
        return match ($formatType) {
            'INT' => 'int',
            'INTEGER' => 'int',
            'TINYINT' => 'int',
            'SMALLINT' => 'int',
            'MEDIUMINT' => 'int',
            'BIGINT' => 'int',
            'UNSIGNED BIG INT' => 'int',
            'INT2' => 'int',
            'INT8' => 'int',
            'CHARACTER(20)' => 'string',
            'VARCHAR(255)' => 'string',
            'VARYING CHARACTER(255)' => 'string',
            'NCHAR(55)' => 'string',
            'NATIVE CHARACTER' => 'string',
            'NVARCHAR(100)' => 'string',
            'TEXT' => 'string',
            'CLOB' => 'string',
            'BLOB' => 'string',
            'REAL' => 'float',
            'DOUBLE' => 'float',
            'DOUBLE PRECISION' => 'float',
            'FLOAT' => 'float',
            'NUMERIC' => 'float',
            'DECIMAL(10,5)' => 'float',
            'BOOLEAN' => 'boolean',
            'DATE' => 'string',
            'DATETIME' => 'string',
            'TIMESTAMP' => 'string',
            'TIME' => 'time',
            'BLOB' => 'binary',
            default => 'string',
        };
    }
}
