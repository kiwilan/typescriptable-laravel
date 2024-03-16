<?php

namespace Kiwilan\Typescriptable\Typed\Database;

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
            'binary' => 'binary',
            'bit' => 'boolean',
            'char' => 'string',
            'date' => 'string',
            'string' => 'string',
            'datetime2' => 'string',
            'datetimeoffset' => 'string',
            'decimal' => 'float',
            'float' => 'float',
            'image' => 'binary',
            'int' => 'int',
            'money' => 'float',
            'nchar' => 'string',
            'ntext' => 'string',
            'numeric' => 'float',
            'nvarchar' => 'string',
            'real' => 'float',
            'smalldatetime' => 'string',
            'smallint' => 'int',
            'smallmoney' => 'float',
            'text' => 'string',
            'time' => 'string',
            'timestamp' => 'binary',
            'tinyint' => 'int',
            'uniqueidentifier' => 'guid',
            'varbinary' => 'binary',
            'varchar' => 'string',
            'xml' => 'string',
            default => 'string',
        };
    }
}
