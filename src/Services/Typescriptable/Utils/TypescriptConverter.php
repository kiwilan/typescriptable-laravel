<?php

namespace Kiwilan\Typescriptable\Services\Typescriptable\Utils;

use Doctrine\DBAL\Types\Types;
use Kiwilan\Typescriptable\Services\Typescriptable\Models\ClassProperty;
use ReflectionClass;

class TypescriptConverter
{
    protected function __construct(
    ) {
    }

    public static function phpType(string $type): string
    {
        $type = explode(' ', preg_replace('/\s*\([^)]*\)/', '', $type))[0] ?? $type;

        $type = match ($type) {
            'double' => Types::FLOAT,
            'numeric' => Types::FLOAT,
            'decimal' => Types::FLOAT,
            'varchar' => Types::STRING,
            'char' => Types::STRING,
            'binary' => Types::STRING,
            'varbinary' => Types::STRING,
            'tinyblob' => Types::STRING,
            'blob' => Types::STRING,
            'mediumblob' => Types::STRING,
            'longblob' => Types::STRING,
            'tinytext' => Types::STRING,
            'text' => Types::STRING,
            'mediumtext' => Types::STRING,
            'longtext' => Types::STRING,
            'enum' => Types::STRING,
            'bigint' => Types::INTEGER,
            'int' => Types::INTEGER,
            'tinyint' => Types::BOOLEAN,
            'id' => Types::INTEGER,
            'date' => Types::STRING,
            'time' => Types::STRING,
            'datetime' => Types::STRING,
            'year' => Types::STRING,
            'timestamp' => Types::STRING,
            'json' => Types::JSON,
            default => Types::STRING,
        };

        if ($type === Types::INTEGER) {
            $type = 'int';
        }

        return $type;
    }

    public static function phpToTs(?string $phpType): string
    {
        return match ($phpType) {
            'string' => 'string',
            'int' => 'number',
            'integer' => 'number',
            'float' => 'number',
            'bool' => 'boolean',
            'boolean' => 'boolean',
            'array' => 'any[]',
            'object' => 'any',
            'mixed' => 'any',
            'null' => 'null',
            'DateTime' => 'Date',
            'DateTimeInterface' => 'Date',
            'Carbon' => 'Date',
            'Model' => 'any',
            default => 'any',
        };
    }

    public static function castToPhpType(?string $cast): string
    {
        if (is_null($cast)) {
            return 'string';
        }

        $cast = explode(':', $cast)[0];

        return match ($cast) {
            'int' => 'int',
            'integer' => 'int',
            'real' => 'float',
            'float' => 'float',
            'double' => 'float',
            'string' => 'string',
            'bool' => 'bool',
            'boolean' => 'bool',
            'object' => 'object',
            'array' => 'array',
            'collection' => 'array',
            'date' => 'DateTime',
            'datetime' => 'DateTime',
            'timestamp' => 'string',
            default => 'string',
        };
    }

    public static function isEnum(ReflectionClass $reflector): bool
    {
        return in_array('UnitEnum', $reflector->getInterfaceNames());
    }

    public static function setEnum(ReflectionClass $reflector): array
    {
        $enums = [];

        foreach ($reflector->getConstants() as $name => $enum) {
            $enums[$name] = is_string($enum) ? "'{$enum}'" : $enum->value;
        }

        return $enums;
    }

    public static function phpEnumToTsType(array $enum): string
    {
        $type = '';

        foreach ($enum as $key => $value) {
            $type .= "'{$value}' |";
        }

        $type = rtrim($type, '|');

        return trim($type);
    }

    public static function docTypeToTsType(ClassProperty $property): ?string
    {
        $type = null;

        if (str_contains($property->dbColumn->Type, 'array')) {
            $regex = '/<[^>]*>/';

            if (preg_match($regex, $property->dbColumn->Type, $matches)) {
                $type = $matches[0] ?? null;
            }

            $type = str_replace('<', '', $type);
            $type = str_replace('>', '', $type);
            $type = str_replace(' ', '', $type);

            if (str_contains($type, ',')) {
                $types = explode(',', $type);
            } else {
                $types = [$type];
            }

            $type = end($types);
        }

        if (str_contains($property->dbColumn->Type, '[]')) {
            $type = str_replace('[]', '', $property->dbColumn->Type);
        }

        if ($type) {
            $type = TypescriptConverter::phpToTs($type);
            $type = "{$type}[]";
        }

        return $type;
    }
}
