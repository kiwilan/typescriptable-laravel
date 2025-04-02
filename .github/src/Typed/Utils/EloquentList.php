<?php

namespace Kiwilan\Typescriptable\Eloquent\Utils;

use Kiwilan\Typescriptable\Eloquent\Utils\Schema\SchemaClass;
use Kiwilan\Typescriptable\Eloquent\Utils\Schema\SchemaCollection;
use Kiwilan\Typescriptable\TypescriptableConfig;

class EloquentList
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
            $path = TypescriptableConfig::eloquentDirectory();
        }

        $self = new self($path);
        $collect = SchemaCollection::make($self->path, TypescriptableConfig::eloquentSkip());
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
