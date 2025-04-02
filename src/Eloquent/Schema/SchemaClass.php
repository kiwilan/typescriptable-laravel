<?php

namespace Kiwilan\Typescriptable\Eloquent\Schema;

use ReflectionClass;
use SplFileInfo;

/**
 * A `SchemaClass` contains information about a class.
 * It contains the class name, namespace, file path, and other information.
 *
 * Compatible with any PHP class, with `bool` to indicate if the class is a Laravel Model.
 */
class SchemaClass
{
    /**
     * @param  string[]  $traits
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
     * Create a new instance of `SchemaClass`.
     *
     * @param  SplFileInfo  $file  Example: `new SplFileInfo($path)`
     * @param  string  $basePath  Example: `/var/www/html/laravel-app/app/Models`
     */
    public static function make(SplFileInfo $file, string $basePath): ?self
    {
        $ext = $file->getExtension();
        if ($ext !== 'php') {
            return null;
        }

        $namespace = SchemaClass::fileNamespace($file);

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
     * Get base path.
     *
     * Example: `/var/www/html/laravel-app/app/Models`
     */
    public function getBasePath(): string
    {
        return $this->basePath;
    }

    /**
     * Get full path of the file.
     *
     * Example: `/var/www/html/laravel-app/app/Models/Movie.php`
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Get file info with `SplFileInfo`.
     */
    public function getFile(): SplFileInfo
    {
        return $this->file;
    }

    /**
     * Get namespace of the class.
     *
     * Example: `App\Models\Movie`
     */
    public function getNamespace(): string
    {
        return $this->namespace;
    }

    /**
     * Get name of the class.
     *
     * Example: `Movie`
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get full name of the class (can be use to get unique model name).
     *
     * Example: `Movie` or `NestedMovie` if the class is in a nested folder.
     */
    public function getFullname(): string
    {
        return $this->fullname;
    }

    /**
     * Get reflection of the class with `ReflectionClass`.
     */
    public function getReflect(): ReflectionClass
    {
        return $this->reflect;
    }

    /**
     * Get traits of the class.
     *
     * Example: [`Illuminate\Database\Eloquent\Factories\HasFactory`]
     *
     * @return string[]
     */
    public function getTraits(): array
    {
        return $this->traits;
    }

    /**
     * Know if the class is a Laravel Model.
     *
     * Example: `true` if the class extends `Illuminate\Database\Eloquent\Model` or `Illuminate\Foundation\Auth\User`.
     */
    public function isModel(): bool
    {
        return $this->isModel;
    }

    /**
     * Get the parent class name.
     *
     * Example: `Illuminate\Database\Eloquent\Model` or `Illuminate\Foundation\Auth\User`.
     */
    public function getExtends(): ?string
    {
        return $this->extends;
    }

    /**
     * Extract class namespace from file.
     */
    private static function fileNamespace(SplFileInfo $file): string
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
