<?php

namespace Kiwilan\Typescriptable\Typed;

use Kiwilan\Typescriptable\Typed\Eloquent\EloquentConfig;
use Kiwilan\Typescriptable\Typed\Eloquent\EloquentTypeArtisan;
use Kiwilan\Typescriptable\Typed\Eloquent\EloquentTypeParser;
use Kiwilan\Typescriptable\Typed\Eloquent\Parser\ParserModelFillable;
use Kiwilan\Typescriptable\Typed\Eloquent\Printer\PrinterToPhp;
use Kiwilan\Typescriptable\Typed\Eloquent\Printer\PrinterToTypescript;
use Kiwilan\Typescriptable\Typed\Eloquent\Schemas\SchemaApp;
use Kiwilan\Typescriptable\Typed\Utils\Schema\SchemaClass;

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

        $typescript = PrinterToTypescript::make($type->app()->models(), "{$type->config()->outputPath}/{$type->config()->tsFilename}");
        $typescript->print();

        if ($type->config()->phpPath) {
            $php = PrinterToPhp::make($type->app()->models(), $type->config()->phpPath);
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

    /**
     * Parse MongoDB model.
     */
    protected function parseMongoDb(SchemaClass $schemaClass, string $driver): ?array
    {
        if ($driver !== 'mongodb') {
            return null;
        }

        $fillable = ParserModelFillable::make($schemaClass);

        return $fillable->attributes();
    }
}
