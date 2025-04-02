<?php

namespace Kiwilan\Typescriptable\Eloquent\Parser;

use Illuminate\Database\Eloquent\Model;
use Kiwilan\Typescriptable\Eloquent\Schema\SchemaAttribute;
use Kiwilan\Typescriptable\Eloquent\Schema\SchemaClass;

/**
 * `ParserFillable` is a parser for Laravel Eloquent models.
 *
 * Used for `mongodb` driver.
 */
class ParserFillable
{
    /**
     * @param  SchemaAttribute[]  $attributes
     */
    protected function __construct(
        protected SchemaClass $class,
        protected array $attributes = [],
    ) {}

    public static function make(SchemaClass $class): self
    {
        $self = new self($class);

        $model = $self->class->getNamespace();
        /** @var Model */
        $instance = new $model;

        $key = $instance->getKeyName();
        $casts = $instance->getCasts();

        if ($key) {
            $self->attributes[$key] = $self->parseMongoDBAttribute(
                casts: $casts,
                name: $key,
                nullable: false,
                unique: true,
                fillable: false,
            );
        }

        foreach ($instance->getHidden() as $field) {
            $self->attributes[$field] = $self->parseMongoDBAttribute(
                casts: $casts,
                name: $field,
                hidden: true,
            );
        }

        foreach ($instance->getFillable() as $field) {
            $self->attributes[$field] = $self->parseMongoDBAttribute(
                casts: $casts,
                name: $field,
            );
        }

        return $self;
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

    private function parseMongoDBAttribute(
        array $casts,
        string $name,
        bool $nullable = true,
        bool $unique = false,
        bool $fillable = true,
        bool $hidden = false,
    ): SchemaAttribute {
        return new SchemaAttribute(
            name: $name,
            driver: 'mongodb',
            databaseType: 'string',
            increments: false,
            nullable: $nullable,
            default: null,
            unique: $unique,
            fillable: $fillable,
            hidden: $hidden,
            appended: false,
            cast: $casts[$name] ?? null,
        );
    }
}
