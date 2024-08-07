<?php

namespace Kiwilan\Typescriptable\Typed\Database\Types;

use Kiwilan\Typescriptable\Typed\Eloquent\Schemas\Model\SchemaModelAttribute;

class SqliteColumn implements IColumn
{
    protected function __construct(
        public ?int $cid = null,
        public ?string $name = null,
        public string $type = 'YES',
        public ?int $notnull = null,
        public ?string $dflt_value = null,
        public ?int $pk = null,
    ) {}

    public static function make(array|object $data): SchemaModelAttribute
    {
        $data = is_object($data) ? get_object_vars($data) : $data;

        $self = new self(
            $data['cid'] ?? null,
            $data['name'] ?? null,
            $data['type'] ?? 'YES',
            $data['notnull'] ?? null,
            $data['dflt_value'] ?? null,
            $data['pk'] ?? null,
        );

        return new SchemaModelAttribute(
            name: $self->name,
            databaseType: $self->type,
            increments: $self->pk === 1,
            nullable: $self->notnull === 0,
            default: $self->dflt_value,
            unique: false,
            databaseFields: $data,
        );
    }
}
