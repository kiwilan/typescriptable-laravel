<?php

namespace Kiwilan\Typescriptable\Typed\Eloquent\Printer;

use Kiwilan\Typescriptable\Typed\Eloquent\Schemas\Model\SchemaModel;
use Kiwilan\Typescriptable\Typed\Utils\LaravelPaginateType;
use Kiwilan\Typescriptable\Typescriptable;
use Kiwilan\Typescriptable\TypescriptableConfig;

class PrinterEloquentTypescript
{
    /**
     * Create a new instance of `PrinterEloquentTypescript`.
     *
     * @param  SchemaModel[]  $models
     */
    public static function make(array $models): string
    {
        $self = new self;

        /** @var string[] */
        $contents = [];

        $contents = array_merge($contents, Typescriptable::TS_HEAD);
        $contents[] = 'declare namespace App.Models {';

        foreach ($models as $modelNamespace => $model) {
            $contents[] = "  export interface {$model->getSchemaClass()?->getFullname()} {";

            foreach ($model->getAttributes() as $attributeName => $attribute) {
                $field = $attribute->isNullable() ? "{$attributeName}?" : $attributeName;
                $contents[] = "    {$field}: {$attribute->getTypescriptType()}";
            }

            foreach ($model->getRelations() as $relationName => $relation) {
                $contents[] = "    {$relationName}?: {$relation->getTypescriptType()}";
            }

            $contents[] = '  }';
        }

        $contents[] = '}';
        $contents[] = '';

        if (TypescriptableConfig::eloquentPaginate()) {
            $contents[] = 'declare namespace App {';
            $contents[] = LaravelPaginateType::make();
            $contents[] = '}';
        }
        $contents[] = '';

        return implode(PHP_EOL, $contents);
    }
}
