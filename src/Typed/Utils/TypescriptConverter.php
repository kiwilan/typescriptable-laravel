<?php

namespace Kiwilan\Typescriptable\Typed\Utils;

use Kiwilan\Typescriptable\Typed\Eloquent\ClassProperty;

class TypescriptConverter
{
    // public static function phpToTs(?string $type): string
    // {
    //     return match ($type) {
    //         'string' => 'string',
    //         'int' => 'number',
    //         'integer' => 'number',
    //         'float' => 'number',
    //         'bool' => 'boolean',
    //         'boolean' => 'boolean',
    //         'array' => 'any[]',
    //         'object' => 'any',
    //         'mixed' => 'any',
    //         'null' => 'null',
    //         'DateTime' => 'Date',
    //         'DateTimeInterface' => 'Date',
    //         'Carbon' => 'Date',
    //         'Model' => 'any',
    //         default => 'any',
    //     };
    // }

    // public static function castToPhpType(?string $cast): string
    // {
    //     if (is_null($cast)) {
    //         return 'string';
    //     }

    //     $cast = explode(':', $cast)[0];

    //     return match ($cast) {
    //         'int' => 'int',
    //         'integer' => 'int',
    //         'real' => 'float',
    //         'float' => 'float',
    //         'double' => 'float',
    //         'string' => 'string',
    //         'bool' => 'bool',
    //         'boolean' => 'bool',
    //         'object' => 'object',
    //         'array' => 'array',
    //         'collection' => 'array',
    //         'date' => 'DateTime',
    //         'datetime' => 'DateTime',
    //         'timestamp' => 'string',
    //         default => 'string',
    //     };
    // }

    // public static function docTypeToTsType(ClassProperty $property): ?string
    // {
    //     $type = null;

    //     if (str_contains($property->dbColumn->Type, 'array')) {
    //         $regex = '/<[^>]*>/';

    //         if (preg_match($regex, $property->dbColumn->Type, $matches)) {
    //             $type = $matches[0] ?? null;
    //         }

    //         $type = str_replace('<', '', $type);
    //         $type = str_replace('>', '', $type);
    //         $type = str_replace(' ', '', $type);

    //         if (str_contains($type, ',')) {
    //             $types = explode(',', $type);
    //         } else {
    //             $types = [$type];
    //         }

    //         $type = end($types);
    //     }

    //     if (str_contains($property->dbColumn->Type, '[]')) {
    //         $type = str_replace('[]', '', $property->dbColumn->Type);
    //     }

    //     if ($type) {
    //         $type = TypescriptConverter::phpToTs($type);
    //         $type = "{$type}[]";
    //     }

    //     return $type;
    // }
}
