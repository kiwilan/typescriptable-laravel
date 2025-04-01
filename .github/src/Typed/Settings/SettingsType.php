<?php

namespace Kiwilan\Typescriptable\Typed\Settings;

use Kiwilan\Typescriptable\Typed\Settings\Printer\PrinterSettings;
use Kiwilan\Typescriptable\Typed\Settings\Schemas\SettingsItem;
use Kiwilan\Typescriptable\Typed\Utils\Schema\SchemaClass;
use Kiwilan\Typescriptable\Typed\Utils\Schema\SchemaCollection;
use Kiwilan\Typescriptable\Typed\Utils\TypescriptableUtils;
use Kiwilan\Typescriptable\TypescriptableConfig;

class SettingsType
{
    /**
     * @param  array<string,SettingsItem>  $settings
     */
    protected function __construct(
        protected SettingsConfig $config,
        protected array $settings = [],
        protected ?string $typescript = null,
    ) {}

    public static function make(SettingsConfig $config = new SettingsConfig): ?self
    {
        $self = new self($config);

        if (! file_exists($self->config->directory)) {
            return null;
        }

        $collect = SchemaCollection::make($self->config->directory, $self->config->toSkip);
        $settings = array_filter($collect->getItems(), fn (SchemaClass $item) => $item->getExtends() === $self->config->extends);

        foreach ($settings as $setting) {
            $setting = SettingsItem::make($setting);
            $self->settings[$setting->getName()] = $setting;
        }

        $self->typescript = PrinterSettings::make($self->settings);
        TypescriptableUtils::print($self->typescript, TypescriptableConfig::setPath($self->config->filename));

        return $self;
    }

    public function getConfig(): SettingsConfig
    {
        return $this->config;
    }

    /**
     * @return array<string,SettingsItem>
     */
    public function getSettings(): array
    {
        return $this->settings;
    }

    public function getSetting(string $name): SettingsItem
    {
        return $this->settings[$name];
    }

    public function getTypescript(): ?string
    {
        return $this->typescript;
    }
}
