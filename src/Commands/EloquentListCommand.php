<?php

namespace Kiwilan\Typescriptable\Commands;

use Illuminate\Console\Command;
use Kiwilan\Typescriptable\Typed\Utils\EloquentList;
use Kiwilan\Typescriptable\Typed\Utils\Schema\SchemaClass;

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
                $model->getName(),
                $model->getNamespace(),
                $model->getPath(),
            ], $list->models())
        );

        return self::SUCCESS;
    }
}
