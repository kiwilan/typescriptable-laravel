<?php

namespace Kiwilan\Typescriptable\Typed\Utils;

use Kiwilan\Typescriptable\Typed\Utils\Schema\SchemaClass;
use Kiwilan\Typescriptable\Typed\Utils\Schema\SchemaCollection;
use Kiwilan\Typescriptable\TypescriptableConfig;

/**
 * Represents a group of `SchemaClass` extended by Laravel model.
 */
class EloquentList
{
    /**
     * @param  string  $path  Base path where models parsed.
     * @param  SchemaClass[]  $models  All classes extended by Laravel model.
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
        $self->models = $collect->getItems(only_models: true);

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
