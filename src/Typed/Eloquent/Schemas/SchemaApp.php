<?php

namespace Kiwilan\Typescriptable\Typed\Eloquent\Schemas;

use Illuminate\Support\Facades\Schema;
use Kiwilan\Typescriptable\Typed\Eloquent\Parser\ParserAccessor;
use Kiwilan\Typescriptable\Typed\Eloquent\Schemas\Model\SchemaModel;
use Kiwilan\Typescriptable\Typed\Eloquent\Schemas\Model\SchemaModelAttribute;
use Kiwilan\Typescriptable\Typed\Utils\Schema\SchemaClass;

class SchemaApp
{
    /**
     * @param  SchemaModel[]  $models
     */
    protected function __construct(
        protected string $modelPath,
        protected ?string $phpPath = null,
        protected bool $useParser = false,
        protected ?string $baseNamespace = null,
        protected array $models = [],
        protected ?string $driver = null,
        protected ?string $databaseName = null,
        protected ?string $databasePrefix = null,
    ) {}

    public static function make(string $modelPath, ?string $phpPath): self
    {
        $self = new self($modelPath, $phpPath);

        try {
            $self->driver = Schema::getConnection()->getDriverName();
            $self->databaseName = Schema::getConnection()->getDatabaseName();
            if (str_contains($self->databaseName, ';Database=')) { // for sqlsrv
                $exploded = explode(';Database=', $self->databaseName);
                $self->databaseName = $exploded[1] ?? Schema::getConnection()->getDatabaseName();
            }

            $self->databasePrefix = config("database.connections.{$self->driver}.prefix");
        } catch (\Exception $e) {
        }

        return $self;
    }

    public function modelPath(): string
    {
        return $this->modelPath;
    }

    public function phpPath(): ?string
    {
        return $this->phpPath;
    }

    public function setPhpPath(string $phpPath): self
    {
        $this->phpPath = $phpPath;

        return $this;
    }

    public function useParser(): bool
    {
        return $this->useParser;
    }

    public function enableParer(): self
    {
        $this->useParser = true;

        return $this;
    }

    public function baseNamespace(): ?string
    {
        return $this->baseNamespace;
    }

    /**
     * @return SchemaModel[]
     */
    public function models(): array
    {
        return $this->models;
    }

    public function getModel(string $namespace): ?SchemaModel
    {
        return $this->models[$namespace] ?? null;
    }

    public function driver(): string
    {
        return $this->driver;
    }

    public function databaseName(): ?string
    {
        return $this->databaseName;
    }

    public function databasePrefix(): ?string
    {
        return $this->databasePrefix;
    }

    /**
     * @param  SchemaModel[]  $models
     */
    public function setModels(array $models): self
    {
        $this->models = $this->improveRelations($models);

        foreach ($this->models as $model) {
            $accessors = ParserAccessor::collection($model->schemaClass());
            foreach ($accessors as $accessor) {
                $model->updateAccessor($accessor);
            }
        }

        return $this;
    }

    /**
     * Parse base namespace.
     *
     * @param  SchemaClass[]  $schemas
     */
    public function parseBaseNamespace(array $schemas): self
    {
        $schemaClass = collect($schemas)->first();

        if (! $schemaClass) {
            return $this;
        }

        $namespace = $schemaClass->namespace();
        $lastBasePathPart = collect(explode(DIRECTORY_SEPARATOR, $this->modelPath))->last(); // `Models` into `App\Models`

        $baseNamespace = substr($namespace, 0, strpos($namespace, $lastBasePathPart)); // `App\`
        $this->baseNamespace = $baseNamespace.$lastBasePathPart; // `App\Models`

        return $this;
    }

    /**
     * Improve relations.
     *
     * @param  SchemaModel[]  $models
     */
    private function improveRelations(array $models): array
    {
        $outputs = $models;

        foreach ($models as $model) {
            foreach ($model->relations() as $relation) {
                $typescript = 'any';
                $relationNamespace = $relation->relatedToModel();

                if ($relationNamespace) {
                    $modelNamespace = array_filter($models, fn (SchemaModel $m) => $m->namespace() === $relationNamespace);
                    $first = reset($modelNamespace);
                    if ($first) {
                        $relation->setPhpType($first->schemaClass()?->fullname());
                        $typescript = $first->typescriptModelName();
                    }
                }

                $relation->setTypescriptType($typescript, $this->baseNamespace);

                if ($relation->isMany()) {
                    $model->setAttribute(new SchemaModelAttribute(
                        name: $relation->name().'_count',
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

        return $outputs;
    }
}
