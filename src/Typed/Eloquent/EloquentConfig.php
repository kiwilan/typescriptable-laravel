<?php

namespace Kiwilan\Typescriptable\Typed\Eloquent;

use Kiwilan\Typescriptable\TypescriptableConfig;

class EloquentConfig
{
    public function __construct(
        public ?string $modelsPath = null,
        public ?string $phpPath = null,
        public bool $useParser = false,
        public array $skipModels = [],
        public ?string $typescriptFilename = null,
    ) {
        $this->modelsPath = TypescriptableConfig::eloquentDirectory();
        $this->typescriptFilename = TypescriptableConfig::eloquentFilename();
        $this->phpPath = TypescriptableConfig::eloquentPhpPath();
        $this->skipModels = TypescriptableConfig::eloquentSkip();
    }
}
