<?php

namespace Kiwilan\Typescriptable\Eloquent;

use Kiwilan\Typescriptable\TypescriptableConfig;

/**
 * A `EloquentConfig` represents the configuration for Eloquent models.
 */
class EloquentConfig
{
    public function __construct(
        public ?string $modelsPath = null,
        public ?string $phpPath = null,
        public bool $useParser = false,
        public array $skipModels = [],
        public ?string $typescriptFilename = null,
    ) {
        if (! $this->modelsPath) {
            $this->modelsPath = TypescriptableConfig::eloquentDirectory();
        }

        if (! $this->useParser) {
            $this->useParser = TypescriptableConfig::engineEloquent() === 'parser';
        }

        if (! $this->phpPath) {
            $this->phpPath = TypescriptableConfig::eloquentPhpPath();
        }

        if (! $this->skipModels) {
            $this->skipModels = TypescriptableConfig::eloquentSkip();
        }

        if (! $this->typescriptFilename) {
            $this->typescriptFilename = TypescriptableConfig::eloquentFilename();
        }
    }
}
