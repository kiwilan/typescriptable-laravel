<?php

namespace Kiwilan\Typescriptable\Typed\Setting;

use Kiwilan\Typescriptable\Typed\Eloquent\TypeConverter;
use Kiwilan\Typescriptable\Typed\Utils\ClassItem;
use ReflectionNamedType;
use ReflectionProperty;

class SettingItem
{
    /**
     * @param  SettingItemProperty[]  $properties
     */
    protected function __construct(
        public ClassItem $class,
        public string $name,
        public array $properties = [],
    ) {}

    public static function make(ClassItem $class): self
    {
        $properties = [];
        foreach ($class->reflect->getProperties() as $property) {
            if ($class->namespace === $property->class) {
                $item = SettingItemProperty::make($property);
                $properties[$item->name] = $item;
            }
        }

        $self = new self(
            class: $class,
            name: $class->name,
            properties: $properties,
        );

        return $self;
    }
}

class SettingItemProperty
{
    protected function __construct(
        public string $name,
        public string $type = 'mixed',
        public bool $isNullable = false,
        public bool $isBuiltin = false,
        public string $typeTs = 'any',
    ) {}

    public static function make(ReflectionProperty $property): self
    {
        $type = $property->getType();
        $extra = $property->getDocComment();
        $typeDoc = null;
        if ($extra) {
            $phpDoc = "/**\n* @var array<string, string>\n*/";
            $regex = '/@var\s+(.*)/';
            preg_match($regex, $phpDoc, $matches);
            $typeDoc = $matches[1];
        }

        $phpType = $type instanceof ReflectionNamedType ? $type->getName() : 'mixed';
        if ($typeDoc) {
            $phpType = $typeDoc;
        }

        $self = new self(
            name: $property->getName(),
            type: $phpType,
            isNullable: $type->allowsNull(),
            isBuiltin: $type instanceof ReflectionNamedType ? $type->isBuiltin() : false,
        );

        $converter = TypeConverter::make($self->type);
        $self->typeTs = $converter->getTypescriptType();

        return $self;
    }
}
