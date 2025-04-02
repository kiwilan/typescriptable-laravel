<?php

namespace Kiwilan\Typescriptable\Eloquent\Parser;

use ReflectionMethod;

/**
 * `ParserRelation` can parse a Laravel relation from a method in a model file.
 * It can parse the relation name, type and related model.
 */
class ParserRelation
{
    public function __construct(
        protected string $name,
        protected ?string $type = null,
        protected ?string $related = null,
        protected ?string $relatedBase = null,
    ) {}

    /**
     * Create a new `ParserRelation` that contains information about a Laravel relation.
     *
     * @param  \ReflectionMethod  $method  The method to parse.
     * @param  string  $baseNamespace  The base namespace of the model.
     */
    public static function make(ReflectionMethod $method, string $baseNamespace): self
    {
        $defaultNamespace = "{$baseNamespace}\\";
        $self = new self(
            name: $method->getName(),
        );

        $lastLine = $self->getLastLine($method);
        $self->type = $self->parseReturnType($method);
        $self->relatedBase = $self->parseRelationModel($lastLine);
        $self->related = $defaultNamespace.$self->relatedBase;

        $lastChar = substr($self->related, -1);
        if ($lastChar === '\\') {
            $self->parseExternalClass($lastLine, $method);
        }

        if ($self->related === null) {
            $self->related = $method->getDeclaringClass()->name;
        }

        if ($self->name === 'author') {
            $lines = $self->getUseLines($method);
            foreach ($lines as $line) {
                // remove last char
                $line = substr($line, 0, -1);
                $lastWord = explode('\\', $line);
                $lastWord = end($lastWord);

                if ($lastWord === $self->relatedBase) {
                    $line = str_replace('use ', '', $line);
                    $self->related = $line;
                }
            }
        }

        return $self;
    }

    /**
     * Get name of the relation.
     *
     * E.g. `chapters`, `category`, etc.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get type of the relation.
     *
     * E.g. `HasMany`, `BelongsTo`, etc.
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * Get related model of the relation.
     *
     * E.g. `App\Models\Chapter`
     */
    public function getRelated(): ?string
    {
        return $this->related;
    }

    /**
     * Parse the last line of the method to get the related model.
     */
    private function parseExternalClass(string $lastLine, ReflectionMethod $method): void
    {
        $this->related = null;
        $external = $this->parseNotInternalClass($lastLine);

        $reflect = $method->getDeclaringClass()->newInstance();
        $externalMethod = str_replace('$this->', '', $external);
        $externalMethod = str_replace('()', '', $externalMethod);
        if (method_exists($reflect, $externalMethod)) {
            try {
                $external = $reflect->{$externalMethod}();
                if ($external) {
                    $this->related = $external;
                }
            } catch (\Throwable $th) {
                // throw $th;
            }
        }
    }

    /**
     * Parse the return type of the method to get the type of the relation.
     */
    private function parseReturnType(\ReflectionMethod $method): string
    {
        $reflectionNamedType = $method->getReturnType();
        $returnType = 'BelongsToMany';

        if ($reflectionNamedType instanceof \ReflectionNamedType) {
            $reflectionNamedTypeName = $reflectionNamedType->getName();
            if (str_contains($reflectionNamedTypeName, '\\')) {
                $returnTypeFull = explode('\\', $reflectionNamedTypeName);
                $returnType = end($returnTypeFull);
            }
        }

        return $returnType;
    }

    /**
     * Parse the last line of the method to get the related model.
     */
    private function parseRelationModel(string $lastLine): ?string
    {
        $type = null;

        $regex = '/\w+::class/';
        if (preg_match($regex, $lastLine, $matches)) {
            $type = $matches[0];
            $type = str_replace('::class', '', $type);
        }

        return $type;
    }

    /**
     * Parse not internal class.
     */
    private function parseNotInternalClass(string $lastLine): ?string
    {
        if (preg_match('/\((.*?)\)/', $lastLine, $matches)) {
            $type = $matches[1];
            $lastChar = substr($type, -1);
            if ($lastChar === '(') {
                $type = "{$type})";
            }

            return $type;
        }

        return null;
    }

    /**
     * Get the last line of the method.
     */
    private function getLastLine(\ReflectionMethod $method): string
    {
        $startLine = $method->getStartLine();
        $endLine = $method->getEndLine();

        $contents = file($method->getFileName());
        $lines = [];

        for ($i = $startLine; $i < $endLine; $i++) {
            $lines[] = $contents[$i];
        }

        $removeChars = ['{', '}', ';', '"', "'", "\n", "\r", "\t", ''];
        $lines = array_map(fn ($line) => str_replace($removeChars, '', $line), $lines);
        $lines = array_map(fn ($line) => trim($line), $lines);
        $lines = array_filter($lines, fn ($line) => ! empty($line));
        $line = implode(' ', $lines);

        return $line;
    }

    /**
     * Get the use lines of the method.
     */
    private function getUseLines(\ReflectionMethod $method): array
    {
        $lines = [];
        $contents = file($method->getFileName());

        foreach ($contents as $line) {
            if (str_contains($line, 'use ')) {
                $lines[] = $line;
            }
        }

        // trim the lines
        $lines = array_map(fn ($line) => trim($line), $lines);
        // remove carriage return
        $lines = array_map(fn ($line) => str_replace("\n", '', $line), $lines);

        return $lines;
    }
}
