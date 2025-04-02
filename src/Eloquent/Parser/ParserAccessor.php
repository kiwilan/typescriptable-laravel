<?php

namespace Kiwilan\Typescriptable\Eloquent\Parser;

use Illuminate\Support\Str;
use Kiwilan\Typescriptable\Eloquent\Schema\SchemaClass;

class ParserAccessor
{
    public function __construct(
        protected string $field,
        protected string $phpType,
        protected bool $isLegacy = false,
        protected bool $isArray = false,
        protected ?string $typescriptType = null,
    ) {}

    /**
     * @return ParserAccessor[]
     */
    public static function collection(SchemaClass $schemaClass): array
    {
        $items = [];

        foreach ($schemaClass->getReflect()->getMethods() as $method) {
            $name = $method->getName();
            $return = $method->getReturnType();

            $item = new self(
                field: $name,
                phpType: 'string',
            );

            if ($name === 'getMediableAttribute') {
                continue;
            }

            if (! $return instanceof \ReflectionNamedType) {
                continue;
            }

            $type = $return->getName();

            // New attributes
            if ($type === 'Illuminate\Database\Eloquent\Casts\Attribute') {
                $item = $item->make($item, $method);
                $items[$item->field] = $item;

                continue;
            }

            // Legacy attributes
            if (str_starts_with($name, 'get') && str_ends_with($name, 'Attribute') && $name !== 'getAttribute') {
                $item->isLegacy = true;
                $item = $item->make($item, $method);
                $items[$item->field] = $item;
            }
        }

        return $items;
    }

    public function getField(): string
    {
        return $this->field;
    }

    public function getPhpType(): string
    {
        return $this->phpType;
    }

    public function getTypescriptType(): string
    {
        return $this->typescriptType;
    }

    public function isLegacy(): bool
    {
        return $this->isLegacy;
    }

    public function isArray(): bool
    {
        return $this->isArray;
    }

    private function make(ParserAccessor $item, \ReflectionMethod $method): ParserAccessor
    {
        $field = str_replace('Attribute', '', str_replace('get', '', $item->field));
        $field = Str::snake($field);

        $doc = $method->getDocComment();
        $return = null;

        $regex = '/(?m)@return *\K(?>(\S+) *)??(\S+)$/';

        if (preg_match($regex, $doc, $matches)) {
            $return = $matches[0];
        }

        $type = $method->getReturnType();

        if ($return) {
            $type = $return;
        }

        if ($type instanceof \ReflectionNamedType) {
            $type = $type->getName();
        }

        $item->field = $field;

        if (str_contains($type, 'Attribute<')) {
            $type = str_replace('Attribute<', '', $type);
            $type = str_replace('>', '', $type);
        }

        if ($type) {
            $item->phpType = $type;

            $parser = ParserPhpType::make($type);
            $item->typescriptType = $parser->typescriptType();
        }

        if (str_contains($type, '[]') || str_contains($type, 'Collection') || str_contains($type, 'array')) {
            $item->isArray = true;
        }

        if (str_contains($type, 'boolean')) {
            $item->phpType = 'bool';
        }

        $advanced = $this->isAdvancedArray($item->phpType);

        if ($advanced) {
            $item->phpType = "{$advanced}[]";

            $parser = ParserPhpType::make($advanced);
            $item->typescriptType = $parser->typescriptType().'[]';
        }

        return $item;
    }

    private function isAdvancedArray(string $type): string|false
    {
        $regex = '/array<(.*?)\>/';

        if (preg_match($regex, $type, $matches)) {
            $matches = $matches[1];
            $matches = explode(',', $matches);

            return $matches[0];
        }

        $regex = '/(.*?)\[]/';

        if (preg_match($regex, $type, $matches)) {
            return $matches[1];
        }

        return false;
    }
}
