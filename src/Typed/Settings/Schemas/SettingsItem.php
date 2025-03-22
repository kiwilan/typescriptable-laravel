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

    /**
     * Create new instance of `SettingsItem` from `SchemaClass`.
     */
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

    /**
     * Get `SchemaClass` based on the model.
     */
    public function getClass(): SchemaClass
    {
        return $this->class;
    }

    /**
     * Get the name of the setting item.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get all `SettingItemProperty` of the setting item.
     *
     * @return SettingItemProperty[]
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    /**
     * Get a property of the setting item, based on the name.
     */
    public function getProperty(string $name): SettingItemProperty
    {
        return $this->properties[$name];
    }
}
