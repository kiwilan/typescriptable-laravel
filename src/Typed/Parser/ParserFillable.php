<?php

namespace Kiwilan\Typescriptable\Typed\Parser;

use Illuminate\Database\Eloquent\Model;
use Kiwilan\Typescriptable\Typed\Schema\SchemaAttribute;
use Kiwilan\Typescriptable\Typed\Schema\SchemaClass;

class ParserFillable
{
    /**
     * @param  SchemaAttribute[]  $attributes
     */
    protected function __construct(
        protected SchemaClass $schemaClass,
        protected string $namespace,
        protected array $attributes = [],
    ) {}

    public static function make(SchemaClass $schemaClass): self
    {
        $self = new self(
            $schemaClass,
            $schemaClass->getNamespace(),
        );

        $model = $self->namespace;
        /** @var Model */
        $instance = new $model;

        $key = $instance->getKeyName();
        $casts = $instance->getCasts();

        if ($key) {
            $self->attributes[$key] = new SchemaAttribute(
                name: $key,
                driver: 'mongodb',
                databaseType: 'string',
                increments: false,
                nullable: false,
                default: null,
                unique: true,
                fillable: false,
                hidden: false,
                appended: false,
                cast: $casts[$key] ?? null,
            );
        }

        foreach ($instance->getHidden() as $field) {
            $self->attributes[$field] = new SchemaAttribute(
                name: $field,
                driver: 'mongodb',
                databaseType: 'string',
                increments: false,
                nullable: true,
                default: null,
                unique: false,
                fillable: true,
                hidden: true,
                appended: false,
                cast: $casts[$field] ?? null,
            );
        }

        foreach ($instance->getFillable() as $field) {
            $self->attributes[$field] = new SchemaAttribute(
                name: $field,
                driver: 'mongodb',
                databaseType: 'string',
                increments: false,
                nullable: true,
                default: null,
                unique: false,
                fillable: true,
                hidden: false,
                appended: false,
                cast: $casts[$field] ?? null,
            );
        }

        return $self;
    }

    /**
     * Get namespace.
     */
    public function getNamespace(): string
    {
        return $this->namespace;
    }

    /**
     * Get attributes.
     *
     * @return SchemaAttribute[]
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }
}
