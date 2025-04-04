<?php

namespace Kiwilan\Typescriptable\Eloquent\Eloquent;

use Kiwilan\Typescriptable\Eloquent\Database\DriverEnum;
use Kiwilan\Typescriptable\Eloquent\EloquentConfig;
use Kiwilan\Typescriptable\Eloquent\Parser\ParserFillable;
use Kiwilan\Typescriptable\Eloquent\Schema\SchemaClass;
use Kiwilan\Typescriptable\Eloquent\Schema\SchemaCollection;
use Kiwilan\Typescriptable\Eloquent\Schema\SchemaLaravel;
use Kiwilan\Typescriptable\Eloquent\Schema\SchemaModel;

/**
 * Base class for Eloquent engines.
 */
abstract class EngineBase
{
    protected ?SchemaLaravel $laravel = null;

    protected function __construct(
        protected EloquentConfig $config,
        protected ?string $typescript = null,
    ) {}

    /**
     * Execute the engine.
     */
    abstract public static function run(EloquentConfig $config = new EloquentConfig): self;

    /**
     * Parse models from the given array of `SchemaClass` with `EngineParser` or `EngineArtisan`
     *
     * @param  SchemaClass[]  $classes
     * @return SchemaModel[]
     */
    abstract protected function parseModels(array $classes): array;

    /**
     * Create new `SchemaLaravel` instance
     */
    protected function parse(bool $enableParser = false)
    {
        // Define Laravel schema, base for models and database information
        $this->laravel = SchemaLaravel::make(
            modelPath: $this->config->modelsPath,
            phpPath: $this->config->phpPath,
        );

        if ($enableParser) {
            $this->laravel->enableParser();
        }

        // Parse classes from the given path and skip models if needed
        $collect = SchemaCollection::make(
            basePath: $this->config->modelsPath,
            skip: $this->config->skipModels
        );
        $models = $collect->getOnlyModels();

        $this->laravel->parseBaseNamespace($models);

        // Parse models to find attributes and relations
        $models = $this->parseModels($models);
        $this->laravel->setModels($models);
    }

    // public static function make(EloquentConfig $config = new EloquentConfig): self
    // {
    //     return new self($config);
    // }

    // public function execute(): self
    // {
    //     $engine = new EngineParser($this->config);
    //     if (! $this->config->useParser) {
    //         $engine = new EngineArtisan($this->config);
    //     }

    //     $engine->run();

    //     // $type->typescript = PrinterEloquentTypescript::make($type->getApp()->getModels());
    //     // TypescriptableUtils::print($type->typescript, TypescriptableConfig::setPath($type->getConfig()->typescriptFilename));

    //     // if ($type->getConfig()->phpPath) {
    //     //     $printer = PrinterEloquentPhp::make($type->getApp()->getModels(), $type->getConfig()->phpPath);
    //     //     $printer->print();
    //     // }

    //     return $engine;
    // }

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
