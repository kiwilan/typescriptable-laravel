<?php

namespace Kiwilan\Typescriptable\Typed;

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
        // delete old `types-models.d.ts` file

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
