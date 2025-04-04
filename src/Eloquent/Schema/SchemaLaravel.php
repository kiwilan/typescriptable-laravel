<?php

namespace Kiwilan\Typescriptable\Eloquent\Schema;

use Illuminate\Support\Facades\Schema;
use Kiwilan\Typescriptable\Eloquent\Database\DriverEnum;
use Kiwilan\Typescriptable\Eloquent\Parser\ParserAccessor;

/**
 * A `SchemaLaravel` contains models, database information (driver, table, policy), and a base namespace.
 * It is used to parse models and their relations.
 *
 * Used by `EngineParser` and `EngineArtisan`.
 */
class SchemaLaravel
{
    /**
     * @param  SchemaModel[]  $models
     */
    protected function __construct(
        protected string $modelPath, // Path to the models directory from `laravel-typescriptable` config, e.g. `app/Models`
        protected ?string $phpPath = null, // Path to the PHP files directory from `laravel-typescriptable` config, e.g. `app/`
        protected bool $useParser = false, // Use parser to parse models and relations
        protected ?string $baseNamespace = null, // Base namespace of the models, e.g. `App\Models`
        protected array $models = [], // Models parsed from the models directory as `SchemaModel` instances
        protected ?DriverEnum $driver = null, // Database driver, e.g. `mysql`, `pgsql`, `sqlite`, `sqlsrv`
        protected ?string $databaseName = null, // Database name, e.g. `movies`
        protected ?string $databasePrefix = null, // Database prefix, e.g. `ts_`
    ) {}

    /**
     * Create a new `SchemaLaravel` instance.
     *
     * @param  string  $modelPath  Path to the models directory from `laravel-typescriptable` config
     * @param  string|null  $phpPath  Path to the PHP files directory from `laravel-typescriptable` config
     */
    public static function make(string $modelPath, ?string $phpPath): self
    {
        $self = new self($modelPath, $phpPath);

        try {
            $self->driver = DriverEnum::tryFrom(Schema::getConnection()->getDriverName());
            $self->databaseName = Schema::getConnection()->getDatabaseName();
            if (str_contains($self->databaseName, ';Database=')) { // for sqlsrv
                $exploded = explode(';Database=', $self->databaseName);
                $self->databaseName = $exploded[1] ?? Schema::getConnection()->getDatabaseName();
            }

            $self->databasePrefix = config("database.connections.{$self->driver->value}.prefix");
            if (empty($self->databasePrefix)) {
                $self->databasePrefix = null;
            }
        } catch (\Exception $e) {
        }

        return $self;
    }

    /**
     * Get the model path.
     *
     * e.g. `app/Models`
     */
    public function getModelPath(): string
    {
        return $this->modelPath;
    }

    /**
     * Get the PHP path.
     *
     * e.g. `app/`
     */
    public function getPhpPath(): ?string
    {
        return $this->phpPath;
    }

    /**
     * Set the PHP path.
     */
    public function setPhpPath(string $phpPath): self
    {
        $this->phpPath = $phpPath;

        return $this;
    }

    /**
     * Get the use parser flag.
     */
    public function useParser(): bool
    {
        return $this->useParser;
    }

    /**
     * Set the use parser flag.
     */
    public function enableParser(): self
    {
        $this->useParser = true;

        return $this;
    }

    /**
     * Get the base namespace.
     *
     * e.g. `App\Models`
     */
    public function getBaseNamespace(): ?string
    {
        return $this->baseNamespace;
    }

    /**
     * Get the models.
     *
     * @return SchemaModel[]
     */
    public function getModels(): array
    {
        return $this->models;
    }

    /**
     * Get a model by its namespace.
     *
     * @param  string  $namespace  Namespace of the model, e.g. `App\Models\Movie`
     */
    public function getModel(string $namespace): ?SchemaModel
    {
        return $this->models[$namespace] ?? null;
    }

    /**
     * Get the database driver.
     *
     * e.g. `mysql`, `pgsql`, `sqlite`, `sqlsrv`
     */
    public function getDriver(): DriverEnum
    {
        return $this->driver;
    }

    /**
     * Get the database name.
     *
     * e.g. `movies`
     */
    public function getDatabaseName(): ?string
    {
        return $this->databaseName;
    }

    /**
     * Get the database prefix.
     *
     * e.g. `ts_`
     */
    public function getDatabasePrefix(): ?string
    {
        return $this->databasePrefix;
    }

    /**
     * Fill the models.
     *
     * @param  SchemaModel[]  $models
     */
    public function setModels(array $models): self
    {
        foreach ($this->models as $model) {
            $this->improveModelRelations($model, $models);
        }

        foreach ($this->models as $model) {
            $accessors = ParserAccessor::collection($model->getClass());
            foreach ($accessors as $accessor) {
                $model->updateAccessor($accessor);
            }
        }

        return $this;
    }

    /**
     * Parse base namespace from first model to get the base namespace.
     *
     * @param  SchemaClass[]  $schemas
     */
    public function parseBaseNamespace(array $schemas): self
    {
        $schemaClass = collect($schemas)->first();

        if (! $schemaClass) {
            return $this;
        }

        $namespace = $schemaClass->getNamespace();
        $lastBasePathPart = collect(explode(DIRECTORY_SEPARATOR, $this->modelPath))->last(); // `Models` into `App\Models`

        $baseNamespace = substr($namespace, 0, strpos($namespace, $lastBasePathPart)); // `App\`
        $this->baseNamespace = $baseNamespace.$lastBasePathPart; // `App\Models`

        return $this;
    }

    /**
     * Improve relations for a model.
     *
     * @param  SchemaModel[]  $models
     */
    private function improveModelRelations(SchemaModel $model, array $models): void
    {
        foreach ($model->getRelations() as $relation) {
            $typescript = 'any';
            $relationNamespace = $relation->getRelatedToModel();

            // Find relation namespace
            // e.g. `App\Models\Movie` for `movies()`
            if ($relationNamespace) {
                $modelNamespace = array_filter($models, fn (SchemaModel $m) => $m->getClass()->getNamespace() === $relationNamespace);
                $first = reset($modelNamespace);
                if ($first) {
                    $relation->setPhpType($first->getClass()?->getFullname());
                    $typescript = $first->getTypescriptModelName();
                }
            }

            // Set the relation type
            // e.g. `App.Models.Movie[]` for `movies()`
            $relation->setTypescriptType($typescript, $this->baseNamespace);

            // Add count attribute for many relations
            // e.g. `movies_count` for `movies()`
            if ($relation->isMany()) {
                $model->setAttribute(new SchemaAttribute(
                    name: $relation->getSnakeCaseName().'_count',
                    databaseType: null,
                    increments: false,
                    nullable: true,
                    default: null,
                    unique: false,
                    fillable: false,
                    hidden: false,
                    appended: false,
                    cast: false,
                    phpType: 'int',
                    typescriptType: 'number',
                    databaseFields: null,
                ));
            }
        }
    }
}
