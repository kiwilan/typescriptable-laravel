<?php

namespace Kiwilan\Typescriptable\Typed\Eloquent\Parser;

use Illuminate\Database\Eloquent\Model;
use Kiwilan\Typescriptable\Typed\Eloquent\Schema\Model\SchemaModelAttribute;
use Kiwilan\Typescriptable\Typed\Utils\Schema\SchemaClass;

class ParserModelFillable
{
    /**
     * @param  SchemaModelAttribute[]  $attributes
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
            $schemaClass->namespace(),
        );

        $model = $self->namespace;
        /** @var Model */
        $instance = new $model();

        $key = $instance->getKeyName();
        $casts = $instance->getCasts();

        if ($key) {
            $self->attributes[$key] = SchemaModelAttribute::make('mongodb', new SchemaModelAttribute(
                name: $key,
                databaseType: 'string',
                increments: false,
                nullable: false,
                default: null,
                unique: true,
                fillable: false,
                hidden: false,
                appended: false,
                cast: $casts[$key] ?? null,
            ));
        }

        foreach ($instance->getHidden() as $field) {
            $self->attributes[$field] = SchemaModelAttribute::make('mongodb', new SchemaModelAttribute(
                name: $field,
                databaseType: 'string',
                increments: false,
                nullable: true,
                default: null,
                unique: false,
                fillable: true,
                hidden: true,
                appended: false,
                cast: $casts[$field] ?? null,
            ));
        }

        foreach ($instance->getFillable() as $field) {
            $self->attributes[$field] = SchemaModelAttribute::make('mongodb', new SchemaModelAttribute(
                name: $field,
                databaseType: 'string',
                increments: false,
                nullable: true,
                default: null,
                unique: false,
                fillable: true,
                hidden: false,
                appended: false,
                cast: $casts[$field] ?? null,
            ));
        }

        return $self;
    }

    /**
     * Get namespace.
     */
    public function namespace(): string
    {
        return $this->namespace;
    }

    /**
     * Get attributes.
     *
     * @return SchemaModelAttribute[]
     */
    public function attributes(): array
    {
        return $this->attributes;
    }
}
