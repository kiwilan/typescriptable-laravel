<?php

namespace Kiwilan\Typescriptable\Typed\Database;

use Doctrine\DBAL\Types\Types;

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
    ) {
    }

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
            'char' => Types::STRING,
            'varchar' => Types::STRING,
            'tinytext' => Types::STRING,
            'text' => Types::STRING,
            'mediumtext' => Types::STRING,
            'longtext' => Types::STRING,
            'tinyblob' => Types::STRING,
            'blob' => Types::STRING,
            'mediumblob' => Types::STRING,
            'longblob' => Types::STRING,
            'enum' => Types::STRING,
            'set' => Types::STRING,
            'binary' => Types::STRING,
            'varbinary' => Types::STRING,
            'bit' => Types::STRING,
            'date' => 'DateTime',
            'datetime' => 'DateTime',
            'timestamp' => 'DateTime',
            'time' => Types::TIME_IMMUTABLE,
            'year' => Types::STRING,
            'int' => 'int',
            'tinyint' => 'int',
            'smallint' => 'int',
            'mediumint' => 'int',
            'bigint' => 'int',
            'float' => Types::FLOAT,
            'double' => Types::FLOAT,
            'decimal' => Types::FLOAT,
            'boolean' => Types::BOOLEAN,
            'json' => Types::JSON,
            'jsonb' => Types::JSON,
            'uuid' => Types::STRING,
            default => Types::STRING,
        };
    }
}
