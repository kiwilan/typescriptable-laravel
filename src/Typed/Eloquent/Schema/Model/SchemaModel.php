<?php

namespace Kiwilan\Typescriptable\Typed\Eloquent\Schema\Model;

use Kiwilan\Typescriptable\Typed\Eloquent\Parser\ParserAccessor;
use Kiwilan\Typescriptable\Typed\Utils\Schema\SchemaClass;

class SchemaModel
{
    /**
     * @param  SchemaModelAttribute[]  $attributes
     * @param  SchemaModelRelation[]  $relations
     */
    protected function __construct(
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

    public static function make(array $data, SchemaClass $schemaClass): self
    {
        $self = new self(
            $schemaClass,
            $data['class'],
            $data['database'],
            $data['table'],
            $data['policy'] ?? null,
            [],
            [],
            $data['observers'] ?? [],
        );

        foreach (array_map(fn ($item) => SchemaModelAttribute::make($self->driver, $item), $data['attributes'] ?? []) as $attribute) {
            $self->attributes[$attribute->name()] = $attribute;
        }

        foreach (array_map(fn ($item) => SchemaModelRelation::make($item), $data['relations'] ?? []) as $relation) {
            $self->relations[$relation->name()] = $relation;
        }

        $self->typescriptModelName = $self->schemaClass->fullname();

        return $self;
    }

    public function schemaClass(): ?SchemaClass
    {
        return $this->schemaClass;
    }

    public function namespace(): string
    {
        return $this->namespace;
    }

    public function driver(): string
    {
        return $this->driver;
    }

    public function table(): string
    {
        return $this->table;
    }

    public function policy(): mixed
    {
        return $this->policy;
    }

    /**
     * @return SchemaModelAttribute[]
     */
    public function attributes(): array
    {
        return $this->attributes;
    }

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
     * @return SchemaModelRelation[]
     */
    public function relations(): array
    {
        return $this->relations;
    }

    public function observers(): array
    {
        return $this->observers;
    }

    public function typescriptModelName(): ?string
    {
        return $this->typescriptModelName;
    }
}
