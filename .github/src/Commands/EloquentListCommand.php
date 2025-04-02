<?php

namespace Kiwilan\Typescriptable\Commands;

use Illuminate\Console\Command;
use Kiwilan\Typescriptable\Eloquent\Utils\EloquentList;
use Kiwilan\Typescriptable\Eloquent\Utils\Schema\SchemaClass;

class EloquentListCommand extends Command
{
    public $signature = 'eloquent:list';

    public $description = 'List all Eloquent models.';

    public function handle(): int
    {
        $list = EloquentList::make();

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
