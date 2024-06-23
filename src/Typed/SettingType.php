<?php

namespace Kiwilan\Typescriptable\Typed;

use Kiwilan\Typescriptable\Typed\Setting\SettingItem;
use Kiwilan\Typescriptable\Typed\Setting\SettingTypescript;
use Kiwilan\Typescriptable\Typed\Utils\ClassItem;
use Kiwilan\Typescriptable\TypescriptableConfig;

class SettingType
{
    /** @var SettingItem[] */
    public array $items = [];

    protected function __construct(
        public string $settingsPath,
        public string $outputPath,
    ) {}

    public static function make(?string $settingsPath, ?string $outputPath, ?string $extends): ?self
    {
        if (! $settingsPath) {
            $settingsPath = TypescriptableConfig::settingsDirectory();
        }

        $tsFilename = TypescriptableConfig::settingsFilename();

        if (! $outputPath) {
            $outputPath = TypescriptableConfig::setPath();
        }

        if (! $extends) {
            $extends = 'Spatie\LaravelSettings\Settings';
        }

        if (! file_exists($settingsPath)) {
            return null;
        }

        $items = ClassItem::list($settingsPath, TypescriptableConfig::settingsSkip());
        $items = array_filter($items, fn (ClassItem $item) => $item->extends === $extends);

        $self = new self($settingsPath, $outputPath);

        foreach ($items as $item) {
            $item = SettingItem::make($item);
            $self->items[$item->name] = $item;
        }

        $typescript = SettingTypescript::make($self->items, "{$outputPath}/{$tsFilename}");
        $typescript->print();

        return $self;
    }
}
