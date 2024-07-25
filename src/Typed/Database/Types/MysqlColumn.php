<?php

namespace Kiwilan\Typescriptable\Typed\Database\Types;

use Kiwilan\Typescriptable\Typed\Eloquent\Schemas\Model\SchemaModelAttribute;

class MysqlColumn implements IColumn
{
    protected function __construct(
        public ?string $Field = null,
        public ?string $Type = null,
        public string $Null = 'YES',
        public ?string $Key = null,
        public ?string $Default = null,
        public ?string $Extra = null,
    ) {}

    public static function make(array|object $data): SchemaModelAttribute
    {
        $data = is_object($data) ? get_object_vars($data) : $data;

        $self = new self(
            $data['Field'] ?? null,
            $data['Type'] ?? null,
            $data['Null'] ?? 'YES',
            $data['Key'] ?? null,
            $data['Default'] ?? null,
            $data['Extra'] ?? null,
        );

        return new SchemaModelAttribute(
            name: $self->Field,
            databaseType: $self->Type,
            increments: $self->Extra === 'auto_increment',
            nullable: $self->Null === 'YES',
            default: $self->Default,
            unique: $self->Key === 'UNI',
            databaseFields: $data,
        );
    }
}
