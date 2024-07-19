<?php

namespace Kiwilan\Typescriptable\Typed\Database\Types;

use Kiwilan\Typescriptable\Typed\Eloquent\Schema\Model\SchemaModelAttribute;

class PostgreColumn implements IColumn
{
    protected function __construct(
        public ?string $column_name = null,
        public ?string $data_type = null,
        public ?string $is_nullable = null,
        public ?string $column_default = null,
    ) {
    }

    public static function make(array|object $data): SchemaModelAttribute
    {
        $data = is_object($data) ? get_object_vars($data) : $data;

        $self = new self(
            $data['column_name'] ?? null,
            $data['data_type'] ?? null,
            $data['is_nullable'] ?? null,
            $data['column_default'] ?? null,
        );

        return new SchemaModelAttribute(
            name: $self->column_name,
            databaseType: $self->data_type,
            increments: false,
            nullable: $self->is_nullable === 'YES',
            default: $self->column_default,
            databaseFields: $data,
        );
    }
}
