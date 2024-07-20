<?php

namespace Kiwilan\Typescriptable\Typed\Database\Types;

use Kiwilan\Typescriptable\Typed\Eloquent\Schema\Model\SchemaModelAttribute;

class SqlServerColumn implements IColumn
{
    protected function __construct(
        public ?string $COLUMN_NAME = null,
        public ?string $DATA_TYPE = null,
        public ?string $IS_NULLABLE = null,
        public ?string $COLUMN_DEFAULT = null,
    ) {
    }

    public static function make(array|object $data): SchemaModelAttribute
    {
        $data = is_object($data) ? get_object_vars($data) : $data;

        $self = new self(
            $data['COLUMN_NAME'] ?? null,
            $data['DATA_TYPE'] ?? null,
            $data['IS_NULLABLE'] ?? null,
            $data['COLUMN_DEFAULT'] ?? null,
        );

        return new SchemaModelAttribute(
            name: $self->COLUMN_NAME,
            databaseType: $self->DATA_TYPE,
            increments: false,
            nullable: $self->IS_NULLABLE === 'YES',
            default: $self->COLUMN_DEFAULT,
            databaseFields: $data,
        );
    }
}
