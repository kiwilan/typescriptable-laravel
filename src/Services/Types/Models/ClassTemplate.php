<?php

namespace Kiwilan\Typescriptable\Services\Types\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use ReflectionClass;
use SplFileInfo;

/**
 * Class ClassTemplate
 *
 * @property string $path
 * @property SplFileInfo $file
 * @property string $namespace
 * @property string $name
 * @property ReflectionClass $reflector
 * @property bool $isModel
 * @property ?string $table
 * @property ClassProperty[] $columns
 * @property string[] $appends
 * @property string[] $casts
 * @property string[] $dates
 * @property string[] $fillable
 * @property string[] $hidden
 */
class ClassTemplate
{
    protected function __construct(
        public ?string $path = null,
        public ?SplFileInfo $file = null,
        public ?string $namespace = null,
        public ?string $name = null,
        public ?ReflectionClass $reflector = null,
        public bool $isModel = false,
        public ?Model $model = null,
        public ?string $table = null,
        /** @var ClassProperty[] */
        public array $columns = [],
        /** @var string[] */
        public array $appends = [],
        /** @var string[] */
        public array $casts = [],
        /** @var string[] */
        public array $dates = [],
        /** @var string[] */
        public array $fillable = [],
        /** @var string[] */
        public array $hidden = [],
        public ?EloquentModel $typeableModel = null,
    ) {
    }

    public static function make(string $path, SplFileInfo $file): self
    {
        $namespace = ClassTemplate::getFileNamespace($file);
        $class = new $namespace();
        $reflector = new ReflectionClass($class);
        $isModel = $class instanceof \Illuminate\Database\Eloquent\Model;

        $parser = new self(
            path: $path,
            file: $file,
            namespace: $reflector->getNamespaceName(),
            name: $reflector->getShortName(),
            reflector: $reflector,
            isModel: $isModel,
        );

        if ($parser->isModel) {
            /** @var Model */
            $model = $class;
            $parser->model = $model;
            $parser->table = $parser->model->getTable();
            $parser->appends = $parser->model->getAppends();
            $parser->casts = $parser->model->getCasts();
            $parser->dates = $parser->model->getDates();
            $parser->fillable = $parser->model->getFillable();
            $parser->hidden = $parser->model->getHidden();
        }

        $parser->typeableModel = EloquentModel::make($parser);
        $parser->columns = $parser->typeableModel->columns;

        return $parser;
    }

    /**
     * @param  ClassProperty[]  $properties
     */
    public static function fake(string $name, array $properties): self
    {
        $snake = Str::snake($name);
        $table = Str::plural($snake);

        $class = new self(
            table: $table,
            name: $name,
        );
        $class->typeableModel = EloquentModel::fake($class, $properties);

        return $class;
    }

    private static function getFileNamespace(SplFileInfo $file): string
    {
        $path = $file->getPathName();
        $name = $file->getBasename('.php');

        $ns = null;
        $handle = fopen($path, 'r');

        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                if (strpos($line, 'namespace') === 0) {
                    $parts = explode(' ', $line);
                    $ns = rtrim(trim($parts[1]), ';');

                    break;
                }
            }
            fclose($handle);
        }

        return "{$ns}\\{$name}";
    }
}
