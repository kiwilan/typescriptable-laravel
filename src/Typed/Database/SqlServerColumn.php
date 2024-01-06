<?php

namespace Kiwilan\Typescriptable\Typed\Database;

use Doctrine\DBAL\Types\Types;

class SqlServerColumn implements IColumn
{
    public const TYPE = 'sqlsrv';

    public const TABLE_NAME = 'name';

    public const TABLE_TYPE = 'type';

    protected function __construct(
        public ?string $columnName = null,
        public ?string $dataType = null,
    ) {
    }

    public static function make(array|object $data, string $table, string $driver): Column
    {
        $data = Column::toArray($data);

        $self = new self(
            $data['COLUMN_NAME'] ?? null,
            $data['DATA_TYPE'] ?? null,
        );

        return Column::convert(
            $driver,
            $table,
            $self->columnName,
            $self->dataType,
        );
    }

    public static function typeToPhp(string $formatType): string
    {
        return match ($formatType) {
            'bigint' => 'int',
            'binary' => Types::BINARY,
            'bit' => Types::BOOLEAN,
            'char' => Types::STRING,
            'date' => 'DateTime',
            'datetime' => 'DateTime',
            'datetime2' => 'DateTime',
            'datetimeoffset' => 'DateTime',
            'decimal' => Types::FLOAT,
            'float' => Types::FLOAT,
            'image' => Types::BINARY,
            'int' => 'int',
            'money' => Types::FLOAT,
            'nchar' => Types::STRING,
            'ntext' => Types::STRING,
            'numeric' => Types::FLOAT,
            'nvarchar' => Types::STRING,
            'real' => Types::FLOAT,
            'smalldatetime' => 'DateTime',
            'smallint' => 'int',
            'smallmoney' => Types::FLOAT,
            'text' => Types::STRING,
            'time' => Types::TIME_IMMUTABLE,
            'timestamp' => Types::BINARY,
            'tinyint' => 'int',
            'uniqueidentifier' => Types::GUID,
            'varbinary' => Types::BINARY,
            'varchar' => Types::STRING,
            'xml' => Types::STRING,
            default => Types::STRING,
        };
    }
}
