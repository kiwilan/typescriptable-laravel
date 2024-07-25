<?php

namespace Kiwilan\Typescriptable\Typed;

use Kiwilan\Typescriptable\Typed\Settings\Printer\PrinterSettings;
use Kiwilan\Typescriptable\Typed\Settings\Schemas\SettingsItem;
use Kiwilan\Typescriptable\Typed\Settings\SettingsConfig;
use Kiwilan\Typescriptable\Typed\Utils\Schema\SchemaClass;
use Kiwilan\Typescriptable\Typed\Utils\Schema\SchemaCollection;
use Kiwilan\Typescriptable\Typed\Utils\TypescriptableUtils;
use Kiwilan\Typescriptable\TypescriptableConfig;

class SettingsType
{
    /**
     * @param  array<string,SettingsItem>  $items
     */
    protected function __construct(
        protected SettingsConfig $config,
        protected array $items = [],
        protected ?string $typescript = null,
    ) {}

    public static function make(SettingsConfig $config = new SettingsConfig): ?self
    {
        $self = new self($config);

        if (! file_exists($self->config->directory)) {
            return null;
        }

        $collect = SchemaCollection::make($self->config->directory, $self->config->toSkip);
        $items = array_filter($collect->items(), fn (SchemaClass $item) => $item->extends() === $self->config->extends);

        foreach ($items as $item) {
            $item = SettingsItem::make($item);
            $self->items[$item->name()] = $item;
        }

        $printer = PrinterSettings::make($self->items);
        TypescriptableUtils::print($printer, TypescriptableConfig::setPath($self->config->filename));

        return $self;
    }

    public function config(): SettingsConfig
    {
        return $this->config;
    }

    /**
     * @return array<string,SettingsItem>
     */
    public function items(): array
    {
        return $this->items;
    }

    public function item(string $name): SettingsItem
    {
        return $this->items[$name];
    }

    public function typescript(): ?string
    {
        return $this->typescript;
    }
}
