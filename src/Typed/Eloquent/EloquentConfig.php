<?php

namespace Kiwilan\Typescriptable\Typed\Eloquent;

class EloquentConfig
{
    public function __construct(
        public ?string $modelsPath = null,
        public ?string $outputPath = null,
        public ?string $phpPath = null,
        public bool $legacy = false,
        public ?string $tsFilename = null,
    ) {
    }
}
