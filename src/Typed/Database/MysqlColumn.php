<?php

namespace Kiwilan\Typescriptable\Typed\Database;

class MysqlColumn implements IColumn
{
    public const TYPE = 'mysql';

    public const TABLE_NAME = 'Tables_in_testing';

    public const TABLE_TYPE = 'Table_type';

    protected function __construct(
        public ?string $Field = null,
        public ?string $Type = null,
        public string $Null = 'YES',
        public ?string $Key = null,
        public ?string $Default = null,
        public ?string $Extra = null,
    ) {}

    public static function make(array|object $data, string $table, string $driver): Column
    {
        $data = Column::toArray($data);

        $self = new self(
            $data['Field'] ?? null,
            $data['Type'] ?? null,
            $data['Null'] ?? 'YES',
            $data['Key'] ?? null,
            $data['Default'] ?? null,
            $data['Extra'] ?? null,
        );

        return Column::convert(
            $driver,
            $table,
            $self->Field,
            $self->Type,
            $self->Null === 'YES',
            $self->Key === 'PRI',
        );
    }

    public static function typeToPhp(string $formatType): string
    {
        return match ($formatType) {
            'char' => 'string',
            'varchar' => 'string',
            'tinytext' => 'string',
            'text' => 'string',
            'mediumtext' => 'string',
            'longtext' => 'string',
            'tinyblob' => 'string',
            'blob' => 'string',
            'mediumblob' => 'string',
            'longblob' => 'string',
            'enum' => 'string',
            'set' => 'string',
            'binary' => 'string',
            'varbinary' => 'string',
            'bit' => 'string',
            'date' => 'string',
            'datetime' => 'string',
            'timestamp' => 'string',
            'time' => 'string',
            'year' => 'string',
            'int' => 'int',
            'tinyint' => 'int',
            'smallint' => 'int',
            'mediumint' => 'int',
            'bigint' => 'int',
            'float' => 'float',
            'double' => 'float',
            'decimal' => 'float',
            'boolean' => 'boolean',
            'json' => 'json',
            'jsonb' => 'json',
            'uuid' => 'string',
            default => 'string',
        };
    }
}
