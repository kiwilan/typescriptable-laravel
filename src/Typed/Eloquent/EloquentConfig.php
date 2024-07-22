<?php

namespace Kiwilan\Typescriptable\Typed\Eloquent;

use Kiwilan\Typescriptable\TypescriptableConfig;

class EloquentConfig
{
    public function __construct(
        public ?string $modelsPath = null,
        public ?string $outputPath = null,
        public ?string $phpPath = null,
        public bool $useParser = false,
        public array $skipModels = [],
        public ?string $tsFilename = null,
    ) {
        if (! $this->modelsPath) {
            $this->modelsPath = TypescriptableConfig::eloquentDirectory();
        }

        $this->tsFilename = TypescriptableConfig::eloquentFilename();
        if (! $this->outputPath) {
            $this->outputPath = TypescriptableConfig::setPath();
        }

        if (! $this->phpPath) {
            $this->phpPath = TypescriptableConfig::eloquentPhpPath();
        }

        if (count($this->skipModels) === 0) {
            $this->skipModels = TypescriptableConfig::eloquentSkip();
        }
    }
}
