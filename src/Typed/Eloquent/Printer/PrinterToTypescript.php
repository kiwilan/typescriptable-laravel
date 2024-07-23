<?php

namespace Kiwilan\Typescriptable\Typed\Eloquent\Printer;

use Illuminate\Support\Facades\File;
use Kiwilan\Typescriptable\Typed\Eloquent\Schemas\Model\SchemaModel;
use Kiwilan\Typescriptable\Typed\Utils\LaravelPaginateType;
use Kiwilan\Typescriptable\Typescriptable;
use Kiwilan\Typescriptable\TypescriptableConfig;

class PrinterToTypescript
{
    protected function __construct(
        public string $path,
        public string $content = '',
    ) {}

    /**
     * @param  SchemaModel[]  $models
     */
    public static function make(array $models, string $path): self
    {
        $self = new self($path);

        /** @var string[] */
        $content = [];

        $content = array_merge($content, Typescriptable::TS_HEAD);
        $content[] = 'declare namespace App.Models {';

        foreach ($models as $modelNamespace => $model) {
            $content[] = "  export interface {$model->schemaClass()?->fullname()} {";

            foreach ($model->attributes() as $attributeName => $attribute) {
                $field = $attribute->nullable() ? "{$attributeName}?" : $attributeName;
                $content[] = "    {$field}: {$attribute->typescriptType()}";
            }

            foreach ($model->relations() as $relationName => $relation) {
                $content[] = "    {$relationName}?: {$relation->typescriptType()}";
            }

            $content[] = '  }';
        }

        $content[] = '}';
        $content[] = '';

        if (TypescriptableConfig::eloquentPaginate()) {
            $content[] = 'declare namespace App {';
            $content[] = LaravelPaginateType::make();
            $content[] = '}';
        }
        $content[] = '';

        $self->content = implode(PHP_EOL, $content);

        return $self;
    }

    public function print(): void
    {
        if (! File::exists(dirname($this->path))) {
            File::makeDirectory(dirname($this->path));
        }
        File::delete($this->path);

        File::put($this->path, $this->content);
    }
}
