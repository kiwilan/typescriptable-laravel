<?php

namespace Kiwilan\Typescriptable\Typed\Settings\Schemas;

use Kiwilan\Typescriptable\Typed\Utils\Schema\SchemaClass;

class SettingsItem
{
    /**
     * @param  SettingItemProperty[]  $properties
     */
    protected function __construct(
        protected SchemaClass $class,
        protected string $name,
        protected array $properties = [],
    ) {}

    public static function make(SchemaClass $class): self
    {
        $properties = [];
        foreach ($class->getReflect()->getProperties() as $property) {
            if ($class->getNamespace() === $property->class) {
                $item = SettingItemProperty::make($property);
                $properties[$item->name()] = $item;
            }
        }

        $self = new self(
            class: $class,
            name: $class->getName(),
            properties: $properties,
        );

        return $self;
    }

    public function getClass(): SchemaClass
    {
        return $this->class;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return SettingItemProperty[]
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    public function getProperty(string $name): SettingItemProperty
    {
        return $this->properties[$name];
    }
}
