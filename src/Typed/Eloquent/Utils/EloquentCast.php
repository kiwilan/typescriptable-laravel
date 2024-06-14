<?php

namespace Kiwilan\Typescriptable\Typed\Eloquent\Utils;

use BackedEnum;
use Kiwilan\Typescriptable\Typed\Eloquent\EloquentItem;
use ReflectionClass;
use UnitEnum;

class EloquentCast
{
    /** @var array<string,string> */
    public array $enums = [];

    protected function __construct(
        public string $field,
        public string $typePhp,
        public bool $isEnum = false,
        public ?string $typescriptType = null,
    ) {
    }

    /**
     * @return array<string,EloquentCast>
     */
    public static function toArray(EloquentItem $eloquent): array
    {
        $casts = $eloquent->model->getCasts();

        /** @var EloquentCast[] $list */
        $list = [];

        foreach ($casts as $field => $type) {
            $cast = new EloquentCast(
                field: $field,
                typePhp: $type,
            );
            $cast = $cast->make($cast);

            $list[$field] = $cast;
        }

        return $list;
    }

    private function make(EloquentCast $cast): EloquentCast
    {
        $type = $cast->typePhp;

        if (str_contains($type, ':') && ! str_contains($type, 'encrypted:')) {
            $type = explode(':', $type)[0];
        }

        $cast->typePhp = match ($cast->typePhp) {
            'array' => 'array',
            'bool' => 'bool',
            'boolean' => 'bool',
            'collection' => 'array',
            'date' => '\DateTime',
            'datetime' => '\DateTime',
            'immutable_date' => '\DateTime',
            'immutable_datetime' => '\DateTime',
            'decimal' => 'float',
            'double' => 'float',
            'encrypted' => 'string',
            'encrypted:array' => 'array',
            'encrypted:collection' => 'array',
            'encrypted:object' => 'object',
            'float' => 'float',
            'int' => 'int',
            'integer' => 'int',
            'object' => 'object',
            'real' => 'float',
            'string' => 'string',
            'timestamp' => '\DateTime',
            default => $cast->typePhp,
        };

        $tsType = match ($type) {
            'array' => 'any[]',
            'boolean' => 'boolean',
            'collection' => 'any[]',
            'date' => 'string',
            'datetime' => 'string',
            'immutable_date' => 'string',
            'immutable_datetime' => 'string',
            'decimal' => 'number',
            'double' => 'number',
            'encrypted' => 'any',
            'encrypted:array' => 'any[]',
            'encrypted:collection' => 'any[]',
            'encrypted:object' => 'any',
            'float' => 'number',
            'int' => 'number',
            'integer' => 'number',
            'object' => 'any',
            'real' => 'number',
            'string' => 'string',
            'timestamp' => 'string',
            default => 'any',
        };

        $cast->typescriptType = $tsType;

        if (str_contains($type, 'boolean')) {
            $cast->typePhp = 'bool';
        }

        if ($tsType !== 'any') {
            return $cast;
        }

        $isClass = str_contains($type, '\\');
        if (! $isClass) {
            return $cast;
        }

        $reflect = new \ReflectionClass($type);
        $cast->isEnum = $this->isEnum($reflect);

        if ($cast->isEnum) {
            $cast->enums = $this->setEnums($reflect);
            $cast->typescriptType = $this->enumsToTs($cast->enums);
        }

        return $cast;
    }

    public static function enumsToTs(array $enum): string
    {
        $type = '';

        foreach ($enum as $key => $value) {
            $type .= " '{$value}' |";
        }

        $type = rtrim($type, '|');

        return trim($type);
    }

    private function setEnums(ReflectionClass $reflector): array
    {
        $enums = [];
        $constants = $reflector->getConstants();
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

    private function isEnum(ReflectionClass $reflect): bool
    {
        return in_array('UnitEnum', $reflect->getInterfaceNames());
    }
}
