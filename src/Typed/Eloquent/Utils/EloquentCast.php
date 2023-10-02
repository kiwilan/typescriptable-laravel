<?php

namespace Kiwilan\Typescriptable\Typed\Eloquent\Utils;

use Kiwilan\Typescriptable\Typed\Eloquent\EloquentItem;
use ReflectionClass;

class EloquentCast
{
    /** @var array<string,string> */
    public array $enums = [];

    protected function __construct(
        public string $field,
        public string $type,
        public bool $isEnum = false,
        public ?string $typeTs = null,
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
                type: $type,
            );
            $cast = $cast->make($cast);

            $list[$field] = $cast;
        }

        return $list;
    }

    private function make(EloquentCast $cast): EloquentCast
    {
        $type = $cast->type;

        if (str_contains($type, ':') && ! str_contains($type, 'encrypted:')) {
            $type = explode(':', $type)[0];
        }

        $tsType = match ($type) {
            'array' => 'any[]',
            'boolean' => 'boolean',
            'collection' => 'any[]',
            'date' => 'Date',
            'datetime' => 'Date',
            'immutable_date' => 'Date',
            'immutable_datetime' => 'Date',
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
            'timestamp' => 'Date',
            default => 'any',
        };

        $cast->typeTs = $tsType;

        if (str_contains($type, 'boolean')) {
            $cast->type = 'bool';
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
            $cast->typeTs = $this->enumsToTs($cast->enums);
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

        foreach ($reflector->getConstants() as $name => $enum) {
            $enums[$name] = is_string($enum) ? "'{$enum}'" : $enum->value;
        }

        return $enums;
    }

    private function isEnum(ReflectionClass $reflect): bool
    {
        return in_array('UnitEnum', $reflect->getInterfaceNames());
    }
}
