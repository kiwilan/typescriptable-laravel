<?php

namespace Kiwilan\Typescriptable\Eloquent\Eloquent;

use Kiwilan\Typescriptable\Eloquent\Database\DriverEnum;
use Kiwilan\Typescriptable\Eloquent\EloquentConfig;
use Kiwilan\Typescriptable\Eloquent\Parser\ParserFillable;
use Kiwilan\Typescriptable\Eloquent\Schema\SchemaClass;
use Kiwilan\Typescriptable\Eloquent\Schema\SchemaLaravel;

/**
 * Base class for Eloquent engines.
 */
class EngineBase
{
    protected ?SchemaLaravel $laravel = null;

    protected function __construct(
        protected EloquentConfig $config,
        protected ?string $typescript = null,
    ) {}

    public static function make(EloquentConfig $config = new EloquentConfig): self
    {
        return new self($config);
    }

    public function execute(): self
    {
        $engine = new EngineParser($this->config);
        if (! $this->config->useParser) {
            $engine = new EngineArtisan($this->config);
        }

        $engine->run();

        // $type->typescript = PrinterEloquentTypescript::make($type->getApp()->getModels());
        // TypescriptableUtils::print($type->typescript, TypescriptableConfig::setPath($type->getConfig()->typescriptFilename));

        // if ($type->getConfig()->phpPath) {
        //     $printer = PrinterEloquentPhp::make($type->getApp()->getModels(), $type->getConfig()->phpPath);
        //     $printer->print();
        // }

        return $engine;
    }

    /**
     * Get the Laravel schema with models and database information.
     */
    public function getLaravel(): SchemaLaravel
    {
        if (! $this->laravel) {
            $this->laravel = SchemaLaravel::make($this->config->modelsPath, $this->config->phpPath);
        }

        return $this->laravel;
    }

    /**
     * Get the Eloquent configuration with models path, database driver, and table name.
     */
    public function getConfig(): EloquentConfig
    {
        return $this->config;
    }

    /**
     * Get the TypeScript string, which is the output of the engine.
     */
    public function getTypescript(): ?string
    {
        return $this->typescript;
    }

    /**
     * Parse MongoDB model.
     */
    protected function parseMongoDB(SchemaClass $class, DriverEnum $driver): ?array
    {
        if ($driver !== DriverEnum::mongodb) {
            return null;
        }

        $fillable = ParserFillable::make($class);

        return $fillable->getAttributes();
    }
}
