<?php

namespace Kiwilan\Typescriptable\Typed\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Kiwilan\Typescriptable\Typed\Database\Table;
use Kiwilan\Typescriptable\Typed\Eloquent\Utils\EloquentAttribute;
use Kiwilan\Typescriptable\Typed\Eloquent\Utils\EloquentCast;
use Kiwilan\Typescriptable\Typed\Eloquent\Utils\EloquentProperty;
use Kiwilan\Typescriptable\Typed\Eloquent\Utils\EloquentRelation;

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

    /** @var string[] */
    public array $modelAppends = [];

    /** @var string[] */
    public array $modelCasts = [];

    /** @var string[] */
    public array $modelDates = [];

    /** @var string[] */
    public array $modelFillable = [];

    /** @var string[] */
    public array $modelHidden = [];

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

        $self->modelAppends = $self->model->getAppends();
        $self->modelCasts = $self->model->getCasts();
        $self->modelDates = $self->model->getDates();
        $self->modelFillable = $self->model->getFillable();
        $self->modelHidden = $self->model->getHidden();

        $self->table = Table::parse($self->tableName);
        $self->columns = $self->setColumns();
        $self->relations = EloquentRelation::toArray($self);
        $self->counts = $self->setCounts();

        $self->attributes = EloquentAttribute::make($self);
        $self->casts = EloquentCast::make($self);

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
            if (! in_array($column->name, $this->modelHidden)) {
                $properties[$column->name] = $column;
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

        if (str_contains($type, 'date')) {
            $type = 'DateTime';
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
            default => $type,
        };

        if ($isArray) {
            $type .= '[]';
        }

        return $type;
    }
}
