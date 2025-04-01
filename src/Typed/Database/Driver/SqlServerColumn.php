<?php

namespace Kiwilan\Typescriptable\Typed\Database\Driver;

use Kiwilan\Typescriptable\Typed\Schema\SchemaAttribute;

class SqlServerColumn implements IColumn
{
    protected function __construct(
        public ?string $COLUMN_NAME = null,
        public ?string $DATA_TYPE = null,
        public ?string $IS_NULLABLE = null,
        public ?string $COLUMN_DEFAULT = null,
    ) {}

    public static function make(array|object $data): SchemaAttribute
    {
        $data = is_object($data) ? get_object_vars($data) : $data;

        $self = new self(
            $data['COLUMN_NAME'] ?? null,
            $data['DATA_TYPE'] ?? null,
            $data['IS_NULLABLE'] ?? null,
            $data['COLUMN_DEFAULT'] ?? null,
        );

        return new SchemaAttribute(
            name: $self->COLUMN_NAME,
            databaseType: $self->DATA_TYPE,
            increments: false,
            nullable: $self->IS_NULLABLE === 'YES',
            default: $self->COLUMN_DEFAULT,
            databaseFields: $data,
        );
    }
}
