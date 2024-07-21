<?php

namespace Kiwilan\Typescriptable\Typed;

use Kiwilan\Typescriptable\Typed\Eloquent\Converter\EloquentToPhp;
use Kiwilan\Typescriptable\Typed\Eloquent\Converter\EloquentToTypescript;
use Kiwilan\Typescriptable\Typed\Eloquent\EloquentConfig;
use Kiwilan\Typescriptable\Typed\Eloquent\EloquentTypeArtisan;
use Kiwilan\Typescriptable\Typed\Eloquent\EloquentTypeParser;
use Kiwilan\Typescriptable\Typed\Eloquent\Schema\SchemaApp;

class EloquentType
{
    protected ?SchemaApp $app = null;

    protected function __construct(
        protected EloquentConfig $config,
    ) {}

    public static function make(EloquentConfig $config): self
    {
        return new self($config);
    }

    public function execute(): self
    {
        $type = null;
        if ($this->config->useParser) {
            $type = new EloquentTypeParser($this->config);
        } else {
            $type = new EloquentTypeArtisan($this->config);
        }

        $type->run();

        $typescript = EloquentToTypescript::make($type->app()->models(), "{$type->config()->outputPath}/{$type->config()->tsFilename}");
        $typescript->print();

        if ($this->config()->phpPath) {
            $php = EloquentToPhp::make($type->app()->models(), $this->config()->phpPath);
            $php->print();
        }

        return $type;
    }

    public function app(): SchemaApp
    {
        if (! $this->app) {
            $this->app = SchemaApp::make($this->config->modelsPath, $this->config->phpPath);
        }

        return $this->app;
    }

    public function config(): EloquentConfig
    {
        return $this->config;
    }
}
