<?php

namespace Kiwilan\Typescriptable\Typed\Database;

use BackedEnum;
use Kiwilan\Typescriptable\Typed\Eloquent\Parser\ParserPhpType;
use UnitEnum;

class DatabaseConversion
{
    protected function __construct(
        protected DatabaseDriverEnum $databaseDriver,
        protected ?string $databaseType,
        protected ?string $phpType = 'mixed',
        protected ?string $castType = null,
        protected string $typescriptType = 'any',
    ) {}

    /**
     * Make a new instance.
     *
     * @param  string  $databaseDriver  Database driver.
     * @param  string|null  $databaseType  Database type.
     * @param  string|null  $cast  Laravel cast.
     */
    public static function make(string $databaseDriver, ?string $databaseType, ?string $cast): self
    {
        $self = new self(
            databaseDriver: DatabaseDriverEnum::tryFrom(strtolower($databaseDriver)),
            databaseType: $databaseType,
        );

        $self->phpType = $self->databaseDriver->toPhp($self->databaseType);
        $self->castType = $cast;
        if ($cast) {
            $self->parseCast($cast);
        } else {
            $self->typescriptType = ParserPhpType::toTypescript($self->phpType);
        }

        return $self;
    }

    /**
     * Get database driver.
     */
    public function databaseDriver(): DatabaseDriverEnum
    {
        return $this->databaseDriver;
    }

    /**
     * Get database type.
     */
    public function databaseType(): string
    {
        return $this->databaseType;
    }

    /**
     * Get PHP type.
     */
    public function phpType(): string
    {
        return $this->phpType;
    }

    /**
     * Get Laravel cast type.
     */
    public function castType(): ?string
    {
        return $this->castType;
    }

    /**
     * Get TypeScript type.
     */
    public function typescriptType(): string
    {
        return $this->typescriptType;
    }

    private function parseDateTimePhpCast(string $cast): self
    {
        if (! in_array($cast, ['date',
            'datetime',
            'immutable_date',
            'immutable_datetime',
            'timestamp'])) {
            return $this;
        }

        $this->phpType = '\\DateTime';

        return $this;
    }

    /**
     * Convert Laravel cast to TypeScript type.
     */
    private function parseCast(string $cast): self
    {
        if (str_contains($cast, ':')) {
            $cast = explode(':', $cast)[0];
        }

        $typescriptCastable = match ($cast) {
            'array' => 'any[]',
            'collection' => 'any[]',
            'encrypted:array' => 'any[]',
            'encrypted:collection' => 'any[]',
            'encrypted:object' => 'any',
            'object' => 'any',

            'AsStringable::class' => 'string',
            'AsCollection::class' => 'any[]',
            \Illuminate\Database\Eloquent\Casts\AsStringable::class => 'string',
            \Illuminate\Database\Eloquent\Casts\AsCollection::class => 'any[]',

            'boolean' => 'boolean',

            'date' => 'string',
            'datetime' => 'string',
            'immutable_date' => 'string',
            'immutable_datetime' => 'string',
            'timestamp' => 'string',

            'decimal' => 'number',
            'double' => 'number',
            'float' => 'number',
            'integer' => 'number',
            'int' => 'number',

            'encrypted' => 'string',
            'hashed' => 'string',
            'real' => 'string',

            'string' => 'string',

            default => 'unknown',
        };

        if ($typescriptCastable === 'unknown') {
            // enum case
            if (str_contains($cast, '\\')) {
                $this->phpType = "\\{$cast}";
                $enums = $this->parseEnum($cast);
                $candidate = $this->arrayToTypescriptTypes($enums);

                // Prevent empty string
                if ($candidate) {
                    $this->typescriptType = $candidate;
                }

                return $this;
            } else {
                // attribute or accessor case
                return $this;
            }
        }

        $this->typescriptType = $typescriptCastable;
        $this->parseDateTimePhpCast($cast);

        return $this;
    }

    /**
     * Parse enum.
     *
     * @param  string  $namespace  Enum namespace.
     * @return string[]
     */
    private function parseEnum(string $namespace): array
    {
        $reflect = new \ReflectionClass($namespace);

        $enums = [];
        $constants = $reflect->getConstants();
        $constants = array_filter($constants, fn ($value) => is_object($value));

        foreach ($constants as $name => $enum) {
            if ($enum instanceof BackedEnum) {
                $enums[$name] = $enum->value;
            } elseif ($enum instanceof UnitEnum) {
                $enums[$name] = $enum->name;
            }
        }

        return $enums;
    }

    /**
     * Convert array to TypeScript types.
     */
    private function arrayToTypescriptTypes(array $types): string
    {
        $typescript = '';

        foreach ($types as $type) {
            $typescript .= " '{$type}' |";
        }

        $typescript = rtrim($typescript, '|');

        return trim($typescript);
    }
}
