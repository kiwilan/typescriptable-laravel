<?php

namespace Kiwilan\Typescriptable\Typed;

use Illuminate\Support\Str;
use Kiwilan\Typescriptable\Typed\Eloquent\ClassItem;
use Kiwilan\Typescriptable\Typed\Eloquent\ClassTemplate;
use Kiwilan\Typescriptable\Typed\Eloquent\Output\EloquentPhp;
use Kiwilan\Typescriptable\Typed\Eloquent\Output\EloquentTypescript;
use Kiwilan\Typescriptable\Typed\Eloquent\Utils\EloquentProperty;
use Kiwilan\Typescriptable\Typed\Utils\LaravelTeamType;
use Kiwilan\Typescriptable\TypescriptableConfig;

class EloquentType
{
    /** @var ClassItem[] */
    public array $items = [];

    /** @var array<string, EloquentProperty[]> */
    public array $eloquents = [];

    /** @var array<string, array<string, array<string, string>>> */
    public array $list = [];

    protected function __construct(
        public string $modelsPath,
        public string $outputPath,
    ) {
    }

    public static function make(?string $modelsPath, ?string $outputPath): self
    {
        if (! $modelsPath) {
            $modelsPath = TypescriptableConfig::modelsDirectory();
        }

        $tsFilename = TypescriptableConfig::modelsFilename();
        if (! $outputPath) {
            $outputPath = TypescriptableConfig::setPath();
        }

        $self = new EloquentType($modelsPath, $outputPath);
        $self->items = $self->setItems();
        $self->list = $self->setList();
        $self->eloquents = $self->setEloquents();

        $typescript = EloquentTypescript::make($self->eloquents, "{$outputPath}/{$tsFilename}");
        $php = EloquentPhp::make($self->eloquents, $outputPath);

        $typescript->print();
        // $php->print();

        // if (TypescriptableConfig::modelsFakeTeam()) {
        //     $service->typeables['Team'] = ClassTemplate::fake('Team', LaravelTeamType::setFakeTeam());
        // }

        return $self;
    }

    private function setEloquents(): array
    {
        $eloquents = [];

        foreach ($this->items as $key => $item) {
            $modelName = $item->name;
            $eloquents[$modelName] = [];

            foreach ($item->eloquent->properties as $field => $property) {
                $field = Str::snake($field);
                $eloquents[$modelName][$field] = $property;
            }
        }

        return $eloquents;
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
                    'name' => $property->name,
                    'isArray' => $property->isArray,
                    'type' => $property->type,
                    'typeTs' => $property->typeTs,
                ];
            }
        }

        return $list;
    }

    /**
     * @return ClassItem[]
     */
    private function setItems(): array
    {
        /** @var ClassItem[] */
        $classes = [];

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($this->modelsPath, \FilesystemIterator::SKIP_DOTS)
        );
        $skip = TypescriptableConfig::modelsSkip();

        /** @var \SplFileInfo $file */
        foreach ($iterator as $file) {
            if (! $file->isDir()) {
                $model = ClassItem::make(
                    path: $file->getPathname(),
                    file: $file,
                );

                if (in_array($model->namespace, $skip)) {
                    continue;
                }
                $classes[$model->name] = $model;
            }
        }

        return $classes;
    }
}
