<?php

namespace Kiwilan\Typescriptable\Typed\Typescript;

use Closure;

class TypescriptToPhp
{
    /**
     * @param  array<string, array<string, array{type: string, nullable: bool}>>  $raw
     * @param  array<string, TypescriptClass>  $classes
     */
    protected function __construct(
        protected string $path,
        protected array $raw = [],
        protected array $classes = [],
    ) {
    }

    public static function make(string $path): self
    {
        $self = new self($path);

        $className = null;
        $self->readLineByLine($self->path, function (string $line, int $lineNumber) use ($self, &$className) {
            $line = trim($line);

            if ($self->isType($line)) {
                $className = $self->isType($line);
                $self->raw[$className] = [];
            }

            $self->parseProperty($line, $className);
        });

        foreach ($self->raw as $className => $properties) {
            $tsClass = TypescriptClass::make($className, $properties);
            $self->classes[$tsClass->name()] = $tsClass;
        }

        return $self;
    }

    /**
     * @return array<string, array<string, array{type: string, nullable: bool}>>
     */
    public function raw(): array
    {
        return $this->raw;
    }

    /**
     * @return array<string, TypescriptClass>
     */
    public function classes(): array
    {
        return $this->classes;
    }

    private function parseProperty(string $line, ?string $className): void
    {
        if (! $className) {
            return;
        }

        $property = explode(':', $line);
        if (count($property) !== 2) {
            return;
        }

        $key = trim($property[0]);
        $value = trim($property[1]);
        $isNullable = str_contains($key, '?');

        $key = str_replace('?', '', $key);
        $value = str_replace(';', '', $value);
        $this->raw[$className][$key] = [
            'type' => $value,
            'nullable' => $isNullable,
        ];
    }

    private function isType(string $content): string|false
    {
        $regex = '/^export\s+interface\s+([A-Za-z0-9]+)(?:<.*>)?\s*{/';

        if (preg_match($regex, $content, $matches)) {
            $className = $matches[1];
            $typeParam = $matches[2] ?? null;

            return $className;
        }

        return false;
    }

    /**
     * @param  Closure  $closure  function(string $line, int $lineNumber): void
     */
    private function readLineByLine(string $path, Closure $closure)
    {
        $contents = file_get_contents($path);
        $lines = explode("\n", $contents);

        foreach ($lines as $lineNumber => $line) {
            $closure($line, $lineNumber);
        }
    }
}

class TypescriptClass
{
    /**
     * @param  array<string, TypescriptProperty>  $properties
     */
    protected function __construct(
        protected string $name,
        protected array $properties = [],
    ) {
    }

    /**
     * @param  array<string, array{type: string, nullable: bool}>  $properties
     */
    public static function make(string $name, array $properties): self
    {
        $items = [];
        foreach ($properties as $key => $property) {
            $tsProperty = TypescriptProperty::make($key, $property['type'], $property['nullable']);
            $items[$tsProperty->name()] = $tsProperty;
        }

        return new self($name, $items);
    }

    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return array<string, TypescriptProperty>
     */
    public function properties(): array
    {
        return $this->properties;
    }
}

class TypescriptProperty
{
    protected function __construct(
        protected string $name,
        protected string $type,
        protected bool $nullable,
    ) {
    }

    public static function make(string $name, string $type, bool $nullable): self
    {
        return new self($name, $type, $nullable);
    }

    public function name(): string
    {
        return $this->name;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function isNullable(): bool
    {
        return $this->nullable;
    }
}
