<?php

namespace Kiwilan\Typescriptable\Typed\Eloquent;

use Kiwilan\Typescriptable\Typed\Eloquent\Parser\ParserModelFillable;
use Kiwilan\Typescriptable\Typed\Eloquent\Printer\PrinterEloquentPhp;
use Kiwilan\Typescriptable\Typed\Eloquent\Printer\PrinterEloquentTypescript;
use Kiwilan\Typescriptable\Typed\Eloquent\Schemas\SchemaApp;
use Kiwilan\Typescriptable\Typed\Utils\Schema\SchemaClass;
use Kiwilan\Typescriptable\Typed\Utils\TypescriptableUtils;
use Kiwilan\Typescriptable\TypescriptableConfig;

class EloquentType
{
    protected ?SchemaApp $app = null;

    protected function __construct(
        protected EloquentConfig $config,
        protected ?string $typescript = null,
    ) {}

    public static function make(EloquentConfig $config = new EloquentConfig()): self
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

        $type->typescript = PrinterEloquentTypescript::make($type->app()->models());
        TypescriptableUtils::print($type->typescript, TypescriptableConfig::setPath($type->config()->typescriptFilename));

        if ($type->config()->phpPath) {
            $printer = PrinterEloquentPhp::make($type->app()->models(), $type->config()->phpPath);
            $printer->print();
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

    public function typescript(): ?string
    {
        return $this->typescript;
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
