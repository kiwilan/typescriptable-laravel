<?php

namespace Kiwilan\Typescriptable\Typed\Utils;

use Illuminate\Support\Str;
use Kiwilan\Typescriptable\Typed\Eloquent\EloquentItem;
use ReflectionClass;
use SplFileInfo;

class ClassItem
{
    protected function __construct(
        public string $path,
        public SplFileInfo $file,
        public string $namespace,
        public string $name,
        public ReflectionClass $reflect,
        public bool $isModel = false,
        public ?string $extends = null,
        public ?EloquentItem $eloquent = null,
    ) {
    }

    public static function make(string $path, SplFileInfo $file): self
    {
        $namespace = ClassItem::getFileNamespace($file);
        $instance = new $namespace();
        $reflect = new ReflectionClass($instance);
        $parent = $reflect->getParentClass();

        $parser = new self(
            path: $path,
            file: $file,
            namespace: $namespace,
            name: $reflect->getShortName(),
            reflect: $reflect,
            extends: $parent ? $parent->getName() : null,
            isModel: $instance instanceof \Illuminate\Database\Eloquent\Model,
        );

        if ($parser->isModel) {
            $parser->eloquent = EloquentItem::make($parser);
        }

        // $parser->typeableModel = EloquentModel::make($parser);
        // $parser->columns = $parser->typeableModel->columns;

        return $parser;
    }

    /**
     * @return ClassItem[]
     */
    public static function list(string $path, array $skip = []): array
    {
        /** @var ClassItem[] */
        $classes = [];

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($path, \FilesystemIterator::SKIP_DOTS)
        );

        /** @var \SplFileInfo $file */
        foreach ($iterator as $file) {
            if (! $file->isDir()) {
                $namespace = ClassItem::getFileNamespace($file);
                if (in_array($namespace, $skip)) {
                    continue;
                }
                $model = ClassItem::make(
                    path: $file->getPathname(),
                    file: $file,
                );
                $classes[$model->name] = $model;
            }
        }

        return $classes;
    }

    // /**
    //  * @param  ClassProperty[]  $properties
    //  */
    // public static function fake(string $name, array $properties): self
    // {
    //     $snake = Str::snake($name);
    //     $table = Str::plural($snake);

    //     $self = new self(
    //         table: $table,
    //         name: $name,
    //     );
    //     $class->typeableModel = EloquentModel::fake($class, $properties);

    //     return $self;
    // }

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
