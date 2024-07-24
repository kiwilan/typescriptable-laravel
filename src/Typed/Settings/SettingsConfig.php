<?php

namespace Kiwilan\Typescriptable\Typed\Settings;

use Kiwilan\Typescriptable\TypescriptableConfig;

class SettingsConfig
{
    /**
     * @param  string[]  $toSkip
     */
    public function __construct(
        public ?string $filename = null,
        public ?string $directory = null,
        public ?string $extends = null,
        public array $toSkip = [],
    ) {
        $this->filename = TypescriptableConfig::settingsFilename();
        $this->directory = TypescriptableConfig::settingsDirectory();
        $this->extends = TypescriptableConfig::settingsExtends();
        $this->toSkip = TypescriptableConfig::settingsSkip();
    }
}
