<?php

namespace Kiwilan\Typescriptable\Typed\Eloquent\Utils;

use Kiwilan\Typescriptable\Typed\Database\Column;
use Kiwilan\Typescriptable\Typed\Eloquent\EloquentItem;

class EloquentProperty
{
    public function __construct(
        public string $table,
        public string $name,
        public string $type,
        public bool $isPrimary = false,
        public bool $isNullable = false,
        public bool $isHidden = false,
        public bool $isEnum = false,
        public bool $isRelation = false,
        public bool $isRelationMorph = false,
        public bool $isArray = false,
        public bool $isAttribute = false,
        public bool $isCount = false,
        public bool $isCast = false,
        public ?string $typeTs = null,
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

        $self->typeTs = EloquentItem::phpToTs($self->type);

        return $self;
    }
}
