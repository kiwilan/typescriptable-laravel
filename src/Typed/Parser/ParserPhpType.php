<?php

namespace Kiwilan\Typescriptable\Typed\Parser;

class ParserPhpType
{
    protected function __construct(
        protected string $phpType,
        protected string $typescriptType = 'any',
        protected bool $isArray = false,
        protected bool $isAdvanced = false,
    ) {}

    public static function make(string $type): self
    {
        $self = new self($type);
        $self->parse();

        return $self;
    }

    /**
     * Convert PHP type to TypeScript type.
     */
    public static function toTypescript(string $phpType): string
    {
        return match ($phpType) {
            'int' => 'number',
            'float' => 'number',
            'string' => 'string',
            'bool' => 'boolean',
            'true' => 'boolean',
            'false' => 'boolean',
            'array' => 'any[]',
            'object' => 'any',
            'mixed' => 'any',
            'null' => 'undefined',
            'void' => 'void',
            'callable' => 'Function',
            'iterable' => 'any[]',
            'DateTime' => 'Date',
            'DateTimeInterface' => 'Date',
            'Carbon' => 'Date',
            'Model' => 'any',
            default => 'any', // skip `Illuminate\Database\Eloquent\Casts\Attribute`
        };
    }

    public function phpType(): string
    {
        return $this->phpType;
    }

    public function typescriptType(): string
    {
        return $this->typescriptType;
    }

    public function isArray(): bool
    {
        return $this->isArray;
    }

    public function isAdvanced(): bool
    {
        return $this->isAdvanced;
    }

    private function parse(): static
    {
        if (str_contains($this->phpType, 'date')) {
            $this->typescriptType = 'DateTime';
        }

        if (str_contains($this->phpType, 'array<')) {
            $regex = '/array<[^,]+,[^>]+>/';
            preg_match($regex, $this->phpType, $matches);

            if (count($matches) > 0) {
                $this->isAdvanced = true;
                $type = str_replace('array<', '', $this->phpType);
                $type = str_replace('>', '', $type);

                $types = explode(',', $type);
                $type = '';

                $keyType = trim($types[0]);
                $valueType = trim($types[1]);

                $keyType = self::toTypescript($this->phpType);
                $valueType = self::toTypescript($this->phpType);

                $this->typescriptType = "{[key: {$keyType}]: {$valueType}}";

                return $this;
            }
        }

        if (str_contains($this->phpType, '[]')) {
            $this->isArray = true;
            $type = str_replace('[]', '', $this->phpType);
        }

        if (str_contains($this->phpType, 'array<')) {
            $this->isArray = true;
            $type = str_replace('array<', '', $this->phpType);
            $type = str_replace('>', '', $type);
        }

        $this->typescriptType = self::toTypescript($this->phpType);

        if ($this->isArray) {
            $this->typescriptType .= '[]';
        }

        return $this;
    }
}
