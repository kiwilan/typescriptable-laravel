<?php

namespace Kiwilan\Typescriptable\Typed\Eloquent\Printer;

use Kiwilan\Typescriptable\Typed\Eloquent\Schemas\Model\SchemaModel;
use Kiwilan\Typescriptable\Typed\Utils\LaravelPaginateType;
use Kiwilan\Typescriptable\Typescriptable;
use Kiwilan\Typescriptable\TypescriptableConfig;

class PrinterEloquentTypescript
{
    /**
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
            $contents[] = "  export interface {$model->schemaClass()?->fullname()} {";

            foreach ($model->attributes() as $attributeName => $attribute) {
                $field = $attribute->nullable() ? "{$attributeName}?" : $attributeName;
                $contents[] = "    {$field}: {$attribute->typescriptType()}";
            }

            foreach ($model->relations() as $relationName => $relation) {
                $contents[] = "    {$relationName}?: {$relation->typescriptType()}";
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
