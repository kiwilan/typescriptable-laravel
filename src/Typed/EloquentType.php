<?php

namespace Kiwilan\Typescriptable\Typed;

use Kiwilan\Typescriptable\Typed\Eloquent\EloquentConfig;
use Kiwilan\Typescriptable\Typed\Eloquent\EloquentTypeLegacy;
use Kiwilan\Typescriptable\Typed\Eloquent\EloquentTypeShow;
use Kiwilan\Typescriptable\Typed\Eloquent\Schema\SchemaApp;
use Kiwilan\Typescriptable\TypescriptableConfig;

class EloquentType
{
    protected ?SchemaApp $app = null;

    protected function __construct(
        protected EloquentConfig $config,
    ) {
    }

    public static function make(EloquentConfig $config): self
    {
        return new self($config);
    }

    public function execute(): self
    {
        $type = null;
        if ($this->config->legacy) {
            $type = new EloquentTypeLegacy($this->config);
        } else {
            $type = new EloquentTypeShow($this->config);
        }

        if (! $type->config->modelsPath) {
            $type->config->modelsPath = $this->config->modelsPath ?: TypescriptableConfig::modelsDirectory();
        }

        $type->config->tsFilename = $this->config->tsFilename ?: TypescriptableConfig::modelsFilename();
        if (! $type->config->outputPath) {
            $type->config->outputPath = $this->config->outputPath ?: TypescriptableConfig::setPath();
        }

        if (! $type->config->phpPath) {
            $type->config->phpPath = $this->config->phpPath ?: TypescriptableConfig::modelsPhpPath();
        }

        return $type->run();
    }

    public function app(): SchemaApp
    {
        if (! $this->app) {
            $this->app = SchemaApp::make($this->config->modelsPath);
        }

        return $this->app;
    }

    public function config(): EloquentConfig
    {
        return $this->config;
    }
}
