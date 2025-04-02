<?php

namespace Kiwilan\Typescriptable\Eloquent\Schema;

use Kiwilan\Typescriptable\Eloquent\Parser\ParserAccessor;

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
        protected SchemaClass $class,
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
     *      driver: $this->app->getDriver(),
     *      table: $tableName,
     *      attributes: $table->getAttributes(),
     *      relations: $relations,
     * );
     * ```
     */
    public static function make(
        SchemaClass $class,
        string $driver,
        string $table,
        ?array $attributes = [],
        ?array $relations = [],
        ?array $observers = [],
        mixed $policy = null,
    ): self {
        $self = new self(
            $class,
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

        $self->typescriptModelName = $self->class->getFullname();

        return $self;
    }

    /**
     * Get `SchemaClass` instance (base information about the class).
     */
    public function getClass(): ?SchemaClass
    {
        return $this->class;
    }

    /**
     * Get the database driver of the model.
     *
     * Example: `mysql`
     */
    public function getDriver(): string
    {
        return $this->driver;
    }

    /**
     * Get the database table name of the model.
     *
     * Example: `movies`
     */
    public function getTable(): string
    {
        return $this->table;
    }

    /**
     * Get the database policy of the model.
     *
     * Example: `App\Policies\MoviePolicy`
     */
    public function getPolicy(): mixed
    {
        return $this->policy;
    }

    /**
     * Get the model attributes.
     *
     * @return SchemaAttribute[]
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * Get the model attribute by name.
     *
     * @param  string  $name  Fillable attribute name
     */
    public function getAttribute(string $name): ?SchemaAttribute
    {
        return $this->attributes[$name] ?? null;
    }

    /**
     * Add a new attribute to the model.
     */
    public function setAttribute(SchemaAttribute $attribute): self
    {
        $this->attributes[$attribute->getName()] = $attribute;

        return $this;
    }

    /**
     * Remove an attribute from the model.
     *
     * @param  string  $name  Fillable attribute name
     */
    public function removeAttribute(string $name): self
    {
        if (isset($this->attributes[$name])) {
            unset($this->attributes[$name]);
        }

        return $this;
    }

    /**
     * Set multiple attributes to the model.
     *
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

    /**
     * Update an existing attribute in the model with the given accessor.
     *
     * @param  ParserAccessor  $accessor  Accessor to update
     */
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
     * Get the model relations as an array of `SchemaRelation`.
     *
     * @return SchemaRelation[]
     */
    public function getRelations(): array
    {
        return $this->relations;
    }

    /**
     * Get a specific relation by name.
     *
     * @param  string  $name  Relation name
     */
    public function getRelation(string $name): ?SchemaRelation
    {
        return $this->relations[$name] ?? null;
    }

    /**
     * Get the model observers as an array.
     */
    public function getObservers(): array
    {
        return $this->observers;
    }

    /**
     * Get the Typescript model name.
     *
     * Example: `Movie`
     */
    public function getTypescriptModelName(): ?string
    {
        return $this->typescriptModelName;
    }
}
