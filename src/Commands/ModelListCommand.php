<?php

namespace Kiwilan\Typescriptable\Commands;

use Illuminate\Console\Command;
use Kiwilan\Typescriptable\Typed\Utils\ModelList;
use Kiwilan\Typescriptable\Typed\Utils\Schema\SchemaClass;
use Kiwilan\Typescriptable\TypescriptableConfig;

class ModelListCommand extends Command
{
    public $signature = 'model:list
                        {modelPath? : The path to the models.}';

    public $description = 'List all models.';

    public function handle(): int
    {
        $modelPath = $this->argument('modelPath') ?? TypescriptableConfig::modelsDirectory();

        $list = ModelList::make($modelPath);

        $this->table(
            ['Name', 'Namespace', 'Path'],
            array_map(fn (SchemaClass $model) => [
                $model->name(),
                $model->namespace(),
                $model->path(),
            ], $list->models())
        );

        return self::SUCCESS;
    }
}
