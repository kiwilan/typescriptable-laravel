<?php

namespace Kiwilan\Typescriptable\Typed\Schema;

use Kiwilan\Typescriptable\Typed\Parser\ParserAccessor;

/**
 * A `SchemaModel` represents a Laravel model, like `App\Models\Movie`.
 *
 * It contains `SchemaClass`, attributes, relations, and observers.
 * Additionally, its contains database information (driver, table, policy).
 */
class SchemaModel
{
    /**
     * @param  SchemaAttribute[]  $attributes
     * @param  SchemaRelation[]  $relations
     */
    protected function __construct(
        protected SchemaClass $schemaClass,
        protected string $namespace, // e.g. `App\Models`
        protected string $driver, // e.g. `mysql`
        protected string $table, // e.g. `movies`
        protected mixed $policy = null,
        protected ?array $attributes = [],
        protected ?array $relations = [],
        protected ?array $observers = [],
        protected ?string $typescriptModelName = null,
    ) {}

    /**
     * Create a new `SchemaModel` instance.
     *
     * ```php
     * $class = SchemaClass::make($spl, models());
     * $model = SchemaModel::make(
     *      schemaClass: $class,
     *      namespace: $class->getNamespace(),
     *      driver: $this->app->getDriver(),
     *      table: $tableName,
     *      attributes: $table->getAttributes(),
     *      relations: $relations,
     * );
     * ```
     */
    public static function make(
        SchemaClass $schemaClass,
        string $namespace,
        string $driver,
        string $table,
        ?array $attributes = [],
        ?array $relations = [],
        ?array $observers = [],
        mixed $policy = null,
    ): self {
        $self = new self(
            $schemaClass,
            $namespace,
            $driver,
            $table,
            $observers ?? [],
            $policy,
        );

        foreach (array_map(fn ($item) => $item, $attributes) as $attribute) {
            $self->attributes[$attribute->getName()] = $attribute;
        }

        foreach (array_map(fn ($item) => SchemaRelation::make($item), $relations) as $relation) {
            $self->relations[$relation->getName()] = $relation;
        }

        $self->typescriptModelName = $self->schemaClass->getFullname();

        return $self;
    }

    /**
     * Get `SchemaClass` instance (base information about the class).
     */
    public function getSchemaClass(): ?SchemaClass
    {
        return $this->schemaClass;
    }

    /**
     * DEPRECATED: Get the class name of the model.
     * Use `getSchemaClass()->getNamespace()` instead.
     *
     * Get the namespace of the model.
     */
    public function getNamespace(): string
    {
        return $this->namespace;
    }

    /**
     * Get the database driver of the model.
     */
    public function getDriver(): string
    {
        return $this->driver;
    }

    public function getTable(): string
    {
        return $this->table;
    }

    public function getPolicy(): mixed
    {
        return $this->policy;
    }

    /**
     * @return SchemaAttribute[]
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function getAttribute(string $name): ?SchemaAttribute
    {
        return $this->attributes[$name] ?? null;
    }

    public function setAttribute(SchemaAttribute $attribute): self
    {
        $this->attributes[$attribute->getName()] = $attribute;

        return $this;
    }

    public function removeAttribute(string $name): self
    {
        if (isset($this->attributes[$name])) {
            unset($this->attributes[$name]);
        }

        return $this;
    }

    /**
     * @param  SchemaAttribute[]  $attributes
     */
    public function setAttributes(array $attributes): self
    {
        $this->attributes = [
            ...$this->attributes,
            ...$attributes,
        ];

        // assign keys if not set
        foreach ($this->attributes as $key => $attribute) {
            if (! $attribute instanceof SchemaAttribute) {
                continue;
            }

            if ($attribute->getName() !== $key) {
                unset($this->attributes[$key]);
                $this->attributes[$attribute->getName()] = $attribute;
            }
        }

        return $this;
    }

    public function updateAccessor(ParserAccessor $accessor): self
    {
        $attribute = $this->attributes[$accessor->getField()] ?? null;
        if ($attribute) {
            $attribute->setPhpType($accessor->getPhpType());
            $attribute->setTypescriptType($accessor->getTypescriptType());
        }

        return $this;
    }

    /**
     * @return SchemaRelation[]
     */
    public function getRelations(): array
    {
        return $this->relations;
    }

    public function getRelation(string $name): ?SchemaRelation
    {
        return $this->relations[$name] ?? null;
    }

    public function getObservers(): array
    {
        return $this->observers;
    }

    public function getTypescriptModelName(): ?string
    {
        return $this->typescriptModelName;
    }
}
