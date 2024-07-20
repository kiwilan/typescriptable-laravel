<?php

namespace Kiwilan\Typescriptable\Typed\Utils;

use Kiwilan\Typescriptable\Typed\Utils\Schema\SchemaClass;
use Kiwilan\Typescriptable\Typed\Utils\Schema\SchemaCollection;
use Kiwilan\Typescriptable\TypescriptableConfig;

class EloquentList
{
    /**
     * @param  SchemaClass[]  $eloquentModels
     */
    protected function __construct(
        protected string $path,
        protected array $eloquentModels = [],
    ) {}

    public static function make(?string $path = null): self
    {
        if (! $path) {
            $path = app_path().'/Models';
        }

        $self = new self($path);
        $collect = SchemaCollection::make($self->path, TypescriptableConfig::eloquentSkip());
        $self->eloquentModels = $collect->onlyModels();

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
    public function eloquentModels(): array
    {
        return $this->eloquentModels;
    }
}
