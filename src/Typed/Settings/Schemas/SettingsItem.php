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
        foreach ($class->reflect()->getProperties() as $property) {
            if ($class->namespace() === $property->class) {
                $item = SettingItemProperty::make($property);
                $properties[$item->name()] = $item;
            }
        }

        $self = new self(
            class: $class,
            name: $class->name(),
            properties: $properties,
        );

        return $self;
    }

    public function class(): SchemaClass
    {
        return $this->class;
    }

    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return SettingItemProperty[]
     */
    public function properties(): array
    {
        return $this->properties;
    }

    public function property(string $name): SettingItemProperty
    {
        return $this->properties[$name];
    }
}
