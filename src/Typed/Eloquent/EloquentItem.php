<?php

namespace Kiwilan\Typescriptable\Typed\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Kiwilan\Typescriptable\Typed\Database\Table;
use Kiwilan\Typescriptable\Typed\Eloquent\Utils\EloquentAttribute;
use Kiwilan\Typescriptable\Typed\Eloquent\Utils\EloquentCast;
use Kiwilan\Typescriptable\Typed\Eloquent\Utils\EloquentProperty;
use Kiwilan\Typescriptable\Typed\Eloquent\Utils\EloquentRelation;
use Kiwilan\Typescriptable\Typed\Utils\ClassItem;

class EloquentItem
{
    /** @var EloquentProperty[] */
    public array $properties = [];

    /** @var EloquentProperty[] */
    public array $columns = [];

    /** @var EloquentRelation[] */
    public array $relations = [];

    /** @var EloquentRelation[] */
    public array $morphRelations = [];

    /** @var EloquentAttribute[] */
    public array $attributes = [];

    /** @var EloquentCast[] */
    public array $casts = [];

    /** @var string[] */
    public array $counts = [];

    protected function __construct(
        public ClassItem $class,
        public Model $model,
        public string $tableName,
        public string $name,
        public ?Table $table = null,
    ) {}

    public static function make(ClassItem $class): self
    {
        /** @var Model */
        $instance = $class->reflect->newInstance();

        $self = new self(
            class: $class,
            model: $instance,
            tableName: Table::getName($instance),
            name: $class->name,
        );

        $self->table = Table::make($self->tableName);
        $self->columns = $self->setColumns();
        $self->relations = EloquentRelation::toArray($self);
        $self->counts = $self->setCounts();

        $self->attributes = EloquentAttribute::toArray($self);
        $self->casts = EloquentCast::toArray($self);

        $self->properties = $self->setProperties();

        return $self;
    }

    /**
     * @return EloquentProperty[]
     */
    private function setColumns(): array
    {
        $columns = [];

        foreach ($this->table->columns as $column) {
            $columns[$column->name] = EloquentProperty::fromDb($column);
        }

        return $columns;
    }

    /**
     * @return string[]
     */
    private function setCounts(): array
    {
        $counts = [];

        foreach ($this->relations as $field => $relation) {
            if ($relation->isArray) {
                $counts[$field] = $relation->phpType;
            }
        }

        return $counts;
    }

    /**
     * @return EloquentProperty[]
     */
    private function setProperties(): array
    {
        $properties = [];

        // Add if not hidden
        foreach ($this->columns as $column) {
            if (! in_array($column->name(), $this->model->getHidden())) {
                $properties[$column->name()] = $column;
            }
        }

        // Add attributes
        foreach ($this->attributes as $field => $type) {
            $properties[$type->field] = new EloquentProperty(
                table: $this->tableName,
                name: $type->field,
                phpType: $type->phpType,
                isAttribute: true,
                isNullable: true,
                isArray: $type->isArray,
                typescriptType: $type->typescriptType,
            );
        }

        // Add relations
        foreach ($this->relations as $field => $relation) {
            $property = new EloquentProperty(
                table: $this->tableName,
                name: $relation->field,
                phpType: $relation->phpType,
                typescriptType: $relation->typescriptType,
                isRelation: true,
                isRelationMorph: $relation->isMorph,
                isNullable: true,
                isArray: $relation->isArray,
            );
            $property->parseMorphRelation($relation);
            $properties[$relation->field] = $property;

            if ($relation->isMorph) {
                $this->morphRelations[$relation->field] = $relation;
            }
        }

        // Add counts
        foreach ($this->counts as $field => $type) {
            $properties["{$field}_count"] = new EloquentProperty(
                table: $this->tableName,
                name: "{$field}_count",
                phpType: 'int',
                typescriptType: 'number',
                isNullable: true,
                isCount: true,
            );
        }

        // Add casts
        foreach ($this->casts as $field => $cast) {
            $properties[$field] = new EloquentProperty(
                table: $this->tableName,
                name: $field,
                phpType: $cast->typePhp,
                typescriptType: $cast->typescriptType,
                isCast: true,
                isNullable: true,
            );
        }

        return $properties;
    }
}
