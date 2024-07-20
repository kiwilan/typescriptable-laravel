<?php

namespace Kiwilan\Typescriptable\Commands;

use Illuminate\Console\Command;
use Kiwilan\Typescriptable\Typed\Utils\EloquentList;
use Kiwilan\Typescriptable\Typed\Utils\Schema\SchemaClass;
use Kiwilan\Typescriptable\TypescriptableConfig;

class EloquentListCommand extends Command
{
    public $signature = 'eloquent:list
                        {modelPath? : The path to the models.}';

    public $description = 'List all Eloquent models.';

    public function handle(): int
    {
        $modelPath = $this->argument('modelPath') ?? TypescriptableConfig::eloquentDirectory();

        $list = EloquentList::make($modelPath);

        $this->table(
            ['Name', 'Namespace', 'Path'],
            array_map(fn (SchemaClass $model) => [
                $model->name(),
                $model->namespace(),
                $model->path(),
            ], $list->eloquentModels())
        );

        return self::SUCCESS;
    }
}
