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
    ) {
    }

    public static function make(ClassItem $class): self
    {
        /** @var Model */
        $instance = $class->reflect->newInstance();

        $self = new self(
            class: $class,
            model: $instance,
            tableName: $instance->getTable(),
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
                $counts[$field] = $relation->type;
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
                type: $type->type,
                isAttribute: true,
                isNullable: true,
                isArray: $type->isArray,
                typeTs: $type->typeTs,
            );
        }

        // Add relations
        foreach ($this->relations as $field => $relation) {
            $properties[$relation->field] = new EloquentProperty(
                table: $this->tableName,
                name: $relation->field,
                type: $relation->type,
                typeTs: $relation->typeTs,
                isRelation: true,
                isRelationMorph: $relation->isMorph,
                isNullable: true,
                isArray: $relation->isArray,
            );
        }

        // Add counts
        foreach ($this->counts as $field => $type) {
            $properties["{$field}_count"] = new EloquentProperty(
                table: $this->tableName,
                name: "{$field}_count",
                type: 'int',
                typeTs: 'number',
                isNullable: true,
                isCount: true,
            );
        }

        // Add casts
        foreach ($this->casts as $field => $cast) {
            $properties[$field] = new EloquentProperty(
                table: $this->tableName,
                name: $field,
                type: $cast->type,
                typeTs: $cast->typeTs,
                isCast: true,
            );
        }

        return $properties;
    }

    public static function phpToTs(string $type): string
    {
        $isArray = false;
        $isAdvanced = false;

        if (str_contains($type, 'date')) {
            $type = 'DateTime';
        }

        if (str_contains($type, 'array<')) {
            $regex = '/array<[^,]+,[^>]+>/';
            preg_match($regex, $type, $matches);

            if (count($matches) > 0) {
                $isAdvanced = true;
                $type = str_replace('array<', '', $type);
                $type = str_replace('>', '', $type);

                $types = explode(',', $type);
                $type = '';

                $keyType = trim($types[0]);
                $valueType = trim($types[1]);

                $keyType = self::primitivesPhpToTs($keyType);
                $valueType = self::primitivesPhpToTs($valueType);

                $type = "{[key: {$keyType}]: {$valueType}}";

                return $type;
            }
        }

        if (str_contains($type, '[]')) {
            $isArray = true;
            $type = str_replace('[]', '', $type);
        }

        if (str_contains($type, 'array<')) {
            $isArray = true;
            $type = str_replace('array<', '', $type);
            $type = str_replace('>', '', $type);
        }

        $type = self::primitivesPhpToTs($type);

        if ($isArray) {
            $type .= '[]';
        }

        return $type;
    }

    private static function primitivesPhpToTs(string $type): string
    {
        $type = match ($type) {
            'int' => 'number',
            'float' => 'number',
            'string' => 'string',
            'bool' => 'boolean',
            'true' => 'boolean',
            'false' => 'boolean',
            'array' => 'any[]',
            'object' => 'any',
            'mixed' => 'any',
            'null' => 'undefined',
            'void' => 'void',
            'callable' => 'Function',
            'iterable' => 'any[]',
            'DateTime' => 'Date',
            'DateTimeInterface' => 'Date',
            'Carbon' => 'Date',
            'Model' => 'any',
            default => 'any', // skip `Illuminate\Database\Eloquent\Casts\Attribute`
        };

        return $type;
    }
}
