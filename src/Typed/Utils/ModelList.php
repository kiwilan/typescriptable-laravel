<?php

namespace Kiwilan\Typescriptable\Typed\Utils;

use Kiwilan\Typescriptable\Typed\Utils\Schema\SchemaClass;
use Kiwilan\Typescriptable\Typed\Utils\Schema\SchemaCollection;
use Kiwilan\Typescriptable\TypescriptableConfig;

class ModelList
{
    /**
     * @param  SchemaClass[]  $models
     */
    protected function __construct(
        protected string $path,
        protected array $models = [],
    ) {}

    public static function make(?string $path = null): self
    {
        if (! $path) {
            $path = app_path().'/Models';
        }

        $self = new self($path);
        $collect = SchemaCollection::make($self->path, TypescriptableConfig::modelsSkip());
        $self->models = $collect->onlyModels();

        return $self;
    }

    /**
     * Get the path of the models.
     */
    public function path(): string
    {
        return $this->path;
    }

    /**
     * Get the list of models.
     *
     * @return SchemaClass[]
     */
    public function models(): array
    {
        return $this->models;
    }
}
