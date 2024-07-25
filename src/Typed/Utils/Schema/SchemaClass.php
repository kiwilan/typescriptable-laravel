<?php

namespace Kiwilan\Typescriptable\Typed\Utils\Schema;

use ReflectionClass;
use SplFileInfo;

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

        if ($parser->extends === 'Illuminate\Database\Eloquent\Model' || $parser->extends === 'Illuminate\Foundation\Auth\User') {
            $parser->isModel = true;
        }

        return $parser;
    }

    /**
     * Get base path.
     */
    public function basePath(): string
    {
        return $this->basePath;
    }

    /**
     * Get path of the file.
     */
    public function path(): string
    {
        return $this->path;
    }

    /**
     * Get file info.
     */
    public function file(): SplFileInfo
    {
        return $this->file;
    }

    public function namespace(): string
    {
        return $this->namespace;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function fullname(): string
    {
        return $this->fullname;
    }

    public function reflect(): ReflectionClass
    {
        return $this->reflect;
    }

    /**
     * @return string[]
     */
    public function traits(): array
    {
        return $this->traits;
    }

    public function isModel(): bool
    {
        return $this->isModel;
    }

    public function extends(): ?string
    {
        return $this->extends;
    }

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
