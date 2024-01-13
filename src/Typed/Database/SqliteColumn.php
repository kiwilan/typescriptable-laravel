<?php

namespace Kiwilan\Typescriptable\Typed\Database;

use Doctrine\DBAL\Types\Types;

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
            'CHARACTER(20)' => Types::STRING,
            'VARCHAR(255)' => Types::STRING,
            'VARYING CHARACTER(255)' => Types::STRING,
            'NCHAR(55)' => Types::STRING,
            'NATIVE CHARACTER' => Types::STRING,
            'NVARCHAR(100)' => Types::STRING,
            'TEXT' => Types::STRING,
            'CLOB' => Types::STRING,
            'BLOB' => Types::STRING,
            'REAL' => Types::FLOAT,
            'DOUBLE' => Types::FLOAT,
            'DOUBLE PRECISION' => Types::FLOAT,
            'FLOAT' => Types::FLOAT,
            'NUMERIC' => Types::FLOAT,
            'DECIMAL(10,5)' => Types::FLOAT,
            'BOOLEAN' => Types::BOOLEAN,
            'DATE' => Types::STRING,
            'DATETIME' => Types::STRING,
            'TIMESTAMP' => Types::STRING,
            'TIME' => Types::TIME_MUTABLE,
            'BLOB' => Types::BINARY,
            default => Types::STRING,
        };
    }
}
