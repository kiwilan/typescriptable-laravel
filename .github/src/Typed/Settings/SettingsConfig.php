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
        if (! $this->filename) {
            $this->filename = TypescriptableConfig::settingsFilename();
        }

        if (! $this->directory) {
            $this->directory = TypescriptableConfig::settingsDirectory();
        }

        if (! $this->extends) {
            $this->extends = TypescriptableConfig::settingsExtends();
        }

        if (! $this->toSkip) {
            $this->toSkip = TypescriptableConfig::settingsSkip();
        }
    }
}
