<?php

namespace Kiwilan\Typescriptable\Typed\Eloquent\Schemas\Model;

use Kiwilan\Typescriptable\Typed\Eloquent\Parser\ParserAccessor;
use Kiwilan\Typescriptable\Typed\Utils\Schema\SchemaClass;

/**
 * Represents a Laravel model.
 */
class SchemaModel
{
    /**
     * @param  SchemaModelAttribute[]  $attributes  All attributes, with database and accessors.
     * @param  SchemaModelRelation[]  $relations  All Laravel relations.
     */
    public function __construct(
        protected SchemaClass $schemaClass,
        protected string $namespace,
        protected string $driver,
        protected string $table,
        protected mixed $policy = null,
        protected array $attributes = [],
        protected array $relations = [],
        protected array $observers = [],
        protected ?string $typescriptModelName = null,
    ) {}

    /**
     * Create new instance of `SchemaModel` from parser or artisan.
     */
    public static function make(array $data, SchemaClass $schemaClass): self
    {
        $observers = $data['observers'] ?? [];
        $observers_items = [];
        if (! empty($observers)) {
            foreach ($observers as $key => $observer) {
                $obs = $observer['observer'] ?? [];
                $name = reset($obs);
                if (str_contains($name, '\\')) {
                    $name = class_basename($name);
                }
                $observers_items["{$key}_{$name}"] = $observer;
            }
        }

        $self = new self(
            $schemaClass,
            $data['class'],
            $data['database'],
            $data['table'],
            $data['policy'] ?? null,
            [],
            [],
            $observers,
        );

        foreach (array_map(fn ($item) => SchemaModelAttribute::make($self->driver, $item), $data['attributes'] ?? []) as $attribute) {
            $self->attributes[$attribute->getName()] = $attribute;
        }

        foreach (array_map(fn ($item) => SchemaModelRelation::make($item), $data['relations'] ?? []) as $relation) {
            $self->relations[$relation->getName()] = $relation;
        }

        $self->typescriptModelName = $self->schemaClass->getFullname();

        return $self;
    }

    /**
     * Get `SchemaClass` based on the model.
     */
    public function getSchemaClass(): ?SchemaClass
    {
        return $this->schemaClass;
    }

    /**
     * Get namespace of the model.
     */
    public function getNamespace(): string
    {
        return $this->namespace;
    }

    /**
     * Get driver used by table in database.
     */
    public function getDriver(): string
    {
        return $this->driver;
    }

    /**
     * Get table name in database.
     */
    public function getTable(): string
    {
        return $this->table;
    }

    /**
     * Get policy.
     */
    public function getPolicy(): mixed
    {
        return $this->policy;
    }

    /**
     * Get all `SchemaModelAttribute` of the model, corresponding to the table and accessors.
     *
     * @return SchemaModelAttribute[]
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * Get `SchemaModelAttribute` from `attributes` array from name.
     */
    public function getAttribute(string $name): ?SchemaModelAttribute
    {
        return $this->attributes[$name] ?? null;
    }

    /**
     * Add an attribute to the model.
     */
    public function addAttribute(SchemaModelAttribute $attribute): self
    {
        $this->attributes[$attribute->getName()] = $attribute;

        return $this;
    }

    /**
     * Add multiple attributes to the model (keeping the existing attributes).
     *
     * @param  SchemaModelAttribute[]  $attributes
     */
    public function addAttributes(array $attributes): self
    {
        $this->attributes = [
            ...$this->attributes,
            ...$attributes,
        ];

        return $this;
    }

    /**
     * Update a specific attribute, with `ParserAccessor` parameter.
     */
    public function updateAccessor(ParserAccessor $accessor): self
    {
        $attribute = $this->attributes[$accessor->field] ?? null;
        if ($attribute) {
            $attribute->setPhpType($accessor->phpType);
            $attribute->setTypescriptType($accessor->typescriptType);
        }

        return $this;
    }

    /**
     * Get all `SchemaModelRelation` of the model.
     *
     * @return SchemaModelRelation[]
     */
    public function getRelations(): array
    {
        return $this->relations;
    }

    /**
     * Get `SchemaModelRelation` from `relations` array from name.
     */
    public function getRelation(string $name): ?SchemaModelRelation
    {
        return $this->relations[$name] ?? null;
    }

    /**
     * Add a relation to the model.
     */
    public function getObservers(): array
    {
        return $this->observers;
    }

    public function getTypescriptModelName(): ?string
    {
        return $this->typescriptModelName;
    }
}
