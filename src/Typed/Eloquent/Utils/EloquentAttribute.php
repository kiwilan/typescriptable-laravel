<?php

namespace Kiwilan\Typescriptable\Typed\Eloquent\Utils;

use Illuminate\Support\Str;
use Kiwilan\Typescriptable\Typed\Eloquent\EloquentItem;
use ReflectionMethod;
use ReflectionNamedType;

class EloquentAttribute
{
    protected function __construct(
        public string $field,
        public string $type,
        public bool $isLegacy = false,
        public bool $isArray = false,
        public ?string $typeTs = null,
    ) {
    }

    /**
     * @return array<string,EloquentAttribute>
     */
    public static function toArray(EloquentItem $eloquent): array
    {
        $reflect = $eloquent->class->reflect;

        $list = [];

        foreach ($reflect->getMethods() as $key => $method) {
            $name = $method->getName();
            $return = $method->getReturnType();

            $item = new EloquentAttribute(
                field: $name,
                type: 'string',
            );

            if ($name === 'getMediableAttribute') {
                continue;
            }

            if (! $return instanceof ReflectionNamedType) {
                continue;
            }

            $type = $return->getName();

            // New attributes
            if ($type === 'Illuminate\Database\Eloquent\Casts\Attribute') {
                $item = $item->make($item, $method);
                $list[$item->field] = $item;

                continue;
            }

            // Legacy attributes
            if (str_starts_with($name, 'get') && str_ends_with($name, 'Attribute') && $name !== 'getAttribute') {
                $item->isLegacy = true;
                $item = $item->make($item, $method);
                $list[$item->field] = $item;
            }
        }

        $mediable = self::mediable($eloquent);

        if ($mediable) {
            $list[$mediable->field] = $mediable;
        }

        return $list;
    }

    private function make(EloquentAttribute $item, ReflectionMethod $method): EloquentAttribute
    {
        $field = str_replace('Attribute', '', str_replace('get', '', $item->field));
        $field = Str::snake($field);

        $doc = $method->getDocComment();
        $return = null;

        $regex = '/(?m)@return *\K(?>(\S+) *)??(\S+)$/';

        if (preg_match($regex, $doc, $matches)) {
            $return = $matches[0] ?? null;
        }

        $type = $method->getReturnType();

        if ($return) {
            $type = $return;
        }

        if ($type instanceof ReflectionNamedType) {
            $type = $type->getName();
        }

        $item->field = $field;

        if (str_contains($type, 'Attribute<')) {
            $type = str_replace('Attribute<', '', $type);
            $type = str_replace('>', '', $type);
        }

        if ($type) {
            $item->type = $type;
            $item->typeTs = EloquentItem::phpToTs($type);
        }

        if (str_contains($type, '[]') || str_contains($type, 'Collection') || str_contains($type, 'array')) {
            $item->isArray = true;
        }

        if (str_contains($type, 'boolean')) {
            $item->type = 'bool';
        }

        $advanced = $this->isAdvancedArray($item->type);

        if ($advanced) {
            $item->type = "{$advanced}[]";
            $item->typeTs = EloquentItem::phpToTs($advanced).'[]';
        }

        return $item;
    }

    private function isAdvancedArray(string $type): string|false
    {
        $regex = '/array<(.*?)\>/';

        if (preg_match($regex, $type, $matches)) {
            if ($matches) {
                $matches = $matches[1];
                $matches = explode(',', $matches);

                return $matches[0];
            }
        }

        $regex = '/(.*?)\[]/';

        if (preg_match($regex, $type, $matches)) {
            return $matches ? $matches[1] : false;
        }

        return false;
    }

    public static function mediable(EloquentItem $eloquent): ?EloquentAttribute
    {
        $reflect = $eloquent->class->reflect;

        foreach ($reflect->getMethods() as $key => $method) {
            $name = $method->getName();

            if ($name === 'getMediableAttribute') {
                return new EloquentAttribute(
                    field: 'mediable',
                    type: 'array',
                    typeTs: self::mediableTs($eloquent),
                );
            }
        }

        return null;
    }

    private static function mediableTs(EloquentItem $eloquent): string
    {
        $model = $eloquent->model;
        $reflect = $eloquent->class->reflect;
        $methods = $reflect->getMethods();

        $names = array_map(fn ($method) => $method->getName(), $methods);

        $mediable_object = null;

        if (in_array('getMediablesListAttribute', $names) && method_exists($model, 'getMediablesListAttribute')) {
            $mediable_object = '{';

            foreach ($model->getMediablesListAttribute() as $media) {
                $mediable_object .= " {$media}?: string, ";
            }
            $mediable_object .= '}';
        }

        return $mediable_object;
    }
}
