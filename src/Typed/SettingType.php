<?php

namespace Kiwilan\Typescriptable\Typed;

use Kiwilan\Typescriptable\Typed\Eloquent\ClassItem;
use Kiwilan\Typescriptable\Typed\Eloquent\Utils\EloquentProperty;
use Kiwilan\Typescriptable\TypescriptableConfig;

class SettingType
{
    /** @var ClassItem[] */
    public array $items = [];

    /** @var array<string, EloquentProperty[]> */
    public array $eloquents = [];

    /** @var array<string, array<string, array<string, string>>> */
    public array $list = [];

    protected function __construct(
        public string $settingsPath,
        public string $outputPath,
    ) {
    }

    public static function make(?string $settingsPath, ?string $outputPath): self
    {
        // if (! $modelsPath) {
        //     $modelsPath = TypescriptableConfig::modelsDirectory();
        // }

        // $tsFilename = TypescriptableConfig::modelsFilename();
        // if (! $outputPath) {
        //     $outputPath = TypescriptableConfig::setPath();
        // }

        $self = new self($settingsPath, $outputPath);

        return $self;
    }
}
