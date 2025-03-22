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

    /**
     * Create new instance of `SchemaApp` from model path, and php path (optional).
     */
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

    /**
     * Get model path.
     */
    public function getModelPath(): string
    {
        return $this->modelPath;
    }

    /**
     * Get PHP path.
     */
    public function getPhpPath(): ?string
    {
        return $this->phpPath;
    }

    /**
     * Use parser.
     */
    public function useParser(): bool
    {
        return $this->useParser;
    }

    /**
     * Enable parser.
     */
    public function enableParer(): self
    {
        $this->useParser = true;

        return $this;
    }

    /**
     * Get base namespace.
     */
    public function getBaseNamespace(): ?string
    {
        return $this->baseNamespace;
    }

    /**
     * Get all `SchemaModel` as array.
     *
     * @return SchemaModel[]
     */
    public function getModels(): array
    {
        return $this->models;
    }

    /**
     * Get `SchemaModel` from namespace.
     */
    public function getModel(string $namespace): ?SchemaModel
    {
        return $this->models[$namespace] ?? null;
    }

    /**
     * Get database driver.
     */
    public function getDriver(): string
    {
        return $this->driver;
    }

    /**
     * Get database name.
     */
    public function getDatabaseName(): ?string
    {
        return $this->databaseName;
    }

    /**
     * Get database prefix, if any.
     */
    public function getDatabasePrefix(): ?string
    {
        return $this->databasePrefix;
    }

    /**
     * Set PHP path.
     */
    public function setPhpPath(string $phpPath): self
    {
        $this->phpPath = $phpPath;

        return $this;
    }

    /**
     * Set models.
     *
     * @param  SchemaModel[]  $models
     */
    public function setModels(array $models): self
    {
        $this->models = $this->improveRelations($models);

        foreach ($this->models as $model) {
            $accessors = ParserAccessor::collection($model->getSchemaClass());
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

        $namespace = $schemaClass->getNamespace();
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
            foreach ($model->getRelations() as $relation) {
                $typescript = 'any';
                $relationNamespace = $relation->getRelatedToModel();

                if ($relationNamespace) {
                    $modelNamespace = array_filter($models, fn (SchemaModel $m) => $m->getNamespace() === $relationNamespace);
                    $first = reset($modelNamespace);
                    if ($first) {
                        $relation->setPhpType($first->getSchemaClass()?->getFullname());
                        $typescript = $first->getTypescriptModelName();
                    }
                }

                $relation->setTypescriptType($typescript, $this->baseNamespace);

                if ($relation->isMany()) {
                    $model->addAttribute(new SchemaModelAttribute(
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

        return $outputs;
    }
}
