<?php

namespace Kiwilan\Typescriptable\Typed\Eloquent;

use Kiwilan\Typescriptable\Typed\EloquentType;
use Kiwilan\Typescriptable\Typed\Utils\Schema\SchemaClass;
use Kiwilan\Typescriptable\TypescriptableConfig;

class EloquentTypeLegacy extends EloquentType implements IEloquentType
{
    /**
     * @param  array<string, EloquentProperty[]>  $eloquents
     * @param  array<string, array<string, EloquentProperty>>  $pivots
     * @param  array<string, array<string, array<string, string>>>  $list
     */
    public function __construct(
        protected array $eloquents = [],
        protected array $pivots = [],
        protected array $list = [],
    ) {
    }

    public function run(): self
    {
        $this->models = SchemaClass::toArray($this->models_path, TypescriptableConfig::modelsSkip());
        $this->models = array_filter($this->models, fn ($item) => $item->isModel);

        $this->list = $this->setList();
        $this->eloquents = $this->setEloquents();
        $this->setPivots();

        $typescript = EloquentTypescript::make($this->eloquents, "{$this->output_path}/{$this->ts_filename}");
        $typescript->print($this->delete);

        if ($this->php_path) {
            $php = EloquentPhp::make($this->eloquents, $this->php_path);
            $php->print($this->delete);
        }

        return $this;
    }

    /**
     * @return array<string, EloquentProperty[]>
     */
    public function eloquents(): array
    {
        return $this->eloquents;
    }

    /**
     * @return array<string, array<string, array<string, string>>>
     */
    public function list(): array
    {
        return $this->list;
    }

    private function setEloquents(): array
    {
        $eloquents = [];

        foreach ($this->models as $key => $item) {
            $modelName = $item->name;
            $eloquents[$modelName] = [];

            if (! empty($item->eloquent->morphRelations)) {
                foreach ($item->eloquent->morphRelations as $relation) {
                    if ($relation->hasPivot) {
                        foreach ($relation->pivotAttributes as $a) {
                            $this->pivots[$relation->pivotModel][$a->name()] = $a;
                        }
                    }
                }
            }

            foreach ($item->eloquent->properties as $field => $property) {
                $field = Str::snake($field);
                $eloquents[$modelName][$field] = $property;
            }
        }

        return $eloquents;
    }

    private function setPivots(): self
    {
        if ($this->pivots) {
            foreach ($this->pivots as $pivot => $attributes) {
                foreach ($attributes as $attribute) {
                    $this->eloquents[$pivot]['pivot'][$attribute->name()] = $attribute;
                }
            }
        }

        return $this;
    }

    /**
     * @return array<string, array<string, array<string, string>>>
     */
    private function setList(): array
    {
        $list = [];

        foreach ($this->models as $key => $item) {
            $modelName = Str::slug($key);
            $list[$modelName] = [];

            foreach ($item->eloquent->properties as $field => $property) {
                $field = Str::snake($field);
                $list[$modelName][$field] = [
                    'name' => $property->name(),
                    'isArray' => $property->isArray(),
                    'phpType' => $property->phpType(),
                    'typescriptType' => $property->typescriptType(),
                ];
            }
        }

        return $list;
    }
}
