<?php

namespace Kiwilan\Typescriptable\Typed\Eloquent\Utils;

use Kiwilan\Typescriptable\Typed\Database\DatabaseScan;
use Kiwilan\Typescriptable\Typed\Database\Table;
use Kiwilan\Typescriptable\Typed\Eloquent\EloquentItem;
use ReflectionMethod;

class EloquentRelation
{
    /**
     * @param  array<string, EloquentProperty>|null  $pivotAttributes
     */
    public function __construct(
        public string $name,
        public string $model,
        public string $field,
        public bool $isArray = false,
        public bool $isMorph = false,
        public string $phpType = 'mixed',
        public string $typescriptType = 'any',
        public ?string $relationType = null,
        public bool $hasPivot = false,
        public ?string $pivotTable = null,
        public ?string $pivotModel = null,
        public ?array $pivotAttributes = null,
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
            if (! $method->getReturnType()) {
                continue;
            }

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
        $isMorph = str_contains($method->getReturnType(), 'Morph');
        $relation = new EloquentRelation(
            name: $method->getName(),
            model: $method->getDeclaringClass()->getName(),
            field: $method->getName(),
            isArray: str_contains($method->getReturnType(), 'Many'),
            isMorph: $isMorph,
        );
        $return_line = $method->getEndLine() - 2;

        $lines = file($method->getFileName());
        $return_line_content = $lines[$return_line];

        $regex = '/\w+::class/';

        if (preg_match($regex, $return_line_content, $matches)) {
            $type = $matches[0];
            $type = str_replace('::class', '', $type);
            $relation->phpType = $type;
        }

        $typeTs = "App.Models.{$relation->phpType}";
        if ($relation->phpType === 'mixed') {
            $typeTs = 'any';
        }

        $relation->typescriptType = $relation->isArray
            ? "{$typeTs}[]"
            : $typeTs;

        if ($relation->isMorph && ! $relation->phpType) {
            $relation->phpType = 'mixed';
            $relation->typescriptType = 'any';
        }

        $relation->relationType = $method->getReturnType();
        if ($isMorph) {
            $relation->parseMorphModel($method);
        }

        return $relation;
    }

    private function parseMorphModel(ReflectionMethod $method)
    {
        $rt = $this->relationType;
        $rt = explode('\\', $rt);
        $rt = end($rt);
        $sl = $method->getStartLine();
        $el = $method->getEndLine();

        $lines = [];
        $content = file($method->getFileName());

        for ($i = $sl; $i < $el; $i++) {
            $lines[] = $content[$i];
        }

        $search = match ($rt) {
            'MorphOne' => 'morphOne', // related*, name*, type, id, localKey
            'MorphMany' => 'morphMany', // related*, name*, type, id, localKey
            // 'MorphTo' => 'morphTo', // name, type, id, ownerKey
            'MorphToMany' => 'morphToMany', // related*, name*, table, id, localKey, parentKey, inverse
            default => null,
        };

        if (! $search) {
            return;
        }

        $related = null;
        $name = null;
        $table = null;

        foreach ($lines as $line) {
            if (str_contains($line, $search)) {
                $line = trim($line);
                $parts = explode($search, $line); // ["return $this->", "(Comment::class, 'commentable');"]

                $params = $parts[1] ?? null;
                if (! $params) {
                    continue;
                }

                $params = str_replace(['(', ')', ';', '"', "'"], '', $params); // remove typo
                $params = explode(',', $params);
                $params = array_map('trim', $params);

                $related = $params[0] ?? null;
                $name = $params[1] ?? null;
                $table = $params[2] ?? null;
            }
        }

        if (str_contains($related, '::class')) {
            $related = str_replace('::class', '', $related);
        }

        if (! $related) {
            return;
        }

        $database = DatabaseScan::make();

        $tableName = $table ?? $name;
        $pivotTable = null;

        if (! $tableName) {
            return;
        }

        foreach ($database->getTables() as $item) {
            if (str_starts_with($item, $tableName)) {
                $pivotTable = $item;
                break;
            }
        }

        if (! $pivotTable) {
            return;
        }

        $this->pivotTable = $pivotTable;
        $this->pivotModel = $related;

        foreach ($database->getItems()[$pivotTable]->columns as $column) {
            $this->pivotAttributes[$column->name] = EloquentProperty::fromDb($column);
        }

        $this->hasPivot = true;
        $this->phpType = $related;
        $this->typescriptType = "App.Models.{$related}";
        if ($this->isArray) {
            $this->typescriptType .= '[]';
        }
    }
}
