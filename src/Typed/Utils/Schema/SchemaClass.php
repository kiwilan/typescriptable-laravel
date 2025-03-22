<?php

namespace Kiwilan\Typescriptable\Typed\Utils\Schema;

use ReflectionClass;
use SplFileInfo;

/**
 * Represents basics of PHP class.
 */
class SchemaClass
{
    /**
     * @param  string  $basePath  Base path where this class exists.
     * @param  string  $path  Full path of this class.
     * @param  SplFileInfo  $file  `SplFileInfo` instance for this class.
     * @param  string  $namespace  Namespace used by this class.
     * @param  string  $name  Name of this class.
     * @param  string  $fullname  Name of this class with potential subdirectory before to have unique name.
     * @param  ReflectionClass  $reflect  `ReflectionClass` instance for this class.
     * @param  bool  $isModel  To know if this class is a Laravel model.
     * @param  string[]  $traits  PHP traits used by this class.
     * @param  string  $extends  If class is extended by another class.
     */
    protected function __construct(
        protected string $basePath,
        protected string $path,
        protected SplFileInfo $file,
        protected string $namespace,
        protected string $name,
        protected string $fullname,
        protected ReflectionClass $reflect,
        protected bool $isModel = false,
        protected array $traits = [],
        protected ?string $extends = null,
    ) {}

    /**
     * Create new instance of `SchemaClass` from `SplFileInfo` and base path.
     *
     * @param  SplFileInfo  $file  Contains all informations about PHP file.
     * @param  string  $basePath  Define base path.
     */
    public static function make(SplFileInfo $file, string $basePath): ?self
    {
        $ext = $file->getExtension();
        if ($ext !== 'php') {
            return null;
        }

        $namespace = SchemaClass::findNamespace($file);

        $instance = null;
        try {
            $instance = new $namespace;
        } catch (\Throwable $th) {
            return null;
        }

        $reflect = new ReflectionClass($instance);
        $parent = $reflect->getParentClass();

        $nestedPath = str_replace($basePath, '', $file->getPathname());
        $nestedPath = str_replace('.php', '', $nestedPath);
        $nestedPath = substr($nestedPath, 1);
        $nestedPath = str_replace('/', '', $nestedPath);

        $parser = new self(
            basePath: $basePath,
            path: $file->getPathname(),
            file: $file,
            namespace: $namespace,
            name: $reflect->getShortName(),
            fullname: $nestedPath,
            reflect: $reflect,
            traits: $reflect->getTraitNames(),
            extends: $parent ? $parent->getName() : null,
        );

        $parser->isModel = $reflect->isSubclassOf('Illuminate\Database\Eloquent\Model') || $reflect->isSubclassOf('Illuminate\Foundation\Auth\User');

        return $parser;
    }

    /**
     * Get base path of the model.
     */
    public function getBasePath(): string
    {
        return $this->basePath;
    }

    /**
     * Get path of the model file.
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Get `SplFileInfo` from the model file.
     */
    public function getFile(): SplFileInfo
    {
        return $this->file;
    }

    /**
     * Get namespace of the model.
     */
    public function getNamespace(): string
    {
        return $this->namespace;
    }

    /**
     * Get name of the model.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get full name of the model, if model is into sub-directory, it will be like `FolderName\ModelName`.
     */
    public function getFullname(): string
    {
        return $this->fullname;
    }

    /**
     * Get `ReflectionClass` of the model.
     */
    public function getReflect(): ReflectionClass
    {
        return $this->reflect;
    }

    /**
     * Get all traits used by the model.
     *
     * @return string[]
     */
    public function getTraits(): array
    {
        return $this->traits;
    }

    /**
     * Check if class is a model.
     */
    public function isModel(): bool
    {
        return $this->isModel;
    }

    /**
     * Get extends class name, if exists.
     */
    public function getExtends(): ?string
    {
        return $this->extends;
    }

    /**
     * Get PHP class namespace from `SplFileInfo`.
     */
    private static function findNamespace(SplFileInfo $file): string
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
