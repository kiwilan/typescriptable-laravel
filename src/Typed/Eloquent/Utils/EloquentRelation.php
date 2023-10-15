<?php

namespace Kiwilan\Typescriptable\Typed\Eloquent\Utils;

use Kiwilan\Typescriptable\Typed\Eloquent\EloquentItem;
use ReflectionMethod;

class EloquentRelation
{
    public function __construct(
        public string $model,
        public string $field,
        public bool $isArray = false,
        public bool $isMorph = false,
        public string $type = 'mixed',
        public string $typeTs = 'any',
    ) {
    }

    /**
     * @return array<string,EloquentRelation>
     */
    public static function toArray(EloquentItem $eloquent): array
    {
        $reflect = $eloquent->class->reflect;
        $relations = [];

        foreach ($reflect->getMethods() as $method) {
            $isRelation = str_contains($method->getReturnType(), 'Illuminate\Database\Eloquent\Relations');

            if (! $isRelation) {
                continue;
            }

            $relation = self::make($method);
            $relations[$relation->field] = $relation;
        }

        return $relations;
    }

    private static function make(ReflectionMethod $method): self
    {
        $relation = new EloquentRelation(
            model: $method->getDeclaringClass()->getName(),
            field: $method->getName(),
            isArray: str_contains($method->getReturnType(), 'Many'),
            isMorph: str_contains($method->getReturnType(), 'Morph'),
        );
        $return_line = $method->getEndLine() - 2;

        $lines = file($method->getFileName());
        $return_line_content = $lines[$return_line];

        $regex = '/\w+::class/';

        if (preg_match($regex, $return_line_content, $matches)) {
            $type = $matches[0];
            $type = str_replace('::class', '', $type);
            $relation->type = $type;
        }

        $typeTs = "App.Models.{$relation->type}";

        $relation->typeTs = $relation->isArray
            ? "{$typeTs}[]"
            : $typeTs;

        if ($relation->isMorph && ! $relation->type) {
            $relation->type = 'mixed';
            $relation->typeTs = 'any';
        }

        return $relation;
    }
}
