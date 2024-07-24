<?php

namespace Kiwilan\Typescriptable\Typed\Settings\Schemas;

use Kiwilan\Typescriptable\Typed\Eloquent\Parser\ParserPhpType;
use ReflectionNamedType;
use ReflectionProperty;

class SettingItemProperty
{
    protected function __construct(
        protected string $name,
        protected string $phpType = 'mixed',
        protected bool $isNullable = false,
        protected bool $isBuiltin = false,
        protected string $typescriptType = 'any',
    ) {}

    public static function make(ReflectionProperty $property): self
    {
        $phpType = $property->getType();
        $extra = $property->getDocComment();
        $typeDoc = null;
        if ($extra) {
            $phpDoc = "/**\n* @var array<string, string>\n*/";
            $regex = '/@var\s+(.*)/';
            preg_match($regex, $phpDoc, $matches);
            $typeDoc = $matches[1];
        }

        $phpType = $phpType instanceof ReflectionNamedType ? $phpType->getName() : 'mixed';
        if ($typeDoc) {
            $phpType = $typeDoc;
        }

        $self = new self(
            name: $property->getName(),
            phpType: $phpType,
            // isNullable: $phpType->allowsNull(),
            // isBuiltin: $phpType instanceof ReflectionNamedType ? $phpType->isBuiltin() : false,
        );

        $self->typescriptType = ParserPhpType::toTypescript($self->phpType);

        return $self;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function phpType(): string
    {
        return $this->phpType;
    }

    public function isNullable(): bool
    {
        return $this->isNullable;
    }

    public function isBuiltin(): bool
    {
        return $this->isBuiltin;
    }

    public function typescriptType(): string
    {
        return $this->typescriptType;
    }
}
