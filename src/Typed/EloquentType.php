<?php

namespace Kiwilan\Typescriptable\Typed;

use Illuminate\Support\Str;
use Kiwilan\Typescriptable\Typed\Eloquent\ClassTemplate;
use Kiwilan\Typescriptable\Typed\Eloquent\Output\EloquentPhp;
use Kiwilan\Typescriptable\Typed\Eloquent\Output\EloquentTypescript;
use Kiwilan\Typescriptable\Typed\Eloquent\Utils\EloquentProperty;
use Kiwilan\Typescriptable\Typed\Utils\ClassItem;
use Kiwilan\Typescriptable\Typed\Utils\LaravelTeamType;
use Kiwilan\Typescriptable\TypescriptableConfig;

class EloquentType
{
    /**
     * @param  ClassItem[]  $items
     * @param  array<string, EloquentProperty[]>  $eloquents
     * @param  array<string, array<string, EloquentProperty>>  $pivots
     * @param  array<string, array<string, array<string, string>>>  $list
     */
    protected function __construct(
        protected string $modelsPath,
        protected string $outputPath,
        protected array $items = [],
        protected array $eloquents = [],
        protected array $pivots = [],
        protected array $list = [],
    ) {}

    public static function make(?string $modelsPath, ?string $outputPath, ?string $phpPath = null, bool $delete = true): self
    {
        if (! $modelsPath) {
            $modelsPath = TypescriptableConfig::modelsDirectory();
        }

        $tsFilename = TypescriptableConfig::modelsFilename();
        if (! $outputPath) {
            $outputPath = TypescriptableConfig::setPath();
        }

        if (! $phpPath) {
            $phpPath = TypescriptableConfig::modelsPhpPath();
        }

        $self = new self($modelsPath, $outputPath);
        $self->items = ClassItem::list($self->modelsPath, TypescriptableConfig::modelsSkip());
        $self->items = array_filter($self->items, fn ($item) => $item->isModel);

        $self->list = $self->setList();
        $self->eloquents = $self->setEloquents();
        $self->setPivots();

        $typescript = EloquentTypescript::make($self->eloquents, "{$outputPath}/{$tsFilename}");
        $typescript->print($delete);

        if ($phpPath) {
            $php = EloquentPhp::make($self->eloquents, $phpPath);
            $php->print($delete);
        }

        // if (TypescriptableConfig::modelsFakeTeam()) {
        //     $service->typeables['Team'] = ClassTemplate::fake('Team', LaravelTeamType::setFakeTeam());
        // }

        return $self;
    }

    public function modelsPath(): string
    {
        return $this->modelsPath;
    }

    public function outputPath(): string
    {
        return $this->outputPath;
    }

    /**
     * @return ClassItem[]
     */
    public function items(): array
    {
        return $this->items;
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

        foreach ($this->items as $key => $item) {
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

        foreach ($this->items as $key => $item) {
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
