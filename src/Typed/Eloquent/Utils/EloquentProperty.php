<?php

namespace Kiwilan\Typescriptable\Typed\Eloquent\Utils;

use Kiwilan\Typescriptable\Typed\Database\Column;
use Kiwilan\Typescriptable\Typed\Eloquent\TypeConverter;

class EloquentProperty
{
    public function __construct(
        protected string $table,
        protected string $name,
        protected string $phpType,
        protected bool $isPrimary = false,
        protected bool $isNullable = false,
        protected bool $isHidden = false,
        protected bool $isEnum = false,
        protected bool $isRelation = false,
        protected bool $isRelationMorph = false,
        protected bool $isArray = false,
        protected bool $isAttribute = false,
        protected bool $isCount = false,
        protected bool $isCast = false,
        protected bool $isPivot = false,
        protected ?string $typescriptType = null,
    ) {
    }

    public static function fromDb(Column $column): self
    {
        $self = new self(
            $column->table,
            $column->name,
            $column->typePhp,
            $column->isPrimary,
            $column->isNullable,
        );

        $converter = TypeConverter::make($self->phpType);
        $self->typescriptType = $converter->getTypescriptType();

        return $self;
    }

    public function parseMorphRelation(EloquentRelation $relation): self
    {
        if (! $this->isRelationMorph || ! $relation->hasPivot) {
            return $this;
        }

        $this->phpType = $relation->phpType;
        $this->typescriptType = $relation->typescriptType;

        return $this;
    }

    public function table(): string
    {
        return $this->table;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function phpType(): string
    {
        return $this->phpType;
    }

    public function isPrimary(): bool
    {
        return $this->isPrimary;
    }

    public function isNullable(): bool
    {
        return $this->isNullable;
    }

    public function isHidden(): bool
    {
        return $this->isHidden;
    }

    public function isEnum(): bool
    {
        return $this->isEnum;
    }

    public function isRelation(): bool
    {
        return $this->isRelation;
    }

    public function isRelationMorph(): bool
    {
        return $this->isRelationMorph;
    }

    public function isArray(): bool
    {
        return $this->isArray;
    }

    public function isAttribute(): bool
    {
        return $this->isAttribute;
    }

    public function isCount(): bool
    {
        return $this->isCount;
    }

    public function isCast(): bool
    {
        return $this->isCast;
    }

    public function typescriptType(): ?string
    {
        return $this->typescriptType;
    }
}
